<?php
// Include necessary files
require_once '../Database.php';
require_once '../User.php';
require_once '../Session.php';

// Start the session
Session::startSession();

// Initialize database connection
$db = new Database();
$user = new User($db);

// Check if the form is submitted to create a new user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["create_user"])) {
    // Get the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $user_code = $_POST['user_code'];
    $password = $_POST['password'];

    // Validate form input
    if (!empty($name) && !empty($email) && !empty($password)) {
        // Hash the password using SHA-256
        $hashedPassword = hash('sha256', $password);

        if ($user->checkEmailExists($email) || $user->checkUsernameExists($name) || $user->checkUsercodeExists($user_code)) {
            $error = 'Email, username or user code already exists!';
            $_SESSION["error"] = $error;
            header('Location: create_user.php');  // Redirect to the create user page
        } else {
            if ($user->createUser($name, $email, $password, $user_code)){
                $success = 'User created succesfully!';
                $_SESSION["success"] = $success;
                header('Location: admin_dashboard.php');  // Redirect to the create user page
            }
            else{
                $error = 'An error occured while trying to create the user';
                $_SESSION["error"] = $error;
                header('Location: create_user.php');  // Redirect to the create user page
            }
        }
    } else {
        echo "Please fill in all fields.";
    }
}
// Close the database connection
$conn->close();
?>
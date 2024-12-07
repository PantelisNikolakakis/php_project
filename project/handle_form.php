<?php
// Include necessary files
require_once 'Database.php';
require_once 'User.php';
require_once 'Admin.php';
require_once 'Session.php'; 

Session::startSession();

// Initialize database connection
$db = new Database();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sign_in'])) {
    $usernameOrEmail = $_POST['username_email'];
    $password = $_POST['password'];

    // Create a User object
    $user = new User($db);

    // Check credentials
    $userData = $user->checkCredentials($usernameOrEmail, $password);

    if ($userData) {
        // Correct credentials, save to session
        Session::login($userData);

        // If user is admin, redirect to the admin dashboard
        if ($userData['role'] === 'admin') {
            $_SESSION['is_admin'] = true;
            header('Location: ./admin/admin_dashboard.php');  // Redirect to the admin dashboard
        } else {
            $_SESSION['is_admin'] = false;
            header('Location: user_dashboard.php');  // Redirect to the user dashboard
        }
        exit;  // Stop script execution after redirect
    } else {
        // Incorrect username/email or password
        $error = 'Invalid username/email or password!';
        $_SESSION["error"] = $error;
        header('Location: index.php');  // Redirect to the log in page
    }

}
?>

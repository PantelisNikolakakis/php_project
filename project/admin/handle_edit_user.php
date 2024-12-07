<?php
// Include database connection file
require_once '../Database.php';
require_once '../User.php';
require_once '../Session.php';

Session::startSession();

// Initialize database connection
$db = new Database();

// Check if the form is submitted to update the user details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["edit_user"])) {
    // Get the form data
    $userId = $_POST['user_id'];
    $updatedName = $_POST['name'];
    $updatedEmail = $_POST['email'];
    $updatedPassword = $_POST['password'];

    $user = new User($db, $userId);

    // Validate form input
    if (!empty($updatedName) && !empty($updatedEmail)) {
        
        if ($user->checkEmailExists($updatedEmail, $userId) || $user->checkUsernameExists($updatedName, $userId)) {
            $error = 'Email or username already exists!';
            $_SESSION["error"] = $error;
            header('Location: edit_user.php?user-id='.$userId.'');  // Redirect to the create user page
        } else {

            // Prepare SQL query to update user details
            if (!empty($updatedPassword)){
                $user->updatePassword($updatedPassword);
            }

            $user->updateUsername($updatedName);
            $user->updateEmail($updatedEmail);
            //print_r($user); die();
            if ($user->commit()){
                $success = 'User updated succesfully!';
                $_SESSION["success"] = $success;
                header('Location: admin_dashboard.php');  // Redirect to the create user page
            }
            else{
                $error = 'An error occured while trying to update the user';
                $_SESSION["error"] = $error;
                header('Location: edit_user.php?user-id='.$userId.'');  // Redirect to the create user page
            }
        }
    } else {
        echo "Please fill in all fields.";
    }
}

?>
<?php
// Include database connection file
require_once '../Database.php';
require_once '../User.php';

// Initialize database connection
$db = new Database();

// Check if the user_id is passed via POST request
if (isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];
    $user = new User($db, $userId);
    
    if ($user->deleteUser()){
        echo "success";
    }
    else{
        echo "error";
    }
} else {
    // Return error response if user_id is not passed
    echo "error";
}

?>

<?php
// Include database connection file
require_once 'Database.php';
require_once 'Request.php';
require_once 'Session.php';

Session::startSession();

// Initialize database connection
$db = new Database();

// Check if request id is passed via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $request = new Request($db, $id);
    
    if ($request->deleteRequest()){
        $success = 'Request deletted succesfully!';
        $_SESSION["success"] = $success;
    }
    else{
        $error = 'An error occured while trying to delete the request';
        $_SESSION["error"] = $error;
    }

    header('Location: user_dashboard.php'); 
} else {
    echo "Request ID is missing.";
    exit;
}

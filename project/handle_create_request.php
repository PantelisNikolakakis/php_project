<?php
// Include database connection file
require_once 'Database.php';
require_once 'Request.php';
require_once 'Session.php';

Session::startSession();

// Initialize database connection
$db = new Database();

// Check if the form is submitted to create a new user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["create_req_btn"])) {
    // Get the form data
    $date_from = $_POST['date_from'];
    $date_to = $_POST['date_to'];
    $reason = $_POST['reason'];
    $status = "0";

    // Validate form input
    if (!empty($date_from) && !empty($date_to)) {
        $request = new Request($db);
        $request->setFields(Session::getUserData()["user_id"], $reason, $status, $date_from, $date_to, date('Y-m-d'));
        if ($request->createRequest()){
            $success = 'Request created succesfully!';
            $_SESSION["success"] = $success;
            header('Location: user_dashboard.php');  // Redirect to the create user page
        }
        else{
            $error = 'An error occured while trying to create the request';
            $_SESSION["error"] = $error;
            header('Location: create_request.php');  // Redirect to the create user page
        }
    } else {
        echo "Please fill in all fields.";
    }
}

?>

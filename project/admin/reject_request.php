<?php
// Include database connection file
require_once '../Database.php';
require_once '../Request.php';

// Initialize database connection
$db = new Database();
// Check if the user_id is passed via POST request
if (isset($_POST['request_id'])) {
    $requestId = $_POST['request_id'];
    $new_status = "2";

    $request = new Request($db, $requestId);
    $request->updateStatus($new_status);
    //echo $request->getStatus(); exit;

    if ($request->commitChanges()){
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

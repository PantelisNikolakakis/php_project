<?php
// Include database connection file
require_once '../Database.php';
require_once '../Admin.php';
require_once '../Session.php'; 

Session::startSession();

// Check if the user is not logged in
if (!Session::isLoggedIn()) {
    header('Location: ../index.php');
}
else if(Session::getUserData()["role"] == "user"){
    header('Location: ../user_dashboard.php');
}

// Initialize database connection
$db = new Database();

$admin = new Admin($db, Session::getUserData()["user_id"]);
$requests = $admin->getAllRequests();

?>

<!doctype html>
<html lang="en">
  <?php include('../header.php');  ?>
  <body>

<?php

// Check if there are any users with the role 'user'
if (sizeof($requests) > 0) {
    // Open the table and table header
    echo '<div cLass="container mt-5">';
    echo '<table class="table table-bordered">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Date submitted</th>';
    echo '<th>Date requested</th>';
    echo '<th>Total days</th>';
    echo '<th>Reason</th>';
    echo '<th>Status</th>';
    echo '<th>Action</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Loop through each row in the result and display it in the table
    foreach ($requests as $request) {
        $date_from = new DateTime($request['date_from']); 
        $date_to = new DateTime($request['date_to']);  

        // Subtract the second date from the first
        $interval = $date_from->diff($date_to);

        $status = array(
            "0" => "pending",
            "1" => "approved",
            "2" => "rejected",
        );

        
        echo '<tr>';
        echo '<td>' . htmlspecialchars($request['date_submit']) . '</td>';
        echo '<td>' . htmlspecialchars($request['date_from']) . ' - '. htmlspecialchars($request['date_to']) .'</td>';
        echo '<td>' . $interval->days. '</td>';
        echo '<td>' . htmlspecialchars($request['reason']) . '</td>';
        echo '<td class="status_text">'.$status[$request['status']].'</td>';

        if ($request["status"] == "0"){
            echo '<td data-status="0"><button data-id='.$request["id"].' class="btn btn-success approve_request" title="approve"> <i class="fas fa-check"></i> </button> 
            <button data-id='.$request["id"].' class="btn btn-danger reject_request" title="reject"> <i class="fas fa-x"></i> </button></td>';
        } 
        else if ($request["status"] == "1"){
            echo '<td data-status="1"><button data-id='.$request["id"].' class="btn btn-danger reject_request" title="reject"> <i class="fas fa-x"></i> </button></td>';
        }
        else{
            echo '<td data-status="2"><button data-id='.$request["id"].' class="btn btn-success approve_request" title="approve"> <i class="fas fa-check"></i> </button> </td>';
        }

        echo '</tr>';
    }

    // Close the table body and table
    echo '</tbody>';
    echo '</table>';
    echo '<div><a href="admin_dashboard.php" style="font-size: 50px;">GO TO USERS TABLE</a></div>';
    echo '<div class="alert alert-warning">';
    echo '<div class="mb-2"><button class="btn btn-danger"> <i class="fas fa-x"></i> </button> <span>stands for <b>reject</b></span></div>';
    echo '<div><button class="btn btn-success"> <i class="fas fa-check"></i> </button> <span>stands for <b>approve</b></span></div>';
    echo '</div>';
    echo "</div>";
} else {
    echo "<div class='container mt-5'><h1>No requests.</h1></div>";
}

?>


<?php include('../footer.php'); 
?>
   <script src="admin.js"></script>
  </body>
</html>
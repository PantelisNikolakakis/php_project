<?php
// Include database connection file
require_once 'Database.php';
require_once 'User.php';
require_once 'Session.php';

Session::startSession();

// Check if the user is not logged in
if (!Session::isLoggedIn()) {
  header('Location: index.php');
}

// Initialize database connection
$db = new Database();

$user = new User($db, Session::getUserData()["user_id"]);
$requests = $user->loadRequests();

?>

<!doctype html>
<html lang="en">
  <?php include('header.php');  ?>
  <body>

  <?php if (isset($_SESSION['success'])){ ?>
    <div cLass="container mt-5">
      <div class="alert alert-success" role="alert">
          <?php echo $_SESSION['success']; 
          unset($_SESSION['success']); ?>
      </div>
    </div>
  <?php } ?>

<?php


echo '<div cLass="container mt-5">';
echo '<div class="mb-3"><a href="create_request.php" class="btn btn-warning">Create request</a></div>';
// Check if there are any users with the role 'user'
if (sizeof($requests) > 0) {
    // Open the table and table header
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
        echo '<td>'.$status[$request['status']].'</td>';
        if ($request["status"] == "0"){
            echo '<td><a href=delete_request.php?id='.$request["id"].' class="btn btn-danger delete_request" title="approve"> <i class="fas fa-trash"></i> </a></td>';
        }
        else{
            echo "<td>-</td>";
        }
        echo '</tr>';
    }

    // Close the table body and table
    echo '</tbody>';
    echo '</table>';
    echo "</div>";
} else {
    echo "<h1>No requests.</h1>";
}

?>


<?php include('footer.php'); 
?>
  </body>
</html>
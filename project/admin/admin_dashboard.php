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
$users = $admin->getUsers();

?>

<!doctype html>
<html lang="en">
  <?php include('../header.php');  ?>
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


echo '<div class="container mt-5">';
echo '<div class="mb-3"><a href="create_user.php" class="btn btn-warning">Create user</a></div>';
// Check if there are any users with the role 'user'
if (sizeof($users) > 0) {
    // Open the table and table header
    echo '<div cLass="container mt-5">';
    echo '<table class="table table-bordered">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Name</th>';
    echo '<th>Email</th>';
    echo '<th>Action</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Loop through each row in the result and display it in the table
    foreach ($users as $user) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($user['username']) . '</td>';
        echo '<td>' . htmlspecialchars($user['email']) . '</td>';
        echo '<td><a href="edit_user.php?user-id=' . $user['id'] . '">edit</a> <a class="delete_user" href="#" user-id='.$user["id"].'>delete</a></td>';
        echo '</tr>';
    }

    // Close the table body and table
    echo '</tbody>';
    echo '</table>';
    echo '<div><a href="requests.php" style="font-size: 50px;">GO TO REQUESTS TABLE</a></div>';
    echo "</div>";
} else {
    echo "No users found with the role 'user'.";
}


?>


<?php include('../footer.php'); 
?>
   <script src="admin.js"></script>
  </body>
</html>
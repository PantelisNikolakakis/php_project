<?php
// Include database connection file
require_once '../Database.php';
require_once '../User.php';
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

// Check if user-id is passed via GET
if (isset($_GET['user-id'])) {
    $userId = $_GET['user-id'];

    $user = new User($db, $userId);
    if (!$user->userExists){
        echo "user doesn't exist!";
        exit;
    }
    
} else {
    echo "User ID is missing.";
    exit;
}

?>

<!-- HTML Form for Editing User -->

<!doctype html>
<html lang="en">
  <?php include('../header.php');  ?>
<body>
    <div class="container mt-5">
        <h2>Edit User</h2>

        <?php if (Session::errorMessageExists()){ ?>
          <div class="alert alert-danger" role="alert">
              <?php echo Session::getErrorMessage(); 
               unset($_SESSION['error']); ?>
          </div>
        <?php } ?>

        <!-- Display a form with the current user data (name and email) -->
        <form action="handle_edit_user.php" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user->getUsername()); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user->getEmail()); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">password:</label>
                <input type="text" class="form-control" id="password" name="password" value="">
            </div>
            <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
            <button type="submit" name="edit_user" class="btn btn-primary">Update</button>
        </form>
        <div class="alert alert-warning mt-5">
            <p class="m-0"><b>NOTE: </b> If you leave the password field empty, it will not be updated. Fill it in, only if you want to update the password for this user</p>
        </div>
    </div>  


<?php include('../footer.php'); 
?>
  </body>
</html>
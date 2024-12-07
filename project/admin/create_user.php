<?php 
require_once '../Session.php';  // Include the Session class
// Start the session
Session::startSession();

// Check if the user is not logged in
if (!Session::isLoggedIn()) {
    header('Location: ../index.php');
}
else if(Session::getUserData()["role"] == "user"){
    header('Location: ../user_dashboard.php');
}
?>
<!-- HTML Form for Creating User -->
<!doctype html>
<html lang="en">
  <?php include('../header.php'); ?>
<body>
    <div class="container mt-5">
        <h2>Create New User</h2>

        <?php if (Session::errorMessageExists()){ ?>
          <div class="alert alert-danger" role="alert">
              <?php echo Session::getErrorMessage(); 
               unset($_SESSION['error']); ?>
          </div>
        <?php } ?>

        <?php if (isset($_SESSION['success'])){ ?>
          <div class="alert alert-success" role="alert">
              <?php echo $_SESSION['success']; 
              unset($_SESSION['success']); ?>
          </div>
        <?php } ?>
        <!-- Display the form to create a new user -->
        <form action="handle_create_user.php" method="POST" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="user_code">User code:</label>
                <input type="number" placeholder="ONLY NUMBERS" class="form-control" id="user_code" name="user_code" maxlength="7" minlength="7" title="User Code must be 7 digits" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" name="create_user" class="btn btn-primary">Create User</button>
        </form>
    </div>

    <?php include('../footer.php'); ?>
    <script>
        // JavaScript function to validate input length before submitting the form
        function validateForm() {
            var userCode = document.getElementById("user_code").value;
            if (userCode.length !== 7) {
                alert("The User Code must be exactly 7 characters long.");
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>
  </body>
</html>
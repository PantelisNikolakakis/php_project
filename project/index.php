<?php 
require_once 'Session.php';  // Include the Session class

// Start the session
Session::startSession();

// Check if the user is already logged in
if (Session::isLoggedIn()) {
    // Redirect to the dashboard or home page based on role
    if (Session::isAdmin()) {
        header('Location: admin/admin_dashboard.php');
        exit();
    } else {
        header('Location: user_dashboard.php');
        exit();
    }
}

include('header.php');  ?>

<!doctype html>
<html lang="en">
  <body>
    <div class="container mt-5">
        <div class="alert alert-warning">
            To sign in the admin, use "pnik" as the username, and "123" as the password
        </div>
        <!-- Display the error message with Bootstrap alert -->
        <?php if (isset($_SESSION['error'])){ ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $_SESSION['error']; 
                unset($_SESSION['error']); ?>
            </div>
        <?php } ?>
        <form action="handle_form.php" method="POST">
            <div>
                <input type="text"  class="form-control mb-3 d-block w-25" label for="username" name="username_email" placeholder="enter username or email" required>
                <input type="password"  class="form-control d-block w-25" label for="password" name="password" placeholder="enter password" required>
                <div class="text-center w-25">
                    <button type="submit" name="sign_in" class="btn btn-primary mt-3 text-center">log in</button>
                </div>
            </div>
        </form>
    </div>
    <?php include('footer.php'); ?>
  </body>
</html>
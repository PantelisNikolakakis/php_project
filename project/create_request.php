<?php
// Include the session
require_once 'Session.php';

Session::startSession();

// Check if the user is not logged in
if (!Session::isLoggedIn()) {
    header('Location: index.php');
  }
?>
<!-- HTML Form for Creating User -->
<!doctype html>
<html lang="en">
  <?php include('header.php');  ?>
<body>
    <div class="container mt-5">

    
        <?php if (isset($_SESSION['error'])){ ?>
            <div cLass="container mt-5">
            <div class="alert alert-danger" role="alert">
                <?php echo $_SESSION['error']; 
                unset($_SESSION['error']); ?>
            </div>
            </div>
        <?php } ?>
        
        <h2>Create New Request</h2>

        <!-- Display the form to create a new request -->
        <form action="handle_create_request.php" method="POST">
            <div class="form-group">
                <label for="date_from" class="form-label">Date From</label>
                <input type="date" class="form-control date_field" id="date_from" name="date_from" required>
            </div>
            <div class="form-group">
                <label for="date_from" class="form-label">Date From</label>
                <input type="date" class="form-control date_field" id="date_to" name="date_to" required>
            </div>
            <div class="form-group">
                <label for="reason" class="form-label">Reason</label>
                <input type="text" class="form-control" id="reason" name="reason">
            </div>
            <button type="submit" name="create_req_btn" class="btn btn-primary">Create Request</button>
        </form>
    </div>

    <?php include('footer.php'); 
    ?>
  </body>
</html>
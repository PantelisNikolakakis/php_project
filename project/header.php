<?php 
require_once 'Session.php'; 
// Start the session
Session::startSession();

if (Session::isLoggedIn()){
    $isAdmin = Session::getUserData()["role"] == "admin" ? 1 : 0;
}
else{
    $isAdmin = -1;
}
//print_r($isAdmin);
?>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <title></title>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <?php 
        if ($isAdmin == -1){
            ?>
            <a class="navbar-brand" href="index.php">MyWebsite</a>
            <?php
        }
        else if ($isAdmin == 1){
            ?>
            <a class="navbar-brand" href="admin_dashboard.php">MyWebsite</a>
            <?php
        }
        else{
            ?>
            <a class="navbar-brand" href="user_dashboard.php">MyWebsite</a>
            <?php
        }
        ?>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <div class="" aria-labelledby="userDropdown">
                        <?php
                        //print_r($_SESSION);
                        if (Session::isLoggedIn()) {
                            // User is logged in, show Sign Out
                            if (!Session::isAdmin()){
                                echo '<a class="dropdown-item" href="logout.php">Sign Out</a>';
                            }
                            else{
                                echo '<a class="dropdown-item" href="../logout.php">Sign Out</a>';
                            }
                        } else {
                            // User is not logged in, show Sign In
                            echo '<a class="dropdown-item" href="index.php">Sign In</a>';
                        }
                        ?>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
  </head>
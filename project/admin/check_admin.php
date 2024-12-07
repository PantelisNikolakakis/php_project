<?php

session_start();
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to the login page
    header("Location: ../index.php");
    exit;
}

if ($_SESSION['role'] != "admin"){
    // User does not have admin rights
    header("Location: ../index.php");
    exit;
}

?>
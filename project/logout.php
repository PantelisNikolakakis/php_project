<?php
require_once 'Session.php';  // Include the Session class

// Start the session
Session::startSession();

// destroy session
Session::logout();

// Redirect the user to the login page
header("Location: index.php");
exit;



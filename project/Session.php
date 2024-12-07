<?php
class Session {
    public static function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();  // Start session if not already started
        }
    }

    // Check if the user is an admin
    public static function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    // Check if the user is a regular user
    public static function isUser() {
        return isset($_SESSION['role']) && ($_SESSION['role'] === 'user' || empty($_SESSION['role']));
    }

    public static function errorMessageExists(){
        return isset($_SESSION['error']);
    }

    public static function successMessageExists(){
        return isset($_SESSION['success']);
    }

    public static function getErrorMessage(){
        return isset($_SESSION['error']) ? $_SESSION['error'] : "";
    }

    public static function getSuccessMessage(){
        return isset($_SESSION['success']) ? $_SESSION['success'] : "";
    }

    // Get user data from session
    public static function getUserData() {
        if (self::isLoggedIn()) {
            return [
                'user_id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'email' => $_SESSION['email'],
                'role' => $_SESSION['role'],
            ];
        }
        return null;
    }

    // Check if the user is logged in by looking for a session variable
    public static function isLoggedIn() {
        // Check if a user session variable exists (e.g., user_id, or email)
        return isset($_SESSION['user_id']);
    }

     // Save user credentials to session after login
     public static function login($userData) {
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['username'] = $userData['username'];
        $_SESSION['email'] = $userData['email'];
        $_SESSION['role'] = $userData['role'];
        $_SESSION['is_admin'] = ($userData['role'] === 'Admin');
    }

    // Destroy the session (log out)
    public static function logout() {
        session_destroy();
    }
}
?>

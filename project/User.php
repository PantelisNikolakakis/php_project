<?php
require_once('Request.php');
class User {
    protected $db;
    protected $connection;
    protected $userId;
    protected $username;
    protected $email;
    protected $password;
    protected $role;
    protected $userCode;
    protected $requests;
    public $userExists = false;

    protected $changedFields = [];
    

    public function __construct($db, $userId = null) {
        $this->db = $db;
        $this->connection = $this->db->connect();
        
        if ($userId) {
            $this->loadUser($userId);
        }
    }

    // Method to check if the user is an admin
    public function isAdmin() {
        return $this->role === 'admin';
    }

    public function loadRequests() {
        $stmt = $this->connection->prepare("SELECT id, reason, status, date_from, date_to, date_submit FROM requests WHERE user_id = ?");
        $stmt->bind_param("i", $this->userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $this->requests = [];
        while ($row = $result->fetch_assoc()) {
            $this->requests[] = $row; // Store each request in the list
        }

        return $this->requests;
    }

    protected function loadUser($userId) {
        $stmt = $this->connection->prepare("SELECT id, username, email, password, role, user_code FROM app_users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($id, $username, $email, $password, $role, $userCode);

        if ($stmt->fetch()) {
            $this->userId = $id;
            $this->username = $username;
            $this->email = $email;
            $this->password = $password;
            $this->role = $role;
            $this->userCode = $userCode;
            $this->userExists = true;
        }
    }

    // Method to check user credentials (for login)
    public function checkCredentials($usernameOrEmail, $password) {
        $stmt = $this->connection->prepare(
            "SELECT id, username, email, role, password FROM app_users WHERE (username = ? OR email = ?)"
        );
        $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
        $stmt->execute();
        $stmt->store_result();

        $hashed_password = hash('sha256', $password);
        //die($stmt->num_rows == 1);
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $username, $email, $role, $storedPassword);
            $stmt->fetch();

            // Verify password
            if ($hashed_password === $storedPassword) {
                // Return user data if credentials are valid
                return [
                    'id' => $id,
                    'username' => $username,
                    'email' => $email,
                    'role' => $role
                ];
            }
        }
        // Return false if credentials are invalid
        return false;
    }

    // Method to delete a user by their user_id
    public function deleteUser() {
        // Check if user exists first
        if ($this->userId) {

            // if user has requests, their requests should be deletted first
            $this->loadRequests();
            if (sizeof($this->requests) > 0){
                foreach ($this->requests as $request){
                    $request_object = new Request($this->db, $request["id"]);
                    $request_object->deleteRequest();
                }
            }

            // Prepare the DELETE query
            $sql = "DELETE FROM app_users WHERE id = ?";

            // Prepare the statement
            $stmt = $this->db->prepare($sql);

            // Bind the user_id to the statement
            $stmt->bind_param("i", $this->userId);

            // Execute the query
            if ($this->db->execute($stmt)) {
                return true;
            }

            return false;
        } else {
            return false;
        }
    }

    public function getId(){
        return $this->userId;
    }

    public function getUsername(){
        return $this->username;
    }

    public function getEmail(){
        return $this->email;
    }

    public function updateEmail($newEmail) {
        $this->email = $newEmail;
        $this->changedFields['email'] = $newEmail;
    }

    public function updateUsername($newUsername) {
        $this->username = $newUsername;
        $this->changedFields['username'] = $newUsername;
    }

    public function updatePassword($newPassword) {
        $this->password =  hash('sha256', $newPassword);
        $this->changedFields['password'] = $this->password;
    }

    public function updateRole($newRole) {
        $this->role = $newRole;
        $this->changedFields['role'] = $newRole;
    }

    public function updateUserCode($newCode) {
        if (!preg_match('/^\d{7}$/', $newCode)) {
            throw new Exception("User code must be a 7-digit number.");
        }

        $this->userCode = $newCode;
        $this->changedFields['user_code'] = $newCode;
    }

    // Create a new user
    public function createUser($username, $email, $password, $userCode, $role = "user") {
        // Hash the password before storing it in the database
        $hashedPassword = hash('sha256', $password);

        // Prepare SQL query to insert a new user into the database
        $sql = "INSERT INTO app_users (username, email, password, user_code, role) VALUES (?, ?, ?, ?, ?)";
        
        // Use a prepared statement to prevent SQL injection
        $stmt = $this->connection->prepare($sql);

        // Bind parameters to the query
        $stmt->bind_param("sssss", $username, $email, $hashedPassword, $userCode, $role);

        // Execute the query
        if ($stmt->execute()) {
            return true;  // User created successfully
        } else {
            return false;  // Failed to create user
        }
    }

    // Check if the provided email already exists in the database
    public function checkEmailExists($email, $id = null) {
        if ($id == null){
            $sql = "SELECT * FROM app_users WHERE email = ?";
            $stmt = $this->connection->prepare($sql);
            $stmt->bind_param("s", $email);
        }
        else{
            $sql = "SELECT * FROM app_users WHERE email = ? and id != ?";
            $stmt = $this->connection->prepare($sql);
            $stmt->bind_param("si", $email, $id);
        }

        $stmt->execute();
        $stmt->store_result();

        // If a user is found with the same email, return true (email exists)
        if ($stmt->num_rows > 0) {
            return true;
        }

        return false;  // No user found with that email
    }

    // Check if the provided username already exists in the database
    public function checkUsernameExists($username, $id = null) {
        if ($id == null){
            $sql = "SELECT * FROM app_users WHERE username = ?";
            $stmt = $this->connection->prepare($sql);
            $stmt->bind_param("s", $username);
        }
        else{
            $sql = "SELECT * FROM app_users WHERE username = ? and id != ?";
            $stmt = $this->connection->prepare($sql);
            $stmt->bind_param("si", $username, $id);
        }

        $stmt->execute();
        $stmt->store_result();
    
        // If a user is found with the same username, return true (username exists)
        if ($stmt->num_rows > 0) {
            return true;
        }

        return false;  // No user found with that username
    }

    // Check if the provided user_code already exists in the database
    public function checkUsercodeExists($user_code) {
        $sql = "SELECT * FROM app_users WHERE user_code = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $user_code);
        $stmt->execute();
        $stmt->store_result();

        // If a user is found with the same user_code, return true (usernauser_codeme exists)
        if ($stmt->num_rows > 0) {
            return true;
        }

        return false;  // No user found with that user_code
    }

    public function commit() {
        if (empty($this->changedFields)) {
            return false; // No changes to commit
        }

        $setPart = [];
        $params = [];
        $types = '';

        foreach ($this->changedFields as $field => $value) {
            $setPart[] = "$field = ?";
            $params[] = $value;
            $types .= is_int($value) ? 'i' : 's';
        }

        $params[] = $this->userId;
        $types .= 'i';

        $query = "UPDATE app_users SET " . implode(', ', $setPart) . " WHERE id = ?";
        $stmt = $this->connection->prepare($query);

        $stmt->bind_param($types, ...$params);
        $result = $stmt->execute();

        if ($result) {
            $this->changedFields = []; // Clear the changed fields after a successful commit
        }

        return $result;
    }

}
?>

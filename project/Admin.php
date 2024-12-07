<?php
require_once 'User.php';

class Admin extends User {
    // Field to store all requests
    private $allRequests = [];

    public function __construct($db, $userId) {
        parent::__construct($db, $userId); // Initialize as a User
    }

    // Get all requests from all users
    public function getAllRequests() {
        $stmt = $this->db->prepare(
            "SELECT r.id, r.user_id, r.reason, r.status, r.date_from, r.date_to, r.date_submit
             FROM requests r"
        );
        $stmt->execute();
        $result = $stmt->get_result();

        $this->allRequests = []; // Reset before storing new results

        // Store the result in the class field
        while ($row = $result->fetch_assoc()) {
            $this->allRequests[] = $row;
        }

        return $this->allRequests;
    }

    // Update the status of a specific request
    public function updateRequestStatus($requestId, $newStatus) {
        $stmt = $this->db->prepare("UPDATE requests SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $requestId);
        return $stmt->execute();
    }

    // Method to get all users with role "user"
    public function getUsers($role = "user") {
        // SQL query to fetch all users with the specified role
        $sql = "SELECT * FROM app_users WHERE role = ?";
        
        // Prepare the SQL statement
        $stmt = $this->db->prepare($sql);
        
        // Bind the role parameter (role is a string "s")
        $stmt->bind_param("s", $role);
        
        // Execute the query
        $this->db->execute($stmt);

        // Fetch all the results
        $result = $this->db->fetchAll($stmt); // Assuming fetchAll fetches all rows as an associative array
        
        // Return the result (an array of users)
        return $result;
    }
}
?>

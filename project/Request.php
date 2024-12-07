<?php
class Request {
    private $db;
    private $connection;

    private $id;
    private $userId;
    private $reason;
    private $status;
    private $dateFrom;
    private $dateTo;
    private $dateSubmitted;

    private $fieldsToUpdate = []; // Tracks which fields need to be updated

    public function __construct($db, $requestId = null) {
        $this->db = $db;
        $this->connection = $this->db->connect();
        $this->id = $requestId;

        if ($requestId){
            $this->loadRequest();
        }
    }

    public function setFields($userId, $reason, $status = "0", $dateFrom, $dateTo, $dateSubmitted) {
        $this->userId = $userId;
        $this->reason = $reason;
        $this->status = $status;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->dateSubmitted = $dateSubmitted;
    }

    private function loadRequest() {
        $stmt = $this->connection->prepare("SELECT user_id, reason, status, date_from, date_to, date_submit FROM requests WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $stmt->bind_result($userId, $reason, $status, $dateFrom, $dateTo, $dateSubmitted);
        if ($stmt->fetch()) {
            $this->userId = $userId;
            $this->reason = $reason;
            $this->status = $status;
            $this->dateFrom = $dateFrom;
            $this->dateTo = $dateTo;
            $this->dateSubmitted = $dateSubmitted;
        }
    }

    public function createRequest() {
        // Prepare SQL query to insert a new user into the database
        $sql = "INSERT INTO requests (reason, date_from, date_to, status, date_submit, user_id) 
        VALUES (?, ?, ?, ?, ?, ?)";

        // Use a prepared statement to prevent SQL injection
        $stmt = $this->connection->prepare($sql);
        
        // Bind parameters
        // "sssssi" stands for 4 strings and 2 integers
        $stmt->bind_param("sssssi", $this->reason, $this->dateFrom, $this->dateTo, $this->status, $this->dateSubmitted, $this->userId);

        if ($stmt->execute()) {
            // Update request_id for the newly created request
            $this->request_data['request_id'] = $stmt->insert_id;
            return true;
        }
        
        return false;
    }

    public function updateReason($reason) {
        $this->reason = $reason;
        $this->fieldsToUpdate['reason'] = $reason;
    }

    public function updateStatus($status) {
        $this->status = $status;
        $this->fieldsToUpdate['status'] = $status;
    }

    public function updateDateFrom($dateFrom) {
        $this->dateFrom = $dateFrom;
        $this->fieldsToUpdate['date_from'] = $dateFrom;
    }

    public function updateDateTo($dateTo) {
        $this->dateTo = $dateTo;
        $this->fieldsToUpdate['date_to'] = $dateTo;
    }

    // Method to delete a request
    public function deleteRequest() {
        // Check if user exists first
        if ($this->id) {
            // Prepare the DELETE query
            $sql = "DELETE FROM requests WHERE id = ?";

            // Prepare the statement
            $stmt = $this->db->prepare($sql);

            // Bind the user_id to the statement
            $stmt->bind_param("i", $this->id);

            // Execute the query
            if ($this->db->execute($stmt)) {
                return true;
            }

            return false;
        } else {
            return false;
        }
    }

    public function commitChanges() {
        if (empty($this->fieldsToUpdate)) {
            return true; // No changes to commit
        }

        // Build the SQL dynamically based on fields to update
        $setParts = [];
        $params = [];
        $paramTypes = '';

        foreach ($this->fieldsToUpdate as $field => $value) {
            $setParts[] = "$field = ?";
            $params[] = $value;
            $paramTypes .= is_int($value) ? 'i' : 's';
        }

        $params[] = $this->id;
        $paramTypes .= 'i';

        $sql = "UPDATE requests SET " . implode(', ', $setParts) . " WHERE id = ?";
        $stmt = $this->connection->prepare($sql);

        $stmt->bind_param($paramTypes, ...$params);
        $success = $stmt->execute();

        if ($success) {
            $this->fieldsToUpdate = []; // Reset after successful commit
        }

        return $success;
    }

    // Optional: Getter methods for accessing request properties
    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getReason() {
        return $this->reason;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getDateFrom() {
        return $this->dateFrom;
    }

    public function getDateTo() {
        return $this->dateTo;
    }

    public function getDateSubmitted() {
        return $this->dateSubmitted;
    }
}
?>

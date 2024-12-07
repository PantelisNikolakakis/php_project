<?php
class Database {
    private $host = 'localhost';
    private $user = 'root';
    private $password = 'password';
    private $database = 'technicalTest';
    private $connection;

    public function connect() {
        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->database);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }

        return $this->connection;
    }

    // Get the MySQLi connection instance (so it can be used for queries)
    public function getConnection() {
        return $this->connection; // Return the MySQLi connection instance
    }

    // Method to prepare a SQL query (using MySQLi)
    public function prepare($sql) {
        return $this->connection->prepare($sql); // Return prepared statement
    }

    // Execute a prepared statement with bind_param
    public function execute($stmt) {
        return $stmt->execute(); // Execute the prepared statement
    }

    // Fetch the results of a query (for SELECT queries)
    public function fetch($stmt) {
        $result = $stmt->get_result(); // Get the result set
        return $result->fetch_assoc(); // Fetch and return the first result row
    }

    // Fetch all results of a query (useful for multiple rows)
    public function fetchAll($stmt) {
        $result = $stmt->get_result(); // Get the result set
        return $result->fetch_all(MYSQLI_ASSOC); // Return all rows as an associative array
    }
}
?>

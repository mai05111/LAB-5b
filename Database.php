<?php
class Database {
    private $host = "localhost";
    private $user = "root";
    private $password = ""; 
    private $database = "lab_5b";
    public $conn;

    public function getConnection() {
        try {
            $this->conn = new mysqli($this->host, $this->user, $this->password, $this->database);
            return $this->conn;
        } catch (mysqli_sql_exception $e) {
            die("Database Connection Error: " . $e->getMessage());
        }
    }
}
?>

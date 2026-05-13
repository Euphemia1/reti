<?php
/**
 * Database Configuration
 * Rising East Training Institute
 */

class Database {
    private $host = '127.0.0.1';
    private $db_name = 'u754414236_reti';
    private $username = 'u754414236_reti';   // your Hostinger DB username
    private $password = 'Reti@2026';   // the password you set on Hostinger
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>

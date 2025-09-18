<?php
class Database {
    private $host = "localhost";
    private $db_name = "attendance_system3.0";
    private $username = "root";
    private $password = "";
    protected $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->pdo->exec("set names utf8");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}
?>

<?php
class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $host = "database";
        $db_name = "appart_db";
        $username = "user";
        $password = "password";

        try {
            $dsn = "pgsql:host=" . $host . ";port=5432;dbname=" . $db_name;
            $this->conn = new PDO($dsn, $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            throw new Exception("Erreur de connexion : " . $exception->getMessage());
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance->conn;
    }
}
?>

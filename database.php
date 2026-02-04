<?php
/**
 * Database configuration and setup for HF Markets Assessment
 * Uses SQLite for data storage
 */

class Database {
    private static $instance = null;
    private $db;

    private function __construct() {
        try {
            $this->db = new PDO('sqlite:' . __DIR__ . '/hfm_users.db');
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->createTables();
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->db;
    }

    private function createTables() {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            first_name TEXT NOT NULL,
            full_name TEXT NOT NULL,
            email TEXT UNIQUE NOT NULL,
            country_code TEXT NOT NULL,
            phone TEXT NOT NULL,
            password TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";

        $this->db->exec($sql);
    }

    /**
     * Check if email already exists in database
     */
    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Register a new user
     */
    public function registerUser($firstName, $fullName, $email, $countryCode, $phone, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("INSERT INTO users (first_name, full_name, email, country_code, phone, password) VALUES (:first_name, :full_name, :email, :country_code, :phone, :password)");

        return $stmt->execute([
            ':first_name' => htmlspecialchars($firstName, ENT_QUOTES, 'UTF-8'),
            ':full_name' => htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8'),
            ':email' => filter_var($email, FILTER_SANITIZE_EMAIL),
            ':country_code' => htmlspecialchars($countryCode, ENT_QUOTES, 'UTF-8'),
            ':phone' => htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'),
            ':password' => $hashedPassword
        ]);
    }

    /**
     * Authenticate user login
     */
    public function authenticateUser($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}

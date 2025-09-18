<?php

require_once "model.php";

class User extends Model {
    protected $table = "users";

    public $id;
    public $name;
    public $email;
    public $role;
    public $course_id;
    public $year_level; // Only for students
    
    public function __construct() {
        parent::__construct(); // This is used for connecting to database
    }

    // REGISTER
    public function register($name, $email, $password, $role, $course_id = null, $year_level = null) {
        $stmt = $this->pdo->prepare("SELECT id FROM $this->table WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            echo "Email was used.";
            return false;
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert user into database
        $stmt = $this->pdo->prepare("INSERT INTO $this->table 
        (name, email, password, role, course_id, year_level) 
        VALUES (:name, :email, :password, :role, :course_id, :year_level)");

        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => $role,
            'course_id' => $course_id,
            'year_level' => $year_level
        ]);
    }

    // LOGIN
    public function login($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start(); 
            }

            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['user_role'] = $user['role']; // important for role check

            return true;
        }

        return false; // Invalid credentials
    }


}


?>
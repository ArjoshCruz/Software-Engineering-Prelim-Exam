<?php
require_once "database.php"; // load Database class
require_once "model.php";

class Course extends Model {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getConnection() {
        return $this->pdo;
    }

    public function getAllCourses() {
        $stmt = $this->pdo->prepare("SELECT id, course_name FROM courses");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCourseById($id) {
        $stmt = $this->pdo->prepare("SELECT id, course_name FROM courses WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addCourse($courseName) {
        $stmt = $this->pdo->prepare("INSERT INTO courses (course_name) VALUES (?)");
        return $stmt->execute([$courseName]);
    }

    public function updateCourse($id, $courseName) {
        $stmt = $this->pdo->prepare("UPDATE courses SET course_name = ? WHERE id = ?");
        return $stmt->execute([$courseName, $id]);
    }

    public function deleteCourse($id) {
        $stmt = $this->pdo->prepare("DELETE FROM courses WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

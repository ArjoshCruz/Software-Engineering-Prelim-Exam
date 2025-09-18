<?php
require_once "../../core/course.php"; 

class CourseDashboard extends Course {
    private $courseId;

    public function __construct($pdo, $courseId) {
        parent::__construct($pdo); 
        $this->courseId = $courseId;
    }

    // Get all students in this course
    public function getStudents($yearLevel = null) {
        $sql = "SELECT id, name, email, year_level 
                FROM users 
                WHERE course_id = ?";
        $params = [$this->courseId];

        // Optional filter by year level
        if ($yearLevel) {
            $sql .= " AND year_level = ?";
            $params[] = $yearLevel;
        }

        // Custom order: 1st → 2nd → 3rd → 4th → Irregular
        $sql .= " ORDER BY FIELD(year_level, '1st Year','2nd Year','3rd Year','4th Year','Irregular'), name ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get attendance for a student
    public function getAttendance($studentId) {
        $stmt = $this->pdo->prepare("
            SELECT date, status, is_late
            FROM attendance
            WHERE user_id = :user_id
            ORDER BY date DESC
        ");
        $stmt->execute(['user_id' => $studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCourseInfo() {
        return parent::getCourseById($this->courseId);
    }

    public function getStudent($studentId) {
        $stmt = $this->pdo->prepare("SELECT id, name, email, year_level FROM users WHERE id = ? AND course_id = ?");
        $stmt->execute([$studentId, $this->courseId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStudentsByYear($yearLevel) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM users 
            WHERE role = 'student' AND course_id = ? AND year_level = ? 
            ORDER BY name ASC
        ");
        $stmt->execute([$this->courseId, $yearLevel]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

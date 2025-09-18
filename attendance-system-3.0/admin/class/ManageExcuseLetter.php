<?php
require_once 'ExcuseLetter.php';
require_once '../core/model.php';

class ManageExcuseLetter extends ExcuseLetter {
    public function __construct() {
        parent::__construct();
    }

    // Admin approving or rejecting an excuse letter
    public function handleAction($action, $id) {
        $status = ($action === 'approve') ? 'approved' : 'rejected';
        return $this->updateStatus($id, $status);
    }

    // Filter by Course
    public function getLetterByCourse($course_id = null) {
        return $this->getAll($course_id);
    }

    // Fetch all courses for dropdown
    public function getCourses() {
        $stmt = $this->pdo->query("SELECT * FROM courses");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
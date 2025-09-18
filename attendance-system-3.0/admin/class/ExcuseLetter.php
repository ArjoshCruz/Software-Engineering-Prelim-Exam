<?php
require_once '../core/model.php';

class ExcuseLetter extends Model {
    protected $table = 'excuse_letters';

    public function __construct() {
        parent::__construct();
    }

    // Admin approval of excuse letter
    public function updateStatus($id, $status) {
        $stmt = $this->pdo->prepare("
            UPDATE $this->table SET status=:status WHERE id=:id
        ");
        return $stmt->execute([
            'status' => $status,
            'id' => $id
        ]);
    }

    // Admin viewing all excuse letters
    public function getAll($course_id = null) {
        $where = "";
        $params = [];
        if ($course_id) {
            $where = "WHERE u.course_id = :course_id";
            $params['course_id'] = $course_id;
        }

        $sql = "SELECT el.*, u.name, u.year_level, c.course_name
                FROM excuse_letters el
                JOIN users u ON el.user_id = u.id
                JOIN courses c ON u.course_id = c.id
                $where
                ORDER BY el.submitted_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
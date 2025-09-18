<?php
require_once '../core/model.php';

class ExcuseLetter extends Model {
    protected $table = "excuse_letters";

    public function __construct() {
        parent::__construct();
    }

    // Student submitting an excuse letter
    public function submit($user_id, $subject, $reason, $attachment = null) {
        $stmt = $this->pdo->prepare("
            INSERT INTO $this->table (user_id, subject, reason, attachment)
            VALUES (:user_id, :subject, :reason, :attachment)");

        return $stmt->execute([
            'user_id' => $user_id,
            'subject' => $subject,
            'reason' => $reason,
            'attachment' => $attachment
        ]);
    }

    // Student viewing their own excuse letters
    public function getByUser($user_id) {
        $stmt = $this->pdo->prepare("
                    SELECT * FROM $this->table WHERE user_id=:user_id ORDER BY submitted_at DESC
                ");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
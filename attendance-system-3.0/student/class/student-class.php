<?php
require_once "../core/user.php";
require_once "../core/course.php";

class StudentDashboard {
    private $user;
    private $course;
    private $studentId;

    public function __construct($studentId) {
        $this->studentId = $studentId;
        $this->user = new User();
        $this->course = new Course($this->user->getConnection());
    }

    public function getStudentInfo() {
        $student = $this->user->read("users", $this->studentId);
        $course = $this->course->getCourseById($student['course_id'] ?? 0);
        return [
            'name' => $student['name'] ?? 'N/A',
            'course_name' => $course['course_name'] ?? 'N/A',
            'year_level' => $student['year_level'] ?? 'N/A'
        ];
    }

    public function fileAttendance($status, $dateTime = null) {
        // Use provided datetime or fallback to now
        $attendanceDateTime = $dateTime ?: date("Y-m-d H:i:s");

        // Determine if late (after 8:30 AM)
        $isLate = (date("H:i", strtotime($attendanceDateTime)) > "08:30") ? 1 : 0;

        $stmt = $this->user->getConnection()->prepare("
            INSERT INTO attendance (user_id, date, status, is_late)
            VALUES (:user_id, :date_time, :status, :is_late)
        ");
        $stmt->execute([
            'user_id'   => $this->studentId,
            'date_time' => $attendanceDateTime,
            'status'    => ucfirst($status),
            'is_late'   => $isLate
        ]);

        return "✅ Attendance filed successfully.";
    }


    public function getAttendanceHistoryGrouped() {
        $stmt = $this->user->getConnection()->prepare("
            SELECT DATE(date) AS attendance_date, status, is_late, date
            FROM attendance
            WHERE user_id = :user_id
            ORDER BY date DESC
        ");
        $stmt->execute(['user_id' => $this->studentId]);
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $grouped = [];
        foreach ($records as $rec) {
            $day = $rec['attendance_date'];
            if (!isset($grouped[$day])) {
                $grouped[$day] = [];
            }
            $grouped[$day][] = $rec;
        }
        return $grouped;
    }
}

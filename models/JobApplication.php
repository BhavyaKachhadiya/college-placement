<?php

require_once __DIR__ . '/../config/Database.php';

class JobApplication {
    private $conn;
    private $table = 'applications';

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    /**
     * Get all applications (for Admin view)
     * Joins with students and companies to get relevant details.
     */
    public function getAllForAdmin($status = '') {
        $sql = "SELECT a.id, a.student_id, a.company_id, a.status, a.applied_at,
                       s.name as student_name, s.gr_no, s.enroll_no, s.resume_file, s.cgpa, s.department,
                       c.company_name, c.job_role
                FROM `{$this->table}` a
                JOIN `students` s ON a.student_id = s.id
                JOIN `companies` c ON a.company_id = c.id
                WHERE 1=1";
        
        $params = [];

        if (!empty($status)) {
            $sql .= " AND a.status = :status";
            $params[':status'] = $status;
        }

        $sql .= " ORDER BY a.applied_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all applications for a specific student
     */
    public function getAllForStudent($studentId) {
        $sql = "SELECT a.id, a.company_id, a.status, a.applied_at,
                       c.company_name, c.job_role, c.location, c.package_lpa
                FROM `{$this->table}` a
                JOIN `companies` c ON a.company_id = c.id
                WHERE a.student_id = :student_id
                ORDER BY a.applied_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':student_id' => (int)$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get application by ID
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM `{$this->table}` WHERE `id` = :id LIMIT 1");
        $stmt->execute([':id' => (int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Check if a student has already applied to a specific company/job
     */
    public function hasApplied($studentId, $companyId) {
        $stmt = $this->conn->prepare("SELECT id FROM `{$this->table}` WHERE `student_id` = :student_id AND `company_id` = :company_id LIMIT 1");
        $stmt->execute([
            ':student_id' => (int)$studentId,
            ':company_id' => (int)$companyId
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    /**
     * Get a list of company IDs that a student has applied to
     */
    public function getAppliedCompanyIds($studentId) {
        $stmt = $this->conn->prepare("SELECT company_id FROM `{$this->table}` WHERE `student_id` = :student_id");
        $stmt->execute([':student_id' => (int)$studentId]);
        $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $result ? $result : [];
    }

    /**
     * Apply for a job
     */
    public function apply($studentId, $companyId) {
        // Double check they haven't applied
        if ($this->hasApplied($studentId, $companyId)) {
            return false; // Already applied
        }

        $sql = "INSERT INTO `{$this->table}` (`student_id`, `company_id`, `status`) VALUES (:student_id, :company_id, 'Pending')";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':student_id' => (int)$studentId,
            ':company_id' => (int)$companyId
        ]);
    }

    /**
     * Update application status (Admin only)
     */
    public function updateStatus($id, $status) {
        $allowedStatuses = ['Pending', 'Reviewed', 'Accepted', 'Rejected'];
        if (!in_array($status, $allowedStatuses)) {
            return false;
        }

        $sql = "UPDATE `{$this->table}` SET `status` = :status WHERE `id` = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':status' => $status,
            ':id'     => (int)$id
        ]);
    }
}

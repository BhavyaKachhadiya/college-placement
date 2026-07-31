<?php

require_once __DIR__ . '/../config/Database.php';

class Company {
    private $conn;
    private $table = 'companies';

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    /**
     * Get all company placement drives with optional search filter and status filter
     */
    public function getAll($search = '', $status = '') {
        $sql = "SELECT * FROM `{$this->table}` WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (`company_name` LIKE :search0 OR `job_role` LIKE :search1 OR `location` LIKE :search2 OR `industry` LIKE :search3)";
            $searchVal = "%{$search}%";
            $params[':search0'] = $searchVal;
            $params[':search1'] = $searchVal;
            $params[':search2'] = $searchVal;
            $params[':search3'] = $searchVal;
        }

        if (!empty($status)) {
            $sql .= " AND `status` = :status";
            $params[':status'] = $status;
        }

        $sql .= " ORDER BY FIELD(`status`, 'Active', 'Upcoming', 'Closed'), `created_at` DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get single company drive by ID
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM `{$this->table}` WHERE `id` = :id LIMIT 1");
        $stmt->execute([':id' => (int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create new company placement drive
     */
    public function create($data) {
        $sql = "INSERT INTO `{$this->table}` 
                (`company_name`, `industry`, `job_role`, `vacancies`, `package_lpa`, `location`, `eligibility`, `deadline`, `description`, `contact_email`, `apply_link`, `status`) 
                VALUES 
                (:company_name, :industry, :job_role, :vacancies, :package_lpa, :location, :eligibility, :deadline, :description, :contact_email, :apply_link, :status)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':company_name'  => $data['company_name'],
            ':industry'      => !empty($data['industry']) ? $data['industry'] : 'IT & Software',
            ':job_role'      => $data['job_role'],
            ':vacancies'     => !empty($data['vacancies']) ? (int)$data['vacancies'] : 1,
            ':package_lpa'   => !empty($data['package_lpa']) ? (float)$data['package_lpa'] : null,
            ':location'      => !empty($data['location']) ? $data['location'] : 'Ahmedabad',
            ':eligibility'  => !empty($data['eligibility']) ? $data['eligibility'] : null,
            ':deadline'     => !empty($data['deadline']) ? $data['deadline'] : null,
            ':description'  => !empty($data['description']) ? $data['description'] : null,
            ':contact_email' => !empty($data['contact_email']) ? $data['contact_email'] : null,
            ':apply_link'    => !empty($data['apply_link']) ? $data['apply_link'] : null,
            ':status'        => !empty($data['status']) ? $data['status'] : 'Active'
        ]);
    }

    /**
     * Update existing company drive
     */
    public function update($id, $data) {
        $sql = "UPDATE `{$this->table}` SET 
                `company_name` = :company_name,
                `industry`     = :industry,
                `job_role`     = :job_role,
                `vacancies`    = :vacancies,
                `package_lpa`  = :package_lpa,
                `location`     = :location,
                `eligibility` = :eligibility,
                `deadline`    = :deadline,
                `description` = :description,
                `contact_email`= :contact_email,
                `apply_link`   = :apply_link,
                `status`       = :status
                WHERE `id` = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id'            => (int)$id,
            ':company_name'  => $data['company_name'],
            ':industry'      => !empty($data['industry']) ? $data['industry'] : 'IT & Software',
            ':job_role'      => $data['job_role'],
            ':vacancies'     => !empty($data['vacancies']) ? (int)$data['vacancies'] : 1,
            ':package_lpa'   => !empty($data['package_lpa']) ? (float)$data['package_lpa'] : null,
            ':location'      => !empty($data['location']) ? $data['location'] : 'Ahmedabad',
            ':eligibility'  => !empty($data['eligibility']) ? $data['eligibility'] : null,
            ':deadline'     => !empty($data['deadline']) ? $data['deadline'] : null,
            ':description'  => !empty($data['description']) ? $data['description'] : null,
            ':contact_email' => !empty($data['contact_email']) ? $data['contact_email'] : null,
            ':apply_link'    => !empty($data['apply_link']) ? $data['apply_link'] : null,
            ':status'        => !empty($data['status']) ? $data['status'] : 'Active'
        ]);
    }

    /**
     * Delete company drive
     */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM `{$this->table}` WHERE `id` = :id");
        return $stmt->execute([':id' => (int)$id]);
    }

    /**
     * Summary statistics for Dashboard and Overview Cards
     */
    public function getStats() {
        $totalCompanies = (int)$this->conn->query("SELECT COUNT(*) FROM `{$this->table}`")->fetchColumn();
        $activeDrives   = (int)$this->conn->query("SELECT COUNT(*) FROM `{$this->table}` WHERE `status` = 'Active'")->fetchColumn();
        $totalVacancies = (int)$this->conn->query("SELECT SUM(`vacancies`) FROM `{$this->table}` WHERE `status` = 'Active'")->fetchColumn();
        $highestPackage = (float)$this->conn->query("SELECT MAX(`package_lpa`) FROM `{$this->table}`")->fetchColumn();

        return [
            'total_companies' => $totalCompanies,
            'active_drives'   => $activeDrives,
            'total_vacancies' => $totalVacancies,
            'highest_package' => $highestPackage
        ];
    }
}

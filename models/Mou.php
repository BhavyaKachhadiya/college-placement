<?php
require_once __DIR__ . '/../config/Database.php';

class Mou {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
        $this->autoUpdateExpiredStatus();
    }

    /**
     * Auto-update status to Expired if expiry_date has passed and status is Active
     */
    public function autoUpdateExpiredStatus() {
        $today = date('Y-m-d');
        $sql = "UPDATE `mous` SET `status` = 'Expired' WHERE `expiry_date` < :today AND `status` = 'Active'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':today' => $today]);
    }

    /**
     * Fetch all MOUs with optional filter parameters
     */
    public function getAll($search = '', $year = '', $status = '') {
        $sql = "SELECT * FROM `mous` WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (`company_name` LIKE :search0 OR `contact_person` LIKE :search1 OR `email` LIKE :search2 OR `description` LIKE :search3)";
            $searchVal = '%' . $search . '%';
            $params[':search0'] = $searchVal;
            $params[':search1'] = $searchVal;
            $params[':search2'] = $searchVal;
            $params[':search3'] = $searchVal;
        }

        if (!empty($year) && $year !== 'all') {
            $sql .= " AND `year` = :year";
            $params[':year'] = (int)$year;
        }

        if (!empty($status) && $status !== 'all') {
            $sql .= " AND `status` = :status";
            $params[':status'] = $status;
        }

        $sql .= " ORDER BY `signed_date` DESC, `id` DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Fetch single MOU record by ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM `mous` WHERE `id` = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => (int)$id]);
        return $stmt->fetch();
    }

    /**
     * Fetch distinct suggestions for MOU searches
     */
    public function getSuggestions() {
        $companies = $this->db->query(
            "SELECT DISTINCT `company_name` FROM `mous` WHERE `company_name` IS NOT NULL AND TRIM(`company_name`) != '' ORDER BY `company_name` ASC LIMIT 15"
        )->fetchAll(PDO::FETCH_COLUMN);

        $contacts = $this->db->query(
            "SELECT DISTINCT `contact_person` FROM `mous` WHERE `contact_person` IS NOT NULL AND TRIM(`contact_person`) != '' ORDER BY `contact_person` ASC LIMIT 15"
        )->fetchAll(PDO::FETCH_COLUMN);

        return [
            'companies' => $companies ?: [],
            'contacts'  => $contacts ?: []
        ];
    }

    /**
     * Create a new MOU
     */
    public function create($data) {
        $year = !empty($data['signed_date']) ? (int)date('Y', strtotime($data['signed_date'])) : (int)date('Y');
        
        $sql = "INSERT INTO `mous` (
            `company_name`, `contact_person`, `email`, `phone`, `address`, 
            `website`, `signed_date`, `expiry_date`, `year`, `description`, 
            `report_file`, `status`
        ) VALUES (
            :company_name, :contact_person, :email, :phone, :address, 
            :website, :signed_date, :expiry_date, :year, :description, 
            :report_file, :status
        )";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            ':company_name'   => trim($data['company_name']),
            ':contact_person' => trim($data['contact_person'] ?? ''),
            ':email'          => trim($data['email'] ?? ''),
            ':phone'          => trim($data['phone'] ?? ''),
            ':address'        => trim($data['address'] ?? ''),
            ':website'        => trim($data['website'] ?? ''),
            ':signed_date'    => $data['signed_date'],
            ':expiry_date'    => $data['expiry_date'],
            ':year'           => $year,
            ':description'    => trim($data['description'] ?? ''),
            ':report_file'    => $data['report_file'] ?? null,
            ':status'         => $data['status'] ?? 'Active'
        ]);

        return $result ? $this->db->lastInsertId() : false;
    }

    /**
     * Update an existing MOU
     */
    public function update($id, $data) {
        $year = !empty($data['signed_date']) ? (int)date('Y', strtotime($data['signed_date'])) : (int)date('Y');

        $sql = "UPDATE `mous` SET 
            `company_name` = :company_name,
            `contact_person` = :contact_person,
            `email` = :email,
            `phone` = :phone,
            `address` = :address,
            `website` = :website,
            `signed_date` = :signed_date,
            `expiry_date` = :expiry_date,
            `year` = :year,
            `description` = :description,
            `status` = :status";

        $params = [
            ':id'             => (int)$id,
            ':company_name'   => trim($data['company_name']),
            ':contact_person' => trim($data['contact_person'] ?? ''),
            ':email'          => trim($data['email'] ?? ''),
            ':phone'          => trim($data['phone'] ?? ''),
            ':address'        => trim($data['address'] ?? ''),
            ':website'        => trim($data['website'] ?? ''),
            ':signed_date'    => $data['signed_date'],
            ':expiry_date'    => $data['expiry_date'],
            ':year'           => $year,
            ':description'    => trim($data['description'] ?? ''),
            ':status'         => $data['status'] ?? 'Active'
        ];

        // Update report_file if a new file was uploaded
        if (array_key_exists('report_file', $data)) {
            $sql .= ", `report_file` = :report_file";
            $params[':report_file'] = $data['report_file'];
        }

        $sql .= " WHERE `id` = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Delete an MOU
     */
    public function delete($id) {
        $sql = "DELETE FROM `mous` WHERE `id` = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => (int)$id]);
    }

    /**
     * Get distinct years for multi-year tracking
     */
    public function getYears() {
        $sql = "SELECT DISTINCT `year` FROM `mous` ORDER BY `year` DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Get KPI statistics
     */
    public function getStats() {
        $sql = "SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN `status` = 'Active' THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN `status` = 'Expired' THEN 1 ELSE 0 END) as expired,
            SUM(CASE WHEN `status` = 'Terminated' THEN 1 ELSE 0 END) as `terminated`,
            COUNT(DISTINCT `year`) as years_count
            FROM `mous`";
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }
}

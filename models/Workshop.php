<?php
require_once __DIR__ . '/../config/Database.php';

class Workshop {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Fetch all workshops with filters
     */
    public function getAll($search = '', $year = '', $certificate = '') {
        $sql = "SELECT * FROM `workshops` WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (`title` LIKE :search OR `instructor_name` LIKE :search OR `company_name` LIKE :search OR `venue` LIKE :search OR `description` LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        if (!empty($year) && $year !== 'all') {
            $sql .= " AND `academic_year` = :year";
            $params[':year'] = (int)$year;
        }

        if ($certificate !== '' && $certificate !== 'all') {
            $sql .= " AND `certificate` = :cert";
            $params[':cert'] = (int)$certificate;
        }

        $sql .= " ORDER BY `held_on` DESC, `id` DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Get single workshop by ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM `workshops` WHERE `id` = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => (int)$id]);
        return $stmt->fetch();
    }

    /**
     * Create a new workshop
     */
    public function create($data) {
        $academicYear = !empty($data['held_on']) ? (int)date('Y', strtotime($data['held_on'])) : (int)date('Y');

        $sql = "INSERT INTO `workshops` (
            `title`, `instructor_name`, `company_name`, `instructor_email`, `venue`, 
            `held_on`, `duration`, `description`, `certificate`, `total_participants`, 
            `academic_year`, `report_file`
        ) VALUES (
            :title, :instructor_name, :company_name, :instructor_email, :venue, 
            :held_on, :duration, :description, :certificate, :total_participants, 
            :academic_year, :report_file
        )";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            ':title'              => trim($data['title']),
            ':instructor_name'   => trim($data['instructor_name'] ?? ''),
            ':company_name'       => trim($data['company_name'] ?? ''),
            ':instructor_email'  => trim($data['instructor_email'] ?? ''),
            ':venue'              => trim($data['venue'] ?? ''),
            ':held_on'            => $data['held_on'],
            ':duration'           => (int)($data['duration'] ?? 1),
            ':description'        => trim($data['description'] ?? ''),
            ':certificate'        => isset($data['certificate']) ? (int)$data['certificate'] : 0,
            ':total_participants' => (int)($data['total_participants'] ?? 0),
            ':academic_year'      => $academicYear,
            ':report_file'        => $data['report_file'] ?? null
        ]);

        return $result ? $this->db->lastInsertId() : false;
    }

    /**
     * Update an existing workshop
     */
    public function update($id, $data) {
        $academicYear = !empty($data['held_on']) ? (int)date('Y', strtotime($data['held_on'])) : (int)date('Y');

        $sql = "UPDATE `workshops` SET 
            `title` = :title,
            `instructor_name` = :instructor_name,
            `company_name` = :company_name,
            `instructor_email` = :instructor_email,
            `venue` = :venue,
            `held_on` = :held_on,
            `duration` = :duration,
            `description` = :description,
            `certificate` = :certificate,
            `total_participants` = :total_participants,
            `academic_year` = :academic_year";

        $params = [
            ':id'                 => (int)$id,
            ':title'              => trim($data['title']),
            ':instructor_name'   => trim($data['instructor_name'] ?? ''),
            ':company_name'       => trim($data['company_name'] ?? ''),
            ':instructor_email'  => trim($data['instructor_email'] ?? ''),
            ':venue'              => trim($data['venue'] ?? ''),
            ':held_on'            => $data['held_on'],
            ':duration'           => (int)($data['duration'] ?? 1),
            ':description'        => trim($data['description'] ?? ''),
            ':certificate'        => isset($data['certificate']) ? (int)$data['certificate'] : 0,
            ':total_participants' => (int)($data['total_participants'] ?? 0),
            ':academic_year'      => $academicYear
        ];

        if (array_key_exists('report_file', $data)) {
            $sql .= ", `report_file` = :report_file";
            $params[':report_file'] = $data['report_file'];
        }

        $sql .= " WHERE `id` = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Delete workshop
     */
    public function delete($id) {
        $sql = "DELETE FROM `workshops` WHERE `id` = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => (int)$id]);
    }

    /**
     * Get distinct academic years
     */
    public function getYears() {
        $sql = "SELECT DISTINCT `academic_year` FROM `workshops` ORDER BY `academic_year` DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Get Workshop Statistics
     */
    public function getStats() {
        $sql = "SELECT 
            COUNT(*) as total_workshops,
            SUM(`total_participants`) as total_participants,
            SUM(CASE WHEN `certificate` = 1 THEN 1 ELSE 0 END) as certificate_count,
            COUNT(DISTINCT `academic_year`) as years_count
            FROM `workshops`";
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }

    /**
     * Fetch grouped distinct suggestions for Title, Company, and Host / Instructor Name
     */
    public function getSuggestions() {
        $titles = $this->db->query(
            "SELECT DISTINCT `title` FROM `workshops` WHERE `title` IS NOT NULL AND TRIM(`title`) != '' ORDER BY `title` ASC"
        )->fetchAll(PDO::FETCH_COLUMN);

        $companies = $this->db->query(
            "SELECT DISTINCT `company_name` FROM `workshops` WHERE `company_name` IS NOT NULL AND TRIM(`company_name`) != '' ORDER BY `company_name` ASC"
        )->fetchAll(PDO::FETCH_COLUMN);

        $instructors = $this->db->query(
            "SELECT DISTINCT `instructor_name` FROM `workshops` WHERE `instructor_name` IS NOT NULL AND TRIM(`instructor_name`) != '' ORDER BY `instructor_name` ASC"
        )->fetchAll(PDO::FETCH_COLUMN);

        return [
            'titles'      => $titles ?: [],
            'companies'   => $companies ?: [],
            'instructors' => $instructors ?: []
        ];
    }
}

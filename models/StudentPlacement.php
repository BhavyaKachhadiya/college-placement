<?php
require_once __DIR__ . '/../config/Database.php';

class StudentPlacement {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Fetch student records with filters
     */
    public function getAll($search = '', $passing_year = '', $department = '', $status = '') {
        $sql = "SELECT * FROM `students` WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (`enroll_no` LIKE :search1 OR `name` LIKE :search2 OR `email` LIKE :search3 OR `company_name` LIKE :search4 OR `skills` LIKE :search5)";
            $searchVal = '%' . $search . '%';
            $params[':search1'] = $searchVal;
            $params[':search2'] = $searchVal;
            $params[':search3'] = $searchVal;
            $params[':search4'] = $searchVal;
            $params[':search5'] = $searchVal;
        }

        if (!empty($passing_year) && $passing_year !== 'all') {
            $sql .= " AND `passing_year` = :year";
            $params[':year'] = (int)$passing_year;
        }

        if (!empty($department) && $department !== 'all') {
            $sql .= " AND `department` = :dept";
            $params[':dept'] = $department;
        }

        if (!empty($status) && $status !== 'all') {
            $sql .= " AND `placement_status` = :status";
            $params[':status'] = $status;
        }

        $sql .= " ORDER BY `passing_year` DESC, `cgpa` DESC, `id` DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }


    /**
     * Get single student profile by ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM `students` WHERE `id` = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => (int)$id]);
        return $stmt->fetch();
    }

    /**
     * Fetch grouped distinct suggestions for Placement/Internship searches
     */
    public function getSuggestions($statusFilter = '') {
        $where = "WHERE 1=1";
        $params = [];
        if (!empty($statusFilter) && $statusFilter !== 'all') {
            $where .= " AND `placement_status` = :status";
            $params[':status'] = $statusFilter;
        }

        $sqlNames = "SELECT DISTINCT `name` FROM `students` {$where} AND `name` IS NOT NULL AND TRIM(`name`) != '' ORDER BY `name` ASC LIMIT 15";
        $stmt1 = $this->db->prepare($sqlNames);
        $stmt1->execute($params);
        $names = $stmt1->fetchAll(PDO::FETCH_COLUMN);

        $sqlCompanies = "SELECT DISTINCT `company_name` FROM `students` {$where} AND `company_name` IS NOT NULL AND TRIM(`company_name`) != '' ORDER BY `company_name` ASC LIMIT 15";
        $stmt2 = $this->db->prepare($sqlCompanies);
        $stmt2->execute($params);
        $companies = $stmt2->fetchAll(PDO::FETCH_COLUMN);

        $sqlRoles = "SELECT DISTINCT `designation` FROM `students` {$where} AND `designation` IS NOT NULL AND TRIM(`designation`) != '' ORDER BY `designation` ASC LIMIT 15";
        $stmt3 = $this->db->prepare($sqlRoles);
        $stmt3->execute($params);
        $designations = $stmt3->fetchAll(PDO::FETCH_COLUMN);

        $sqlDepts = "SELECT DISTINCT `department` FROM `students` {$where} AND `department` IS NOT NULL AND TRIM(`department`) != '' ORDER BY `department` ASC LIMIT 15";
        $stmt4 = $this->db->prepare($sqlDepts);
        $stmt4->execute($params);
        $departments = $stmt4->fetchAll(PDO::FETCH_COLUMN);

        return [
            'names'        => $names ?: [],
            'companies'    => $companies ?: [],
            'designations' => $designations ?: [],
            'departments'  => $departments ?: []
        ];
    }

    /**
     * Create single student record
     */
    public function create($data) {
        $enrollNo = trim($data['enroll_no']);
        // Default password = bcrypt hash of enroll_no
        $hashedPassword = password_hash($enrollNo, PASSWORD_BCRYPT);

        $sql = "INSERT INTO `students` (
            `enroll_no`, `name`, `email`, `phone`, `gender`, `dob`, 
            `department`, `semester`, `cgpa`, `passing_year`, `address`, 
            `skills`, `password`, `placement_status`, `company_name`, `designation`, 
            `package_lpa`, `offer_letter_file`
        ) VALUES (
            :enroll_no, :name, :email, :phone, :gender, :dob, 
            :department, :semester, :cgpa, :passing_year, :address, 
            :skills, :password, :placement_status, :company_name, :designation, 
            :package_lpa, :offer_letter_file
        )";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            ':enroll_no'        => $enrollNo,
            ':name'             => trim($data['name']),
            ':email'            => trim($data['email'] ?? ''),
            ':phone'            => trim($data['phone'] ?? ''),
            ':gender'           => $data['gender'] ?? 'Male',
            ':dob'              => !empty($data['dob']) ? $data['dob'] : null,
            ':department'       => trim($data['department']),
            ':semester'         => (int)($data['semester'] ?? 8),
            ':cgpa'             => (float)($data['cgpa'] ?? 0.00),
            ':passing_year'     => (int)($data['passing_year'] ?? date('Y')),
            ':address'          => trim($data['address'] ?? ''),
            ':skills'           => trim($data['skills'] ?? ''),
            ':password'         => $hashedPassword,
            ':placement_status' => $data['placement_status'] ?? 'Unplaced',
            ':company_name'     => trim($data['company_name'] ?? ''),
            ':designation'      => trim($data['designation'] ?? ''),
            ':package_lpa'      => !empty($data['package_lpa']) ? (float)$data['package_lpa'] : null,
            ':offer_letter_file' => $data['offer_letter_file'] ?? null
        ]);

        return $result ? $this->db->lastInsertId() : false;
    }

    /**
     * Update existing student profile
     */
    public function update($id, $data) {
        $sql = "UPDATE `students` SET 
            `enroll_no` = :enroll_no,
            `name` = :name,
            `email` = :email,
            `phone` = :phone,
            `gender` = :gender,
            `dob` = :dob,
            `department` = :department,
            `semester` = :semester,
            `cgpa` = :cgpa,
            `passing_year` = :passing_year,
            `address` = :address,
            `skills` = :skills,
            `placement_status` = :placement_status,
            `company_name` = :company_name,
            `designation` = :designation,
            `package_lpa` = :package_lpa";

        $params = [
            ':id'               => (int)$id,
            ':enroll_no'        => trim($data['enroll_no']),
            ':name'             => trim($data['name']),
            ':email'            => trim($data['email'] ?? ''),
            ':phone'            => trim($data['phone'] ?? ''),
            ':gender'           => $data['gender'] ?? 'Male',
            ':dob'              => !empty($data['dob']) ? $data['dob'] : null,
            ':department'       => trim($data['department']),
            ':semester'         => (int)($data['semester'] ?? 8),
            ':cgpa'             => (float)($data['cgpa'] ?? 0.00),
            ':passing_year'     => (int)($data['passing_year'] ?? date('Y')),
            ':address'          => trim($data['address'] ?? ''),
            ':skills'           => trim($data['skills'] ?? ''),
            ':placement_status' => $data['placement_status'] ?? 'Unplaced',
            ':company_name'     => trim($data['company_name'] ?? ''),
            ':designation'      => trim($data['designation'] ?? ''),
            ':package_lpa'      => !empty($data['package_lpa']) ? (float)$data['package_lpa'] : null
        ];

        if (array_key_exists('offer_letter_file', $data)) {
            $sql .= ", `offer_letter_file` = :offer_letter_file";
            $params[':offer_letter_file'] = $data['offer_letter_file'];
        }

        $sql .= " WHERE `id` = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Delete student profile
     */
    public function delete($id) {
        $sql = "DELETE FROM `students` WHERE `id` = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => (int)$id]);
    }

    /**
     * Bulk Upload CSV Handler
     */
    public function bulkUploadCsv($filePath) {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new Exception("Uploaded CSV file is not readable.");
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) throw new Exception("Failed to open CSV file.");

        // Read header line
        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            throw new Exception("CSV file is empty.");
        }

        // Normalize header column names
        $headerMap = [];
        foreach ($header as $index => $colName) {
            $key = strtolower(trim(preg_replace('/[^a-zA-Z0-9_]/', '', str_replace([' ', '/'], '_', $colName))));
            $headerMap[$key] = $index;
        }

        $imported = 0;
        $updated  = 0;

        $sql = "INSERT INTO `students` (
            `enroll_no`, `name`, `email`, `phone`, `gender`, `department`, 
            `semester`, `cgpa`, `passing_year`, `skills`, `password`,
            `placement_status`, `company_name`, `designation`, `package_lpa`
        ) VALUES (
            :enroll_no, :name, :email, :phone, :gender, :department, 
            :semester, :cgpa, :passing_year, :skills, :password,
            :placement_status, :company_name, :designation, :package_lpa
        ) ON DUPLICATE KEY UPDATE 
            `name` = VALUES(`name`),
            `email` = VALUES(`email`),
            `phone` = VALUES(`phone`),
            `gender` = VALUES(`gender`),
            `department` = VALUES(`department`),
            `cgpa` = VALUES(`cgpa`),
            `passing_year` = VALUES(`passing_year`),
            `skills` = VALUES(`skills`),
            `placement_status` = VALUES(`placement_status`),
            `company_name` = VALUES(`company_name`),
            `designation` = VALUES(`designation`),
            `package_lpa` = VALUES(`package_lpa`)";


        $stmt = $this->db->prepare($sql);

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 2) continue; // Skip empty rows

            $enrollNo = isset($headerMap['enroll_no']) ? trim($row[$headerMap['enroll_no']]) : (isset($row[0]) ? trim($row[0]) : '');
            $name     = isset($headerMap['name']) ? trim($row[$headerMap['name']]) : (isset($row[1]) ? trim($row[1]) : '');

            if (empty($enrollNo) || empty($name)) continue;

            $email        = isset($headerMap['email']) ? trim($row[$headerMap['email']]) : ($row[2] ?? '');
            $phone        = isset($headerMap['phone']) ? trim($row[$headerMap['phone']]) : ($row[3] ?? '');
            $gender       = isset($headerMap['gender']) ? trim($row[$headerMap['gender']]) : ($row[4] ?? 'Male');
            $department   = isset($headerMap['department']) ? trim($row[$headerMap['department']]) : ($row[5] ?? 'Computer Engineering');
            $semester     = isset($headerMap['semester']) ? (int)$row[$headerMap['semester']] : 8;
            $cgpa         = isset($headerMap['cgpa']) ? (float)$row[$headerMap['cgpa']] : (isset($row[6]) ? (float)$row[6] : 0.0);
            $passing_year = isset($headerMap['passing_year']) ? (int)$row[$headerMap['passing_year']] : (isset($row[7]) ? (int)$row[7] : (int)date('Y'));
            $skills       = isset($headerMap['skills']) ? trim($row[$headerMap['skills']]) : ($row[8] ?? '');
            $status       = isset($headerMap['placement_status']) ? trim($row[$headerMap['placement_status']]) : ($row[9] ?? 'Unplaced');
            $company      = isset($headerMap['company_name']) ? trim($row[$headerMap['company_name']]) : ($row[10] ?? '');
            $designation  = isset($headerMap['designation']) ? trim($row[$headerMap['designation']]) : ($row[11] ?? '');
            $package      = isset($headerMap['package_lpa']) && !empty($row[$headerMap['package_lpa']]) ? (float)$row[$headerMap['package_lpa']] : null;

            // Validate gender & status
            if (!in_array($gender, ['Male', 'Female'])) $gender = 'Male';
            $validStatuses = ['Placed', 'Internship', 'Higher Studies', 'Business', 'Unplaced'];
            if (!in_array($status, $validStatuses)) $status = 'Unplaced';

            // Auto-generate password = bcrypt(enroll_no)
            $hashedPwd = password_hash($enrollNo, PASSWORD_BCRYPT);

            $stmt->execute([
                ':enroll_no'        => $enrollNo,
                ':name'             => $name,
                ':email'            => $email,
                ':phone'            => $phone,
                ':gender'           => $gender,
                ':department'       => $department,
                ':semester'         => $semester,
                ':cgpa'             => $cgpa,
                ':passing_year'     => $passing_year,
                ':skills'           => $skills,
                ':password'         => $hashedPwd,
                ':placement_status' => $status,
                ':company_name'     => $company,
                ':designation'      => $designation,
                ':package_lpa'      => $package
            ]);

            $imported++;
        }

        fclose($handle);
        return $imported;
    }

    /**
     * Get distinct passing years
     */
    public function getPassingYears() {
        $sql = "SELECT DISTINCT `passing_year` FROM `students` ORDER BY `passing_year` DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Get distinct departments
     */
    public function getDepartments() {
        $sql = "SELECT DISTINCT `department` FROM `students` ORDER BY `department` ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Get KPI placement statistics
     */
    public function getStats() {
        $sql = "SELECT 
            COUNT(*) as total_students,
            SUM(CASE WHEN `placement_status` = 'Placed' THEN 1 ELSE 0 END) as placed_count,
            SUM(CASE WHEN `placement_status` = 'Internship' THEN 1 ELSE 0 END) as internship_count,
            SUM(CASE WHEN `placement_status` = 'Higher Studies' THEN 1 ELSE 0 END) as higher_studies_count,
            SUM(CASE WHEN `placement_status` = 'Business' THEN 1 ELSE 0 END) as business_count,
            SUM(CASE WHEN `placement_status` = 'Unplaced' THEN 1 ELSE 0 END) as unplaced_count,
            AVG(`cgpa`) as avg_cgpa,
            MAX(`package_lpa`) as max_package
            FROM `students`";
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }
}

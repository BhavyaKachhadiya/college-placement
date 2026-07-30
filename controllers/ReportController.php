<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Mou.php';
require_once __DIR__ . '/../models/Workshop.php';
require_once __DIR__ . '/../models/StudentPlacement.php';

class ReportController {

    private $db;
    private $mouModel;
    private $workshopModel;
    private $placementModel;

    public function __construct() {
        $this->db             = Database::getConnection();
        $this->mouModel       = new Mou();
        $this->workshopModel  = new Workshop();
        $this->placementModel = new StudentPlacement();
    }

    public function index() {
        // ── Global Summary Stats ────────────────────────────────────────────
        $mouStats       = $this->mouModel->getStats();
        $workshopStats  = $this->workshopModel->getStats();
        $placementStats = $this->placementModel->getStats();

        // ── Placement: Status Breakdown ─────────────────────────────────────
        $placementStatusBreakdown = [
            'Placed'        => (int)($placementStats['placed_count']         ?? 0),
            'Internship'    => (int)($placementStats['internship_count']      ?? 0),
            'Higher Studies'=> (int)($placementStats['higher_studies_count']  ?? 0),
            'Business'      => (int)($placementStats['business_count']        ?? 0),
            'Unplaced'      => (int)($placementStats['unplaced_count']        ?? 0),
        ];

        // ── Placement: Department-wise Breakdown ────────────────────────────
        $deptBreakdown = $this->query(
            "SELECT `department`,
                COUNT(*) as total,
                SUM(CASE WHEN `placement_status` = 'Placed' THEN 1 ELSE 0 END) as placed,
                SUM(CASE WHEN `placement_status` = 'Internship' THEN 1 ELSE 0 END) as internship,
                SUM(CASE WHEN `placement_status` = 'Higher Studies' THEN 1 ELSE 0 END) as higher,
                SUM(CASE WHEN `placement_status` = 'Unplaced' THEN 1 ELSE 0 END) as unplaced,
                AVG(`cgpa`) as avg_cgpa,
                MAX(`package_lpa`) as max_package
             FROM `students`
             GROUP BY `department`
             ORDER BY total DESC"
        );

        // ── Placement: Year-wise Trend ──────────────────────────────────────
        $yearTrend = $this->query(
            "SELECT `passing_year`,
                COUNT(*) as total,
                SUM(CASE WHEN `placement_status` = 'Placed' THEN 1 ELSE 0 END) as placed,
                SUM(CASE WHEN `placement_status` = 'Internship' THEN 1 ELSE 0 END) as internship,
                SUM(CASE WHEN `placement_status` = 'Unplaced' THEN 1 ELSE 0 END) as unplaced,
                AVG(`cgpa`) as avg_cgpa,
                MAX(`package_lpa`) as max_package
             FROM `students`
             GROUP BY `passing_year`
             ORDER BY `passing_year` ASC"
        );

        // ── Placement: Gender Split ─────────────────────────────────────────
        $genderSplit = $this->query(
            "SELECT `gender`, COUNT(*) as total,
                SUM(CASE WHEN `placement_status` = 'Placed' THEN 1 ELSE 0 END) as placed
             FROM `students`
             GROUP BY `gender`"
        );

        // ── Placement: Top packages ─────────────────────────────────────────
        $topPackages = $this->query(
            "SELECT `name`, `department`, `company_name`, `designation`, `package_lpa`, `passing_year`
             FROM `students`
             WHERE `package_lpa` IS NOT NULL AND `package_lpa` > 0
             ORDER BY `package_lpa` DESC
             LIMIT 10"
        );

        // ── Workshops: Year-wise Trend ──────────────────────────────────────
        $workshopTrend = $this->query(
            "SELECT `academic_year`,
                COUNT(*) as total_workshops,
                SUM(`total_participants`) as total_participants,
                SUM(CASE WHEN `certificate` = 1 THEN 1 ELSE 0 END) as certified
             FROM `workshops`
             GROUP BY `academic_year`
             ORDER BY `academic_year` ASC"
        );

        // ── MOUs: Status by Year ────────────────────────────────────────────
        $mouByYear = $this->query(
            "SELECT `year`,
                COUNT(*) as total,
                SUM(CASE WHEN `status` = 'Active' THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN `status` = 'Expired' THEN 1 ELSE 0 END) as expired,
                SUM(CASE WHEN `status` = 'Terminated' THEN 1 ELSE 0 END) as term_count
             FROM `mous`
             GROUP BY `year`
             ORDER BY `year` ASC"
        );

        // ── CGPA Distribution Buckets ───────────────────────────────────────
        $cgpaBuckets = $this->query(
            "SELECT
                SUM(CASE WHEN cgpa >= 9.0 THEN 1 ELSE 0 END) as `9_to_10`,
                SUM(CASE WHEN cgpa >= 8.0 AND cgpa < 9.0 THEN 1 ELSE 0 END) as `8_to_9`,
                SUM(CASE WHEN cgpa >= 7.0 AND cgpa < 8.0 THEN 1 ELSE 0 END) as `7_to_8`,
                SUM(CASE WHEN cgpa >= 6.0 AND cgpa < 7.0 THEN 1 ELSE 0 END) as `6_to_7`,
                SUM(CASE WHEN cgpa < 6.0 THEN 1 ELSE 0 END) as `below_6`
             FROM `students`"
        );
        $cgpaBuckets = $cgpaBuckets[0] ?? [];

        // ── Available Passing Years for the picker ──────────────────────────
        $availableYears = $this->placementModel->getPassingYears();

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/report/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    /**
     * Student Report by Passing Year
     * URL: index.php?module=report&action=studentReport&year=2026
     */
    public function studentReport() {
        $year = isset($_GET['year']) ? (int)$_GET['year'] : 0;

        // All available years for the year-picker dropdown
        $availableYears = $this->placementModel->getPassingYears();

        if ($year > 0) {
            // ── Full student list for the year ──────────────────────────────
            $students = $this->queryPrepared(
                "SELECT * FROM `students`
                 WHERE `passing_year` = :year
                 ORDER BY `department` ASC, `cgpa` DESC, `name` ASC",
                [':year' => $year]
            );

            // ── KPI Stats for this batch ────────────────────────────────────
            $batchStats = $this->queryPrepared(
                "SELECT
                    COUNT(*) as total,
                    SUM(CASE WHEN `placement_status` = 'Placed'         THEN 1 ELSE 0 END) as placed,
                    SUM(CASE WHEN `placement_status` = 'Internship'     THEN 1 ELSE 0 END) as internship,
                    SUM(CASE WHEN `placement_status` = 'Higher Studies' THEN 1 ELSE 0 END) as higher,
                    SUM(CASE WHEN `placement_status` = 'Business'       THEN 1 ELSE 0 END) as business,
                    SUM(CASE WHEN `placement_status` = 'Unplaced'       THEN 1 ELSE 0 END) as unplaced,
                    AVG(`cgpa`)        as avg_cgpa,
                    MAX(`cgpa`)        as max_cgpa,
                    MIN(`cgpa`)        as min_cgpa,
                    MAX(`package_lpa`) as max_package,
                    AVG(`package_lpa`) as avg_package,
                    SUM(CASE WHEN `gender` = 'Male'   THEN 1 ELSE 0 END) as male_count,
                    SUM(CASE WHEN `gender` = 'Female' THEN 1 ELSE 0 END) as female_count
                 FROM `students`
                 WHERE `passing_year` = :year",
                [':year' => $year]
            );
            $batchStats = $batchStats[0] ?? [];

            // ── Department-wise breakdown for this year ─────────────────────
            $deptStats = $this->queryPrepared(
                "SELECT `department`,
                    COUNT(*) as total,
                    SUM(CASE WHEN `placement_status` = 'Placed'         THEN 1 ELSE 0 END) as placed,
                    SUM(CASE WHEN `placement_status` = 'Internship'     THEN 1 ELSE 0 END) as internship,
                    SUM(CASE WHEN `placement_status` = 'Higher Studies' THEN 1 ELSE 0 END) as higher,
                    SUM(CASE WHEN `placement_status` = 'Business'       THEN 1 ELSE 0 END) as business,
                    SUM(CASE WHEN `placement_status` = 'Unplaced'       THEN 1 ELSE 0 END) as unplaced,
                    AVG(`cgpa`)        as avg_cgpa,
                    MAX(`package_lpa`) as max_package
                 FROM `students`
                 WHERE `passing_year` = :year
                 GROUP BY `department`
                 ORDER BY total DESC",
                [':year' => $year]
            );

            // ── CGPA distribution for this year ────────────────────────────
            $cgpaDist = $this->queryPrepared(
                "SELECT
                    SUM(CASE WHEN cgpa >= 9.0 THEN 1 ELSE 0 END) as `9_to_10`,
                    SUM(CASE WHEN cgpa >= 8.0 AND cgpa < 9.0 THEN 1 ELSE 0 END) as `8_to_9`,
                    SUM(CASE WHEN cgpa >= 7.0 AND cgpa < 8.0 THEN 1 ELSE 0 END) as `7_to_8`,
                    SUM(CASE WHEN cgpa >= 6.0 AND cgpa < 7.0 THEN 1 ELSE 0 END) as `6_to_7`,
                    SUM(CASE WHEN cgpa < 6.0 THEN 1 ELSE 0 END) as `below_6`
                 FROM `students`
                 WHERE `passing_year` = :year",
                [':year' => $year]
            );
            $cgpaDist = $cgpaDist[0] ?? [];

        } else {
            $students   = [];
            $batchStats = [];
            $deptStats  = [];
            $cgpaDist   = [];
        }

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/report/student_year.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    private function query($sql) {
        $stmt = $this->db->query($sql);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    private function queryPrepared($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

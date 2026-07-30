<?php

class Database {
    private static $hosts = ['127.0.0.1', 'localhost', 'unix_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock', 'unix_socket=/tmp/mysql.sock'];
    private static $db_name = 'college_db';
    private static $username = 'root';
    private static $password = '';
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn === null) {
            $lastException = null;

            // Try connecting using different host strategies
            foreach (self::$hosts as $host) {
                try {
                    $dsn = strpos($host, 'unix_socket=') === 0 
                        ? "mysql:" . $host . ";dbname=" . self::$db_name . ";charset=utf8mb4" 
                        : "mysql:host=" . $host . ";dbname=" . self::$db_name . ";charset=utf8mb4";

                    self::$conn = new PDO(
                        $dsn,
                        self::$username,
                        self::$password,
                        [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                            PDO::ATTR_EMULATE_PREPARES => false
                        ]
                    );
                    break; // Successfully connected!
                } catch (PDOException $e) {
                    $lastException = $e;

                    // Try initializing database if missing
                    try {
                        $initDsn = strpos($host, 'unix_socket=') === 0 
                            ? "mysql:" . $host . ";charset=utf8mb4" 
                            : "mysql:host=" . $host . ";charset=utf8mb4";

                        $pdo = new PDO($initDsn, self::$username, self::$password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
                        $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . self::$db_name . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                        
                        self::$conn = new PDO($dsn, self::$username, self::$password, [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                            PDO::ATTR_EMULATE_PREPARES => false
                        ]);
                        self::initializeTable();
                        break;
                    } catch (PDOException $ex) {
                        $lastException = $ex;
                    }
                }
            }

            if (self::$conn === null) {
                self::renderDbErrorPage($lastException ? $lastException->getMessage() : 'Unable to connect to MySQL database.');
                exit;
            }

            self::initializeTable();
        }
        return self::$conn;
    }

    private static function renderDbErrorPage($errorMsg) {
        http_response_code(200);
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>MySQL Connection Required | MOU Portal</title>
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <style>
                body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #1e293b; padding: 40px 20px; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
                .card { background: white; max-width: 620px; width: 100%; padding: 32px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; }
                .icon { font-size: 48px; color: #ef4444; margin-bottom: 16px; }
                h2 { font-size: 24px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 12px; }
                p { font-size: 15px; color: #475569; line-height: 1.6; margin-bottom: 20px; }
                .steps { background: #f1f5f9; padding: 20px; border-radius: 10px; margin-bottom: 24px; font-size: 14px; }
                .steps ol { margin: 0; padding-left: 20px; }
                .steps li { margin-bottom: 8px; color: #334155; }
                .code { background: #0f172a; color: #38bdf8; padding: 12px; border-radius: 8px; font-family: monospace; font-size: 13px; word-break: break-all; margin-top: 10px; }
                .btn { display: inline-block; background: #4f46e5; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 15px; }
                .btn:hover { background: #4338ca; }
            </style>
        </head>
        <body>
            <div class="card">
                <div class="icon"><i class="fa-solid fa-database"></i></div>
                <h2>MySQL Server is Offline</h2>
                <p>The MOU Management application cannot connect to MySQL database on your system.</p>
                <div class="steps">
                    <strong>How to start MySQL in XAMPP:</strong>
                    <ol>
                        <li>Open the <strong>XAMPP Manager</strong> application on your Mac.</li>
                        <li>Click on the <strong>Manage Servers</strong> tab.</li>
                        <li>Select <strong>MySQL Database</strong> and click <strong>Start</strong>.</li>
                        <li>Alternatively, run this command in your Mac Terminal:</li>
                    </ol>
                    <div class="code">sudo /Applications/XAMPP/xamppfiles/xampp startmysql</div>
                </div>
                <a href="index.php" class="btn"><i class="fa-solid fa-rotate-right"></i> Refresh Page</a>
            </div>
        </body>
        </html>
        <?php
    }

    private static function initializeTable() {
        if (self::$conn === null) return;
        
        // MOUs Table
        $mouSql = "CREATE TABLE IF NOT EXISTS `mous` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `company_name` VARCHAR(255) NOT NULL,
            `contact_person` VARCHAR(150) DEFAULT NULL,
            `email` VARCHAR(150) DEFAULT NULL,
            `phone` VARCHAR(50) DEFAULT NULL,
            `address` TEXT DEFAULT NULL,
            `website` VARCHAR(255) DEFAULT NULL,
            `signed_date` DATE NOT NULL,
            `expiry_date` DATE NOT NULL,
            `year` INT NOT NULL,
            `description` TEXT DEFAULT NULL,
            `report_file` VARCHAR(255) DEFAULT NULL,
            `status` ENUM('Active', 'Expired', 'Terminated') NOT NULL DEFAULT 'Active',
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX (`year`),
            INDEX (`status`),
            INDEX (`signed_date`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        
        self::$conn->exec($mouSql);

        // Check if MOUs table is empty, if so insert sample data
        $stmt = self::$conn->query("SELECT COUNT(*) FROM `mous`");
        if ($stmt->fetchColumn() == 0) {
            $mouSeedSql = "INSERT INTO `mous` (`company_name`, `contact_person`, `email`, `phone`, `address`, `website`, `signed_date`, `expiry_date`, `year`, `description`, `status`) VALUES
            ('Tech Corp Solutions', 'Dr. Ramesh Sharma', 'ramesh@techcorp.com', '+91 98765 43210', '123 Innovation Park, Cyber City, Hyderabad', 'https://techcorp.com', '2026-01-15', '2029-01-14', 2026, 'Joint Research & Development in Artificial Intelligence and Machine Learning projects, guest lectures, and student internships.', 'Active'),
            ('Global Data Analytics Inc.', 'Sarah Jenkins', 'contact@globalanalytics.io', '+1 555 019 2831', '450 Silicon Avenue, San Francisco, CA', 'https://globalanalytics.io', '2025-06-10', '2028-06-09', 2025, 'Industry placement programs, curriculum review, and cloud computing workshop sponsorship.', 'Active'),
            ('NextGen Robotics Lab', 'Prof. Anil Varma', 'a.varma@nextgenrobotics.org', '+91 91234 56789', '78 Science Hub, Electronic City, Bengaluru', 'https://nextgenrobotics.org', '2024-03-22', '2027-03-21', 2024, 'Establishment of Advanced Automation Lab, faculty exchange program, and joint patent filings.', 'Active'),
            ('BioHealth Innovations', 'Dr. Sunita Patel', 'sunita@biohealth.co.in', '+91 94455 66778', '12 Bio Tech Park, Pune, Maharashtra', 'https://biohealth.co.in', '2023-08-01', '2025-07-31', 2023, 'Biomedical signal processing collaboration and student research fellowships.', 'Expired'),
            ('Quantum Cyber Security Systems', 'Rajesh Kulkarni', 'info@quantumsec.net', '+91 98111 22334', '56 Cyber Tower, Sector 62, Noida', 'https://quantumsec.net', '2024-11-05', '2026-11-04', 2024, 'Cybersecurity certification training, ethical hacking workshops, and infrastructure audit support.', 'Active'),
            ('EcoEnergy Dynamics', 'Vikram Malhotra', 'v.malhotra@ecoenergy.in', '+91 97788 99001', '89 Green Energy Zone, Ahmedabad', 'https://ecoenergy.in', '2023-02-14', '2024-02-13', 2023, 'Solar energy efficiency study and sustainable campus initiatives.', 'Terminated')";
            self::$conn->exec($mouSeedSql);
        }

        // Workshops Table
        $workshopSql = "CREATE TABLE IF NOT EXISTS `workshops` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `title` VARCHAR(255) NOT NULL,
            `instructor_name` VARCHAR(150) DEFAULT NULL,
            `company_name` VARCHAR(255) DEFAULT NULL,
            `instructor_email` VARCHAR(150) DEFAULT NULL,
            `venue` VARCHAR(255) DEFAULT NULL,
            `held_on` DATE NOT NULL,
            `duration` INT DEFAULT 1,
            `description` TEXT DEFAULT NULL,
            `certificate` TINYINT(1) DEFAULT 0,
            `total_participants` INT DEFAULT 0,
            `academic_year` INT NOT NULL,
            `report_file` VARCHAR(255) DEFAULT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX (`academic_year`),
            INDEX (`held_on`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        
        self::$conn->exec($workshopSql);

        // Check if Workshops table is empty, if so insert sample data
        $stmtW = self::$conn->query("SELECT COUNT(*) FROM `workshops`");
        if ($stmtW->fetchColumn() == 0) {
            $workshopSeedSql = "INSERT INTO `workshops` (`title`, `instructor_name`, `company_name`, `instructor_email`, `venue`, `held_on`, `duration`, `description`, `certificate`, `total_participants`, `academic_year`) VALUES
            ('Generative AI & LLM Deployment Masterclass', 'Dr. Ramesh Sharma', 'Tech Corp Solutions', 'ramesh@techcorp.com', 'Seminar Hall A, Main Campus', '2026-02-10', 8, 'Hands-on workshop covering Fine-tuning LLMs, Prompt Engineering, RAG architectures, and deploying models to cloud infrastructure.', 1, 145, 2026),
            ('Cloud Native DevOps & Kubernetes Architecture', 'Sarah Jenkins', 'Global Data Analytics Inc.', 'contact@globalanalytics.io', 'Computer Center Lab 3', '2025-10-18', 16, 'Two-day intensive boot-camp covering Docker containerization, Kubernetes cluster orchestration, and CI/CD pipelines.', 1, 98, 2025)";
            self::$conn->exec($workshopSeedSql);
        }

        // Admin Table
        $adminSql = "CREATE TABLE IF NOT EXISTS `admin` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(150) NOT NULL,
            `email` VARCHAR(150) NOT NULL UNIQUE,
            `password` VARCHAR(255) NOT NULL,
            `phone` VARCHAR(50) DEFAULT NULL,
            `role` VARCHAR(50) DEFAULT 'Admin',
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        self::$conn->exec($adminSql);

        $stmtA = self::$conn->query("SELECT COUNT(*) FROM `admin`");
        if ($stmtA->fetchColumn() == 0) {
            self::$conn->exec("INSERT INTO `admin` (`name`, `email`, `password`, `phone`, `role`) VALUES ('System Administrator', 'admin@college.edu', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFvX4rJcE09kH8lWvE4s1/J3c0Z5c.m2', '+91 98000 11122', 'Super Admin')");
        }

        // Class Coordinators (CC) Table
        $ccSql = "CREATE TABLE IF NOT EXISTS `cc` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(150) NOT NULL,
            `email` VARCHAR(150) NOT NULL UNIQUE,
            `phone` VARCHAR(50) DEFAULT NULL,
            `department` VARCHAR(100) NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        self::$conn->exec($ccSql);

        $stmtCC = self::$conn->query("SELECT COUNT(*) FROM `cc`");
        if ($stmtCC->fetchColumn() == 0) {
            self::$conn->exec("INSERT INTO `cc` (`name`, `email`, `phone`, `department`, `password`) VALUES 
            ('Prof. Rajesh Patel', 'cc.ce@college.edu', '+91 98222 33344', 'Computer Engineering', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFvX4rJcE09kH8lWvE4s1/J3c0Z5c.m2'),
            ('Dr. Meera Joshi', 'cc.it@college.edu', '+91 98333 44455', 'Information Technology', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFvX4rJcE09kH8lWvE4s1/J3c0Z5c.m2')");
        }

        // Students Table
        $studentSql = "CREATE TABLE IF NOT EXISTS `students` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `gr_no` VARCHAR(100) DEFAULT NULL,
            `enroll_no` VARCHAR(100) NOT NULL UNIQUE,
            `name` VARCHAR(150) NOT NULL,
            `email` VARCHAR(150) DEFAULT NULL,
            `phone` VARCHAR(50) DEFAULT NULL,
            `gender` ENUM('Male', 'Female') NOT NULL DEFAULT 'Male',
            `dob` DATE DEFAULT NULL,
            `department` VARCHAR(100) NOT NULL,
            `semester` INT DEFAULT 8,
            `cgpa` DECIMAL(3,2) DEFAULT 0.00,
            `passing_year` INT NOT NULL,
            `address` TEXT DEFAULT NULL,
            `skills` TEXT DEFAULT NULL,
            `password` VARCHAR(255) DEFAULT NULL COMMENT 'Bcrypt hash of enrollment number',
            `placement_status` ENUM('Placed', 'Internship', 'Higher Studies', 'Business', 'Unplaced') NOT NULL DEFAULT 'Unplaced',
            `company_name` VARCHAR(255) DEFAULT NULL,
            `designation` VARCHAR(150) DEFAULT NULL,
            `package_lpa` DECIMAL(5,2) DEFAULT NULL,
            `offer_letter_file` VARCHAR(255) DEFAULT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX (`gr_no`),
            INDEX (`passing_year`),
            INDEX (`department`),
            INDEX (`placement_status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        self::$conn->exec($studentSql);

        // Auto-migration: ensure gr_no column exists in existing tables & populate missing GR numbers
        try {
            self::$conn->exec("ALTER TABLE `students` ADD COLUMN `gr_no` VARCHAR(100) DEFAULT NULL AFTER `id`");
        } catch (PDOException $e) {
            // Column already exists
        }

        try {
            self::$conn->exec("UPDATE `students` SET `gr_no` = CONCAT('105', 487 + `id`) WHERE `gr_no` IS NULL OR TRIM(`gr_no`) = ''");
        } catch (PDOException $e) {
            // Migration query complete
        }

        $stmtS = self::$conn->query("SELECT COUNT(*) FROM `students`");
        if ($stmtS->fetchColumn() == 0) {
            $studentSeedSql = "INSERT INTO `students` (`gr_no`, `enroll_no`, `name`, `email`, `phone`, `gender`, `dob`, `department`, `semester`, `cgpa`, `passing_year`, `address`, `skills`, `placement_status`, `company_name`, `designation`, `package_lpa`) VALUES
            ('105488', '250114305001', 'Aarav Mehta', 'aarav.m@college.edu', '+91 98111 11111', 'Male', '2004-05-12', 'Computer Engineering', 8, 8.95, 2026, 'Ahmedabad, Gujarat', 'Python, React, Node.js, AWS', 'Placed', 'Tech Corp Solutions', 'Software Engineer', 12.50),
            ('105489', '250114305002', 'Ananya Sharma', 'ananya.s@college.edu', '+91 98222 22222', 'Female', '2004-08-22', 'Computer Engineering', 8, 9.20, 2026, 'Mumbai, Maharashtra', 'Machine Learning, PyTorch, C++', 'Placed', 'Global Data Analytics Inc.', 'Data Scientist', 15.00),
            ('105490', '250114405015', 'Rohan Verma', 'rohan.v@college.edu', '+91 98333 33333', 'Male', '2004-01-30', 'Information Technology', 8, 8.40, 2026, 'Pune, Maharashtra', 'Java, Spring Boot, MySQL', 'Internship', 'NextGen Robotics Lab', 'RPA Developer Intern', 6.00),
            ('105491', '240114305045', 'Priya Desai', 'priya.d@college.edu', '+91 98444 44444', 'Female', '2003-11-14', 'Computer Engineering', 8, 9.45, 2025, 'Surat, Gujarat', 'Algorithms, Data Structures, Java', 'Higher Studies', 'Stanford University (MS in CS)', 'Postgraduate Student', NULL),
            ('105492', '240114405088', 'Karan Shah', 'karan.s@college.edu', '+91 98555 55555', 'Male', '2003-03-18', 'Information Technology', 8, 7.80, 2025, 'Vadodara, Gujarat', 'UI/UX Design, Figma, Flutter', 'Business', 'DevCraft Studios (Founder)', 'Co-Founder & CEO', NULL),
            ('105493', '250114305099', 'Vikram Singh', 'vikram.s@college.edu', '+91 98666 66666', 'Male', '2004-09-05', 'Computer Engineering', 8, 7.10, 2026, 'Indore, MP', 'HTML, CSS, PHP, SQL', 'Unplaced', NULL, NULL, NULL)";
            self::$conn->exec($studentSeedSql);
        }

        // Companies / Placement Drives Table
        $companySql = "CREATE TABLE IF NOT EXISTS `companies` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `company_name` VARCHAR(255) NOT NULL,
            `industry` VARCHAR(100) DEFAULT 'IT & Software',
            `job_role` VARCHAR(255) NOT NULL,
            `vacancies` INT DEFAULT 1,
            `package_lpa` DECIMAL(5,2) DEFAULT NULL,
            `location` VARCHAR(255) DEFAULT 'Ahmedabad',
            `eligibility` VARCHAR(255) DEFAULT NULL,
            `deadline` DATE DEFAULT NULL,
            `description` TEXT DEFAULT NULL,
            `contact_email` VARCHAR(255) DEFAULT NULL,
            `apply_link` VARCHAR(255) DEFAULT NULL,
            `status` ENUM('Active', 'Upcoming', 'Closed') NOT NULL DEFAULT 'Active',
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX (`status`),
            INDEX (`company_name`),
            INDEX (`deadline`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        self::$conn->exec($companySql);

        $stmtComp = self::$conn->query("SELECT COUNT(*) FROM `companies`");
        if ($stmtComp->fetchColumn() == 0) {
            $companySeedSql = "INSERT INTO `companies` (`company_name`, `industry`, `job_role`, `vacancies`, `package_lpa`, `location`, `eligibility`, `deadline`, `description`, `contact_email`, `apply_link`, `status`) VALUES
            ('Tech Corp Solutions', 'IT & Software', 'Software Engineer Trainee', 15, 12.50, 'GIFT City, Gandhinagar', 'B.E. Computer / IT, Minimum 6.5 CGPA, No Active Backlogs', '2026-08-30', 'Join our core engineering team working on scalable cloud infrastructure, full-stack microservices, and AI integrations. Training provided.', 'careers@techcorp.com', 'https://techcorp.com/careers/freshers-2026', 'Active'),
            ('Global Data Analytics Inc.', 'Data & AI', 'Associate Data Engineer', 10, 15.00, 'Electronic City, Bengaluru / Hybrid', 'B.E. CE/IT/EC, Strong SQL, Python & Data Warehousing basics', '2026-09-15', 'High-growth data consulting firm seeking motivated fresh graduates for big data pipeline development and BI dashboard creation.', 'campus@globalanalytics.io', 'https://globalanalytics.io/campus-drive', 'Active'),
            ('CyberPulse Systems', 'Cybersecurity', 'Security Analyst Intern', 8, 8.00, 'Infocity, Gandhinagar', 'B.E. ALL Branches, Certifications in Ethical Hacking/Network Security preferred', '2026-08-20', 'Entry-level Security Operations Center (SOC) analyst role monitoring threat intelligence and conducting vulnerability assessment.', 'jobs@cyberpulse.sec', 'https://cyberpulse.sec/internships', 'Active'),
            ('Nexus Automation Labs', 'Robotics & Embedded Systems', 'Embedded Firmware Developer', 5, 9.50, 'Pune, Maharashtra', 'B.E. EC / Electrical / Mechanical, C/C++ & Microcontrollers', '2026-09-30', 'R&D role in smart industrial IoT hardware, microcontroller programming, and real-time operating systems.', 'hr@nexusautomation.in', 'https://nexusautomation.in/careers', 'Upcoming'),
            ('Apex Cloud Technologies', 'Cloud Services', 'Cloud DevOps Engineer', 12, 11.00, 'Remote / Work From Anywhere', 'B.E. CE / IT / EC, AWS / Azure Practitioner certification is a plus', '2026-07-25', 'Deploy and manage infrastructure as code, CI/CD automation pipelines, and Docker Kubernetes clusters.', 'recruitment@apexcloud.com', 'https://apexcloud.com/jobs', 'Closed')";
            self::$conn->exec($companySeedSql);
        }
    }
}

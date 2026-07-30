-- MOU Management, Workshop Management & Student Placement Database Schema

CREATE DATABASE IF NOT EXISTS `college_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `college_db`;

-- MOUs Table
CREATE TABLE IF NOT EXISTS `mous` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Workshops & Seminars Table
CREATE TABLE IF NOT EXISTS `workshops` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin Table
CREATE TABLE IF NOT EXISTS `admin` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `role` VARCHAR(50) DEFAULT 'Admin',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Class Coordinator (CC) Table
CREATE TABLE IF NOT EXISTS `cc` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `phone` VARCHAR(50) DEFAULT NULL,
  `department` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Students Table
CREATE TABLE IF NOT EXISTS `students` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Sample Data

-- Admins Seed
INSERT INTO `admin` (`name`, `email`, `password`, `phone`, `role`) VALUES
('System Administrator', 'admin@college.edu', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFvX4rJcE09kH8lWvE4s1/J3c0Z5c.m2', '+91 98000 11122', 'Super Admin');

-- Class Coordinators Seed
INSERT INTO `cc` (`name`, `email`, `phone`, `department`, `password`) VALUES
('Prof. Rajesh Patel', 'cc.ce@college.edu', '+91 98222 33344', 'Computer Engineering', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFvX4rJcE09kH8lWvE4s1/J3c0Z5c.m2'),
('Dr. Meera Joshi', 'cc.it@college.edu', '+91 98333 44455', 'Information Technology', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFvX4rJcE09kH8lWvE4s1/J3c0Z5c.m2');

-- MOUs Seed
INSERT INTO `mous` (`company_name`, `contact_person`, `email`, `phone`, `address`, `website`, `signed_date`, `expiry_date`, `year`, `description`, `status`) VALUES
('Tech Corp Solutions', 'Dr. Ramesh Sharma', 'ramesh@techcorp.com', '+91 98765 43210', '123 Innovation Park, Cyber City, Hyderabad', 'https://techcorp.com', '2026-01-15', '2029-01-14', 2026, 'Joint Research & Development in Artificial Intelligence and Machine Learning projects, guest lectures, and student internships.', 'Active'),
('Global Data Analytics Inc.', 'Sarah Jenkins', 'contact@globalanalytics.io', '+1 555 019 2831', '450 Silicon Avenue, San Francisco, CA', 'https://globalanalytics.io', '2025-06-10', '2028-06-09', 2025, 'Industry placement programs, curriculum review, and cloud computing workshop sponsorship.', 'Active'),
('NextGen Robotics Lab', 'Prof. Anil Varma', 'a.varma@nextgenrobotics.org', '+91 91234 56789', '78 Science Hub, Electronic City, Bengaluru', 'https://nextgenrobotics.org', '2024-03-22', '2027-03-21', 2024, 'Establishment of Advanced Automation Lab, faculty exchange program, and joint patent filings.', 'Active');

-- Workshops Seed
INSERT INTO `workshops` (`title`, `instructor_name`, `company_name`, `instructor_email`, `venue`, `held_on`, `duration`, `description`, `certificate`, `total_participants`, `academic_year`) VALUES
('Generative AI & LLM Deployment Masterclass', 'Dr. Ramesh Sharma', 'Tech Corp Solutions', 'ramesh@techcorp.com', 'Seminar Hall A, Main Campus', '2026-02-10', 8, 'Hands-on workshop covering Fine-tuning LLMs, Prompt Engineering, RAG architectures, and deploying models to cloud infrastructure.', 1, 145, 2026),
('Cloud Native DevOps & Kubernetes Architecture', 'Sarah Jenkins', 'Global Data Analytics Inc.', 'contact@globalanalytics.io', 'Computer Center Lab 3', '2025-10-18', 16, 'Two-day intensive boot-camp covering Docker containerization, Kubernetes cluster orchestration, and CI/CD pipelines.', 1, 98, 2025);

-- Students Seed
INSERT INTO `students` (`gr_no`, `enroll_no`, `name`, `email`, `phone`, `gender`, `dob`, `department`, `semester`, `cgpa`, `passing_year`, `address`, `skills`, `placement_status`, `company_name`, `designation`, `package_lpa`) VALUES
('105488', '250114305001', 'Aarav Mehta', 'aarav.m@college.edu', '+91 98111 11111', 'Male', '2004-05-12', 'Computer Engineering', 8, 8.95, 2026, 'Ahmedabad, Gujarat', 'Python, React, Node.js, AWS', 'Placed', 'Tech Corp Solutions', 'Software Engineer', 12.50),
('105489', '250114305002', 'Ananya Sharma', 'ananya.s@college.edu', '+91 98222 22222', 'Female', '2004-08-22', 'Computer Engineering', 8, 9.20, 2026, 'Mumbai, Maharashtra', 'Machine Learning, PyTorch, C++', 'Placed', 'Global Data Analytics Inc.', 'Data Scientist', 15.00),
('105490', '250114405015', 'Rohan Verma', 'rohan.v@college.edu', '+91 98333 33333', 'Male', '2004-01-30', 'Information Technology', 8, 8.40, 2026, 'Pune, Maharashtra', 'Java, Spring Boot, MySQL', 'Internship', 'NextGen Robotics Lab', 'RPA Developer Intern', 6.00),
('105491', '240114305045', 'Priya Desai', 'priya.d@college.edu', '+91 98444 44444', 'Female', '2003-11-14', 'Computer Engineering', 8, 9.45, 2025, 'Surat, Gujarat', 'Algorithms, Data Structures, Java', 'Higher Studies', 'Stanford University (MS in CS)', 'Postgraduate Student', NULL),
('105492', '240114405088', 'Karan Shah', 'karan.s@college.edu', '+91 98555 55555', 'Male', '2003-03-18', 'Information Technology', 8, 7.80, 2025, 'Vadodara, Gujarat', 'UI/UX Design, Figma, Flutter', 'Business', 'DevCraft Studios (Founder)', 'Co-Founder & CEO', NULL),
('105493', '250114305099', 'Vikram Singh', 'vikram.s@college.edu', '+91 98666 66666', 'Male', '2004-09-05', 'Computer Engineering', 8, 7.10, 2026, 'Indore, MP', 'HTML, CSS, PHP, SQL', 'Unplaced', NULL, NULL, NULL);

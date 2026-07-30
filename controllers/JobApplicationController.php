<?php

require_once __DIR__ . '/../models/JobApplication.php';
require_once __DIR__ . '/../models/StudentPlacement.php';
require_once __DIR__ . '/../models/Company.php';

class JobApplicationController {
    private $applicationModel;
    private $studentModel;
    private $companyModel;

    public function __construct() {
        $this->applicationModel = new JobApplication();
        $this->studentModel = new StudentPlacement();
        $this->companyModel = new Company();
    }

    /**
     * Render Job Applications Directory for Admins
     */
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Restrict to Admin
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            header('Location: index.php?module=dashboard&error=' . urlencode('Unauthorized access.'));
            exit;
        }

        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        $applications = $this->applicationModel->getAllForAdmin($status);

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/application/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    /**
     * Handle Student Application Submit
     */
    public function apply() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Restrict to Student
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'student') {
            header('Location: index.php?module=company&error=' . urlencode('Only students can apply for jobs.'));
            exit;
        }

        $studentId = (int)$_SESSION['user_id'];
        $companyId = isset($_POST['company_id']) ? (int)$_POST['company_id'] : 0;

        if (!$companyId) {
            header('Location: index.php?module=company&error=' . urlencode('Invalid company selected.'));
            exit;
        }

        // Check if student has uploaded their resume and filled their profile
        $student = $this->studentModel->getById($studentId);
        if (!$student || empty($student['resume_file'])) {
            header('Location: index.php?module=company&error=' . urlencode('You must upload your resume in settings before applying.'));
            exit;
        }

        $company = $this->companyModel->getById($companyId);
        if (!$company || $company['status'] === 'Closed') {
            header('Location: index.php?module=company&error=' . urlencode('This placement drive is no longer accepting applications.'));
            exit;
        }

        if ($this->applicationModel->hasApplied($studentId, $companyId)) {
            header('Location: index.php?module=company&error=' . urlencode('You have already applied for this position.'));
            exit;
        }

        $success = $this->applicationModel->apply($studentId, $companyId);

        if ($success) {
            header('Location: index.php?module=company&msg=applied');
            exit;
        } else {
            header('Location: index.php?module=company&error=' . urlencode('Failed to submit application. Please try again.'));
            exit;
        }
    }

    /**
     * Handle Admin Update Application Status
     */
    public function updateStatus() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Restrict to Admin
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            header('Location: index.php?module=application&error=' . urlencode('Unauthorized access.'));
            exit;
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $status = isset($_POST['status']) ? trim($_POST['status']) : '';

        if (!$id || !$status) {
            header('Location: index.php?module=application&error=' . urlencode('Invalid request.'));
            exit;
        }

        $success = $this->applicationModel->updateStatus($id, $status);

        if ($success) {
            header('Location: index.php?module=application&msg=status_updated');
            exit;
        } else {
            header('Location: index.php?module=application&error=' . urlencode('Failed to update status.'));
            exit;
        }
    }
}

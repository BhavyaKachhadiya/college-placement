<?php

require_once __DIR__ . '/../models/Company.php';
require_once __DIR__ . '/../models/JobApplication.php';

class CompanyController {
    private $companyModel;

    public function __construct() {
        $this->companyModel = new Company();
    }

    /**
     * Render Companies Directory & Vacancies Grid
     */
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';

        $companies = $this->companyModel->getAll($search, $status);
        $stats     = $this->companyModel->getStats();

        $appliedCompanyIds = [];
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'student') {
            $applicationModel = new JobApplication();
            $appliedCompanyIds = $applicationModel->getAppliedCompanyIds($_SESSION['user_id']);
        }

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/company/index.php';
        require_once __DIR__ . '/../views/company/form_modal.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    /**
     * Store New Company Drive (Admin Only)
     */
    public function store() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Restrict to Admin
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            header('Location: index.php?module=company&error=' . urlencode('Unauthorized action. Admin login required.'));
            exit;
        }

        try {
            $data = $this->sanitizeInput($_POST);

            if (empty($data['company_name']) || empty($data['job_role'])) {
                throw new Exception("Company Name and Job Role are required.");
            }

            $success = $this->companyModel->create($data);
            if ($success) {
                header('Location: index.php?module=company&msg=created');
                exit;
            } else {
                throw new Exception("Failed to add company drive.");
            }
        } catch (Exception $e) {
            header('Location: index.php?module=company&error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    /**
     * Update Existing Company Drive (Admin Only)
     */
    public function update() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            header('Location: index.php?module=company&error=' . urlencode('Unauthorized action. Admin login required.'));
            exit;
        }

        try {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            if (!$id) throw new Exception("Invalid company ID.");

            $data = $this->sanitizeInput($_POST);

            if (empty($data['company_name']) || empty($data['job_role'])) {
                throw new Exception("Company Name and Job Role are required.");
            }

            $success = $this->companyModel->update($id, $data);
            if ($success) {
                header('Location: index.php?module=company&msg=updated');
                exit;
            } else {
                throw new Exception("Failed to update company drive.");
            }
        } catch (Exception $e) {
            header('Location: index.php?module=company&error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    /**
     * Delete Company Drive (Admin Only)
     */
    public function delete() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            header('Location: index.php?module=company&error=' . urlencode('Unauthorized action. Admin login required.'));
            exit;
        }

        try {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['id']) ? (int)$_POST['id'] : 0);
            if (!$id) throw new Exception("Invalid company ID.");

            $success = $this->companyModel->delete($id);
            if ($success) {
                header('Location: index.php?module=company&msg=deleted');
                exit;
            } else {
                throw new Exception("Failed to delete company drive.");
            }
        } catch (Exception $e) {
            header('Location: index.php?module=company&error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    /**
     * AJAX Endpoint: JSON data for single company drive
     */
    public function getJson() {
        header('Content-Type: application/json');
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID']);
            exit;
        }

        $company = $this->companyModel->getById($id);
        if ($company) {
            echo json_encode(['success' => true, 'data' => $company]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Company drive not found']);
        }
        exit;
    }

    /**
     * Sanitize POST array inputs
     */
    private function sanitizeInput($input) {
        $clean = [];
        foreach ($input as $key => $val) {
            if (is_string($val)) {
                $clean[$key] = trim($val);
            } else {
                $clean[$key] = $val;
            }
        }
        return $clean;
    }
}

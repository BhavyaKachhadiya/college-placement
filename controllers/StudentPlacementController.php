<?php
require_once __DIR__ . '/../models/StudentPlacement.php';

class StudentPlacementController {
    private $placementModel;
    private $uploadDir;

    public function __construct() {
        $this->placementModel = new StudentPlacement();
        $this->uploadDir = __DIR__ . '/../uploads/placement_documents/';
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    /**
     * Dashboard View for Student Placement & Internship Tracking
     */
    public function index() {
        $search       = isset($_REQUEST['search']) ? trim($_REQUEST['search']) : '';
        $passing_year = isset($_REQUEST['year']) ? trim($_REQUEST['year']) : '';
        $department   = isset($_REQUEST['dept']) ? trim($_REQUEST['dept']) : '';
        $status       = isset($_REQUEST['status']) ? trim($_REQUEST['status']) : '';

        $students    = $this->placementModel->getAll($search, $passing_year, $department, $status);
        $years       = $this->placementModel->getPassingYears();
        $departments = $this->placementModel->getDepartments();
        $stats       = $this->placementModel->getStats();
        $suggestions = $this->placementModel->getSuggestions();

        // Render views
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/placement/index.php';
        require_once __DIR__ . '/../views/placement/form_modal.php';
        require_once __DIR__ . '/../views/placement/bulk_modal.php';
        require_once __DIR__ . '/../views/placement/view_modal.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    /**
     * Render Dedicated Student Profile View
     */
    public function studentProfile() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'student') {
            $id = (int)($_SESSION['user_id'] ?? 0);
        } else {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : (isset($_GET['id']) ? (int)$_GET['id'] : 0);
        }

        $student = null;
        if ($id > 0) {
            $student = $this->placementModel->getById($id);
        }

        if (!$student && isset($_SESSION['user_id'])) {
            $student = $this->placementModel->getById((int)$_SESSION['user_id']);
        }

        if (!$student) {
            header('Location: index.php?module=auth&action=login');
            exit;
        }

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/placement/profile.php';
        require_once __DIR__ . '/../views/placement/form_modal.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    /**
     * Render Student Account Settings Page
     */
    public function studentSettings() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $studentId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
        $student = null;

        if ($studentId > 0) {
            $student = $this->placementModel->getById($studentId);
        }

        if (!$student) {
            header('Location: index.php?module=auth&action=login');
            exit;
        }

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/placement/settings.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    /**
     * Handle Student Self-Update from Settings Page
     */
    public function updateSelf() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {
            $studentId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
            if (!$studentId) throw new Exception("Unauthorized student session.");

            $existing = $this->placementModel->getById($studentId);
            if (!$existing) throw new Exception("Student record not found.");

            $input = $this->sanitizeInput($_POST);

            // Keep personal, academic & career placement status fields intact, update ONLY allowed editable fields (skills & address)
            $data = $existing;
            $data['skills']  = $input['skills'] ?? $existing['skills'];
            $data['address'] = $input['address'] ?? $existing['address'];

            $success = $this->placementModel->update($studentId, $data);
            if ($success) {
                header('Location: index.php?module=student&action=studentSettings&msg=updated');
                exit;
            } else {
                throw new Exception("Failed to update student profile settings.");
            }
        } catch (Exception $e) {
            header('Location: index.php?module=student&action=studentSettings&error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    /**
     * AJAX Endpoint to return JSON data for single student
     */
    public function getJson() {
        header('Content-Type: application/json');
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Invalid Student ID']);
            exit;
        }

        $student = $this->placementModel->getById($id);
        if ($student) {
            echo json_encode(['success' => true, 'data' => $student]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Student record not found']);
        }
        exit;
    }

    /**
     * Handle Create Student
     */
    public function store() {
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

        try {
            $data = $this->sanitizeInput($_POST);

            if (empty($data['enroll_no'])) throw new Exception("Enrollment Number is required.");
            if (empty($data['name'])) throw new Exception("Student Name is required.");
            if (empty($data['department'])) throw new Exception("Department is required.");
            if (empty($data['passing_year'])) throw new Exception("Passing Year is required.");

            // Handle Offer Letter File Upload
            $offerLetter = $this->handleFileUpload($_FILES['offer_letter_file'] ?? null);
            if ($offerLetter) {
                $data['offer_letter_file'] = $offerLetter;
            }

            $newId = $this->placementModel->create($data);
            if ($newId) {
                if ($isAjax) {
                    echo json_encode(['success' => true, 'message' => 'Student record added successfully!', 'id' => $newId]);
                    exit;
                }
                header('Location: index.php?module=placement&msg=added');
                exit;
            } else {
                throw new Exception("Failed to insert student record into database.");
            }
        } catch (Exception $e) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit;
            }
            header('Location: index.php?module=placement&error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    /**
     * Handle Update Student
     */
    public function update() {
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

        try {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            if (!$id) throw new Exception("Invalid Student ID.");

            $existing = $this->placementModel->getById($id);
            if (!$existing) throw new Exception("Target student record not found.");

            $data = $this->sanitizeInput($_POST);

            if (empty($data['enroll_no'])) throw new Exception("Enrollment Number is required.");
            if (empty($data['name'])) throw new Exception("Student Name is required.");
            if (empty($data['department'])) throw new Exception("Department is required.");

            // Handle file upload replacement
            if (isset($_FILES['offer_letter_file']) && $_FILES['offer_letter_file']['error'] !== UPLOAD_ERR_NO_FILE) {
                $newFile = $this->handleFileUpload($_FILES['offer_letter_file']);
                if ($newFile) {
                    if (!empty($existing['offer_letter_file']) && file_exists($this->uploadDir . $existing['offer_letter_file'])) {
                        @unlink($this->uploadDir . $existing['offer_letter_file']);
                    }
                    $data['offer_letter_file'] = $newFile;
                }
            }

            $success = $this->placementModel->update($id, $data);
            if ($success) {
                if ($isAjax) {
                    echo json_encode(['success' => true, 'message' => 'Student record updated successfully!']);
                    exit;
                }
                header('Location: index.php?module=placement&msg=updated');
                exit;
            } else {
                throw new Exception("Failed to update student record.");
            }
        } catch (Exception $e) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit;
            }
            header('Location: index.php?module=placement&error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    /**
     * Handle Delete Student
     */
    public function delete() {
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

        try {
            $id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
            if (!$id) throw new Exception("Invalid Student ID.");

            $student = $this->placementModel->getById($id);
            if ($student) {
                if (!empty($student['offer_letter_file']) && file_exists($this->uploadDir . $student['offer_letter_file'])) {
                    @unlink($this->uploadDir . $student['offer_letter_file']);
                }
                $this->placementModel->delete($id);
            }

            if ($isAjax) {
                echo json_encode(['success' => true, 'message' => 'Student record deleted successfully!']);
                exit;
            }
            header('Location: index.php?module=placement&msg=deleted');
            exit;
        } catch (Exception $e) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit;
            }
            header('Location: index.php?module=placement&error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    /**
     * Handle CSV Bulk Data Upload
     */
    public function bulkUpload() {
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

        try {
            if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Please select a valid CSV file to upload.");
            }

            $fileName = $_FILES['csv_file']['name'];
            $fileTmp  = $_FILES['csv_file']['tmp_name'];
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if ($ext !== 'csv') {
                throw new Exception("Only CSV files (.csv) are allowed for bulk upload.");
            }

            $count = $this->placementModel->bulkUploadCsv($fileTmp);

            if ($isAjax) {
                echo json_encode(['success' => true, 'message' => "Successfully imported/updated {$count} student placement records!"]);
                exit;
            }
            header("Location: index.php?module=placement&msg=bulk_success&count={$count}");
            exit;
        } catch (Exception $e) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit;
            }
            header('Location: index.php?module=placement&error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    /**
     * Download Sample CSV Template
     */
    public function sampleCsv() {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="student_placement_sample_template.csv"');

        $output = fopen('php://output', 'w');

        // Header row
        fputcsv($output, [
            'Enroll_No', 'Name', 'Email', 'Phone', 'Gender',
            'Department', 'Semester', 'CGPA', 'Passing_Year',
            'Skills', 'Placement_Status', 'Company_Name', 'Designation', 'Package_LPA'
        ]);

        // Format hint row (comment)
        fputcsv($output, [
            '# Format: YY+CollegeCode+BranchCode+Division+Roll', '', '', '', '',
            '', '', '', '', '', '', '', '', ''
        ]);

        // Sample row 1 - Placed
        fputcsv($output, [
            '250114305101', 'Rahul Verma', 'rahul.v@college.edu', '+91 98000 12345', 'Male',
            'Computer Engineering', '8', '8.75', '2026',
            'Python, React, AWS', 'Placed', 'Tech Corp Solutions', 'Software Engineer', '10.50'
        ]);

        // Sample row 2 - Internship
        fputcsv($output, [
            '250114405102', 'Neha Sharma', 'neha.s@college.edu', '+91 98000 67890', 'Female',
            'Information Technology', '8', '9.10', '2026',
            'Data Analytics, Python, SQL', 'Internship', 'Global Data Analytics Inc.', 'Data Analyst Intern', '5.00'
        ]);

        // Sample row 3 - Higher Studies
        fputcsv($output, [
            '240114305088', 'Arjun Patel', 'arjun.p@college.edu', '+91 98111 99000', 'Male',
            'Computer Engineering', '8', '9.50', '2025',
            'Machine Learning, Deep Learning', 'Higher Studies', 'IIT Bombay (M.Tech)', 'Postgraduate Student', ''
        ]);

        // Sample row 4 - Unplaced
        fputcsv($output, [
            '250114305199', 'Pooja Singh', 'pooja.s@college.edu', '+91 98222 33444', 'Female',
            'Computer Engineering', '8', '7.20', '2026',
            'HTML, CSS, JavaScript', 'Unplaced', '', '', ''
        ]);

        fclose($output);
        exit;
    }

    private function sanitizeInput($input) {
        $clean = [];
        foreach ($input as $key => $val) {
            if (is_string($val)) {
                $clean[$key] = htmlspecialchars(trim($val), ENT_QUOTES, 'UTF-8');
            } else {
                $clean[$key] = $val;
            }
        }
        return $clean;
    }

    private function handleFileUpload($file) {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $fileName = $file['name'];
        $fileTmp  = $file['tmp_name'];
        $fileSize = $file['size'];

        if ($fileSize > 10 * 1024 * 1024) {
            throw new Exception("File size exceeds 10MB limit.");
        }

        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];

        if (!in_array($ext, $allowed)) {
            throw new Exception("Invalid file extension. Allowed formats: PDF, DOC, DOCX, JPG, PNG.");
        }

        $newFileName = 'offer_letter_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
        $targetPath  = $this->uploadDir . $newFileName;

        if (move_uploaded_file($fileTmp, $targetPath)) {
            return $newFileName;
        } else {
            throw new Exception("Failed to upload offer letter document.");
        }
    }
}

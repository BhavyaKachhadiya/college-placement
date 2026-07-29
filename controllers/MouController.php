<?php
require_once __DIR__ . '/../models/Mou.php';

class MouController {
    private $mouModel;
    private $uploadDir;

    public function __construct() {
        $this->mouModel = new Mou();
        $this->uploadDir = __DIR__ . '/../uploads/reports/';
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    /**
     * Dashboard view: Displays list, stats, and filters
     */
    public function index() {
        $search = isset($_REQUEST['search']) ? trim($_REQUEST['search']) : '';
        $year   = isset($_REQUEST['year']) ? trim($_REQUEST['year']) : '';
        $status = isset($_REQUEST['status']) ? trim($_REQUEST['status']) : '';

        $mous  = $this->mouModel->getAll($search, $year, $status);
        $years = $this->mouModel->getYears();
        $stats = $this->mouModel->getStats();

        // Render views
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/mou/index.php';
        require_once __DIR__ . '/../views/mou/form_modal.php';
        require_once __DIR__ . '/../views/mou/view_modal.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    /**
     * AJAX endpoint to return JSON for a single MOU record
     */
    public function getJson() {
        header('Content-Type: application/json');
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Invalid MOU ID']);
            exit;
        }

        $mou = $this->mouModel->getById($id);
        if ($mou) {
            echo json_encode(['success' => true, 'data' => $mou]);
        } else {
            echo json_encode(['success' => false, 'message' => 'MOU record not found']);
        }
        exit;
    }

    /**
     * Handle Creation of new MOU
     */
    public function store() {
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
        
        try {
            $data = $this->sanitizeInput($_POST);
            
            // Validate required fields
            if (empty($data['company_name'])) throw new Exception("Partnering Company Name is required.");
            if (empty($data['signed_date'])) throw new Exception("Date of Signing is required.");
            if (empty($data['expiry_date'])) throw new Exception("Expiry Date is required.");

            if (strtotime($data['expiry_date']) < strtotime($data['signed_date'])) {
                throw new Exception("Expiry Date cannot be prior to Date of Signing.");
            }

            // Handle file upload
            $reportFile = $this->handleFileUpload($_FILES['report_file'] ?? null);
            if ($reportFile) {
                $data['report_file'] = $reportFile;
            }

            $newId = $this->mouModel->create($data);
            if ($newId) {
                if ($isAjax) {
                    echo json_encode(['success' => true, 'message' => 'MOU record added successfully!', 'id' => $newId]);
                    exit;
                }
                header('Location: index.php?msg=added');
                exit;
            } else {
                throw new Exception("Failed to insert MOU into database.");
            }
        } catch (Exception $e) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit;
            }
            header('Location: index.php?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    /**
     * Handle Update of existing MOU
     */
    public function update() {
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

        try {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            if (!$id) throw new Exception("Invalid MOU ID for update.");

            $existingMou = $this->mouModel->getById($id);
            if (!$existingMou) throw new Exception("Target MOU record not found.");

            $data = $this->sanitizeInput($_POST);

            // Validation
            if (empty($data['company_name'])) throw new Exception("Partnering Company Name is required.");
            if (empty($data['signed_date'])) throw new Exception("Date of Signing is required.");
            if (empty($data['expiry_date'])) throw new Exception("Expiry Date is required.");

            if (strtotime($data['expiry_date']) < strtotime($data['signed_date'])) {
                throw new Exception("Expiry Date cannot be prior to Date of Signing.");
            }

            // Handle file upload
            if (isset($_FILES['report_file']) && $_FILES['report_file']['error'] !== UPLOAD_ERR_NO_FILE) {
                $newReportFile = $this->handleFileUpload($_FILES['report_file']);
                if ($newReportFile) {
                    // Delete old file if present
                    if (!empty($existingMou['report_file']) && file_exists($this->uploadDir . $existingMou['report_file'])) {
                        @unlink($this->uploadDir . $existingMou['report_file']);
                    }
                    $data['report_file'] = $newReportFile;
                }
            }

            $success = $this->mouModel->update($id, $data);
            if ($success) {
                if ($isAjax) {
                    echo json_encode(['success' => true, 'message' => 'MOU updated successfully!']);
                    exit;
                }
                header('Location: index.php?msg=updated');
                exit;
            } else {
                throw new Exception("Failed to update MOU in database.");
            }
        } catch (Exception $e) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit;
            }
            header('Location: index.php?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    /**
     * Handle Delete MOU
     */
    public function delete() {
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
        
        try {
            $id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
            if (!$id) throw new Exception("Invalid MOU ID for deletion.");

            $mou = $this->mouModel->getById($id);
            if ($mou) {
                // Delete associated uploaded file
                if (!empty($mou['report_file']) && file_exists($this->uploadDir . $mou['report_file'])) {
                    @unlink($this->uploadDir . $mou['report_file']);
                }
                $this->mouModel->delete($id);
            }

            if ($isAjax) {
                echo json_encode(['success' => true, 'message' => 'MOU deleted successfully!']);
                exit;
            }
            header('Location: index.php?msg=deleted');
            exit;
        } catch (Exception $e) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit;
            }
            header('Location: index.php?error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    /**
     * Sanitize input helper
     */
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

    /**
     * Upload report file helper
     */
    private function handleFileUpload($file) {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $fileName = $file['name'];
        $fileTmp  = $file['tmp_name'];
        $fileSize = $file['size'];

        // Maximum size: 10MB
        if ($fileSize > 10 * 1024 * 1024) {
            throw new Exception("File size exceeds maximum limit of 10MB.");
        }

        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExts = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];

        if (!in_array($ext, $allowedExts)) {
            throw new Exception("Invalid file extension. Allowed formats: PDF, DOC, DOCX, JPG, PNG.");
        }

        $newFileName = 'mou_report_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
        $targetPath  = $this->uploadDir . $newFileName;

        if (move_uploaded_file($fileTmp, $targetPath)) {
            return $newFileName;
        } else {
            throw new Exception("Failed to save uploaded file.");
        }
    }
}

<?php
require_once __DIR__ . '/../models/Workshop.php';

class WorkshopController {
    private $workshopModel;
    private $uploadDir;

    public function __construct() {
        $this->workshopModel = new Workshop();
        $this->uploadDir = __DIR__ . '/../uploads/workshop_reports/';
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    /**
     * Dashboard view for Workshop Management
     */
    public function index() {
        $search      = isset($_REQUEST['search']) ? trim($_REQUEST['search']) : '';
        $year        = isset($_REQUEST['year']) ? trim($_REQUEST['year']) : '';
        $certificate = isset($_REQUEST['certificate']) ? trim($_REQUEST['certificate']) : '';

        $workshops   = $this->workshopModel->getAll($search, $year, $certificate);
        $years       = $this->workshopModel->getYears();
        $stats       = $this->workshopModel->getStats();
        $suggestions = $this->workshopModel->getSuggestions();

        // Render view
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/workshop/index.php';
        require_once __DIR__ . '/../views/workshop/form_modal.php';
        require_once __DIR__ . '/../views/workshop/view_modal.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    /**
     * AJAX Endpoint to fetch single workshop record
     */
    public function getJson() {
        header('Content-Type: application/json');
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Invalid Workshop ID']);
            exit;
        }

        $workshop = $this->workshopModel->getById($id);
        if ($workshop) {
            echo json_encode(['success' => true, 'data' => $workshop]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Workshop record not found']);
        }
        exit;
    }

    /**
     * Handle Create Workshop
     */
    public function store() {
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

        try {
            $data = $this->sanitizeInput($_POST);

            if (empty($data['title'])) throw new Exception("Workshop/Seminar Title is required.");
            if (empty($data['held_on'])) throw new Exception("Date Held On is required.");

            // Handle Summary Report File Upload
            $reportFile = $this->handleFileUpload($_FILES['report_file'] ?? null);
            if ($reportFile) {
                $data['report_file'] = $reportFile;
            }

            $newId = $this->workshopModel->create($data);
            if ($newId) {
                if ($isAjax) {
                    echo json_encode(['success' => true, 'message' => 'Workshop recorded successfully!', 'id' => $newId]);
                    exit;
                }
                header('Location: index.php?module=workshop&msg=added');
                exit;
            } else {
                throw new Exception("Failed to insert Workshop into database.");
            }
        } catch (Exception $e) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit;
            }
            header('Location: index.php?module=workshop&error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    /**
     * Handle Update Workshop
     */
    public function update() {
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

        try {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            if (!$id) throw new Exception("Invalid Workshop ID.");

            $existing = $this->workshopModel->getById($id);
            if (!$existing) throw new Exception("Target workshop record not found.");

            $data = $this->sanitizeInput($_POST);

            if (empty($data['title'])) throw new Exception("Workshop/Seminar Title is required.");
            if (empty($data['held_on'])) throw new Exception("Date Held On is required.");

            // Handle file upload
            if (isset($_FILES['report_file']) && $_FILES['report_file']['error'] !== UPLOAD_ERR_NO_FILE) {
                $newReportFile = $this->handleFileUpload($_FILES['report_file']);
                if ($newReportFile) {
                    if (!empty($existing['report_file']) && file_exists($this->uploadDir . $existing['report_file'])) {
                        @unlink($this->uploadDir . $existing['report_file']);
                    }
                    $data['report_file'] = $newReportFile;
                }
            }

            $success = $this->workshopModel->update($id, $data);
            if ($success) {
                if ($isAjax) {
                    echo json_encode(['success' => true, 'message' => 'Workshop updated successfully!']);
                    exit;
                }
                header('Location: index.php?module=workshop&msg=updated');
                exit;
            } else {
                throw new Exception("Failed to update workshop.");
            }
        } catch (Exception $e) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit;
            }
            header('Location: index.php?module=workshop&error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    /**
     * Handle Delete Workshop
     */
    public function delete() {
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

        try {
            $id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
            if (!$id) throw new Exception("Invalid Workshop ID.");

            $workshop = $this->workshopModel->getById($id);
            if ($workshop) {
                if (!empty($workshop['report_file']) && file_exists($this->uploadDir . $workshop['report_file'])) {
                    @unlink($this->uploadDir . $workshop['report_file']);
                }
                $this->workshopModel->delete($id);
            }

            if ($isAjax) {
                echo json_encode(['success' => true, 'message' => 'Workshop record deleted successfully!']);
                exit;
            }
            header('Location: index.php?module=workshop&msg=deleted');
            exit;
        } catch (Exception $e) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit;
            }
            header('Location: index.php?module=workshop&error=' . urlencode($e->getMessage()));
            exit;
        }
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

        $newFileName = 'workshop_report_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
        $targetPath  = $this->uploadDir . $newFileName;

        if (move_uploaded_file($fileTmp, $targetPath)) {
            return $newFileName;
        } else {
            throw new Exception("Failed to upload summary report.");
        }
    }
}

<?php
require_once __DIR__ . '/../config/Database.php';

class AuthController {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function index() {
        $this->login();
    }

    /**
     * Render the Login view
     */
    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // If already logged in, redirect based on user_type
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'student') {
                $studentId = $_SESSION['user_id'] ?? 1;
                header('Location: index.php?module=student&action=studentProfile&id=' . $studentId);
                exit;
            } else {
                header('Location: index.php?module=dashboard');
                exit;
            }
        }

        $error = isset($_SESSION['auth_error']) ? $_SESSION['auth_error'] : null;
        $success = isset($_SESSION['auth_success']) ? $_SESSION['auth_success'] : null;
        unset($_SESSION['auth_error'], $_SESSION['auth_success']);

        $activeTab = isset($_GET['type']) && $_GET['type'] === 'admin' ? 'admin' : 'student';

        // Fetch sample students for quick-login hints
        $sampleStudents = [];
        try {
            $stmt = $this->db->query("SELECT id, name, enroll_no, department FROM `students` ORDER BY id ASC LIMIT 4");
            $sampleStudents = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $sampleStudents = [];
        }

        require_once __DIR__ . '/../views/auth/login.php';
    }

    /**
     * Process Login Form Submission
     */
    public function processLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?module=auth&action=login');
            exit;
        }

        $loginType = isset($_POST['login_type']) ? trim($_POST['login_type']) : 'student';
        $username  = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password  = isset($_POST['password']) ? trim($_POST['password']) : '';

        if (empty($username) || empty($password)) {
            $_SESSION['auth_error'] = 'Please enter both Username/Enrollment No and Password.';
            header('Location: index.php?module=auth&action=login&type=' . urlencode($loginType));
            exit;
        }

        if ($loginType === 'admin') {
            // ADMIN LOGIN: username 'admin' & password 'admin' (for now), or database check
            $authenticated = false;
            $adminUser = null;

            if (strtolower($username) === 'admin' && $password === 'admin') {
                $authenticated = true;
                $adminUser = [
                    'id' => 1,
                    'name' => 'System Administrator',
                    'email' => 'admin@college.edu',
                    'role' => 'Super Admin'
                ];
            } else {
                // Also check database admin table
                try {
                    $stmt = $this->db->prepare("SELECT * FROM `admin` WHERE `email` = :u OR `name` = :u LIMIT 1");
                    $stmt->execute([':u' => $username]);
                    $admin = $stmt->fetch();
                    if ($admin) {
                        if (password_verify($password, $admin['password']) || $password === 'admin') {
                            $authenticated = true;
                            $adminUser = $admin;
                        }
                    }
                } catch (Exception $e) {
                    // Fallback
                }
            }

            if ($authenticated) {
                $_SESSION['logged_in'] = true;
                $_SESSION['user_type'] = 'admin';
                $_SESSION['user_id']   = $adminUser['id'];
                $_SESSION['user_name'] = $adminUser['name'];
                $_SESSION['user_role'] = $adminUser['role'] ?? 'Super Admin';
                $_SESSION['user_email']= $adminUser['email'] ?? 'admin@college.edu';

                header('Location: index.php?module=dashboard');
                exit;
            } else {
                $_SESSION['auth_error'] = 'Invalid Admin credentials! Default login: Username <strong>admin</strong> and Password <strong>admin</strong>.';
                header('Location: index.php?module=auth&action=login&type=admin');
                exit;
            }

        } else {
            // STUDENT LOGIN: username is enroll_no and password is enroll_no
            try {
                $stmt = $this->db->prepare("SELECT * FROM `students` WHERE `enroll_no` = :enroll LIMIT 1");
                $stmt->execute([':enroll' => $username]);
                $student = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($student) {
                    // Check if password matches enroll_no OR bcrypt password hash
                    $isValidPassword = false;
                    if ($password === $student['enroll_no']) {
                        $isValidPassword = true;
                    } elseif (!empty($student['password']) && password_verify($password, $student['password'])) {
                        $isValidPassword = true;
                    }

                    if ($isValidPassword) {
                        $_SESSION['logged_in'] = true;
                        $_SESSION['user_type'] = 'student';
                        $_SESSION['user_id']   = $student['id'];
                        $_SESSION['user_name'] = $student['name'];
                        $_SESSION['enroll_no'] = $student['enroll_no'];
                        $_SESSION['gr_no']     = $student['gr_no'] ?? 'N/A';
                        $_SESSION['department']= $student['department'] ?? 'N/A';
                        $_SESSION['email']     = $student['email'] ?? '';

                        header('Location: index.php?module=student&action=studentProfile&id=' . $student['id']);
                        exit;
                    } else {
                        $_SESSION['auth_error'] = 'Invalid password for Enrollment No <strong>' . htmlspecialchars($username) . '</strong>. Note: Student password is your Enrollment No.';
                        header('Location: index.php?module=auth&action=login&type=student');
                        exit;
                    }
                } else {
                    $_SESSION['auth_error'] = 'Enrollment Number <strong>' . htmlspecialchars($username) . '</strong> not found in student records.';
                    header('Location: index.php?module=auth&action=login&type=student');
                    exit;
                }
            } catch (Exception $e) {
                $_SESSION['auth_error'] = 'Login database query error: ' . $e->getMessage();
                header('Location: index.php?module=auth&action=login&type=student');
                exit;
            }
        }
    }

    /**
     * Handle User Logout
     */
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['auth_success'] = 'You have been logged out successfully.';

        header('Location: index.php?module=auth&action=login');
        exit;
    }
}

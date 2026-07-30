<?php
/**
 * College Management Portal - Front Controller Router (MVC Pattern)
 * Modules: Auth (Login/Logout), Dashboard, MOU Management, Workshop Management, Student Placement & Internships
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/MouController.php';
require_once __DIR__ . '/controllers/WorkshopController.php';
require_once __DIR__ . '/controllers/StudentPlacementController.php';
require_once __DIR__ . '/controllers/InternshipController.php';
require_once __DIR__ . '/controllers/ReportController.php';
require_once __DIR__ . '/controllers/CompanyController.php';

// Determine Module and Action (Support both POST and GET)
$module = isset($_POST['module']) ? trim($_POST['module']) : (isset($_GET['module']) ? trim($_GET['module']) : 'dashboard');
$action = isset($_POST['action']) ? trim($_POST['action']) : (isset($_GET['action']) ? trim($_GET['action']) : 'index');

// 1. Handle Authentication Module
if ($module === 'auth' || $module === 'login') {
    $authController = new AuthController();
    if ($action === 'processLogin') {
        $authController->processLogin();
    } elseif ($action === 'logout') {
        $authController->logout();
    } else {
        $authController->login();
    }
    exit;
}

// 2. Authentication Protection Guard & Role Access Isolation
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php?module=auth&action=login');
    exit;
}

// Student Role Isolation: Students can view their own profile, settings & company vacancies
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'student') {
    $allowedStudentActions = ['studentProfile', 'studentSettings', 'updateSelf', 'getJson', 'index', 'deleteResume'];
    $allowedStudentModules = ['student', 'students', 'company', 'companies'];
    if (!in_array($module, $allowedStudentModules) || !in_array($action, $allowedStudentActions)) {
        header('Location: index.php?module=student&action=studentProfile');
        exit;
    }
}

// Dispatch Request to target Controller
if ($module === 'placement' || $module === 'student' || $module === 'students') {
    $controller = new StudentPlacementController();
} elseif ($module === 'workshop') {
    $controller = new WorkshopController();
} elseif ($module === 'internship') {
    $controller = new InternshipController();
} elseif ($module === 'mou') {
    $controller = new MouController();
} elseif ($module === 'report') {
    $controller = new ReportController();
} elseif ($module === 'company' || $module === 'companies') {
    $controller = new CompanyController();
} else {
    // Default → Dashboard
    $controller = new DashboardController();
}

// Special non-view actions
if ($action === 'sampleCsv' && method_exists($controller, 'sampleCsv')) {
    $controller->sampleCsv();
    exit;
}

// Dispatch Request to Controller Action
switch ($action) {
    case 'index':
        $controller->index();
        break;

    case 'getJson':
        $controller->getJson();
        break;

    case 'store':
        $controller->store();
        break;

    case 'update':
        $controller->update();
        break;

    case 'delete':
        $controller->delete();
        break;

    case 'bulkUpload':
        if (method_exists($controller, 'bulkUpload')) {
            $controller->bulkUpload();
        }
        break;

    case 'studentReport':
        if (method_exists($controller, 'studentReport')) {
            $controller->studentReport();
        }
        break;

    case 'studentProfile':
        if (method_exists($controller, 'studentProfile')) {
            $controller->studentProfile();
        }
        break;

    case 'studentSettings':
        if (method_exists($controller, 'studentSettings')) {
            $controller->studentSettings();
        }
        break;

    case 'updateSelf':
        if (method_exists($controller, 'updateSelf')) {
            $controller->updateSelf();
        }
        break;

    case 'suggestEnrollment':
        if (method_exists($controller, 'suggestEnrollment')) {
            $controller->suggestEnrollment();
            exit;
        }
        break;

    case 'deleteResume':
        if (method_exists($controller, 'deleteResume')) {
            $controller->deleteResume();
        }
        break;

    default:
        $controller->index();
        break;
}

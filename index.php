<?php
/**
 * College Management Portal - Front Controller Router (MVC Pattern)
 * Modules: Dashboard, MOU Management, Workshop Management, Student Placement & Internships
 */

require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/MouController.php';
require_once __DIR__ . '/controllers/WorkshopController.php';
require_once __DIR__ . '/controllers/StudentPlacementController.php';
require_once __DIR__ . '/controllers/InternshipController.php';
require_once __DIR__ . '/controllers/ReportController.php';

// Determine Module and Action
$module = isset($_GET['module']) ? trim($_GET['module']) : 'dashboard';
$action = isset($_GET['action']) ? trim($_GET['action']) : 'index';

// Dispatch Request to target Controller
if ($module === 'placement') {
    $controller = new StudentPlacementController();
} elseif ($module === 'workshop') {
    $controller = new WorkshopController();
} elseif ($module === 'internship') {
    $controller = new InternshipController();
} elseif ($module === 'mou') {
    $controller = new MouController();
} elseif ($module === 'report') {
    $controller = new ReportController();
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

    default:
        $controller->index();
        break;
}

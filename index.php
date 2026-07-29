<?php
/**
 * College Management Portal - Front Controller Router (MVC Pattern)
 * Modules: MOU Management, Workshop Management, Student Placement & Internships
 */

require_once __DIR__ . '/controllers/MouController.php';
require_once __DIR__ . '/controllers/WorkshopController.php';
require_once __DIR__ . '/controllers/StudentPlacementController.php';

// Determine Module and Action
$module = isset($_GET['module']) ? trim($_GET['module']) : 'mou';
$action = isset($_GET['action']) ? trim($_GET['action']) : 'index';

// Dispatch Request to target Controller
if ($module === 'placement') {
    $controller = new StudentPlacementController();
} elseif ($module === 'workshop') {
    $controller = new WorkshopController();
} else {
    $controller = new MouController();
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

    default:
        $controller->index();
        break;
}

<?php
require_once __DIR__ . '/../models/StudentPlacement.php';

class InternshipController {

    private $model;

    public function __construct() {
        $this->model = new StudentPlacement();
    }

    /**
     * Render Internship listing page (status=Internship filtered)
     */
    public function index() {
        $search      = isset($_REQUEST['search'])      ? trim($_REQUEST['search'])      : '';
        $passing_year = isset($_REQUEST['year'])       ? trim($_REQUEST['year'])        : '';
        $department  = isset($_REQUEST['department'])  ? trim($_REQUEST['department'])  : '';

        // Always filter by Internship status
        $students    = $this->model->getAll($search, $passing_year, $department, 'Internship');
        $years       = $this->model->getPassingYears();
        $depts       = $this->model->getDepartments();
        $stats       = $this->model->getStats(); // full stats for KPI context
        $suggestions = $this->model->getSuggestions('Internship');

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/internship/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}

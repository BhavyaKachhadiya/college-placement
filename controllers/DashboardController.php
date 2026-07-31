<?php
require_once __DIR__ . '/../models/Mou.php';
require_once __DIR__ . '/../models/Workshop.php';
require_once __DIR__ . '/../models/StudentPlacement.php';

class DashboardController {

    private $mouModel;
    private $workshopModel;
    private $placementModel;

    public function __construct() {
        $this->mouModel       = new Mou();
        $this->workshopModel  = new Workshop();
        $this->placementModel = new StudentPlacement();
    }

    /**
     * Render the Dashboard overview page
     */
    public function index() {
        // Aggregate stats from all three modules
        $mouStats       = $this->mouModel->getStats();
        $workshopStats  = $this->workshopModel->getStats();
        $placementStats = $this->placementModel->getStats();

        // Recent records (latest 5 from each module)
        $recentMous      = $this->mouModel->getAll('', '', '');
        $recentWorkshops = $this->workshopModel->getAll('', '', '');
        $recentStudents  = $this->placementModel->getAll('', '', '', '');

        // Limit to 5 most recent
        $recentMous      = array_slice($recentMous, 0, 5);
        $recentWorkshops = array_slice($recentWorkshops, 0, 5);
        $recentStudents  = array_slice($recentStudents, 0, 5);

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/dashboard/index.php';
        require_once __DIR__ . '/../views/mou/view_modal.php';
        require_once __DIR__ . '/../views/workshop/view_modal.php';
        require_once __DIR__ . '/../views/placement/view_modal.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}

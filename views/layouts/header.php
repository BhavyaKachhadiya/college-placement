<?php
$currentModule = isset($_GET['module']) ? $_GET['module'] : (isset($_POST['module']) ? $_POST['module'] : 'dashboard');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
            if ($currentModule === 'placement') echo 'Student Placement &amp; Internships';
            elseif ($currentModule === 'workshop') echo 'Workshop &amp; Seminar Management';
            elseif ($currentModule === 'internship') echo 'Industry Internships';
            elseif ($currentModule === 'mou') echo 'Institutional MOU Management';
            elseif ($currentModule === 'report') echo 'Reports &amp; Analytics';
            else echo 'Dashboard';
        ?> — College Portal
    </title>
    <meta name="description" content="College Institutional Management Portal — MOUs, Workshops, Student Placement &amp; Internship Tracking">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>">
</head>
<body>

<!-- ===================== SIDEBAR OVERLAY ===================== -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- ===================== SIDEBAR ===================== -->
<aside class="app-sidebar" id="appSidebar" aria-label="Main Navigation">

    <!-- Sidebar Header / Brand -->
    <div class="sidebar-brand">
        <div class="sidebar-brand-inner">
            <div class="logo-icon">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <div class="brand-text">
                <span class="brand-name">College Portal</span>
                <span class="brand-sub">Institutional Management</span>
            </div>
        </div>
        <button class="sidebar-close-btn" id="sidebarCloseBtn" onclick="closeSidebar()" aria-label="Close sidebar">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- Module Label -->
    <div class="sidebar-section-label">Navigation</div>

    <!-- Navigation Links -->
    <nav class="sidebar-nav" role="navigation">

        <!-- Dashboard -->
        <a href="index.php?module=dashboard"
           class="sidebar-nav-item <?= ($currentModule === 'dashboard' || $currentModule === '') ? 'active' : '' ?>"
           aria-current="<?= ($currentModule === 'dashboard' || $currentModule === '') ? 'page' : 'false' ?>">
            <span class="nav-item-icon">
                <i class="fa-solid fa-gauge-high"></i>
            </span>
            <span class="nav-item-label">Dashboard</span>
            <span class="nav-item-desc">Overview &amp; Analytics</span>
            <?php if ($currentModule === 'dashboard' || $currentModule === ''): ?>
                <span class="nav-active-indicator"></span>
            <?php endif; ?>
        </a>

        <!-- MOUs -->
        <a href="index.php?module=mou"
           class="sidebar-nav-item <?= $currentModule === 'mou' ? 'active' : '' ?>"
           aria-current="<?= $currentModule === 'mou' ? 'page' : 'false' ?>">
            <span class="nav-item-icon">
                <i class="fa-solid fa-handshake"></i>
            </span>
            <span class="nav-item-label">MOUs</span>
            <span class="nav-item-desc">Institutional Agreements</span>
            <?php if ($currentModule === 'mou'): ?>
                <span class="nav-active-indicator"></span>
            <?php endif; ?>
        </a>

        <!-- Workshops -->
        <a href="index.php?module=workshop"
           class="sidebar-nav-item <?= $currentModule === 'workshop' ? 'active' : '' ?>"
           aria-current="<?= $currentModule === 'workshop' ? 'page' : 'false' ?>">
            <span class="nav-item-icon">
                <i class="fa-solid fa-chalkboard-user"></i>
            </span>
            <span class="nav-item-label">Workshops</span>
            <span class="nav-item-desc">Seminars &amp; Events</span>
            <?php if ($currentModule === 'workshop'): ?>
                <span class="nav-active-indicator"></span>
            <?php endif; ?>
        </a>

        <!-- Placement -->
        <a href="index.php?module=placement"
           class="sidebar-nav-item <?= $currentModule === 'placement' ? 'active' : '' ?>"
           aria-current="<?= $currentModule === 'placement' ? 'page' : 'false' ?>">
            <span class="nav-item-icon">
                <i class="fa-solid fa-user-graduate"></i>
            </span>
            <span class="nav-item-label">Placement</span>
            <span class="nav-item-desc">Student Job Outcomes</span>
            <?php if ($currentModule === 'placement'): ?>
                <span class="nav-active-indicator"></span>
            <?php endif; ?>
        </a>

        <!-- Internship -->
        <a href="index.php?module=internship"
           class="sidebar-nav-item <?= $currentModule === 'internship' ? 'active' : '' ?>"
           aria-current="<?= $currentModule === 'internship' ? 'page' : 'false' ?>">
            <span class="nav-item-icon">
                <i class="fa-solid fa-laptop-code"></i>
            </span>
            <span class="nav-item-label">Internships</span>
            <span class="nav-item-desc">Industry Training</span>
            <?php if ($currentModule === 'internship'): ?>
                <span class="nav-active-indicator"></span>
            <?php endif; ?>
        </a>

        <!-- Divider before Reports -->
        <div style="height:1px; background: rgba(255,255,255,0.06); margin: 0.5rem 0.5rem;"></div>

        <!-- Reports & Analytics -->
        <a href="index.php?module=report"
           class="sidebar-nav-item sidebar-nav-report <?= $currentModule === 'report' ? 'active' : '' ?>"
           aria-current="<?= $currentModule === 'report' ? 'page' : 'false' ?>">
            <span class="nav-item-icon">
                <i class="fa-solid fa-chart-line"></i>
            </span>
            <span class="nav-item-label">Reports</span>
            <span class="nav-item-desc">Analytics &amp; Insights</span>
            <?php if ($currentModule === 'report'): ?>
                <span class="nav-active-indicator"></span>
            <?php endif; ?>
        </a>

    </nav>

    <!-- Sidebar Divider -->
    <div class="sidebar-divider"></div>
    <div class="sidebar-section-label">Quick Actions</div>

    <!-- Context-sensitive Action Buttons -->
    <div class="sidebar-actions">
        <?php if ($currentModule === 'placement'): ?>
            <button class="sidebar-action-btn sidebar-action-primary" onclick="openAddStudentModal(); closeSidebar();" id="sidebarBtnAddStudent">
                <i class="fa-solid fa-user-plus"></i>
                <span>Add Student</span>
            </button>
            <button class="sidebar-action-btn sidebar-action-light" onclick="openBulkModal(); closeSidebar();" id="sidebarBtnBulkImport">
                <i class="fa-solid fa-file-csv"></i>
                <span>Bulk Import</span>
            </button>
        <?php elseif ($currentModule === 'workshop'): ?>
            <button class="sidebar-action-btn sidebar-action-primary" onclick="openAddWorkshopModal(); closeSidebar();" id="sidebarBtnAddWorkshop">
                <i class="fa-solid fa-plus"></i>
                <span>New Workshop</span>
            </button>
        <?php elseif ($currentModule === 'mou'): ?>
            <button class="sidebar-action-btn sidebar-action-primary" onclick="openAddModal(); closeSidebar();" id="sidebarBtnAddMou">
                <i class="fa-solid fa-plus"></i>
                <span>New MOU</span>
            </button>
        <?php else: ?>
            <!-- Dashboard, Internship & Report: shortcut links -->
            <?php if ($currentModule === 'report'): ?>
                <button class="sidebar-action-btn sidebar-action-primary" onclick="window.print(); closeSidebar();" id="sidebarBtnPrint">
                    <i class="fa-solid fa-print"></i>
                    <span>Print Report</span>
                </button>
                <button class="sidebar-action-btn sidebar-action-light" onclick="exportAllCSV(); closeSidebar();" id="sidebarBtnExport">
                    <i class="fa-solid fa-file-csv"></i>
                    <span>Export CSV</span>
                </button>
            <?php else: ?>
                <a href="index.php?module=mou" class="sidebar-action-btn sidebar-action-light">
                    <i class="fa-solid fa-handshake"></i>
                    <span>Go to MOUs</span>
                </a>
                <a href="index.php?module=report" class="sidebar-action-btn sidebar-action-light">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>View Reports</span>
                </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="sidebar-footer-info">
            <i class="fa-solid fa-circle-info"></i>
            <span>College Portal v2.0</span>
        </div>
        <span class="sidebar-footer-year"><?= date('Y') ?></span>
    </div>

</aside>

<!-- ===================== TOP HEADER BAR ===================== -->
<header class="app-header">
    <div class="header-container">

        <!-- Hamburger Toggle -->
        <button class="hamburger-btn" id="hamburgerBtn" onclick="toggleSidebar()" aria-label="Toggle sidebar" aria-expanded="false" aria-controls="appSidebar">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>

        <!-- Brand -->
        <div class="brand-logo">
            <div class="logo-icon">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <div class="brand-text">
                <h1>College Portal</h1>
                <span class="sub-title">Institutional Management System</span>
            </div>
        </div>

        <!-- Active Module Breadcrumb -->
        <div class="header-breadcrumb">
            <i class="fa-solid fa-chevron-right breadcrumb-sep"></i>
            <span class="breadcrumb-module">
                <?php
                    if ($currentModule === 'placement') echo '<i class="fa-solid fa-user-graduate"></i> Placement';
                    elseif ($currentModule === 'workshop') echo '<i class="fa-solid fa-chalkboard-user"></i> Workshops';
                    elseif ($currentModule === 'internship') echo '<i class="fa-solid fa-laptop-code"></i> Internships';
                    elseif ($currentModule === 'mou') echo '<i class="fa-solid fa-handshake"></i> MOUs';
                    elseif ($currentModule === 'report') echo '<i class="fa-solid fa-chart-line"></i> Reports';
                    else echo '<i class="fa-solid fa-gauge-high"></i> Dashboard';
                ?>
            </span>
        </div>

        <!-- Spacer -->
        <div class="header-spacer"></div>

        <!-- Header CTA Buttons -->
        <div class="header-actions">
            <?php if ($currentModule === 'placement'): ?>
                <button class="btn btn-light" onclick="openBulkModal()" id="btnBulkImport">
                    <i class="fa-solid fa-file-csv"></i> Bulk Import
                </button>
                <button class="btn btn-primary" onclick="openAddStudentModal()" id="btnAddStudent">
                    <i class="fa-solid fa-user-plus"></i> Add Student
                </button>
            <?php elseif ($currentModule === 'workshop'): ?>
                <button class="btn btn-primary" onclick="openAddWorkshopModal()" id="btnAddWorkshop">
                    <i class="fa-solid fa-plus"></i> New Workshop
                </button>
            <?php elseif ($currentModule === 'mou'): ?>
                <button class="btn btn-primary" onclick="openAddModal()" id="btnAddMou">
                    <i class="fa-solid fa-plus"></i> New MOU
                </button>
            <?php elseif ($currentModule === 'report'): ?>
                <button class="btn btn-light" onclick="window.print()" id="btnHeaderPrint">
                    <i class="fa-solid fa-print"></i> Print
                </button>
                <button class="btn btn-primary" onclick="exportAllCSV()" id="btnHeaderExport">
                    <i class="fa-solid fa-file-csv"></i> Export CSV
                </button>
            <?php endif; ?>
        </div>

    </div>
</header>

<!-- ===================== PAGE WRAPPER ===================== -->
<div class="page-wrapper">
<main class="app-main-content">

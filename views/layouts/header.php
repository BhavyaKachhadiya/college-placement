<?php
$currentModule = isset($_GET['module']) ? $_GET['module'] : (isset($_POST['module']) ? $_POST['module'] : 'mou');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
            if ($currentModule === 'placement') echo 'Student Placement & Internships';
            elseif ($currentModule === 'workshop') echo 'Workshop & Seminar Management';
            else echo 'Institutional MOU Management';
        ?> — College Portal
    </title>
    <meta name="description" content="College Institutional Management Portal — MOUs, Workshops, Student Placement & Internship Tracking">
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

<header class="app-header">
    <div class="header-container">

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

        <!-- Navigation -->
        <nav class="main-nav-tabs" role="navigation" aria-label="Module Navigation">
            <a href="index.php?module=mou" class="nav-tab <?= $currentModule === 'mou' ? 'active' : '' ?>" aria-current="<?= $currentModule === 'mou' ? 'page' : 'false' ?>">
                <i class="fa-solid fa-handshake"></i> MOUs
            </a>
            <a href="index.php?module=workshop" class="nav-tab <?= $currentModule === 'workshop' ? 'active' : '' ?>" aria-current="<?= $currentModule === 'workshop' ? 'page' : 'false' ?>">
                <i class="fa-solid fa-chalkboard-user"></i> Workshops
            </a>
            <a href="index.php?module=placement" class="nav-tab <?= $currentModule === 'placement' ? 'active' : '' ?>" aria-current="<?= $currentModule === 'placement' ? 'page' : 'false' ?>">
                <i class="fa-solid fa-user-graduate"></i> Placement
            </a>
        </nav>

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
            <?php else: ?>
                <button class="btn btn-primary" onclick="openAddModal()" id="btnAddMou">
                    <i class="fa-solid fa-plus"></i> New MOU
                </button>
            <?php endif; ?>
        </div>

    </div>
</header>

<main class="app-main-content">

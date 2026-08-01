<?php
/**
 * Company Drives & Placement Vacancies View
 * Renders company vacancies for Students & CRUD management for Admins
 */
$isAdmin   = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
$isStudent = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'student';
$msg       = isset($_GET['msg']) ? trim($_GET['msg']) : '';
$error     = isset($_GET['error']) ? trim($_GET['error']) : '';
$search    = isset($_GET['search']) ? trim($_GET['search']) : '';
$status    = isset($_GET['status']) ? trim($_GET['status']) : '';
?>

<div class="container py-4">

    <!-- Header & Action Bar -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.75rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-family: var(--font-display); font-size: 1.6rem; font-weight: 800; color: var(--neutral-900); margin: 0; display: flex; align-items: center; gap: 0.65rem;">
                <i class="fa-solid fa-building" style="color: var(--brand-500);"></i> Company Drives &amp; Placement Vacancies
            </h2>
            <p style="color: var(--neutral-500); font-size: 0.9rem; margin-top: 0.25rem;">
                <?= $isStudent ? 'Explore active campus recruitment drives, open vacancies, eligibility &amp; CTC packages.' : 'Manage company placement drives, post new job openings &amp; track vacancies.' ?>
            </p>
        </div>

        <?php if ($isAdmin): ?>
            <button type="button" class="btn btn-primary" onclick="openAddCompanyModal()" id="btnOpenAddCompanyModal">
                <i class="fa-solid fa-plus"></i> Add Company Drive
            </button>
        <?php endif; ?>
    </div>

    <!-- Notifications -->
    <?php if ($msg === 'created'): ?>
        <div class="alert alert-success" style="background: rgba(22, 163, 74, 0.1); border: 1px solid var(--green-500); color: var(--green-600); padding: 0.9rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; font-weight: 600;">
            <i class="fa-solid fa-circle-check" style="font-size: 1.25rem;"></i>
            <span>New company placement drive added successfully!</span>
        </div>
    <?php elseif ($msg === 'updated'): ?>
        <div class="alert alert-success" style="background: rgba(22, 163, 74, 0.1); border: 1px solid var(--green-500); color: var(--green-600); padding: 0.9rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; font-weight: 600;">
            <i class="fa-solid fa-circle-check" style="font-size: 1.25rem;"></i>
            <span>Company drive details updated successfully!</span>
        </div>
    <?php elseif ($msg === 'deleted'): ?>
        <div class="alert alert-success" style="background: rgba(22, 163, 74, 0.1); border: 1px solid var(--green-500); color: var(--green-600); padding: 0.9rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; font-weight: 600;">
            <i class="fa-solid fa-circle-check" style="font-size: 1.25rem;"></i>
            <span>Company drive record deleted successfully.</span>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" style="background: rgba(225, 29, 72, 0.1); border: 1px solid var(--rose-600); color: var(--rose-600); padding: 0.9rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; font-weight: 600;">
            <i class="fa-solid fa-circle-exclamation" style="font-size: 1.25rem;"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <!-- Student Resume Missing Toast & Banner Popup -->
    <?php if (!$isAdmin && isset($hasResume) && !$hasResume): ?>
        <!-- Floating Interactive Toast Notification for Missing Resume -->
        <div id="resumeToastPopup" onclick="window.location.href='index.php?module=student&action=studentSettings';" style="position: fixed; top: 5.5rem; right: 1.5rem; z-index: 99999; width: calc(100% - 3rem); max-width: 440px; background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); border: 2px solid #ea580c; border-radius: var(--radius-lg); padding: 1.1rem 1.25rem; box-shadow: 0 16px 40px rgba(234, 88, 12, 0.35); cursor: pointer; animation: slideInToast 0.4s cubic-bezier(0.16, 1, 0.3, 1); transition: all 0.25s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 20px 48px rgba(234, 88, 12, 0.45)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 16px 40px rgba(234, 88, 12, 0.35)';">
            <div style="display: flex; align-items: flex-start; gap: 0.85rem;">
                <div style="width: 44px; height: 44px; border-radius: var(--radius-md); background: #ea580c; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0; box-shadow: 0 4px 12px rgba(234,88,12,0.4);">
                    <i class="fa-solid fa-file-circle-exclamation"></i>
                </div>
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.2rem;">
                        <strong style="font-size: 0.98rem; color: #9a3412; font-family: var(--font-display); font-weight: 800;">Resume Not Uploaded ⚠️</strong>
                        <span style="font-size: 0.68rem; font-weight: 800; background: #ea580c; color: #ffffff; padding: 0.15rem 0.55rem; border-radius: 9999px; text-transform: uppercase;">Required</span>
                    </div>
                    <p style="font-size: 0.83rem; color: #c2410c; margin: 0; line-height: 1.4;">
                        Upload your PDF resume in settings to enable job applications for recruitment drives.
                    </p>
                    <div style="margin-top: 0.65rem; display: flex; align-items: center; gap: 0.4rem; font-size: 0.83rem; font-weight: 800; color: #ea580c;">
                        <span>Go to Upload Settings</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inline Highlight Alert Banner -->
        <div onclick="window.location.href='index.php?module=student&action=studentSettings';" style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); border: 1.5px solid #f97316; border-radius: var(--radius-xl); padding: 1.25rem 1.5rem; margin-bottom: 2rem; cursor: pointer; box-shadow: var(--shadow-sm); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; transition: transform 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: #ea580c; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0; box-shadow: 0 4px 14px rgba(234,88,12,0.35);">
                    <i class="fa-solid fa-file-arrow-up"></i>
                </div>
                <div>
                    <h4 style="margin: 0; font-size: 1.05rem; font-weight: 800; color: #9a3412;">Resume Upload Required for Applications ⚠️</h4>
                    <p style="margin: 0.2rem 0 0 0; font-size: 0.85rem; color: #c2410c;">
                        You have not uploaded your resume yet. Click here to upload your resume before applying.
                    </p>
                </div>
            </div>
            <a href="index.php?module=student&action=studentSettings" class="btn" style="background: #ea580c; color: #ffffff; font-weight: 700; padding: 0.65rem 1.25rem; font-size: 0.85rem; border-radius: var(--radius-md); box-shadow: 0 4px 12px rgba(234, 88, 12, 0.3);">
                <i class="fa-solid fa-gear"></i> Upload Resume Now &rarr;
            </a>
        </div>
    <?php endif; ?>

    <!-- Summary Stats Bar -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
        <!-- Card 1: Active Drives -->
        <div style="background: var(--white); border: 1px solid var(--neutral-200); padding: 1.25rem 1.5rem; border-radius: var(--radius-xl); box-shadow: var(--shadow-sm); display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 52px; height: 52px; border-radius: 14px; background: rgba(37, 99, 235, 0.1); color: var(--brand-600); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                <i class="fa-solid fa-briefcase"></i>
            </div>
            <div>
                <span style="font-size: 0.8rem; font-weight: 600; color: var(--neutral-500); text-transform: uppercase; letter-spacing: 0.05em;">Active Drives</span>
                <h3 style="font-size: 1.6rem; font-weight: 800; color: var(--neutral-900); margin: 0; line-height: 1.2;"><?= (int)($stats['active_drives'] ?? 0) ?></h3>
            </div>
        </div>

        <!-- Card 2: Total Open Vacancies -->
        <div style="background: var(--white); border: 1px solid var(--neutral-200); padding: 1.25rem 1.5rem; border-radius: var(--radius-xl); box-shadow: var(--shadow-sm); display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 52px; height: 52px; border-radius: 14px; background: rgba(22, 163, 74, 0.1); color: var(--green-600); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <div>
                <span style="font-size: 0.8rem; font-weight: 600; color: var(--neutral-500); text-transform: uppercase; letter-spacing: 0.05em;">Total Open Vacancies</span>
                <h3 style="font-size: 1.6rem; font-weight: 800; color: var(--neutral-900); margin: 0; line-height: 1.2;"><?= (int)($stats['total_vacancies'] ?? 0) ?></h3>
            </div>
        </div>

        <!-- Card 3: Highest Package -->
        <div style="background: var(--white); border: 1px solid var(--neutral-200); padding: 1.25rem 1.5rem; border-radius: var(--radius-xl); box-shadow: var(--shadow-sm); display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 52px; height: 52px; border-radius: 14px; background: rgba(217, 119, 6, 0.1); color: var(--amber-600); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
            <div>
                <span style="font-size: 0.8rem; font-weight: 600; color: var(--neutral-500); text-transform: uppercase; letter-spacing: 0.05em;">Highest Package</span>
                <h3 style="font-size: 1.6rem; font-weight: 800; color: var(--neutral-900); margin: 0; line-height: 1.2;">
                    <?= !empty($stats['highest_package']) ? '₹ ' . number_format((float)$stats['highest_package'], 2) . ' LPA' : 'N/A' ?>
                </h3>
            </div>
        </div>

        <!-- Card 4: Partner Companies -->
        <div style="background: var(--white); border: 1px solid var(--neutral-200); padding: 1.25rem 1.5rem; border-radius: var(--radius-xl); box-shadow: var(--shadow-sm); display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 52px; height: 52px; border-radius: 14px; background: rgba(147, 51, 234, 0.1); color: var(--purple-600); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <div>
                <span style="font-size: 0.8rem; font-weight: 600; color: var(--neutral-500); text-transform: uppercase; letter-spacing: 0.05em;">Partner Companies</span>
                <h3 style="font-size: 1.6rem; font-weight: 800; color: var(--neutral-900); margin: 0; line-height: 1.2;"><?= (int)($stats['total_companies'] ?? 0) ?></h3>
            </div>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div style="background: var(--white); border: 1px solid var(--neutral-200); padding: 1.25rem 1.5rem; border-radius: var(--radius-xl); margin-bottom: 2rem; box-shadow: var(--shadow-sm);">
        <form method="GET" action="index.php" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; justify-content: space-between;">
            <input type="hidden" name="module" value="company">
            <input type="hidden" name="action" value="index">

            <!-- Search input -->
            <div class="autocomplete-wrapper" style="flex: 1; min-width: 260px; position: relative;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--neutral-400); z-index: 2;"></i>
                <input type="text" name="search" id="companySearchInput" value="<?= htmlspecialchars($search) ?>" placeholder="Search company name, role, location, or industry..." autocomplete="off" spellcheck="false" style="width: 100%; padding: 0.7rem 1rem 0.7rem 2.6rem; border: 1px solid var(--neutral-300); border-radius: var(--radius-md); font-family: var(--font-primary);">
                <!-- Live Floating Suggestions Dropdown -->
                <div class="search-suggestions-dropdown" id="companySearchDropdown"></div>
            </div>

            <!-- Status Filter Tabs -->
            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                <a href="index.php?module=company&search=<?= urlencode($search) ?>" class="btn btn-sm <?= empty($status) ? 'btn-primary' : 'btn-light' ?>">All Status</a>
                <a href="index.php?module=company&status=Active&search=<?= urlencode($search) ?>" class="btn btn-sm <?= $status === 'Active' ? 'btn-primary' : 'btn-light' ?>">Active</a>
                <a href="index.php?module=company&status=Upcoming&search=<?= urlencode($search) ?>" class="btn btn-sm <?= $status === 'Upcoming' ? 'btn-primary' : 'btn-light' ?>">Upcoming</a>
                <a href="index.php?module=company&status=Closed&search=<?= urlencode($search) ?>" class="btn btn-sm <?= $status === 'Closed' ? 'btn-primary' : 'btn-light' ?>">Closed</a>
                <button type="submit" class="btn btn-dark btn-sm"><i class="fa-solid fa-filter"></i> Search</button>
            </div>
        </form>
    </div>

    <!-- Company Vacancies Grid -->
    <?php if (empty($companies)): ?>
        <div style="background: var(--white); border: 1px solid var(--neutral-200); border-radius: var(--radius-xl); padding: 4rem 2rem; text-align: center;">
            <div style="width: 70px; height: 70px; border-radius: 50%; background: var(--neutral-100); color: var(--neutral-400); display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.25rem;">
                <i class="fa-solid fa-building-circle-xmark"></i>
            </div>
            <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--neutral-800); margin: 0 0 0.5rem;">No Company Placement Drives Found</h3>
            <p style="color: var(--neutral-500); max-width: 460px; margin: 0 auto 1.5rem;">
                No recruitment drives match your filter criteria. Check back soon for new campus drive announcements.
            </p>
            <?php if ($isAdmin): ?>
                <button type="button" class="btn btn-primary" onclick="openAddCompanyModal()">
                    <i class="fa-solid fa-plus"></i> Post First Company Drive
                </button>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
            <?php foreach ($companies as $comp): ?>
                <?php
                    $statusBadge = 'badge-success';
                    if ($comp['status'] === 'Upcoming') $statusBadge = 'badge-info';
                    elseif ($comp['status'] === 'Closed') $statusBadge = 'badge-neutral';

                    $initials = strtoupper(substr($comp['company_name'], 0, 2));
                ?>
                <div class="card" style="background: var(--white); border-radius: var(--radius-xl); border: 1px solid var(--neutral-200); padding: 1.5rem; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; justify-content: space-between; transition: all 0.25s ease;" onmouseover="this.style.boxShadow='var(--shadow-md)'; this.style.borderColor='var(--brand-300)';" onmouseout="this.style.boxShadow='var(--shadow-sm)'; this.style.borderColor='var(--neutral-200)';">
                    
                    <div>
                        <!-- Card Header: Company Logo/Initials & Status Badge -->
                        <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem; margin-bottom: 1rem;">
                            <div style="display: flex; align-items: center; gap: 0.85rem;">
                                <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, var(--brand-600), var(--brand-800)); color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; box-shadow: 0 4px 12px rgba(37,99,235,0.25); flex-shrink: 0;">
                                    <?= htmlspecialchars($initials) ?>
                                </div>
                                <div>
                                    <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--neutral-900); margin: 0; line-height: 1.25;">
                                        <?= htmlspecialchars($comp['company_name']) ?>
                                    </h3>
                                    <span style="font-size: 0.78rem; font-weight: 600; color: var(--neutral-500);">
                                        <i class="fa-solid fa-industry"></i> <?= htmlspecialchars($comp['industry'] ?? 'IT & Software') ?>
                                    </span>
                                </div>
                            </div>

                            <span class="badge <?= $statusBadge ?>" style="font-size: 0.75rem; font-weight: 700; padding: 0.3rem 0.65rem; border-radius: 9999px;">
                                <?= htmlspecialchars($comp['status']) ?>
                            </span>
                        </div>

                        <!-- Job Title & Location -->
                        <div style="margin-bottom: 1.25rem;">
                            <h4 style="font-size: 1rem; font-weight: 700; color: var(--brand-700); margin: 0 0 0.3rem; display: flex; align-items: center; gap: 0.4rem;">
                                <i class="fa-solid fa-briefcase" style="font-size: 0.9rem;"></i> <?= htmlspecialchars($comp['job_role']) ?>
                            </h4>
                            <div style="font-size: 0.83rem; color: var(--neutral-600); display: flex; align-items: center; gap: 0.4rem;">
                                <i class="fa-solid fa-location-dot" style="color: var(--rose-600);"></i> <?= htmlspecialchars($comp['location'] ?? 'Ahmedabad') ?>
                            </div>
                        </div>

                        <!-- Highlights: Vacancies & Package -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; background: var(--neutral-50); border: 1px solid var(--neutral-150); border-radius: var(--radius-lg); padding: 0.85rem; margin-bottom: 1.25rem;">
                            <div>
                                <span style="display: block; font-size: 0.72rem; font-weight: 700; color: var(--neutral-500); text-transform: uppercase;">Open Vacancies</span>
                                <span style="font-size: 0.95rem; font-weight: 800; color: var(--green-600); display: flex; align-items: center; gap: 0.3rem; margin-top: 0.15rem;">
                                    <i class="fa-solid fa-users"></i> <?= (int)$comp['vacancies'] ?> Positions
                                </span>
                            </div>
                            <div>
                                <span style="display: block; font-size: 0.72rem; font-weight: 700; color: var(--neutral-500); text-transform: uppercase;">CTC Package</span>
                                <span style="font-size: 0.95rem; font-weight: 800; color: var(--brand-600); margin-top: 0.15rem; display: block;">
                                    <?= !empty($comp['package_lpa']) ? '₹ ' . number_format((float)$comp['package_lpa'], 2) . ' LPA' : 'Best in Industry' ?>
                                </span>
                            </div>
                        </div>

                        <!-- Eligibility & Deadline -->
                        <div style="font-size: 0.82rem; color: var(--neutral-700); margin-bottom: 1rem; display: flex; flex-direction: column; gap: 0.4rem;">
                            <?php if (!empty($comp['eligibility'])): ?>
                                <div>
                                    <strong style="color: var(--neutral-900);"><i class="fa-solid fa-graduation-cap" style="color: var(--purple-600);"></i> Eligibility:</strong> 
                                    <?= htmlspecialchars($comp['eligibility']) ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($comp['deadline'])): ?>
                                <div>
                                    <strong style="color: var(--neutral-900);"><i class="fa-solid fa-calendar-xmark" style="color: var(--rose-600);"></i> Application Deadline:</strong> 
                                    <span style="font-weight: 600; color: var(--rose-600);"><?= date('d M Y', strtotime($comp['deadline'])) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Description Snippet -->
                        <?php if (!empty($comp['description'])): ?>
                            <p style="font-size: 0.83rem; color: var(--neutral-600); line-height: 1.45; margin-bottom: 1.25rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                <?= htmlspecialchars($comp['description']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Footer Action Bar -->
                    <div style="padding-top: 1rem; border-top: 1px solid var(--neutral-150); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;">
                        <!-- Contact Email / Apply Link (Student Only) -->
                        <?php if (!$isAdmin): ?>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <?php if (in_array($comp['id'], $appliedCompanyIds)): ?>
                                    <button type="button" class="btn btn-success btn-sm" disabled style="display: flex; align-items: center; gap: 0.35rem; cursor: not-allowed; opacity: 0.8;">
                                        <i class="fa-solid fa-check-circle"></i> Already Applied
                                    </button>
                                <?php elseif ($comp['status'] !== 'Closed'): ?>
                                    <?php if (!$hasResume): ?>
                                        <button type="button" onclick="window.location.href='index.php?module=student&action=studentSettings';" class="btn btn-sm" style="display: flex; align-items: center; gap: 0.35rem; background: #ea580c; border: 1px solid #ea580c; color: #ffffff; font-weight: 700;" title="Upload Resume in Settings to Apply">
                                            <i class="fa-solid fa-file-circle-exclamation"></i> Upload Resume to Apply
                                        </button>
                                    <?php else: ?>
                                        <form action="index.php?module=application&action=apply" method="POST" onsubmit="return confirm('Are you sure you want to apply? Your profile and resume will be submitted for this position.');" style="margin: 0;">
                                            <input type="hidden" name="company_id" value="<?= (int)$comp['id'] ?>">
                                            <button type="submit" class="btn btn-primary btn-sm" style="display: flex; align-items: center; gap: 0.35rem;">
                                                <i class="fa-solid fa-paper-plane"></i> Apply Now
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span style="font-size: 0.78rem; font-weight: 600; color: var(--rose-600);">Applications Closed</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Admin Edit & Delete Controls -->
                        <?php if ($isAdmin): ?>
                            <div style="display: flex; align-items: center; gap: 0.4rem;">
                                <button type="button" class="btn btn-light btn-sm" onclick="editCompanyDrive(<?= (int)$comp['id'] ?>)" title="Edit Drive">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <a href="index.php?module=company&action=delete&id=<?= (int)$comp['id'] ?>" class="btn btn-sm" style="background: rgba(225,29,72,0.1); color: var(--rose-600); border: 1px solid rgba(225,29,72,0.2);" onclick="return confirm('Are you sure you want to delete this company placement drive?');" title="Delete Drive">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<script>
    const COMPANY_SUGGESTIONS = <?= json_encode($suggestions ?? ['companies' => [], 'roles' => [], 'industries' => [], 'locations' => []]) ?>;

    document.addEventListener('DOMContentLoaded', function() {
        const input    = document.getElementById('companySearchInput');
        const dropdown = document.getElementById('companySearchDropdown');
        let activeIndex = -1;

        if (!input || !dropdown) return;

        function escapeHtml(str) {
            return String(str).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }

        function highlightMatch(text, query) {
            if (!query) return escapeHtml(text);
            const escapedText  = escapeHtml(text);
            const escapedQuery = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            const regex        = new RegExp(`(${escapedQuery})`, 'gi');
            return escapedText.replace(regex, '<mark class="suggestion-highlight">$1</mark>');
        }

        function renderCompanySuggestions(query) {
            const q = query.trim().toLowerCase();
            if (!q || q.length < 1) {
                dropdown.style.display = 'none';
                dropdown.innerHTML = '';
                return;
            }

            const matchedCompanies  = (COMPANY_SUGGESTIONS.companies || []).filter(c => c.toLowerCase().includes(q)).slice(0, 5);
            const matchedRoles      = (COMPANY_SUGGESTIONS.roles || []).filter(r => r.toLowerCase().includes(q)).slice(0, 5);
            const matchedIndustries = (COMPANY_SUGGESTIONS.industries || []).filter(i => i.toLowerCase().includes(q)).slice(0, 5);
            const matchedLocations  = (COMPANY_SUGGESTIONS.locations || []).filter(l => l.toLowerCase().includes(q)).slice(0, 5);

            const totalMatches = matchedCompanies.length + matchedRoles.length + matchedIndustries.length + matchedLocations.length;

            if (totalMatches === 0) {
                dropdown.innerHTML = `<div class="suggestion-empty"><i class="fa-solid fa-magnifying-glass"></i> No matching company drives found</div>`;
                dropdown.style.display = 'block';
                return;
            }

            let html = '';

            if (matchedCompanies.length > 0) {
                html += `<div class="suggestion-group-header"><i class="fa-solid fa-building"></i> Companies</div>`;
                matchedCompanies.forEach(c => {
                    html += `<div class="suggestion-item" data-value="${escapeHtml(c)}">
                                <i class="fa-solid fa-building suggestion-icon"></i>
                                <span class="suggestion-text">${highlightMatch(c, q)}</span>
                                <span class="suggestion-badge">Company</span>
                             </div>`;
                });
            }

            if (matchedRoles.length > 0) {
                html += `<div class="suggestion-group-header"><i class="fa-solid fa-user-tie"></i> Job Roles</div>`;
                matchedRoles.forEach(r => {
                    html += `<div class="suggestion-item" data-value="${escapeHtml(r)}">
                                <i class="fa-solid fa-user-tie suggestion-icon"></i>
                                <span class="suggestion-text">${highlightMatch(r, q)}</span>
                                <span class="suggestion-badge">Role</span>
                             </div>`;
                });
            }

            if (matchedIndustries.length > 0) {
                html += `<div class="suggestion-group-header"><i class="fa-solid fa-city"></i> Industries</div>`;
                matchedIndustries.forEach(ind => {
                    html += `<div class="suggestion-item" data-value="${escapeHtml(ind)}">
                                <i class="fa-solid fa-city suggestion-icon"></i>
                                <span class="suggestion-text">${highlightMatch(ind, q)}</span>
                                <span class="suggestion-badge">Industry</span>
                             </div>`;
                });
            }

            if (matchedLocations.length > 0) {
                html += `<div class="suggestion-group-header"><i class="fa-solid fa-location-dot"></i> Locations</div>`;
                matchedLocations.forEach(loc => {
                    html += `<div class="suggestion-item" data-value="${escapeHtml(loc)}">
                                <i class="fa-solid fa-map-pin suggestion-icon"></i>
                                <span class="suggestion-text">${highlightMatch(loc, q)}</span>
                                <span class="suggestion-badge">Location</span>
                             </div>`;
                });
            }

            dropdown.innerHTML = html;
            dropdown.style.display = 'block';
            activeIndex = -1;

            dropdown.querySelectorAll('.suggestion-item').forEach(el => {
                el.addEventListener('click', function() {
                    input.value = this.getAttribute('data-value');
                    dropdown.style.display = 'none';
                    input.closest('form').submit();
                });
            });
        }

        function updateActiveItem(items) {
            items.forEach((item, index) => {
                if (index === activeIndex) {
                    item.classList.add('active');
                    item.scrollIntoView({ block: 'nearest' });
                } else {
                    item.classList.remove('active');
                }
            });
        }

        input.addEventListener('input', function() { renderCompanySuggestions(this.value); });
        input.addEventListener('focus', function() { renderCompanySuggestions(this.value); });

        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });

        input.addEventListener('keydown', function(e) {
            const items = dropdown.querySelectorAll('.suggestion-item');
            if (!items.length || dropdown.style.display === 'none') return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                activeIndex = (activeIndex + 1) % items.length;
                updateActiveItem(items);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                activeIndex = (activeIndex - 1 + items.length) % items.length;
                updateActiveItem(items);
            } else if (e.key === 'Enter') {
                if (activeIndex >= 0 && items[activeIndex]) {
                    e.preventDefault();
                    items[activeIndex].click();
                }
            } else if (e.key === 'Escape') {
                dropdown.style.display = 'none';
            }
        });
    });
</script>

<?php
// Variables $student and $searchEnroll are set by ReportController::studentProfile()
$searchEnroll = $searchEnroll ?? (isset($_POST['enroll']) ? trim($_POST['enroll']) : (isset($_GET['enroll']) ? trim($_GET['enroll']) : ''));
$student      = $student ?? null;
$error        = ($searchEnroll !== '' && $student === null)
    ? "No student found with enrollment number <strong>" . htmlspecialchars($searchEnroll) . "</strong>."
    : '';

// Colour helpers for status
$statusMeta = [
    'Placed'        => ['class' => 'status-active',    'icon' => 'fa-briefcase',      'color' => '#22c55e', 'bg' => '#f0fdf4'],
    'Internship'    => ['class' => 'status-internship', 'icon' => 'fa-laptop-code',    'color' => '#0ea5e9', 'bg' => '#f0f9ff'],
    'Higher Studies'=> ['class' => 'status-higher',    'icon' => 'fa-graduation-cap',  'color' => '#8b5cf6', 'bg' => '#faf5ff'],
    'Business'      => ['class' => 'status-business',  'icon' => 'fa-store',           'color' => '#d97706', 'bg' => '#fffbeb'],
    'Unplaced'      => ['class' => 'status-expired',   'icon' => 'fa-user-clock',      'color' => '#94a3b8', 'bg' => '#f8fafc'],
];

$sm = $student ? ($statusMeta[$student['placement_status']] ?? $statusMeta['Unplaced']) : [];
?>

<div class="container" id="studentProfileRoot">

    <!-- ── HERO ── -->
    <div class="report-hero spr-hero">
        <div class="report-hero-left">
            <div class="report-hero-icon" style="background: linear-gradient(135deg,#7c3aed,#3b5bdb);">
                <i class="fa-solid fa-id-card"></i>
            </div>
            <div>
                <h2 class="report-hero-title">Individual Student Report</h2>
                <p class="report-hero-sub">Search by enrollment number to generate a detailed student profile &amp; placement card</p>
            </div>
        </div>
        <div class="report-hero-actions">
            <a href="index.php?module=report" class="btn btn-light" id="btnSprBack">
                <i class="fa-solid fa-arrow-left"></i> All Reports
            </a>
            <?php if ($student): ?>
                <button class="btn btn-light" onclick="window.print()" id="btnSprPrint">
                    <i class="fa-solid fa-print"></i> Print Card
                </button>
                <button class="btn btn-primary" onclick="exportSprCSV()" id="btnSprExport">
                    <i class="fa-solid fa-file-csv"></i> Export CSV
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- ── SEARCH BAR ── -->
    <div class="card spr-search-card">
        <div class="spr-search-inner">
            <div class="spr-search-label">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span>Search by Enrollment Number</span>
            </div>
            <form action="index.php" method="POST" class="spr-search-form" id="sprSearchForm">
                <input type="hidden" name="module" value="report">
                <input type="hidden" name="action" value="studentProfile">
                <div class="spr-input-wrap autocomplete-wrapper">
                    <i class="fa-solid fa-id-badge spr-input-icon"></i>
                    <input type="text" name="enroll" id="sprEnrollInput"
                           class="form-control spr-enroll-input"
                           placeholder="Enter enrollment number (e.g. 250114305001)"
                           value="<?= htmlspecialchars($searchEnroll) ?>"
                           autocomplete="off" autocorrect="off"
                           spellcheck="false">
                    <!-- Live Floating Suggestions Dropdown -->
                    <div class="search-suggestions-dropdown" id="sprEnrollDropdown"></div>
                    <?php if ($searchEnroll): ?>
                        <button type="button" class="spr-clear-btn" onclick="document.getElementById('sprEnrollInput').value=''; this.closest('form').submit();" title="Clear">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary" id="btnSprSearch">
                    <i class="fa-solid fa-magnifying-glass"></i> Find Student
                </button>
            </form>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input    = document.getElementById('sprEnrollInput');
            const dropdown = document.getElementById('sprEnrollDropdown');
            let activeIndex = -1;
            let debounceTimer = null;

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

            function fetchSuggestions(query) {
                const q = query.trim();
                // OPTIMIZATION: Do not show suggestions when empty
                if (!q || q.length < 1) {
                    dropdown.style.display = 'none';
                    dropdown.innerHTML = '';
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    fetch('index.php?module=report&action=suggestEnrollment&q=' + encodeURIComponent(q))
                        .then(res => res.json())
                        .then(matches => {
                            if (!matches || matches.length === 0) {
                                dropdown.innerHTML = `<div class="suggestion-empty"><i class="fa-solid fa-id-badge"></i> No matching students found</div>`;
                                dropdown.style.display = 'block';
                                return;
                            }

                            let html = `<div class="suggestion-group-header"><i class="fa-solid fa-users"></i> Matching Students</div>`;
                            matches.forEach(s => {
                                html += `<div class="suggestion-item" data-enroll="${escapeHtml(s.enroll_no)}">
                                            <i class="fa-solid fa-id-card suggestion-icon"></i>
                                            <div class="suggestion-text" style="display:flex; flex-direction:column; gap:2px;">
                                                <span style="font-weight:700; font-family:'Courier New', monospace; color:#38bdf8;">${highlightMatch(s.enroll_no, q)}</span>
                                                <span style="font-size:0.75rem; color:#94a3b8;">${highlightMatch(s.name || '', q)} · ${escapeHtml(s.department || '')}</span>
                                            </div>
                                            <span class="suggestion-badge">Select</span>
                                         </div>`;
                            });

                            dropdown.innerHTML = html;
                            dropdown.style.display = 'block';
                            activeIndex = -1;

                            dropdown.querySelectorAll('.suggestion-item').forEach(item => {
                                item.addEventListener('click', function() {
                                    input.value = this.getAttribute('data-enroll');
                                    dropdown.style.display = 'none';
                                    document.getElementById('sprSearchForm').submit();
                                });
                            });
                        })
                        .catch(() => {
                            dropdown.style.display = 'none';
                        });
                }, 120);
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

            input.addEventListener('input', function() { fetchSuggestions(this.value); });
            input.addEventListener('focus', function() { fetchSuggestions(this.value); });

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

        <?php if ($error): ?>
            <div class="spr-not-found">
                <i class="fa-solid fa-circle-exclamation" style="color:var(--rose-600);"></i>
                <?= $error ?>
                <span>Try checking the enrollment number and search again.</span>
            </div>
        <?php endif; ?>
    </div>

    <!-- ── STUDENT PROFILE CARD ── -->
    <?php if ($student): ?>

    <?php
    $cgpa    = (float)$student['cgpa'];
    $cgpaColor = $cgpa >= 9 ? '#22c55e' : ($cgpa >= 8 ? '#3b5bdb' : ($cgpa >= 7 ? '#d97706' : '#94a3b8'));
    $skills  = !empty($student['skills']) ? array_filter(array_map('trim', explode(',', $student['skills']))) : [];
    $dob     = !empty($student['dob']) ? date('d M Y', strtotime($student['dob'])) : null;
    $placed  = !in_array($student['placement_status'], ['Unplaced']);
    ?>

    <div class="spr-card-wrapper" id="sprProfileCard">

        <!-- ── LEFT: Identity Panel ── -->
        <div class="card spr-identity-card">

            <!-- Avatar -->
            <div class="spr-avatar-section">
                <div class="spr-avatar" style="background: linear-gradient(135deg, <?= $sm['color'] ?>22, <?= $sm['color'] ?>44); border: 3px solid <?= $sm['color'] ?>44;">
                    <span><?= mb_strtoupper(mb_substr($student['name'], 0, 1)) ?></span>
                </div>
                <div class="spr-avatar-info">
                    <h3 class="spr-student-name"><?= htmlspecialchars($student['name']) ?></h3>
                    <code class="spr-enroll-code"><?= htmlspecialchars($student['enroll_no']) ?></code>
                    <span class="status-badge <?= $sm['class'] ?>" style="margin-top:0.375rem;">
                        <i class="fa-solid <?= $sm['icon'] ?> status-dot"></i>
                        <?= htmlspecialchars($student['placement_status']) ?>
                    </span>
                </div>
            </div>

            <!-- CGPA Gauge-style display -->
            <div class="spr-cgpa-block">
                <div class="spr-cgpa-ring" style="--cgpa-color: <?= $cgpaColor ?>; --cgpa-pct: <?= round(($cgpa / 10) * 100) ?>%">
                    <span class="spr-cgpa-value"><?= number_format($cgpa, 2) ?></span>
                    <span class="spr-cgpa-label">CGPA</span>
                </div>
                <div class="spr-cgpa-meta">
                    <span>Out of 10.00</span>
                    <span style="color:<?= $cgpaColor ?>; font-weight:700;"><?=
                        $cgpa >= 9 ? '🏆 Distinction' : ($cgpa >= 8 ? '⭐ First Class' : ($cgpa >= 7 ? '✓ Second Class' : 'Pass'))
                    ?></span>
                </div>
            </div>

            <!-- Info Grid -->
            <div class="spr-info-grid">
                <div class="spr-info-item">
                    <span class="spr-info-label"><i class="fa-solid fa-building-columns"></i> Department</span>
                    <span class="spr-info-value"><?= htmlspecialchars($student['department']) ?></span>
                </div>
                <div class="spr-info-item">
                    <span class="spr-info-label"><i class="fa-solid fa-layer-group"></i> Semester</span>
                    <span class="spr-info-value">Semester <?= (int)$student['semester'] ?></span>
                </div>
                <div class="spr-info-item">
                    <span class="spr-info-label"><i class="fa-solid fa-calendar-days"></i> Passing Year</span>
                    <span class="spr-info-value year-pill"><?= (int)$student['passing_year'] ?></span>
                </div>
                <div class="spr-info-item">
                    <span class="spr-info-label"><i class="fa-solid fa-venus-mars"></i> Gender</span>
                    <span class="spr-info-value">
                        <span class="<?= strtolower($student['gender']) ?>-pill">
                            <i class="fa-solid fa-<?= $student['gender'] === 'Male' ? 'mars' : 'venus' ?>"></i>
                            <?= $student['gender'] ?>
                        </span>
                    </span>
                </div>
                <?php if ($dob): ?>
                <div class="spr-info-item">
                    <span class="spr-info-label"><i class="fa-solid fa-cake-candles"></i> Date of Birth</span>
                    <span class="spr-info-value"><?= $dob ?></span>
                </div>
                <?php endif; ?>
            </div>

            <!-- Contact -->
            <div class="spr-contact-section">
                <div class="spr-contact-header">Contact Information</div>
                <?php if (!empty($student['email'])): ?>
                <a href="mailto:<?= htmlspecialchars($student['email']) ?>" class="spr-contact-row">
                    <i class="fa-solid fa-envelope spr-contact-icon"></i>
                    <span><?= htmlspecialchars($student['email']) ?></span>
                </a>
                <?php endif; ?>
                <?php if (!empty($student['phone'])): ?>
                <a href="tel:<?= htmlspecialchars($student['phone']) ?>" class="spr-contact-row">
                    <i class="fa-solid fa-phone spr-contact-icon"></i>
                    <span><?= htmlspecialchars($student['phone']) ?></span>
                </a>
                <?php endif; ?>
                <?php if (!empty($student['address'])): ?>
                <div class="spr-contact-row">
                    <i class="fa-solid fa-location-dot spr-contact-icon"></i>
                    <span><?= htmlspecialchars($student['address']) ?></span>
                </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- ── RIGHT: Details Panel ── -->
        <div class="spr-details-panel">

            <!-- Placement Status Card -->
            <div class="card spr-placement-card" style="border-left: 4px solid <?= $sm['color'] ?>; background: <?= $sm['bg'] ?>;">
                <div class="spr-placement-header">
                    <div class="spr-placement-icon" style="background: <?= $sm['color'] ?>22; color: <?= $sm['color'] ?>;">
                        <i class="fa-solid <?= $sm['icon'] ?>"></i>
                    </div>
                    <div>
                        <span class="spr-placement-status-label">Placement Status</span>
                        <h3 class="spr-placement-status-value" style="color: <?= $sm['color'] ?>;">
                            <?= htmlspecialchars($student['placement_status']) ?>
                        </h3>
                    </div>
                </div>

                <?php if ($placed && (!empty($student['company_name']) || !empty($student['designation']))): ?>
                <div class="spr-placement-details">
                    <?php if (!empty($student['company_name'])): ?>
                    <div class="spr-pd-row">
                        <span class="spr-pd-label"><i class="fa-solid fa-building"></i> Company / Institution</span>
                        <span class="spr-pd-value company-name"><?= htmlspecialchars($student['company_name']) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($student['designation'])): ?>
                    <div class="spr-pd-row">
                        <span class="spr-pd-label"><i class="fa-solid fa-user-tie"></i> Designation / Role</span>
                        <span class="spr-pd-value"><?= htmlspecialchars($student['designation']) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($student['package_lpa']) && $student['package_lpa'] > 0): ?>
                    <div class="spr-pd-row">
                        <span class="spr-pd-label"><i class="fa-solid fa-indian-rupee-sign"></i> Package</span>
                        <span class="spr-pd-value">
                            <span class="package-tag" style="font-size:0.9rem;">
                                <i class="fa-solid fa-indian-rupee-sign"></i>
                                <?= number_format((float)$student['package_lpa'], 2) ?> LPA
                            </span>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
                <?php elseif ($student['placement_status'] === 'Unplaced'): ?>
                <p class="spr-unplaced-msg">
                    <i class="fa-solid fa-hourglass-half"></i>
                    This student is currently seeking placement opportunities.
                </p>
                <?php endif; ?>
            </div>

            <!-- Academic Summary -->
            <div class="card spr-academic-card">
                <div class="spr-section-title">
                    <i class="fa-solid fa-chart-line"></i> Academic Summary
                </div>
                <div class="spr-academic-grid">
                    <div class="spr-academic-stat">
                        <span class="spr-academic-value" style="color:<?= $cgpaColor ?>;"><?= number_format($cgpa, 2) ?></span>
                        <span class="spr-academic-label">CGPA</span>
                    </div>
                    <div class="spr-academic-stat">
                        <span class="spr-academic-value"><?= (int)$student['semester'] ?></span>
                        <span class="spr-academic-label">Semester</span>
                    </div>
                    <div class="spr-academic-stat">
                        <span class="spr-academic-value"><?= (int)$student['passing_year'] ?></span>
                        <span class="spr-academic-label">Passing Year</span>
                    </div>
                    <div class="spr-academic-stat">
                        <span class="spr-academic-value" style="font-size:0.9rem;"><?= htmlspecialchars(mb_strimwidth($student['department'], 0, 3, '')) ?></span>
                        <span class="spr-academic-label">Department</span>
                    </div>
                </div>

                <!-- CGPA visual bar -->
                <div style="margin-top: 1rem;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:0.35rem;">
                        <span style="font-size:0.72rem; font-weight:600; color:var(--neutral-500);">CGPA Progress</span>
                        <span style="font-size:0.72rem; font-weight:700; color:<?= $cgpaColor ?>;"><?= number_format($cgpa, 2) ?> / 10.00</span>
                    </div>
                    <div class="dash-bar-track" style="height:12px;">
                        <div class="dash-bar-fill" style="width:<?= round(($cgpa / 10) * 100) ?>%; background: linear-gradient(90deg, <?= $cgpaColor ?>, <?= $cgpaColor ?>99);"></div>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-top:0.25rem; font-size:0.65rem; color:var(--neutral-400);">
                        <span>0</span><span>5</span><span>10</span>
                    </div>
                </div>
            </div>

            <!-- Skills -->
            <?php if (!empty($skills)): ?>
            <div class="card spr-skills-card">
                <div class="spr-section-title">
                    <i class="fa-solid fa-code"></i> Skills &amp; Technologies
                </div>
                <div class="spr-skills-wrap">
                    <?php foreach ($skills as $skill): ?>
                        <span class="spr-skill-tag"><?= htmlspecialchars(trim($skill)) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Record Meta -->
            <div class="card spr-meta-card">
                <div class="spr-section-title">
                    <i class="fa-solid fa-circle-info"></i> Record Information
                </div>
                <div class="spr-meta-grid">
                    <div class="spr-meta-row">
                        <span class="spr-meta-label">Record ID</span>
                        <span class="spr-meta-value"><code>#<?= (int)$student['id'] ?></code></span>
                    </div>
                    <?php if (!empty($student['created_at'])): ?>
                    <div class="spr-meta-row">
                        <span class="spr-meta-label">Added On</span>
                        <span class="spr-meta-value"><?= date('d M Y', strtotime($student['created_at'])) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($student['updated_at'])): ?>
                    <div class="spr-meta-row">
                        <span class="spr-meta-label">Last Updated</span>
                        <span class="spr-meta-value"><?= date('d M Y, g:i A', strtotime($student['updated_at'])) ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="spr-meta-row">
                        <span class="spr-meta-label">Report Generated</span>
                        <span class="spr-meta-value"><?= date('d M Y, g:i A') ?></span>
                    </div>
                </div>
            </div>

        </div>

    </div><!-- /.spr-card-wrapper -->

    <script>
    function exportSprCSV() {
        const s = <?= json_encode([
            'Enrollment No'    => $student['enroll_no'],
            'Name'             => $student['name'],
            'Gender'           => $student['gender'],
            'Department'       => $student['department'],
            'Semester'         => $student['semester'],
            'CGPA'             => $student['cgpa'],
            'Passing Year'     => $student['passing_year'],
            'Placement Status' => $student['placement_status'],
            'Company'          => $student['company_name'] ?? '',
            'Designation'      => $student['designation'] ?? '',
            'Package (LPA)'    => $student['package_lpa'] ?? '',
            'Email'            => $student['email'] ?? '',
            'Phone'            => $student['phone'] ?? '',
            'Address'          => $student['address'] ?? '',
            'Skills'           => $student['skills'] ?? '',
        ]) ?>;
        const headers = Object.keys(s);
        const values  = Object.values(s);
        const csv = [
            headers.map(h => `"${h}"`).join(','),
            values.map(v => `"${String(v).replace(/"/g,'""')}"`).join(',')
        ].join('\n');
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `student_profile_<?= htmlspecialchars($student['enroll_no']) ?>_<?= date('Y-m-d') ?>.csv`;
        link.click();
    }
    </script>

    <?php endif; ?>

</div>

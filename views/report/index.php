<?php
// Pre-process data for JavaScript charts
$placementLabels = array_keys($placementStatusBreakdown);
$placementValues = array_values($placementStatusBreakdown);

$deptLabels    = array_column($deptBreakdown,  'department');
$deptTotal     = array_column($deptBreakdown,  'total');
$deptPlaced    = array_column($deptBreakdown,  'placed');
$deptIntern    = array_column($deptBreakdown,  'internship');

$yearLabels    = array_column($yearTrend, 'passing_year');
$yearTotal     = array_column($yearTrend, 'total');
$yearPlaced    = array_column($yearTrend, 'placed');
$yearIntern    = array_column($yearTrend, 'internship');
$yearUnplaced  = array_column($yearTrend, 'unplaced');

$wsYearLabels  = array_column($workshopTrend, 'academic_year');
$wsParticipants= array_column($workshopTrend, 'total_participants');
$wsWorkshops   = array_column($workshopTrend, 'total_workshops');

$mouYearLabels = array_column($mouByYear, 'year');
$mouYearActive = array_column($mouByYear, 'active');
$mouYearExp    = array_column($mouByYear, 'expired');
$mouYearTotal  = array_column($mouByYear, 'total');

$cgpaLabels = ['9–10', '8–9', '7–8', '6–7', '<6'];
$cgpaValues = [
    (int)($cgpaBuckets['9_to_10']   ?? 0),
    (int)($cgpaBuckets['8_to_9']    ?? 0),
    (int)($cgpaBuckets['7_to_8']    ?? 0),
    (int)($cgpaBuckets['6_to_7']    ?? 0),
    (int)($cgpaBuckets['below_6']   ?? 0),
];

$totalStudents = (int)($placementStats['total_students'] ?? 0);
$placedRate    = $totalStudents > 0 ? round(($placementStats['placed_count'] / $totalStudents) * 100, 1) : 0;
$internRate    = $totalStudents > 0 ? round(($placementStats['internship_count'] / $totalStudents) * 100, 1) : 0;
$higherRate    = $totalStudents > 0 ? round(($placementStats['higher_studies_count'] / $totalStudents) * 100, 1) : 0;
?>

<!-- ======== REPORT PAGE ======== -->
<div class="container" id="reportRoot">

    <!-- ── HERO ── -->
    <div class="report-hero">
        <div class="report-hero-left">
            <div class="report-hero-icon">
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <div>
                <h2 class="report-hero-title">Reports &amp; Analytics</h2>
                <p class="report-hero-sub">
                    Comprehensive institutional insights · Generated <?= date('d M Y, g:i A') ?>
                </p>
            </div>
        </div>
        <div class="report-hero-actions">
            <button class="btn btn-light" onclick="window.print()" id="btnPrintReport">
                <i class="fa-solid fa-print"></i> Print Report
            </button>
            <button class="btn btn-primary" onclick="exportAllCSV()" id="btnExportCSV">
                <i class="fa-solid fa-file-csv"></i> Export CSV
            </button>
        </div>
    </div>

    <!-- ── SUMMARY KPI ROW ── -->
    <div class="report-summary-grid">
        <div class="report-sum-card report-sum-blue">
            <div class="report-sum-icon"><i class="fa-solid fa-handshake"></i></div>
            <div class="report-sum-body">
                <span class="report-sum-value"><?= number_format($mouStats['total'] ?? 0) ?></span>
                <span class="report-sum-label">Total MOUs</span>
                <span class="report-sum-meta"><?= $mouStats['active'] ?? 0 ?> Active · <?= $mouStats['expired'] ?? 0 ?> Expired</span>
            </div>
        </div>
        <div class="report-sum-card report-sum-purple">
            <div class="report-sum-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
            <div class="report-sum-body">
                <span class="report-sum-value"><?= number_format($workshopStats['total_workshops'] ?? 0) ?></span>
                <span class="report-sum-label">Workshops</span>
                <span class="report-sum-meta"><?= number_format($workshopStats['total_participants'] ?? 0) ?> total participants</span>
            </div>
        </div>
        <div class="report-sum-card report-sum-green">
            <div class="report-sum-icon"><i class="fa-solid fa-briefcase"></i></div>
            <div class="report-sum-body">
                <span class="report-sum-value"><?= $placedRate ?>%</span>
                <span class="report-sum-label">Placement Rate</span>
                <span class="report-sum-meta"><?= number_format($placementStats['placed_count'] ?? 0) ?> of <?= number_format($totalStudents) ?> students</span>
            </div>
        </div>
        <div class="report-sum-card report-sum-amber">
            <div class="report-sum-icon"><i class="fa-solid fa-laptop-code"></i></div>
            <div class="report-sum-body">
                <span class="report-sum-value"><?= $internRate ?>%</span>
                <span class="report-sum-label">Internship Rate</span>
                <span class="report-sum-meta"><?= number_format($placementStats['internship_count'] ?? 0) ?> students</span>
            </div>
        </div>
        <div class="report-sum-card report-sum-rose">
            <div class="report-sum-icon"><i class="fa-solid fa-graduation-cap"></i></div>
            <div class="report-sum-body">
                <span class="report-sum-value"><?= $higherRate ?>%</span>
                <span class="report-sum-label">Higher Studies</span>
                <span class="report-sum-meta"><?= number_format($placementStats['higher_studies_count'] ?? 0) ?> students</span>
            </div>
        </div>
        <div class="report-sum-card report-sum-teal">
            <div class="report-sum-icon"><i class="fa-solid fa-star"></i></div>
            <div class="report-sum-body">
                <span class="report-sum-value"><?= $placementStats['max_package'] ? number_format((float)$placementStats['max_package'], 2) . ' LPA' : 'N/A' ?></span>
                <span class="report-sum-label">Highest Package</span>
                <span class="report-sum-meta">Avg CGPA: <?= number_format((float)($placementStats['avg_cgpa'] ?? 0), 2) ?></span>
            </div>
        </div>
    </div>

    <!-- ── SECTION 1: PLACEMENT CHARTS ── -->
    <div class="report-section-header">
        <div class="report-section-badge report-badge-green">
            <i class="fa-solid fa-user-graduate"></i>
        </div>
        <div>
            <h3 class="report-section-title">Student Placement Analysis</h3>
            <p class="report-section-sub">Status breakdown, year-wise trends, and department performance</p>
        </div>
    </div>

    <div class="report-chart-row-3">

        <!-- Donut: Placement Status -->
        <div class="card report-chart-card">
            <div class="report-chart-head">
                <h4><i class="fa-solid fa-chart-pie"></i> Status Distribution</h4>
            </div>
            <div class="report-chart-body">
                <canvas id="chartPlacementDonut" height="240"></canvas>
            </div>
            <div class="report-chart-legend" id="donutLegend"></div>
        </div>

        <!-- Bar: Year-wise Trend -->
        <div class="card report-chart-card">
            <div class="report-chart-head">
                <h4><i class="fa-solid fa-chart-column"></i> Year-wise Trend</h4>
            </div>
            <div class="report-chart-body">
                <canvas id="chartYearTrend" height="240"></canvas>
            </div>
        </div>

        <!-- Bar: CGPA Distribution -->
        <div class="card report-chart-card">
            <div class="report-chart-head">
                <h4><i class="fa-solid fa-chart-bar"></i> CGPA Distribution</h4>
            </div>
            <div class="report-chart-body">
                <canvas id="chartCgpa" height="240"></canvas>
            </div>
        </div>

    </div>

    <!-- Department-wise Table -->
    <div class="card" style="margin-bottom: 1.25rem;">
        <div class="card-header">
            <div class="card-title">
                <h2><i class="fa-solid fa-building-columns"></i> Department-wise Placement Summary</h2>
                <span class="record-count"><?= count($deptBreakdown) ?> department(s)</span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="data-table" id="deptPlacementTable">
                <thead>
                    <tr>
                        <th>Department</th>
                        <th>Total</th>
                        <th>Placed</th>
                        <th>Internship</th>
                        <th>Higher Studies</th>
                        <th>Unplaced</th>
                        <th>Placement %</th>
                        <th>Avg CGPA</th>
                        <th>Top Package</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($deptBreakdown)): ?>
                        <tr><td colspan="9"><div class="empty-state"><div class="empty-icon"><i class="fa-solid fa-database"></i></div><h3>No Data Yet</h3></div></td></tr>
                    <?php else: foreach ($deptBreakdown as $d): ?>
                        <?php $dPct = $d['total'] > 0 ? round(($d['placed'] / $d['total']) * 100, 1) : 0; ?>
                        <tr>
                            <td><span class="company-name"><?= htmlspecialchars($d['department']) ?></span></td>
                            <td><span class="year-pill"><?= (int)$d['total'] ?></span></td>
                            <td><span class="status-badge status-active"><i class="fa-solid fa-circle status-dot"></i><?= (int)$d['placed'] ?></span></td>
                            <td><span class="status-badge status-internship"><i class="fa-solid fa-circle status-dot"></i><?= (int)$d['internship'] ?></span></td>
                            <td><span class="status-badge" style="background:var(--purple-100);color:var(--purple-600)"><i class="fa-solid fa-circle status-dot"></i><?= (int)$d['higher'] ?></span></td>
                            <td><span class="status-badge status-expired"><i class="fa-solid fa-circle status-dot"></i><?= (int)$d['unplaced'] ?></span></td>
                            <td>
                                <div class="report-inline-bar">
                                    <div class="report-inline-fill" style="width:<?= $dPct ?>%;"></div>
                                    <span><?= $dPct ?>%</span>
                                </div>
                            </td>
                            <td><span class="mou-id"><?= number_format((float)($d['avg_cgpa'] ?? 0), 2) ?></span></td>
                            <td>
                                <?php if ($d['max_package']): ?>
                                    <span class="package-tag"><i class="fa-solid fa-indian-rupee-sign"></i><?= number_format((float)$d['max_package'], 2) ?> LPA</span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Year-wise Placement Detail Table -->
    <div class="card" style="margin-bottom: 1.75rem;">
        <div class="card-header">
            <div class="card-title">
                <h2><i class="fa-solid fa-calendar-days"></i> Year-wise Placement Report</h2>
                <span class="record-count"><?= count($yearTrend) ?> batch(es)</span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="data-table" id="yearPlacementTable">
                <thead>
                    <tr>
                        <th>Passing Year</th>
                        <th>Total Students</th>
                        <th>Placed</th>
                        <th>Internship</th>
                        <th>Unplaced</th>
                        <th>Placement Rate</th>
                        <th>Avg CGPA</th>
                        <th>Highest Package</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($yearTrend)): ?>
                        <tr><td colspan="8"><div class="empty-state"><div class="empty-icon"><i class="fa-solid fa-database"></i></div><h3>No Data Yet</h3></div></td></tr>
                    <?php else: foreach ($yearTrend as $y): ?>
                        <?php $yPct = $y['total'] > 0 ? round(($y['placed'] / $y['total']) * 100, 1) : 0; ?>
                        <tr>
                            <td><span class="year-pill" style="font-size:0.85rem; padding:0.3rem 0.75rem;"><?= (int)$y['passing_year'] ?></span></td>
                            <td><strong><?= (int)$y['total'] ?></strong></td>
                            <td><span class="status-badge status-active"><i class="fa-solid fa-circle status-dot"></i><?= (int)$y['placed'] ?></span></td>
                            <td><span class="status-badge status-internship"><i class="fa-solid fa-circle status-dot"></i><?= (int)$y['internship'] ?></span></td>
                            <td><span class="status-badge status-expired"><i class="fa-solid fa-circle status-dot"></i><?= (int)$y['unplaced'] ?></span></td>
                            <td>
                                <div class="report-inline-bar">
                                    <div class="report-inline-fill" style="width:<?= $yPct ?>%;"></div>
                                    <span><?= $yPct ?>%</span>
                                </div>
                            </td>
                            <td><span class="mou-id"><?= number_format((float)($y['avg_cgpa'] ?? 0), 2) ?></span></td>
                            <td>
                                <?php if ($y['max_package']): ?>
                                    <span class="package-tag"><i class="fa-solid fa-indian-rupee-sign"></i><?= number_format((float)$y['max_package'], 2) ?> LPA</span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ── SECTION 2: WORKSHOP & MOU CHARTS ── -->
    <div class="report-section-header">
        <div class="report-section-badge report-badge-purple">
            <i class="fa-solid fa-chart-line"></i>
        </div>
        <div>
            <h3 class="report-section-title">Workshops &amp; MOU Analytics</h3>
            <p class="report-section-sub">Annual workshop participation trends and MOU activity by year</p>
        </div>
    </div>

    <div class="report-chart-row-2">

        <!-- Line: Workshop Participants per Year -->
        <div class="card report-chart-card">
            <div class="report-chart-head">
                <h4><i class="fa-solid fa-chart-line"></i> Workshop Participation Trend</h4>
            </div>
            <div class="report-chart-body">
                <canvas id="chartWorkshopTrend" height="220"></canvas>
            </div>
        </div>

        <!-- Bar: MOU Activity by Year -->
        <div class="card report-chart-card">
            <div class="report-chart-head">
                <h4><i class="fa-solid fa-chart-column"></i> MOU Activity by Year</h4>
            </div>
            <div class="report-chart-body">
                <canvas id="chartMouByYear" height="220"></canvas>
            </div>
        </div>

    </div>

    <!-- Workshop Detail Table -->
    <div class="card" style="margin-bottom: 1.75rem;">
        <div class="card-header">
            <div class="card-title">
                <h2><i class="fa-solid fa-chalkboard-user"></i> Workshop Year-wise Summary</h2>
            </div>
        </div>
        <div class="table-responsive">
            <table class="data-table" id="workshopYearTable">
                <thead>
                    <tr>
                        <th>Academic Year</th>
                        <th>Workshops Held</th>
                        <th>Total Participants</th>
                        <th>Certified Events</th>
                        <th>Avg Participants / Event</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($workshopTrend)): ?>
                        <tr><td colspan="5"><div class="empty-state"><div class="empty-icon"><i class="fa-solid fa-database"></i></div><h3>No Data Yet</h3></div></td></tr>
                    <?php else: foreach ($workshopTrend as $w): ?>
                        <?php $avgPart = $w['total_workshops'] > 0 ? round($w['total_participants'] / $w['total_workshops']) : 0; ?>
                        <tr>
                            <td><span class="year-pill"><?= (int)$w['academic_year'] ?></span></td>
                            <td><strong><?= (int)$w['total_workshops'] ?></strong></td>
                            <td><span class="status-badge status-internship"><i class="fa-solid fa-users status-dot" style="font-size:0.6rem;"></i><?= number_format((int)$w['total_participants']) ?></span></td>
                            <td><?= (int)$w['certified'] ?> <span class="text-muted">/ <?= (int)$w['total_workshops'] ?></span></td>
                            <td><span class="mou-id"><?= $avgPart ?> students</span></td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ── SECTION 3: TOP PACKAGES ── -->
    <?php if (!empty($topPackages)): ?>
    <div class="report-section-header">
        <div class="report-section-badge report-badge-amber">
            <i class="fa-solid fa-trophy"></i>
        </div>
        <div>
            <h3 class="report-section-title">Top Package Earners</h3>
            <p class="report-section-sub">Highest-paid placed students across all batches</p>
        </div>
    </div>

    <div class="card" style="margin-bottom: 1.75rem;">
        <div class="table-responsive">
            <table class="data-table" id="topPackageTable">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Student</th>
                        <th>Department</th>
                        <th>Company</th>
                        <th>Designation</th>
                        <th>Batch</th>
                        <th>Package</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rank = 1; foreach ($topPackages as $tp): ?>
                    <tr>
                        <td>
                            <?php if ($rank <= 3): ?>
                                <span class="report-rank report-rank-<?= $rank ?>">
                                    <i class="fa-solid fa-<?= $rank === 1 ? 'trophy' : ($rank === 2 ? 'medal' : 'award') ?>"></i>
                                    #<?= $rank ?>
                                </span>
                            <?php else: ?>
                                <span class="mou-id">#<?= $rank ?></span>
                            <?php endif; ?>
                        </td>
                        <td><span class="company-name"><?= htmlspecialchars($tp['name']) ?></span></td>
                        <td><?= htmlspecialchars($tp['department']) ?></td>
                        <td><?= htmlspecialchars($tp['company_name'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($tp['designation'] ?? '—') ?></td>
                        <td><span class="year-pill"><?= (int)$tp['passing_year'] ?></span></td>
                        <td>
                            <span class="package-tag" style="font-size:0.85rem;">
                                <i class="fa-solid fa-indian-rupee-sign"></i>
                                <?= number_format((float)$tp['package_lpa'], 2) ?> LPA
                            </span>
                        </td>
                    </tr>
                    <?php $rank++; endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- ── SECTION 4: GENDER ANALYTICS ── -->
    <?php if (!empty($genderSplit)): ?>
    <div class="report-section-header">
        <div class="report-section-badge report-badge-rose">
            <i class="fa-solid fa-venus-mars"></i>
        </div>
        <div>
            <h3 class="report-section-title">Gender Distribution</h3>
            <p class="report-section-sub">Student enrollment and placement split by gender</p>
        </div>
    </div>

    <div class="report-gender-grid" style="margin-bottom: 1.75rem;">
        <?php foreach ($genderSplit as $g):
            $gPct = $g['total'] > 0 ? round(($g['placed'] / $g['total']) * 100, 1) : 0;
            $isMale = $g['gender'] === 'Male';
        ?>
        <div class="card report-gender-card">
            <div class="report-gender-icon <?= $isMale ? 'gender-male' : 'gender-female' ?>">
                <i class="fa-solid fa-<?= $isMale ? 'mars' : 'venus' ?>"></i>
            </div>
            <div class="report-gender-body">
                <span class="report-gender-label"><?= htmlspecialchars($g['gender']) ?></span>
                <span class="report-gender-value"><?= number_format($g['total']) ?> Students</span>
                <div class="report-gender-stat">
                    <span><?= (int)$g['placed'] ?> Placed</span>
                    <span class="report-gender-pct"><?= $gPct ?>%</span>
                </div>
                <div class="dash-bar-track" style="margin-top:0.5rem;">
                    <div class="dash-bar-fill" style="width:<?= $gPct ?>%; background: <?= $isMale ? 'var(--blue-500)' : 'var(--rose-600)' ?>;"></div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- ── SECTION: STUDENT BATCH REPORT ── -->
    <div class="report-section-header" style="margin-top: 0.5rem;">
        <div class="report-section-badge" style="background:linear-gradient(135deg,#e0f2fe,#bfdbfe); color:#0369a1;">
            <i class="fa-solid fa-users-between-lines"></i>
        </div>
        <div>
            <h3 class="report-section-title">Student Batch Report</h3>
            <p class="report-section-sub">Generate a full report for any passing year — includes all students, placement status, CGPA and more</p>
        </div>
    </div>

    <div class="card syr-cta-card">
        <div class="syr-cta-inner">
            <div class="syr-cta-icon">
                <i class="fa-solid fa-magnifying-glass-chart"></i>
            </div>
            <div class="syr-cta-body">
                <h4 class="syr-cta-title">Generate Year-wise Student Report</h4>
                <p class="syr-cta-desc">Select a batch year to view a detailed breakdown of every student's placement status, CGPA, company details, and department analytics.</p>
                <div class="syr-cta-year-row">
                    <?php foreach ($availableYears as $ay): ?>
                        <a href="index.php?module=report&action=studentReport&year=<?= (int)$ay ?>"
                           class="syr-quick-btn" id="ctaYear<?= (int)$ay ?>">
                            <i class="fa-solid fa-calendar-check"></i>
                            Batch <?= (int)$ay ?>
                        </a>
                    <?php endforeach; ?>
                    <?php if (empty($availableYears)): ?>
                        <span class="text-muted" style="font-size:0.82rem;">No student data available yet.</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="syr-cta-form">
                <p style="font-size:0.78rem; font-weight:600; color:var(--neutral-500); margin-bottom:0.5rem;">Or enter a year manually:</p>
                <form action="index.php" method="POST" style="display:flex; gap:0.5rem; align-items:center;">
                    <input type="hidden" name="module" value="report">
                    <input type="hidden" name="action" value="studentReport">
                    <input type="number" name="year" class="form-control" id="ctaYearInput"
                           placeholder="e.g. 2026"
                           min="2000" max="2100"
                           style="width:120px; text-align:center; font-weight:700; font-size:1rem;">
                    <button type="submit" class="btn btn-primary" id="btnCtaGenerate">
                        <i class="fa-solid fa-arrow-right"></i> Go
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- ── SECTION: INDIVIDUAL STUDENT REPORT ── -->
    <div class="report-section-header" style="margin-top: 0.25rem;">
        <div class="report-section-badge" style="background:linear-gradient(135deg,#faf5ff,#ede9fe); color:#7c3aed;">
            <i class="fa-solid fa-id-card"></i>
        </div>
        <div>
            <h3 class="report-section-title">Individual Student Profile</h3>
            <p class="report-section-sub">Look up any student by enrollment number and generate a full placement &amp; academic profile card</p>
        </div>
    </div>

    <div class="card spr-cta-card">
        <div class="syr-cta-inner">
            <div class="syr-cta-icon" style="background: linear-gradient(135deg, #7c3aed, #3b5bdb);">
                <i class="fa-solid fa-id-card"></i>
            </div>
            <div class="syr-cta-body">
                <h4 class="syr-cta-title">Generate Student Profile Report</h4>
                <p class="syr-cta-desc">Enter an enrollment number to instantly view a student's placement status, CGPA, skills, contact details, and company information — printable and exportable.</p>
            </div>
            <div class="syr-cta-form">
                <p style="font-size:0.78rem; font-weight:600; color:var(--neutral-500); margin-bottom:0.5rem;">Enter enrollment number:</p>
                <form action="index.php" method="POST" style="display:flex; gap:0.5rem; align-items:center;">
                    <input type="hidden" name="module" value="report">
                    <input type="hidden" name="action" value="studentProfile">
                    <input type="text" name="enroll" class="form-control" id="ctaEnrollInput"
                           placeholder="e.g. 250114305001"
                           autocomplete="off"
                           style="width:200px; font-weight:600; font-size:0.9rem; letter-spacing:0.02em;">
                    <button type="submit" class="btn btn-primary" id="btnCtaProfile">
                        <i class="fa-solid fa-arrow-right"></i> Find
                    </button>
                </form>
            </div>
        </div>
    </div>

</div><!-- /.container -->


<!-- ── CHART.JS ── -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font.family = "'Inter', system-ui, sans-serif";
Chart.defaults.color = '#64748b';

const COLORS = {
    placed:  '#22c55e',
    intern:  '#0ea5e9',
    higher:  '#8b5cf6',
    business:'#d97706',
    unplaced:'#cbd5e1',
    brand:   '#3b5bdb',
    accent:  '#7c3aed',
    amber:   '#d97706',
    rose:    '#e11d48',
};

// 1. Placement Donut
(function() {
    const labels = <?= json_encode($placementLabels) ?>;
    const values = <?= json_encode($placementValues) ?>;
    const palette = [COLORS.placed, COLORS.intern, COLORS.higher, COLORS.business, COLORS.unplaced];

    new Chart(document.getElementById('chartPlacementDonut'), {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{ data: values, backgroundColor: palette, borderWidth: 3, borderColor: '#fff', hoverOffset: 8 }]
        },
        options: {
            responsive: true,
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.label}: ${ctx.parsed} students`
                    }
                }
            }
        }
    });

    // Build custom legend
    const legend = document.getElementById('donutLegend');
    labels.forEach((l, i) => {
        legend.innerHTML += `<span class="report-legend-item"><span class="report-legend-dot" style="background:${palette[i]}"></span>${l} (${values[i]})</span>`;
    });
})();

// 2. Year-wise Trend Bar
(function() {
    const labels   = <?= json_encode(array_map('strval', $yearLabels)) ?>;
    const placed   = <?= json_encode(array_map('intval', $yearPlaced)) ?>;
    const intern   = <?= json_encode(array_map('intval', $yearIntern)) ?>;
    const unplaced = <?= json_encode(array_map('intval', $yearUnplaced)) ?>;

    new Chart(document.getElementById('chartYearTrend'), {
        type: 'bar',
        data: {
            labels,
            datasets: [
                { label: 'Placed',     data: placed,   backgroundColor: COLORS.placed,   borderRadius: 4, borderSkipped: false },
                { label: 'Internship', data: intern,   backgroundColor: COLORS.intern,   borderRadius: 4, borderSkipped: false },
                { label: 'Unplaced',   data: unplaced, backgroundColor: COLORS.unplaced, borderRadius: 4, borderSkipped: false },
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: { stacked: true, grid: { display: false } },
                y: { stacked: true, beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } }
            },
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, padding: 14 } } }
        }
    });
})();

// 3. CGPA Distribution Bar
(function() {
    const labels = <?= json_encode($cgpaLabels) ?>;
    const values = <?= json_encode($cgpaValues) ?>;
    const palette = ['#3b5bdb','#5c7cfa','#7c3aed','#8b5cf6','#cbd5e1'];

    new Chart(document.getElementById('chartCgpa'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{ label: 'Students', data: values, backgroundColor: palette, borderRadius: 6, borderSkipped: false }]
        },
        options: {
            responsive: true,
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } }
            },
            plugins: { legend: { display: false } }
        }
    });
})();

// 4. Workshop Participation Line
(function() {
    const labels = <?= json_encode(array_map('strval', $wsYearLabels)) ?>;
    const parts  = <?= json_encode(array_map('intval', $wsParticipants)) ?>;
    const events = <?= json_encode(array_map('intval', $wsWorkshops)) ?>;

    new Chart(document.getElementById('chartWorkshopTrend'), {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Participants',
                    data: parts,
                    borderColor: COLORS.accent,
                    backgroundColor: 'rgba(124,58,237,0.08)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: COLORS.accent,
                    pointRadius: 5
                },
                {
                    label: 'Events',
                    data: events,
                    borderColor: COLORS.brand,
                    backgroundColor: 'transparent',
                    tension: 0.4,
                    borderDash: [5, 4],
                    pointBackgroundColor: COLORS.brand,
                    pointRadius: 5,
                    yAxisID: 'y2'
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index' },
            scales: {
                x: { grid: { display: false } },
                y:  { beginAtZero: true, grid: { color: '#f1f5f9' }, title: { display: true, text: 'Participants' } },
                y2: { beginAtZero: true, position: 'right', grid: { display: false }, title: { display: true, text: 'Events' } }
            },
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, padding: 14 } } }
        }
    });
})();

// 5. MOU by Year Bar
(function() {
    const labels  = <?= json_encode(array_map('strval', $mouYearLabels)) ?>;
    const active  = <?= json_encode(array_map('intval', $mouYearActive)) ?>;
    const expired = <?= json_encode(array_map('intval', $mouYearExp)) ?>;

    new Chart(document.getElementById('chartMouByYear'), {
        type: 'bar',
        data: {
            labels,
            datasets: [
                { label: 'Active',  data: active,  backgroundColor: COLORS.placed,   borderRadius: 4, borderSkipped: false },
                { label: 'Expired', data: expired, backgroundColor: COLORS.unplaced, borderRadius: 4, borderSkipped: false },
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: { stacked: true, grid: { display: false } },
                y: { stacked: true, beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } }
            },
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, padding: 14 } } }
        }
    });
})();

// CSV Export helper
function tableToCSV(tableId) {
    const table = document.getElementById(tableId);
    if (!table) return '';
    let csv = [];
    for (let row of table.rows) {
        let cells = [];
        for (let cell of row.cells) {
            cells.push('"' + cell.innerText.replace(/"/g, '""').trim() + '"');
        }
        csv.push(cells.join(','));
    }
    return csv.join('\n');
}

function downloadCSV(filename, content) {
    const blob = new Blob([content], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    link.click();
}

function exportAllCSV() {
    const tables = [
        { id: 'deptPlacementTable',  name: 'dept_placement' },
        { id: 'yearPlacementTable',  name: 'year_placement' },
        { id: 'workshopYearTable',   name: 'workshop_summary' },
        { id: 'topPackageTable',     name: 'top_packages' },
    ];
    const date = new Date().toISOString().slice(0,10);
    tables.forEach(t => {
        const csv = tableToCSV(t.id);
        if (csv) downloadCSV(`college_report_${t.name}_${date}.csv`, csv);
    });
}
</script>

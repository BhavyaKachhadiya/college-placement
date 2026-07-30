<?php
$total       = (int)($batchStats['total']       ?? 0);
$placedCount = (int)($batchStats['placed']       ?? 0);
$internCount = (int)($batchStats['internship']   ?? 0);
$higherCount = (int)($batchStats['higher']       ?? 0);
$bizCount    = (int)($batchStats['business']     ?? 0);
$unplacedCnt = (int)($batchStats['unplaced']     ?? 0);

$placedPct  = $total > 0 ? round(($placedCount / $total) * 100, 1) : 0;
$internPct  = $total > 0 ? round(($internCount / $total) * 100, 1) : 0;
$higherPct  = $total > 0 ? round(($higherCount / $total) * 100, 1) : 0;

$avgCgpa    = number_format((float)($batchStats['avg_cgpa']    ?? 0), 2);
$maxCgpa    = number_format((float)($batchStats['max_cgpa']    ?? 0), 2);
$maxPkg     = $batchStats['max_package'] ? number_format((float)$batchStats['max_package'], 2) : null;
$avgPkg     = $batchStats['avg_package'] ? number_format((float)$batchStats['avg_package'], 2) : null;
$maleCount  = (int)($batchStats['male_count']   ?? 0);
$femaleCount= (int)($batchStats['female_count'] ?? 0);
?>

<div class="container" id="studentYearReportRoot">

    <!-- ── HERO ── -->
    <div class="report-hero syr-hero">
        <div class="report-hero-left">
            <div class="report-hero-icon" style="background: linear-gradient(135deg,#0891b2,#7c3aed);">
                <i class="fa-solid fa-users-between-lines"></i>
            </div>
            <div>
                <h2 class="report-hero-title">
                    Student Batch Report
                    <?php if ($year > 0): ?>
                        <span class="syr-year-badge"><?= $year ?></span>
                    <?php endif; ?>
                </h2>
                <p class="report-hero-sub">
                    <?php if ($year > 0 && $total > 0): ?>
                        <?= $total ?> students · Passing Year <?= $year ?> · Generated <?= date('d M Y, g:i A') ?>
                    <?php elseif ($year > 0): ?>
                        No students found for passing year <?= $year ?>
                    <?php else: ?>
                        Select a passing year to generate a full student report
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <div class="report-hero-actions">
            <a href="index.php?module=report" class="btn btn-light" id="btnBackToReports">
                <i class="fa-solid fa-arrow-left"></i> All Reports
            </a>
            <?php if ($year > 0 && $total > 0): ?>
                <button class="btn btn-light" onclick="window.print()" id="btnSyrPrint">
                    <i class="fa-solid fa-print"></i> Print
                </button>
                <button class="btn btn-primary" onclick="exportSyrCSV()" id="btnSyrExport">
                    <i class="fa-solid fa-file-csv"></i> Export CSV
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- ── YEAR PICKER ── -->
    <div class="card syr-picker-card">
        <div class="syr-picker-inner">
            <div class="syr-picker-label">
                <i class="fa-solid fa-calendar-days"></i>
                <span>Select Passing Year</span>
            </div>
            <form action="index.php" method="GET" class="syr-picker-form" id="syrPickerForm">
                <input type="hidden" name="module" value="report">
                <input type="hidden" name="action" value="studentReport">

                <!-- Quick Year Tabs -->
                <div class="syr-year-tabs">
                    <?php foreach ($availableYears as $ay): ?>
                        <a href="index.php?module=report&action=studentReport&year=<?= (int)$ay ?>"
                           class="syr-year-tab <?= $year == $ay ? 'active' : '' ?>"
                           id="syrTab<?= (int)$ay ?>">
                           <?= (int)$ay ?>
                        </a>
                    <?php endforeach; ?>
                    <?php if (empty($availableYears)): ?>
                        <span class="text-muted" style="font-size:0.82rem; padding:0.5rem;">No student records yet</span>
                    <?php endif; ?>
                </div>

                <!-- Manual year input -->
                <div class="syr-manual-input">
                    <input type="number" name="year" id="syrYearInput"
                           class="form-control"
                           placeholder="e.g. 2025"
                           min="2000" max="2100"
                           value="<?= $year > 0 ? $year : '' ?>"
                           style="width: 130px; text-align: center; font-weight: 700; font-size: 1rem;">
                    <button type="submit" class="btn btn-primary" id="btnGenerateReport">
                        <i class="fa-solid fa-magnifying-glass-chart"></i> Generate Report
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if ($year > 0 && $total > 0): ?>

    <!-- ── KPI STRIP ── -->
    <div class="syr-kpi-grid">
        <div class="syr-kpi syr-kpi-total">
            <i class="fa-solid fa-users syr-kpi-icon"></i>
            <span class="syr-kpi-value"><?= $total ?></span>
            <span class="syr-kpi-label">Total Students</span>
        </div>
        <div class="syr-kpi syr-kpi-placed">
            <i class="fa-solid fa-briefcase syr-kpi-icon"></i>
            <span class="syr-kpi-value"><?= $placedCount ?></span>
            <span class="syr-kpi-label">Placed <span class="syr-kpi-pct"><?= $placedPct ?>%</span></span>
        </div>
        <div class="syr-kpi syr-kpi-intern">
            <i class="fa-solid fa-laptop-code syr-kpi-icon"></i>
            <span class="syr-kpi-value"><?= $internCount ?></span>
            <span class="syr-kpi-label">Internship <span class="syr-kpi-pct"><?= $internPct ?>%</span></span>
        </div>
        <div class="syr-kpi syr-kpi-higher">
            <i class="fa-solid fa-graduation-cap syr-kpi-icon"></i>
            <span class="syr-kpi-value"><?= $higherCount ?></span>
            <span class="syr-kpi-label">Higher Studies <span class="syr-kpi-pct"><?= $higherPct ?>%</span></span>
        </div>
        <div class="syr-kpi syr-kpi-biz">
            <i class="fa-solid fa-store syr-kpi-icon"></i>
            <span class="syr-kpi-value"><?= $bizCount ?></span>
            <span class="syr-kpi-label">Business</span>
        </div>
        <div class="syr-kpi syr-kpi-unplaced">
            <i class="fa-solid fa-user-clock syr-kpi-icon"></i>
            <span class="syr-kpi-value"><?= $unplacedCnt ?></span>
            <span class="syr-kpi-label">Unplaced</span>
        </div>
        <div class="syr-kpi syr-kpi-cgpa">
            <i class="fa-solid fa-star-half-stroke syr-kpi-icon"></i>
            <span class="syr-kpi-value"><?= $avgCgpa ?></span>
            <span class="syr-kpi-label">Avg CGPA <span class="syr-kpi-pct">max <?= $maxCgpa ?></span></span>
        </div>
        <?php if ($maxPkg): ?>
        <div class="syr-kpi syr-kpi-pkg">
            <i class="fa-solid fa-trophy syr-kpi-icon"></i>
            <span class="syr-kpi-value"><?= $maxPkg ?><small> LPA</small></span>
            <span class="syr-kpi-label">Highest Pkg <span class="syr-kpi-pct">avg <?= $avgPkg ?></span></span>
        </div>
        <?php endif; ?>
        <div class="syr-kpi syr-kpi-gender">
            <i class="fa-solid fa-venus-mars syr-kpi-icon"></i>
            <span class="syr-kpi-value"><?= $maleCount ?><small style="font-size:0.7em;opacity:0.6;"> M</small> / <?= $femaleCount ?><small style="font-size:0.7em;opacity:0.6;"> F</small></span>
            <span class="syr-kpi-label">Gender Split</span>
        </div>
    </div>

    <!-- ── CHARTS + DEPT TABLE ── -->
    <div class="syr-charts-row">

        <!-- Donut Chart -->
        <div class="card syr-chart-card">
            <div class="report-chart-head">
                <h4><i class="fa-solid fa-chart-pie"></i> Placement Breakdown</h4>
            </div>
            <div class="report-chart-body" style="display:flex; align-items:center; justify-content:center;">
                <canvas id="syrDonut" width="220" height="220" style="max-width:220px;"></canvas>
            </div>
            <div class="report-chart-legend" id="syrDonutLegend"></div>
        </div>

        <!-- CGPA Distribution Bar -->
        <div class="card syr-chart-card">
            <div class="report-chart-head">
                <h4><i class="fa-solid fa-chart-bar"></i> CGPA Distribution</h4>
            </div>
            <div class="report-chart-body">
                <canvas id="syrCgpa" height="200"></canvas>
            </div>
        </div>

        <!-- Department Table -->
        <div class="card syr-chart-card syr-dept-card">
            <div class="report-chart-head">
                <h4><i class="fa-solid fa-building-columns"></i> By Department</h4>
            </div>
            <div class="table-responsive">
                <table class="data-table" id="syrDeptTable" style="min-width: auto;">
                    <thead>
                        <tr>
                            <th>Dept</th>
                            <th>Total</th>
                            <th>Placed</th>
                            <th>Intern</th>
                            <th>Unplaced</th>
                            <th>%</th>
                            <th>CGPA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($deptStats as $d):
                            $dp = $d['total'] > 0 ? round(($d['placed'] / $d['total']) * 100, 0) : 0;
                        ?>
                        <tr>
                            <td style="max-width:120px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"
                                title="<?= htmlspecialchars($d['department']) ?>">
                                <span class="company-name" style="font-size:0.78rem;"><?= htmlspecialchars($d['department']) ?></span>
                            </td>
                            <td><strong><?= (int)$d['total'] ?></strong></td>
                            <td><span class="status-badge status-active" style="font-size:0.7rem;"><i class="fa-solid fa-circle status-dot"></i><?= (int)$d['placed'] ?></span></td>
                            <td><span class="status-badge status-internship" style="font-size:0.7rem;"><i class="fa-solid fa-circle status-dot"></i><?= (int)$d['internship'] ?></span></td>
                            <td><span class="status-badge status-expired" style="font-size:0.7rem;"><i class="fa-solid fa-circle status-dot"></i><?= (int)$d['unplaced'] ?></span></td>
                            <td>
                                <span style="font-size:0.78rem; font-weight:700; color: <?= $dp >= 70 ? 'var(--green-600)' : ($dp >= 40 ? 'var(--amber-600)' : 'var(--rose-600)') ?>">
                                    <?= $dp ?>%
                                </span>
                            </td>
                            <td><span class="mou-id" style="font-size:0.72rem;"><?= number_format((float)$d['avg_cgpa'], 2) ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- ── FULL STUDENT TABLE ── -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <div class="card-title">
                <h2><i class="fa-solid fa-list-ul"></i> All Students — Passing Year <?= $year ?></h2>
                <span class="record-count"><?= $total ?> student<?= $total !== 1 ? 's' : '' ?></span>
            </div>
            <div style="display:flex; gap:0.5rem; align-items:center;">
                <!-- Live search inside table -->
                <div class="search-input-group" style="max-width: 220px;">
                    <i class="fa-solid fa-search search-icon"></i>
                    <input type="text" id="syrSearch" class="form-control search-control"
                           placeholder="Search name, enroll, company…"
                           oninput="filterSyrTable(this.value)">
                </div>
                <!-- Status filter -->
                <select id="syrStatusFilter" class="form-select" style="min-width:150px;" onchange="filterSyrTable()">
                    <option value="">All Statuses</option>
                    <option value="Placed">Placed</option>
                    <option value="Internship">Internship</option>
                    <option value="Higher Studies">Higher Studies</option>
                    <option value="Business">Business</option>
                    <option value="Unplaced">Unplaced</option>
                </select>
            </div>
        </div>
        <div class="table-responsive">
            <table class="data-table" id="syrStudentTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Enroll No.</th>
                        <th>Student Name</th>
                        <th>Gender</th>
                        <th>Department</th>
                        <th>Semester</th>
                        <th>CGPA</th>
                        <th>Status</th>
                        <th>Company / Institution</th>
                        <th>Designation</th>
                        <th>Package (LPA)</th>
                        <th>Contact</th>
                    </tr>
                </thead>
                <tbody id="syrTableBody">
                    <?php if (empty($students)): ?>
                        <tr><td colspan="12">
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fa-solid fa-users"></i></div>
                                <h3>No Students Found</h3>
                                <p>No records for passing year <?= $year ?>.</p>
                            </div>
                        </td></tr>
                    <?php else: $sr = 1; foreach ($students as $s):
                        $statusClass = match($s['placement_status']) {
                            'Placed'        => 'status-active',
                            'Internship'    => 'status-internship',
                            'Higher Studies'=> 'status-higher',
                            'Business'      => 'status-business',
                            default         => 'status-expired',
                        };
                    ?>
                        <tr data-status="<?= htmlspecialchars($s['placement_status']) ?>"
                            data-search="<?= strtolower(htmlspecialchars($s['name'] . ' ' . $s['enroll_no'] . ' ' . ($s['company_name'] ?? ''))) ?>">
                            <td><span class="mou-id"><?= $sr++ ?></span></td>
                            <td><code style="font-size:0.78rem; color:var(--brand-600);"><?= htmlspecialchars($s['enroll_no']) ?></code></td>
                            <td>
                                <div class="company-info">
                                    <span class="company-name"><?= htmlspecialchars($s['name']) ?></span>
                                    <?php if (!empty($s['email'])): ?>
                                    <a href="mailto:<?= htmlspecialchars($s['email']) ?>" class="contact-sub" style="color:var(--brand-500); font-size:0.72rem;">
                                        <i class="fa-solid fa-envelope"></i> <?= htmlspecialchars($s['email']) ?>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <span class="gender-pill <?= strtolower($s['gender']) ?>-pill">
                                    <i class="fa-solid fa-<?= $s['gender'] === 'Male' ? 'mars' : 'venus' ?>"></i>
                                    <?= $s['gender'] ?>
                                </span>
                            </td>
                            <td>
                                <span class="contact-name" style="font-size:0.8rem;"><?= htmlspecialchars($s['department']) ?></span>
                            </td>
                            <td><span class="mou-id">Sem <?= (int)$s['semester'] ?></span></td>
                            <td>
                                <?php $cgpa = (float)$s['cgpa'];
                                $cgpaColor = $cgpa >= 9 ? 'var(--green-600)' : ($cgpa >= 8 ? 'var(--blue-600)' : ($cgpa >= 7 ? 'var(--amber-600)' : 'var(--neutral-500)')); ?>
                                <span class="year-pill" style="background:var(--neutral-50); color:<?= $cgpaColor ?>; font-weight:800;">
                                    <?= number_format($cgpa, 2) ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge <?= $statusClass ?>">
                                    <i class="fa-solid fa-circle status-dot"></i>
                                    <?= htmlspecialchars($s['placement_status']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($s['company_name'])): ?>
                                    <span class="company-name" style="font-size:0.8rem;"><?= htmlspecialchars($s['company_name']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($s['designation'])): ?>
                                    <span class="contact-name" style="font-size:0.78rem;"><?= htmlspecialchars($s['designation']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($s['package_lpa']): ?>
                                    <span class="package-tag">
                                        <i class="fa-solid fa-indian-rupee-sign"></i>
                                        <?= number_format((float)$s['package_lpa'], 2) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($s['phone'])): ?>
                                    <a href="tel:<?= htmlspecialchars($s['phone']) ?>" style="color:var(--brand-500); font-size:0.78rem;">
                                        <i class="fa-solid fa-phone"></i> <?= htmlspecialchars($s['phone']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Live row count -->
        <div style="padding: 0.625rem 1.25rem; border-top: 1px solid var(--neutral-100); font-size:0.78rem; color:var(--neutral-400);">
            Showing <strong id="syrVisibleCount"><?= $total ?></strong> of <?= $total ?> students
        </div>
    </div>

    <?php elseif ($year > 0): ?>
    <!-- No data for year -->
    <div class="card" style="margin-top:1.5rem;">
        <div class="empty-state" style="padding: 3rem 1rem;">
            <div class="empty-icon"><i class="fa-solid fa-calendar-xmark"></i></div>
            <h3>No Records for <?= $year ?></h3>
            <p>There are no student records with passing year <?= $year ?>. Try a different year.</p>
        </div>
    </div>
    <?php endif; ?>

</div>

<!-- ── CHART.JS ── -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
<?php if ($year > 0 && $total > 0): ?>

Chart.defaults.font.family = "'Inter', system-ui, sans-serif";
Chart.defaults.color = '#64748b';

// Donut chart
(function(){
    const labels  = ['Placed','Internship','Higher Studies','Business','Unplaced'];
    const values  = [<?= $placedCount ?>,<?= $internCount ?>,<?= $higherCount ?>,<?= $bizCount ?>,<?= $unplacedCnt ?>];
    const palette = ['#22c55e','#0ea5e9','#8b5cf6','#d97706','#cbd5e1'];

    new Chart(document.getElementById('syrDonut'), {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{ data: values, backgroundColor: palette, borderWidth: 3, borderColor: '#fff', hoverOffset: 8 }]
        },
        options: {
            responsive: false,
            cutout: '68%',
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed}` } } }
        }
    });

    const legend = document.getElementById('syrDonutLegend');
    labels.forEach((l, i) => {
        if (values[i] > 0) {
            legend.innerHTML += `<span class="report-legend-item"><span class="report-legend-dot" style="background:${palette[i]}"></span>${l} (${values[i]})</span>`;
        }
    });
})();

// CGPA bar chart
(function(){
    const labels = ['9–10','8–9','7–8','6–7','<6'];
    const values = [
        <?= (int)($cgpaDist['9_to_10'] ?? 0) ?>,
        <?= (int)($cgpaDist['8_to_9']  ?? 0) ?>,
        <?= (int)($cgpaDist['7_to_8']  ?? 0) ?>,
        <?= (int)($cgpaDist['6_to_7']  ?? 0) ?>,
        <?= (int)($cgpaDist['below_6'] ?? 0) ?>
    ];
    const palette = ['#3b5bdb','#5c7cfa','#7c3aed','#8b5cf6','#cbd5e1'];

    new Chart(document.getElementById('syrCgpa'), {
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

// Live search + status filter
function filterSyrTable(searchVal) {
    const q      = (searchVal !== undefined ? searchVal : document.getElementById('syrSearch').value).toLowerCase().trim();
    const status = document.getElementById('syrStatusFilter').value;
    const rows   = document.querySelectorAll('#syrTableBody tr');
    let visible  = 0;
    rows.forEach(row => {
        const matchSearch = !q      || (row.dataset.search || '').includes(q);
        const matchStatus = !status || (row.dataset.status || '') === status;
        const show = matchSearch && matchStatus;
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    document.getElementById('syrVisibleCount').textContent = visible;
}

// CSV Export
function exportSyrCSV() {
    const table = document.getElementById('syrStudentTable');
    if (!table) return;
    let csv = [];
    for (let row of table.rows) {
        if (row.style.display === 'none') continue;
        let cells = [];
        for (let cell of row.cells) {
            cells.push('"' + cell.innerText.replace(/"/g,'""').trim() + '"');
        }
        csv.push(cells.join(','));
    }
    const blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `student_report_<?= $year ?>_${new Date().toISOString().slice(0,10)}.csv`;
    link.click();
}

<?php endif; ?>
</script>

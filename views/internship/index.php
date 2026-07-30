<div class="container">

    <!-- Feedback Alerts -->
    <?php if (isset($_GET['msg'])): ?>
        <?php if ($_GET['msg'] === 'deleted'): ?>
            <div class="alert alert-warning"><i class="fa-solid fa-trash-can"></i> Internship record removed.</div>
        <?php endif; ?>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><i class="fa-solid fa-triangle-exclamation"></i> <?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <!-- Page Hero -->
    <div class="dash-hero" style="margin-bottom: 1.5rem;">
        <div class="dash-hero-content">
            <div class="dash-hero-icon" style="background: linear-gradient(135deg, var(--blue-600), var(--accent-400));">
                <i class="fa-solid fa-laptop-code"></i>
            </div>
            <div>
                <h2 class="dash-hero-title">Industry Internships</h2>
                <p class="dash-hero-sub">Students currently on industry internship programmes</p>
            </div>
        </div>
        <div class="dash-hero-meta">
            <span class="dash-meta-pill" style="background: var(--blue-100); color: var(--blue-600); border-color: var(--blue-100);">
                <i class="fa-solid fa-users"></i> <?= number_format($stats['internship_count'] ?? 0) ?> Interns
            </span>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="kpi-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); margin-bottom: 1.5rem;">
        <div class="kpi-card kpi-years">
            <div class="kpi-icon"><i class="fa-solid fa-laptop-code"></i></div>
            <div class="kpi-content">
                <span class="kpi-value"><?= number_format($stats['internship_count'] ?? 0) ?></span>
                <span class="kpi-label">Total Interns</span>
            </div>
        </div>
        <div class="kpi-card kpi-total">
            <div class="kpi-icon"><i class="fa-solid fa-users"></i></div>
            <div class="kpi-content">
                <span class="kpi-value"><?= number_format($stats['total_students'] ?? 0) ?></span>
                <span class="kpi-label">Total Students</span>
            </div>
        </div>
        <div class="kpi-card kpi-active">
            <div class="kpi-icon"><i class="fa-solid fa-percent"></i></div>
            <div class="kpi-content">
                <?php
                $internPct = $stats['total_students'] > 0
                    ? round(($stats['internship_count'] / $stats['total_students']) * 100, 1)
                    : 0;
                ?>
                <span class="kpi-value"><?= $internPct ?>%</span>
                <span class="kpi-label">Internship Rate</span>
            </div>
        </div>
        <div class="kpi-card kpi-expired">
            <div class="kpi-icon"><i class="fa-solid fa-building"></i></div>
            <div class="kpi-content">
                <span class="kpi-value"><?= count(array_unique(array_filter(array_column($students, 'company_name')))) ?></span>
                <span class="kpi-label">Companies</span>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-wrapper" style="margin-bottom: 1.25rem;">
        <form action="index.php?module=internship" method="GET" class="filter-form" id="internshipFilterForm">
            <input type="hidden" name="module" value="internship">
            <input type="hidden" name="year" id="internPostYear" value="<?= htmlspecialchars($passing_year) ?>">

            <!-- Year Tabs -->
            <div class="year-tabs-container">
                <span class="tabs-label"><i class="fa-solid fa-filter"></i> Batch:</span>
                <div class="year-tabs">
                    <button type="button" onclick="setInternYear('all')"
                        class="year-tab <?= (empty($passing_year) || $passing_year === 'all') ? 'active' : '' ?>">All</button>
                    <?php foreach ($years as $yr): ?>
                        <button type="button" onclick="setInternYear('<?= (int)$yr ?>')"
                            class="year-tab <?= ($passing_year == $yr) ? 'active' : '' ?>"><?= (int)$yr ?></button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Search + Department -->
            <div class="search-input-group" style="max-width: 280px;">
                <i class="fa-solid fa-search search-icon"></i>
                <input type="text" name="search" class="form-control search-control"
                    placeholder="Search name, enroll, company…"
                    value="<?= htmlspecialchars($search) ?>" id="internSearch">
            </div>

            <div class="select-group" style="min-width: 180px;">
                <select name="department" class="form-select" id="internDept" onchange="this.form.submit()">
                    <option value="all">All Departments</option>
                    <?php foreach ($depts as $d): ?>
                        <option value="<?= htmlspecialchars($d) ?>" <?= $department === $d ? 'selected' : '' ?>>
                            <?= htmlspecialchars($d) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" id="btnInternSearch">
                <i class="fa-solid fa-search"></i> Search
            </button>
            <?php if ($search || ($passing_year && $passing_year !== 'all') || ($department && $department !== 'all')): ?>
                <a href="index.php?module=internship" class="btn btn-light" id="btnInternClear">
                    <i class="fa-solid fa-xmark"></i> Clear
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Table -->
    <div class="card table-card">
        <div class="card-header">
            <div class="card-title">
                <h2><i class="fa-solid fa-laptop-code"></i> Internship Records</h2>
                <span class="record-count"><?= count($students) ?> intern<?= count($students) !== 1 ? 's' : '' ?> found</span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="data-table internship-table" id="internshipTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Enroll No.</th>
                        <th>Department</th>
                        <th>Batch</th>
                        <th>Company</th>
                        <th>Designation</th>
                        <th>CGPA</th>
                        <th>Contact</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="fa-solid fa-laptop-code"></i></div>
                                    <h3>No Internship Records</h3>
                                    <p>No students with internship status match your current filters.</p>
                                    <a href="index.php?module=internship" class="btn btn-light" style="margin-top:0.5rem;">Clear Filters</a>
                                </div>
                            </td>
                        </tr>
                    <?php else: $sr = 1; foreach ($students as $s): ?>
                        <tr class="row-internship">
                            <td><span class="mou-id"><?= $sr++ ?></span></td>
                            <td>
                                <div class="company-info">
                                    <span class="company-name"><?= htmlspecialchars($s['name']) ?></span>
                                    <span class="contact-sub">
                                        <span class="gender-pill"><?= htmlspecialchars($s['gender']) ?></span>
                                        <?php if (!empty($s['email'])): ?>
                                            <a href="mailto:<?= htmlspecialchars($s['email']) ?>" style="color:var(--brand-500); font-size:0.75rem;">
                                                <i class="fa-solid fa-envelope"></i> <?= htmlspecialchars($s['email']) ?>
                                            </a>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </td>
                            <td><span class="mou-id"><?= htmlspecialchars($s['enroll_no']) ?></span></td>
                            <td>
                                <span class="contact-name"><?= htmlspecialchars($s['department']) ?></span>
                                <span class="contact-sub">Sem <?= (int)$s['semester'] ?></span>
                            </td>
                            <td><span class="year-pill"><?= (int)$s['passing_year'] ?></span></td>
                            <td>
                                <?php if (!empty($s['company_name'])): ?>
                                    <span class="company-name"><?= htmlspecialchars($s['company_name']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($s['designation'])): ?>
                                    <span class="contact-name"><?= htmlspecialchars($s['designation']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="year-pill" style="background: var(--blue-100); color: var(--blue-600);">
                                    <?= number_format((float)$s['cgpa'], 2) ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($s['phone'])): ?>
                                    <a href="tel:<?= htmlspecialchars($s['phone']) ?>" style="color: var(--brand-500); font-size:0.82rem;">
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
    </div>

</div>

<script>
function setInternYear(yr) {
    document.getElementById('internPostYear').value = yr;
    document.getElementById('internshipFilterForm').submit();
}
</script>

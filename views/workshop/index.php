<div class="container">
    <!-- Feedback Alerts -->
    <?php if (isset($_GET['msg'])): ?>
        <?php if ($_GET['msg'] === 'added'): ?>
            <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> Workshop activity recorded successfully!</div>
        <?php elseif ($_GET['msg'] === 'updated'): ?>
            <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> Workshop activity updated successfully!</div>
        <?php elseif ($_GET['msg'] === 'deleted'): ?>
            <div class="alert alert-warning"><i class="fa-solid fa-trash-can"></i> Workshop record deleted.</div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><i class="fa-solid fa-triangle-exclamation"></i> <?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <!-- KPI Statistics Cards -->
    <div class="kpi-grid">
        <div class="kpi-card kpi-total">
            <div class="kpi-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
            <div class="kpi-content">
                <span class="kpi-value"><?= number_format($stats['total_workshops'] ?? 0) ?></span>
                <span class="kpi-label">Total Training Activities</span>
            </div>
        </div>
        <div class="kpi-card kpi-active">
            <div class="kpi-icon"><i class="fa-solid fa-users-line"></i></div>
            <div class="kpi-content">
                <span class="kpi-value"><?= number_format($stats['total_participants'] ?? 0) ?></span>
                <span class="kpi-label">Total Participants</span>
            </div>
        </div>
        <div class="kpi-card kpi-expired">
            <div class="kpi-icon"><i class="fa-solid fa-certificate"></i></div>
            <div class="kpi-content">
                <span class="kpi-value"><?= number_format($stats['certificate_count'] ?? 0) ?></span>
                <span class="kpi-label">Certified Workshops</span>
            </div>
        </div>
        <div class="kpi-card kpi-years">
            <div class="kpi-icon"><i class="fa-solid fa-calendar-days"></i></div>
            <div class="kpi-content">
                <span class="kpi-value"><?= number_format($stats['years_count'] ?? 0) ?></span>
                <span class="kpi-label">Academic Years</span>
            </div>
        </div>
    </div>

    <!-- Multi-Year Filter Tabs & Controls Header -->
    <div class="filter-wrapper">
        <form action="index.php?module=workshop" method="POST" class="filter-form" id="wsFilterForm" style="width: 100%; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.25rem;">
            <input type="hidden" name="year" id="postWsYearInput" value="<?= htmlspecialchars($year) ?>">

            <div class="year-tabs-container">
                <span class="tabs-label"><i class="fa-solid fa-filter"></i> Academic Year:</span>
                <div class="year-tabs">
                    <button type="button" onclick="submitWsPostYear('all')" 
                       class="year-tab <?= (empty($year) || $year === 'all') ? 'active' : '' ?>">
                       All Years
                    </button>
                    <?php foreach ($years as $y): ?>
                        <button type="button" onclick="submitWsPostYear('<?= $y ?>')" 
                           class="year-tab <?= ($year == $y) ? 'active' : '' ?>">
                           <?= $y ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="filter-controls-group" style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                <div class="search-input-group">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" name="search" class="form-control search-control" 
                           placeholder="Search title, instructor, host company..." 
                           value="<?= htmlspecialchars($search) ?>">
                </div>

                <div class="select-group">
                    <select name="certificate" class="form-select" onchange="this.form.submit()">
                        <option value="all" <?= ($certificate === 'all' || $certificate === '') ? 'selected' : '' ?>>All Certifications</option>
                        <option value="1" <?= ($certificate === '1') ? 'selected' : '' ?>>Certificate Provided</option>
                        <option value="0" <?= ($certificate === '0') ? 'selected' : '' ?>>No Certificate</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-secondary">Filter</button>
                <?php if (!empty($search) || (!empty($year) && $year !== 'all') || ($certificate !== '' && $certificate !== 'all')): ?>
                    <a href="index.php?module=workshop" class="btn btn-light" title="Reset Filters"><i class="fa-solid fa-rotate-left"></i> Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <script>
    function submitWsPostYear(yearVal) {
        document.getElementById('postWsYearInput').value = yearVal;
        document.getElementById('wsFilterForm').submit();
    }
    </script>

    <!-- Data Display Section -->
    <div class="card table-card">
        <div class="card-header">
            <div class="card-title">
                <h2><i class="fa-solid fa-graduation-cap"></i> Organized Workshops & Seminars Log</h2>
                <span class="record-count"><?= count($workshops) ?> activity record(s) found</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>Topic / Training Activity</th>
                        <th>Guest Expert / Host Company</th>
                        <th>Date & Duration</th>
                        <th>Venue</th>
                        <th>Year</th>
                        <th>Attendees</th>
                        <th>Certificate</th>
                        <th>Summary Report</th>
                        <th style="width: 140px;" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($workshops)): ?>
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fa-solid fa-chalkboard-user empty-icon"></i>
                                    <h3>No Workshops Found</h3>
                                    <p>No workshop or seminar activity matches your filter criteria.</p>
                                    <button class="btn btn-primary mt-3" onclick="openAddWorkshopModal()">
                                        <i class="fa-solid fa-plus"></i> Add New Workshop
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($workshops as $ws): ?>
                            <tr>
                                <td><span class="mou-id">#<?= $ws['id'] ?></span></td>
                                <td>
                                    <div class="company-info">
                                        <span class="company-name"><?= htmlspecialchars($ws['title']) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="contact-details">
                                        <div class="contact-name"><i class="fa-solid fa-user-tie"></i> <?= htmlspecialchars($ws['instructor_name'] ?: 'N/A') ?></div>
                                        <?php if (!empty($ws['company_name'])): ?>
                                            <div class="contact-sub"><i class="fa-solid fa-building"></i> <?= htmlspecialchars($ws['company_name']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($ws['instructor_email'])): ?>
                                            <div class="contact-sub"><i class="fa-regular fa-envelope"></i> <?= htmlspecialchars($ws['instructor_email']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="date-badge">
                                        <i class="fa-regular fa-calendar"></i> <?= date('M d, Y', strtotime($ws['held_on'])) ?>
                                    </div>
                                    <div class="contact-sub mt-1" style="font-size:0.775rem;">
                                        <i class="fa-regular fa-clock"></i> <?= $ws['duration'] ?> hour(s)
                                    </div>
                                </td>
                                <td>
                                    <span class="contact-sub"><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($ws['venue'] ?: 'Campus') ?></span>
                                </td>
                                <td>
                                    <span class="year-pill"><?= $ws['academic_year'] ?></span>
                                </td>
                                <td>
                                    <span class="badge badge-info" style="font-size:0.825rem;"><i class="fa-solid fa-users"></i> <?= number_format($ws['total_participants']) ?></span>
                                </td>
                                <td>
                                    <?php if ($ws['certificate']): ?>
                                        <span class="status-badge status-active"><i class="fa-solid fa-certificate"></i> Yes</span>
                                    <?php else: ?>
                                        <span class="status-badge status-expired"><i class="fa-solid fa-minus"></i> No</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($ws['report_file'])): ?>
                                        <a href="uploads/workshop_reports/<?= htmlspecialchars($ws['report_file']) ?>" target="_blank" class="report-link" title="View Summary Report">
                                            <i class="fa-solid fa-file-pdf"></i> Report File
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted"><i class="fa-solid fa-file-excel"></i> No file</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="btn-action btn-view" title="View Details" onclick="viewWorkshop(<?= $ws['id'] ?>)">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn-action btn-edit" title="Edit Record" onclick="editWorkshop(<?= $ws['id'] ?>)">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button type="button" class="btn-action btn-delete" title="Delete Record" onclick="confirmDeleteWorkshop(<?= $ws['id'] ?>, '<?= htmlspecialchars(addslashes($ws['title'])) ?>')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

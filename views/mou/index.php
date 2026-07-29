<div class="container">
    <!-- Feedback Alerts -->
    <?php if (isset($_GET['msg'])): ?>
        <?php if ($_GET['msg'] === 'added'): ?>
            <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> MOU record created successfully!</div>
        <?php elseif ($_GET['msg'] === 'updated'): ?>
            <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> MOU record updated successfully!</div>
        <?php elseif ($_GET['msg'] === 'deleted'): ?>
            <div class="alert alert-warning"><i class="fa-solid fa-trash-can"></i> MOU record deleted.</div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><i class="fa-solid fa-triangle-exclamation"></i> <?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <!-- KPI Statistics Cards -->
    <div class="kpi-grid">
        <div class="kpi-card kpi-total">
            <div class="kpi-icon"><i class="fa-solid fa-file-contract"></i></div>
            <div class="kpi-content">
                <span class="kpi-value"><?= number_format($stats['total'] ?? 0) ?></span>
                <span class="kpi-label">Total Institutional MOUs</span>
            </div>
        </div>
        <div class="kpi-card kpi-active">
            <div class="kpi-icon"><i class="fa-solid fa-circle-check"></i></div>
            <div class="kpi-content">
                <span class="kpi-value"><?= number_format($stats['active'] ?? 0) ?></span>
                <span class="kpi-label">Active Agreements</span>
            </div>
        </div>
        <div class="kpi-card kpi-expired">
            <div class="kpi-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
            <div class="kpi-content">
                <span class="kpi-value"><?= number_format($stats['expired'] ?? 0) ?></span>
                <span class="kpi-label">Expired MOUs</span>
            </div>
        </div>
        <div class="kpi-card kpi-years">
            <div class="kpi-icon"><i class="fa-solid fa-calendar-week"></i></div>
            <div class="kpi-content">
                <span class="kpi-value"><?= number_format($stats['years_count'] ?? 0) ?></span>
                <span class="kpi-label">Tracked Years</span>
            </div>
        </div>
    </div>

    <!-- Multi-Year Filter Tabs & Controls Header -->
    <div class="filter-wrapper">
        <form action="index.php?module=mou" method="POST" class="filter-form" id="mouFilterForm" style="width: 100%; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.25rem;">
            <input type="hidden" name="year" id="postMouYearInput" value="<?= htmlspecialchars($year) ?>">

            <div class="year-tabs-container">
                <span class="tabs-label"><i class="fa-solid fa-filter"></i> Filter Year:</span>
                <div class="year-tabs">
                    <button type="button" onclick="submitMouPostYear('all')" 
                       class="year-tab <?= (empty($year) || $year === 'all') ? 'active' : '' ?>">
                       All Years
                    </button>
                    <?php foreach ($years as $y): ?>
                        <button type="button" onclick="submitMouPostYear('<?= $y ?>')" 
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
                           placeholder="Search company, contact, purpose..." 
                           value="<?= htmlspecialchars($search) ?>">
                </div>

                <div class="select-group">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="all" <?= ($status === 'all' || empty($status)) ? 'selected' : '' ?>>All Statuses</option>
                        <option value="Active" <?= ($status === 'Active') ? 'selected' : '' ?>>Active Only</option>
                        <option value="Expired" <?= ($status === 'Expired') ? 'selected' : '' ?>>Expired Only</option>
                        <option value="Terminated" <?= ($status === 'Terminated') ? 'selected' : '' ?>>Terminated Only</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-secondary">Filter</button>
                <?php if (!empty($search) || (!empty($year) && $year !== 'all') || (!empty($status) && $status !== 'all')): ?>
                    <a href="index.php?module=mou" class="btn btn-light" title="Reset Filters"><i class="fa-solid fa-rotate-left"></i> Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <script>
    function submitMouPostYear(yearVal) {
        document.getElementById('postMouYearInput').value = yearVal;
        document.getElementById('mouFilterForm').submit();
    }
    </script>

    <!-- Data Display Section -->
    <div class="card table-card">
        <div class="card-header">
            <div class="card-title">
                <h2><i class="fa-solid fa-table-list"></i> Institutional MOUs List</h2>
                <span class="record-count"><?= count($mous) ?> MOU record(s) found</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>Partnering Company</th>
                        <th>Key Contact & Info</th>
                        <th>Date of Signing</th>
                        <th>Expiry Date</th>
                        <th>Year</th>
                        <th>Status</th>
                        <th>Report Upload</th>
                        <th style="width: 140px;" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($mous)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fa-solid fa-folder-open empty-icon"></i>
                                    <h3>No MOUs Found</h3>
                                    <p>No agreement matches your current filter criteria or search query.</p>
                                    <button class="btn btn-primary mt-3" onclick="openAddModal()">
                                        <i class="fa-solid fa-plus"></i> Add New MOU
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($mous as $mou): ?>
                            <tr>
                                <td><span class="mou-id">#<?= $mou['id'] ?></span></td>
                                <td>
                                    <div class="company-info">
                                        <span class="company-name"><?= htmlspecialchars($mou['company_name']) ?></span>
                                        <?php if (!empty($mou['website'])): ?>
                                            <a href="<?= htmlspecialchars($mou['website']) ?>" target="_blank" class="company-website">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i> <?= parse_url($mou['website'], PHP_URL_HOST) ?? 'Website' ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="contact-details">
                                        <div class="contact-name"><i class="fa-solid fa-user"></i> <?= htmlspecialchars($mou['contact_person'] ?: 'N/A') ?></div>
                                        <?php if (!empty($mou['email'])): ?>
                                            <div class="contact-sub"><i class="fa-regular fa-envelope"></i> <?= htmlspecialchars($mou['email']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($mou['phone'])): ?>
                                            <div class="contact-sub"><i class="fa-solid fa-phone"></i> <?= htmlspecialchars($mou['phone']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="date-badge">
                                        <i class="fa-regular fa-calendar-check"></i> <?= date('M d, Y', strtotime($mou['signed_date'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="date-badge">
                                        <i class="fa-regular fa-calendar-xmark"></i> <?= date('M d, Y', strtotime($mou['expiry_date'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="year-pill"><?= $mou['year'] ?></span>
                                </td>
                                <td>
                                    <?php 
                                        $statusClass = 'status-active';
                                        if ($mou['status'] === 'Expired') $statusClass = 'status-expired';
                                        if ($mou['status'] === 'Terminated') $statusClass = 'status-terminated';
                                    ?>
                                    <span class="status-badge <?= $statusClass ?>">
                                        <i class="fa-solid fa-circle status-dot"></i> <?= $mou['status'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($mou['report_file'])): ?>
                                        <a href="uploads/reports/<?= htmlspecialchars($mou['report_file']) ?>" target="_blank" class="report-link" title="View Uploaded Report">
                                            <i class="fa-solid fa-file-pdf"></i> Report File
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted"><i class="fa-solid fa-file-excel"></i> No file</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="btn-action btn-view" title="View Details" onclick="viewMou(<?= $mou['id'] ?>)">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn-action btn-edit" title="Edit Record" onclick="editMou(<?= $mou['id'] ?>)">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button type="button" class="btn-action btn-delete" title="Delete Record" onclick="confirmDelete(<?= $mou['id'] ?>, '<?= htmlspecialchars(addslashes($mou['company_name'])) ?>')">
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

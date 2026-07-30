<div class="container">
    <!-- Feedback Alerts -->
    <?php if (isset($_GET['msg'])): ?>
        <?php if ($_GET['msg'] === 'added'): ?>
            <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> Student placement record added successfully!</div>
        <?php elseif ($_GET['msg'] === 'updated'): ?>
            <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> Student placement record updated successfully!</div>
        <?php elseif ($_GET['msg'] === 'deleted'): ?>
            <div class="alert alert-warning"><i class="fa-solid fa-trash-can"></i> Student record deleted.</div>
        <?php elseif ($_GET['msg'] === 'bulk_success'): ?>
            <div class="alert alert-success"><i class="fa-solid fa-file-excel"></i> Bulk import completed! <?= isset($_GET['count']) ? (int)$_GET['count'] : 0 ?> records processed.</div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><i class="fa-solid fa-triangle-exclamation"></i> <?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <!-- KPI Statistics Cards -->
    <div class="kpi-grid">
        <div class="kpi-card kpi-total">
            <div class="kpi-icon"><i class="fa-solid fa-users"></i></div>
            <div class="kpi-content">
                <span class="kpi-value"><?= number_format($stats['total_students'] ?? 0) ?></span>
                <span class="kpi-label">Total Registered Students</span>
            </div>
        </div>
        <div class="kpi-card kpi-active">
            <div class="kpi-icon"><i class="fa-solid fa-briefcase"></i></div>
            <div class="kpi-content">
                <span class="kpi-value"><?= number_format($stats['placed_count'] ?? 0) ?></span>
                <span class="kpi-label">Placed (Job Offers)</span>
            </div>
        </div>
        <div class="kpi-card kpi-years">
            <div class="kpi-icon"><i class="fa-solid fa-user-gear"></i></div>
            <div class="kpi-content">
                <span class="kpi-value"><?= number_format($stats['internship_count'] ?? 0) ?></span>
                <span class="kpi-label">Industry Internships</span>
            </div>
        </div>
        <div class="kpi-card kpi-expired">
            <div class="kpi-icon"><i class="fa-solid fa-money-bill-trend-up"></i></div>
            <div class="kpi-content">
                <span class="kpi-value"><?= $stats['max_package'] ? number_format($stats['max_package'], 2) . ' LPA' : 'N/A' ?></span>
                <span class="kpi-label">Highest Package</span>
            </div>
        </div>
    </div>

    <!-- Batch / Passing Year Filter Tabs & Controls Header -->
    <div class="filter-wrapper">
        <form action="index.php?module=placement" method="POST" class="filter-form" id="placementFilterForm" style="width: 100%; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.25rem;">
            <input type="hidden" name="year" id="postYearInput" value="<?= htmlspecialchars($passing_year) ?>">

            <div class="year-tabs-container">
                <span class="tabs-label"><i class="fa-solid fa-filter"></i> Passing Batch:</span>
                <div class="year-tabs">
                    <button type="button" onclick="submitPostYear('all')" 
                       class="year-tab <?= (empty($passing_year) || $passing_year === 'all') ? 'active' : '' ?>">
                       All Batches
                    </button>
                    <?php foreach ($years as $y): ?>
                        <button type="button" onclick="submitPostYear('<?= $y ?>')" 
                           class="year-tab <?= ($passing_year == $y) ? 'active' : '' ?>">
                           Batch <?= $y ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="filter-controls-group" style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
            <div class="filter-controls-group" style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                <div class="search-input-group autocomplete-wrapper">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" name="search" id="placementSearchInput" class="form-control search-control" 
                           placeholder="Search enroll no, name, company, skills..." 
                           value="<?= htmlspecialchars($search) ?>"
                           autocomplete="off" spellcheck="false">
                    <!-- Live Floating Suggestions Dropdown -->
                    <div class="search-suggestions-dropdown" id="placementSuggestionsDropdown"></div>
                </div>

                <div class="select-group">
                    <select name="dept" class="form-select" onchange="this.form.submit()">
                        <option value="all" <?= ($department === 'all' || empty($department)) ? 'selected' : '' ?>>All Departments</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= htmlspecialchars($dept) ?>" <?= ($department === $dept) ? 'selected' : '' ?>><?= htmlspecialchars($dept) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="select-group">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="all" <?= ($status === 'all' || empty($status)) ? 'selected' : '' ?>>All Statuses</option>
                        <option value="Placed" <?= ($status === 'Placed') ? 'selected' : '' ?>>🟢 Placed (Job)</option>
                        <option value="Internship" <?= ($status === 'Internship') ? 'selected' : '' ?>>🔵 Internship</option>
                        <option value="Higher Studies" <?= ($status === 'Higher Studies') ? 'selected' : '' ?>>🟣 Higher Studies</option>
                        <option value="Business" <?= ($status === 'Business') ? 'selected' : '' ?>>🟠 Business</option>
                        <option value="Unplaced" <?= ($status === 'Unplaced') ? 'selected' : '' ?>>⚪ Unplaced</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-secondary">Filter</button>
                <?php if (!empty($search) || (!empty($passing_year) && $passing_year !== 'all') || (!empty($department) && $department !== 'all') || (!empty($status) && $status !== 'all')): ?>
                    <a href="index.php?module=placement" class="btn btn-light" title="Reset Filters"><i class="fa-solid fa-rotate-left"></i> Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <script>
    const PLACEMENT_SUGGESTIONS = <?= json_encode($suggestions ?? ['names'=>[], 'companies'=>[], 'designations'=>[], 'departments'=>[]]) ?>;

    function submitPostYear(yearVal) {
        document.getElementById('postYearInput').value = yearVal;
        document.getElementById('placementFilterForm').submit();
    }

    // Live Autocomplete Popup for Placement Search
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('placementSearchInput');
        const dropdown    = document.getElementById('placementSuggestionsDropdown');
        let activeIndex   = -1;

        if (!searchInput || !dropdown) return;

        function escapeHtml(str) {
            return String(str).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }

        function highlightMatch(text, query) {
            if (!query) return escapeHtml(text);
            const escapedText = escapeHtml(text);
            const escapedQuery = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            const regex = new RegExp(`(${escapedQuery})`, 'gi');
            return escapedText.replace(regex, '<mark class="suggestion-highlight">$1</mark>');
        }

        function renderSuggestions(query) {
            const q = query.trim().toLowerCase();
            // OPTIMIZATION: Do not show suggestions when empty
            if (!q || q.length < 1) {
                dropdown.style.display = 'none';
                dropdown.innerHTML = '';
                return;
            }

            const matchedNames       = (PLACEMENT_SUGGESTIONS.names || []).filter(n => n.toLowerCase().includes(q)).slice(0, 5);
            const matchedCompanies   = (PLACEMENT_SUGGESTIONS.companies || []).filter(c => c.toLowerCase().includes(q)).slice(0, 5);
            const matchedRoles       = (PLACEMENT_SUGGESTIONS.designations || []).filter(r => r.toLowerCase().includes(q)).slice(0, 5);
            const matchedDepts       = (PLACEMENT_SUGGESTIONS.departments || []).filter(d => !q || d.toLowerCase().includes(q)).slice(0, 5);

            const totalMatches = matchedNames.length + matchedCompanies.length + matchedRoles.length + matchedDepts.length;

            if (totalMatches === 0) {
                dropdown.innerHTML = `<div class="suggestion-empty"><i class="fa-solid fa-magnifying-glass"></i> No matching suggestions found</div>`;
                dropdown.style.display = 'block';
                return;
            }

            let html = '';

            if (matchedNames.length > 0) {
                html += `<div class="suggestion-group-header"><i class="fa-solid fa-user-graduate"></i> Student Names</div>`;
                matchedNames.forEach(n => {
                    html += `<div class="suggestion-item" data-value="${escapeHtml(n)}">
                                <i class="fa-solid fa-user suggestion-icon"></i>
                                <span class="suggestion-text">${highlightMatch(n, q)}</span>
                                <span class="suggestion-badge">Student</span>
                             </div>`;
                });
            }

            if (matchedCompanies.length > 0) {
                html += `<div class="suggestion-group-header"><i class="fa-solid fa-building"></i> Companies</div>`;
                matchedCompanies.forEach(c => {
                    html += `<div class="suggestion-item" data-value="${escapeHtml(c)}">
                                <i class="fa-solid fa-city suggestion-icon"></i>
                                <span class="suggestion-text">${highlightMatch(c, q)}</span>
                                <span class="suggestion-badge badge-company">Company</span>
                             </div>`;
                });
            }

            if (matchedRoles.length > 0) {
                html += `<div class="suggestion-group-header"><i class="fa-solid fa-briefcase"></i> Roles / Designations</div>`;
                matchedRoles.forEach(r => {
                    html += `<div class="suggestion-item" data-value="${escapeHtml(r)}">
                                <i class="fa-solid fa-user-tie suggestion-icon"></i>
                                <span class="suggestion-text">${highlightMatch(r, q)}</span>
                                <span class="suggestion-badge badge-instructor">Role</span>
                             </div>`;
                });
            }

            if (matchedDepts.length > 0) {
                html += `<div class="suggestion-group-header"><i class="fa-solid fa-building-columns"></i> Departments</div>`;
                matchedDepts.forEach(d => {
                    html += `<div class="suggestion-item" data-value="${escapeHtml(d)}">
                                <i class="fa-solid fa-graduation-cap suggestion-icon"></i>
                                <span class="suggestion-text">${highlightMatch(d, q)}</span>
                                <span class="suggestion-badge">Dept</span>
                             </div>`;
                });
            }

            dropdown.innerHTML = html;
            dropdown.style.display = 'block';
            activeIndex = -1;

            dropdown.querySelectorAll('.suggestion-item').forEach(item => {
                item.addEventListener('click', function() {
                    searchInput.value = this.getAttribute('data-value');
                    dropdown.style.display = 'none';
                    document.getElementById('placementFilterForm').submit();
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

        searchInput.addEventListener('input', function() { renderSuggestions(this.value); });
        searchInput.addEventListener('focus', function() { renderSuggestions(this.value); });

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });

        searchInput.addEventListener('keydown', function(e) {
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

    <!-- Data Display Section with Visual Color Coding -->
    <div class="card table-card">
        <div class="card-header">
            <div class="card-title">
                <h2><i class="fa-solid fa-user-graduate"></i> Student Placement & Internship Records</h2>
                <span class="record-count"><?= count($students) ?> student profile(s) found</span>
            </div>
            <div class="status-legend">
                <span class="legend-item legend-placed"><i class="fa-solid fa-circle"></i> Placed</span>
                <span class="legend-item legend-internship"><i class="fa-solid fa-circle"></i> Internship</span>
                <span class="legend-item legend-higher"><i class="fa-solid fa-circle"></i> Higher Studies</span>
                <span class="legend-item legend-business"><i class="fa-solid fa-circle"></i> Business</span>
                <span class="legend-item legend-unplaced"><i class="fa-solid fa-circle"></i> Unplaced</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table placement-table">
                <thead>
                    <tr>
                        <th>Enrollment No</th>
                        <th>Student Profile</th>
                        <th>Department</th>
                        <th>CGPA</th>
                        <th>Batch</th>
                        <th>Career Status & Placement Info</th>
                        <th>Package (LPA)</th>
                        <th>Offer Letter / Certificate</th>
                        <th style="width: 130px;" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fa-solid fa-user-slash empty-icon"></i>
                                    <h3>No Student Records Found</h3>
                                    <p>No student matches your current filter query.</p>
                                    <button class="btn btn-primary mt-3" onclick="openAddStudentModal()">
                                        <i class="fa-solid fa-user-plus"></i> Add Student Profile
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $st): ?>
                            <?php 
                                $rowClass = 'row-unplaced';
                                if ($st['placement_status'] === 'Placed') $rowClass = 'row-placed';
                                elseif ($st['placement_status'] === 'Internship') $rowClass = 'row-internship';
                                elseif ($st['placement_status'] === 'Higher Studies') $rowClass = 'row-higher';
                                elseif ($st['placement_status'] === 'Business') $rowClass = 'row-business';
                            ?>
                            <tr class="<?= $rowClass ?>">
                                <td>
                                    <span class="mou-id"><?= htmlspecialchars($st['enroll_no']) ?></span>
                                </td>
                                <td>
                                    <div class="company-info">
                                        <span class="company-name"><?= htmlspecialchars($st['name']) ?></span>
                                        <div class="contact-sub">
                                            <i class="fa-regular fa-envelope"></i> <?= htmlspecialchars($st['email'] ?: 'N/A') ?> 
                                            <span class="gender-pill"><?= htmlspecialchars($st['gender']) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="contact-name"><?= htmlspecialchars($st['department']) ?></span>
                                    <div class="contact-sub">Sem <?= $st['semester'] ?></div>
                                </td>
                                <td>
                                    <span class="badge <?= $st['cgpa'] >= 8.0 ? 'badge-info' : 'badge-secondary' ?>">
                                        <?= number_format($st['cgpa'], 2) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="year-pill"><?= $st['passing_year'] ?></span>
                                </td>
                                <td>
                                    <div class="placement-details">
                                        <?php if ($st['placement_status'] === 'Placed'): ?>
                                            <span class="status-badge status-active"><i class="fa-solid fa-briefcase"></i> Placed</span>
                                            <div class="contact-name mt-1"><?= htmlspecialchars($st['company_name']) ?></div>
                                            <div class="contact-sub"><?= htmlspecialchars($st['designation'] ?: 'Engineer') ?></div>
                                        <?php elseif ($st['placement_status'] === 'Internship'): ?>
                                            <span class="status-badge status-internship"><i class="fa-solid fa-user-gear"></i> Internship</span>
                                            <div class="contact-name mt-1"><?= htmlspecialchars($st['company_name']) ?></div>
                                            <div class="contact-sub"><?= htmlspecialchars($st['designation'] ?: 'Intern') ?></div>
                                        <?php elseif ($st['placement_status'] === 'Higher Studies'): ?>
                                            <span class="status-badge status-higher"><i class="fa-solid fa-graduation-cap"></i> Higher Studies</span>
                                            <div class="contact-name mt-1"><?= htmlspecialchars($st['company_name'] ?: 'University') ?></div>
                                            <div class="contact-sub"><?= htmlspecialchars($st['designation'] ?: 'Master Degree') ?></div>
                                        <?php elseif ($st['placement_status'] === 'Business'): ?>
                                            <span class="status-badge status-business"><i class="fa-solid fa-store"></i> Entrepreneurship</span>
                                            <div class="contact-name mt-1"><?= htmlspecialchars($st['company_name'] ?: 'Startup') ?></div>
                                            <div class="contact-sub"><?= htmlspecialchars($st['designation'] ?: 'Founder') ?></div>
                                        <?php else: ?>
                                            <span class="status-badge status-expired"><i class="fa-solid fa-hourglass"></i> Unplaced</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($st['package_lpa']) && $st['package_lpa'] > 0): ?>
                                        <span class="package-tag"><i class="fa-solid fa-indian-rupee-sign"></i> <?= number_format($st['package_lpa'], 2) ?> LPA</span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($st['offer_letter_file'])): ?>
                                        <a href="uploads/placement_documents/<?= htmlspecialchars($st['offer_letter_file']) ?>" target="_blank" class="report-link" title="View Offer Letter / Certificate">
                                            <i class="fa-solid fa-file-contract"></i> Offer Document
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted"><i class="fa-solid fa-file-excel"></i> No document</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="btn-action btn-view" title="View Profile" onclick="viewStudent(<?= $st['id'] ?>)">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn-action btn-edit" title="Edit Profile" onclick="editStudent(<?= $st['id'] ?>)">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button type="button" class="btn-action btn-delete" title="Delete Record" onclick="confirmDeleteStudent(<?= $st['id'] ?>, '<?= htmlspecialchars(addslashes($st['name'])) ?>')">
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

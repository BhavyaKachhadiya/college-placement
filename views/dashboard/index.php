<div class="container">

    <!-- ===== PAGE HERO ===== -->
    <div class="dash-hero">
        <div class="dash-hero-content">
            <div class="dash-hero-icon">
                <i class="fa-solid fa-gauge-high"></i>
            </div>
            <div>
                <h2 class="dash-hero-title">Welcome back 👋</h2>
                <p class="dash-hero-sub">College Institutional Management — <?= date('l, d F Y') ?></p>
            </div>
        </div>
        <div class="dash-hero-meta">
            <span class="dash-meta-pill"><i class="fa-solid fa-circle-dot"></i> Live Data</span>
        </div>
    </div>

    <!-- ===== TOP-LEVEL KPI GRID ===== -->
    <div class="dash-kpi-grid">

        <!-- MOUs -->
        <a href="index.php?module=mou" class="dash-kpi-card dash-kpi-blue">
            <div class="dash-kpi-icon"><i class="fa-solid fa-handshake"></i></div>
            <div class="dash-kpi-body">
                <span class="dash-kpi-value"><?= number_format($mouStats['total'] ?? 0) ?></span>
                <span class="dash-kpi-label">Total MOUs</span>
                <div class="dash-kpi-sub-row">
                    <span class="dash-mini-pill green"><?= $mouStats['active'] ?? 0 ?> Active</span>
                    <span class="dash-mini-pill amber"><?= $mouStats['expired'] ?? 0 ?> Expired</span>
                </div>
            </div>
            <i class="fa-solid fa-arrow-right dash-kpi-arrow"></i>
        </a>

        <!-- Workshops -->
        <a href="index.php?module=workshop" class="dash-kpi-card dash-kpi-purple">
            <div class="dash-kpi-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
            <div class="dash-kpi-body">
                <span class="dash-kpi-value"><?= number_format($workshopStats['total_workshops'] ?? 0) ?></span>
                <span class="dash-kpi-label">Workshops &amp; Seminars</span>
                <div class="dash-kpi-sub-row">
                    <span class="dash-mini-pill blue"><?= number_format($workshopStats['total_participants'] ?? 0) ?> Participants</span>
                    <span class="dash-mini-pill green"><?= $workshopStats['certificate_count'] ?? 0 ?> Certified</span>
                </div>
            </div>
            <i class="fa-solid fa-arrow-right dash-kpi-arrow"></i>
        </a>

        <!-- Students -->
        <a href="index.php?module=placement" class="dash-kpi-card dash-kpi-green">
            <div class="dash-kpi-icon"><i class="fa-solid fa-user-graduate"></i></div>
            <div class="dash-kpi-body">
                <span class="dash-kpi-value"><?= number_format($placementStats['total_students'] ?? 0) ?></span>
                <span class="dash-kpi-label">Registered Students</span>
                <div class="dash-kpi-sub-row">
                    <span class="dash-mini-pill green"><?= $placementStats['placed_count'] ?? 0 ?> Placed</span>
                    <span class="dash-mini-pill blue"><?= $placementStats['internship_count'] ?? 0 ?> Intern</span>
                </div>
            </div>
            <i class="fa-solid fa-arrow-right dash-kpi-arrow"></i>
        </a>

        <!-- Internships -->
        <a href="index.php?module=internship" class="dash-kpi-card dash-kpi-amber">
            <div class="dash-kpi-icon"><i class="fa-solid fa-laptop-code"></i></div>
            <div class="dash-kpi-body">
                <span class="dash-kpi-value"><?= number_format($placementStats['internship_count'] ?? 0) ?></span>
                <span class="dash-kpi-label">Industry Internships</span>
                <div class="dash-kpi-sub-row">
                    <span class="dash-mini-pill amber"><?= $placementStats['higher_studies_count'] ?? 0 ?> Higher Studies</span>
                </div>
            </div>
            <i class="fa-solid fa-arrow-right dash-kpi-arrow"></i>
        </a>

    </div>

    <!-- ===== PLACEMENT BREAKDOWN + QUICK STATS ===== -->
    <div class="dash-two-col">

        <!-- Placement breakdown chart-style -->
        <div class="card dash-breakdown-card">
            <div class="card-header">
                <div class="card-title">
                    <h2><i class="fa-solid fa-chart-pie"></i> Placement Breakdown</h2>
                    <span class="record-count">Student outcome distribution</span>
                </div>
                <a href="index.php?module=placement" class="btn btn-light" style="font-size:0.78rem; padding: 0.4rem 0.9rem;">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> View All
                </a>
            </div>
            <div class="card-body-pad dash-breakdown-body">
                <div class="dash-bars-group">
                    <?php
                    $total = max(1, (int)($placementStats['total_students'] ?? 1));
                    $bars = [
                        ['label' => 'Placed',         'count' => (int)($placementStats['placed_count']        ?? 0), 'color' => 'var(--green-500)',   'icon' => 'fa-briefcase'],
                        ['label' => 'Internship',      'count' => (int)($placementStats['internship_count']    ?? 0), 'color' => 'var(--blue-500)',    'icon' => 'fa-laptop-code'],
                        ['label' => 'Higher Studies',  'count' => (int)($placementStats['higher_studies_count']?? 0), 'color' => 'var(--accent-400)',  'icon' => 'fa-book-open'],
                        ['label' => 'Business',        'count' => (int)($placementStats['business_count']      ?? 0), 'color' => 'var(--amber-600)',   'icon' => 'fa-store'],
                        ['label' => 'Unplaced',        'count' => (int)($placementStats['unplaced_count']      ?? 0), 'color' => 'var(--neutral-300)', 'icon' => 'fa-user-clock'],
                    ];
                    foreach ($bars as $bar):
                        $pct = round(($bar['count'] / $total) * 100, 1);
                    ?>
                    <div class="dash-bar-row">
                        <div class="dash-bar-label">
                            <i class="fa-solid <?= $bar['icon'] ?>" style="color:<?= $bar['color'] ?>; width:16px;"></i>
                            <span><?= $bar['label'] ?></span>
                        </div>
                        <div class="dash-bar-track">
                            <div class="dash-bar-fill" style="width: <?= $pct ?>%; background: <?= $bar['color'] ?>;"></div>
                        </div>
                        <div class="dash-bar-stats">
                            <strong><?= $bar['count'] ?></strong>
                            <span class="text-muted"><?= $pct ?>%</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Footer Summary Blocks -->
                <div class="dash-breakdown-footer">
                    <?php if (!empty($placementStats['max_package']) && $placementStats['max_package'] > 0): ?>
                    <div class="dash-highlight-stat">
                        <span><i class="fa-solid fa-trophy" style="color: var(--amber-600);"></i> Highest Package: <strong><?= number_format((float)$placementStats['max_package'], 2) ?> LPA</strong></span>
                        <span><i class="fa-solid fa-star" style="color: var(--brand-500);"></i> Avg CGPA: <strong><?= number_format((float)($placementStats['avg_cgpa'] ?? 0), 2) ?></strong></span>
                    </div>
                    <?php endif; ?>

                    <div class="dash-summary-strip">
                        <span><i class="fa-solid fa-circle-check" style="color: var(--green-600);"></i> Overall Success Rate: <strong style="color: var(--green-700); font-weight: 800;"><?= round((((int)($placementStats['placed_count'] ?? 0) + (int)($placementStats['internship_count'] ?? 0)) / $total) * 100, 1) ?>%</strong></span>
                        <span>Total Tracked: <strong style="color: var(--neutral-900);"><?= number_format((float)($placementStats['total_students'] ?? 0)) ?> Students</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Column -->
        <div class="dash-quick-col">

            <!-- MOU Status -->
            <div class="card dash-stat-card">
                <div class="card-header">
                    <div class="card-title">
                        <h2><i class="fa-solid fa-handshake"></i> MOU Status</h2>
                    </div>
                    <a href="index.php?module=mou" class="btn btn-light" style="font-size:0.78rem; padding: 0.4rem 0.9rem;">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </a>
                </div>
                <div class="card-body-pad dash-stat-pills">
                    <div class="dash-stat-pill dash-stat-green">
                        <span class="dash-stat-pill-value"><?= $mouStats['active'] ?? 0 ?></span>
                        <span class="dash-stat-pill-label">Active</span>
                    </div>
                    <div class="dash-stat-pill dash-stat-amber">
                        <span class="dash-stat-pill-value"><?= $mouStats['expired'] ?? 0 ?></span>
                        <span class="dash-stat-pill-label">Expired</span>
                    </div>
                    <div class="dash-stat-pill dash-stat-rose">
                        <span class="dash-stat-pill-value"><?= $mouStats['terminated'] ?? 0 ?></span>
                        <span class="dash-stat-pill-label">Terminated</span>
                    </div>
                    <div class="dash-stat-pill dash-stat-blue">
                        <span class="dash-stat-pill-value"><?= $mouStats['years_count'] ?? 0 ?></span>
                        <span class="dash-stat-pill-label">Years</span>
                    </div>
                </div>
            </div>

            <!-- Workshop Stats -->
            <div class="card dash-stat-card">
                <div class="card-header">
                    <div class="card-title">
                        <h2><i class="fa-solid fa-chalkboard-user"></i> Workshop Stats</h2>
                    </div>
                    <a href="index.php?module=workshop" class="btn btn-light" style="font-size:0.78rem; padding: 0.4rem 0.9rem;">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </a>
                </div>
                <div class="card-body-pad dash-stat-pills">
                    <div class="dash-stat-pill dash-stat-purple">
                        <span class="dash-stat-pill-value"><?= number_format($workshopStats['total_workshops'] ?? 0) ?></span>
                        <span class="dash-stat-pill-label">Events</span>
                    </div>
                    <div class="dash-stat-pill dash-stat-blue">
                        <span class="dash-stat-pill-value"><?= number_format($workshopStats['total_participants'] ?? 0) ?></span>
                        <span class="dash-stat-pill-label">Participants</span>
                    </div>
                    <div class="dash-stat-pill dash-stat-green">
                        <span class="dash-stat-pill-value"><?= $workshopStats['certificate_count'] ?? 0 ?></span>
                        <span class="dash-stat-pill-label">Certified</span>
                    </div>
                    <div class="dash-stat-pill dash-stat-amber">
                        <span class="dash-stat-pill-value"><?= $workshopStats['years_count'] ?? 0 ?></span>
                        <span class="dash-stat-pill-label">Years</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ===== RECENT ACTIVITY PANELS ===== -->
    <div class="dash-three-col">

        <!-- Recent MOUs -->
        <div class="card dash-recent-card">
            <div class="card-header">
                <div class="card-title">
                    <h2><i class="fa-solid fa-handshake"></i> Recent MOUs</h2>
                </div>
            </div>
            <div class="dash-recent-list">
                <?php if (empty($recentMous)): ?>
                    <div class="dash-empty">No MOU records yet</div>
                <?php else: foreach ($recentMous as $m): ?>
                    <div class="dash-recent-item" onclick="viewMou(<?= (int)$m['id'] ?>)" style="cursor: pointer;" title="Click to view details of <?= htmlspecialchars($m['company_name']) ?>">
                        <div class="dash-recent-icon dash-ri-blue">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <div class="dash-recent-info">
                            <span class="dash-recent-name"><?= htmlspecialchars($m['company_name']) ?></span>
                            <span class="dash-recent-meta"><?= htmlspecialchars($m['signed_date']) ?> · <?= $m['year'] ?></span>
                        </div>
                        <span class="status-badge <?= $m['status'] === 'Active' ? 'status-active' : ($m['status'] === 'Expired' ? 'status-expired' : 'status-terminated') ?>">
                            <i class="fa-solid fa-circle status-dot"></i><?= $m['status'] ?>
                        </span>
                    </div>
                <?php endforeach; endif; ?>
            </div>
            <div class="dash-recent-footer">
                <a href="index.php?module=mou" class="dash-see-all"><i class="fa-solid fa-list"></i> View All MOUs</a>
            </div>
        </div>

        <!-- Recent Workshops -->
        <div class="card dash-recent-card">
            <div class="card-header">
                <div class="card-title">
                    <h2><i class="fa-solid fa-chalkboard-user"></i> Recent Workshops</h2>
                </div>
            </div>
            <div class="dash-recent-list">
                <?php if (empty($recentWorkshops)): ?>
                    <div class="dash-empty">No workshop records yet</div>
                <?php else: foreach ($recentWorkshops as $w): ?>
                    <div class="dash-recent-item" onclick="viewWorkshop(<?= (int)$w['id'] ?>)" style="cursor: pointer;" title="Click to view details of <?= htmlspecialchars($w['title']) ?>">
                        <div class="dash-recent-icon dash-ri-purple">
                            <i class="fa-solid fa-chalkboard"></i>
                        </div>
                        <div class="dash-recent-info">
                            <span class="dash-recent-name"><?= htmlspecialchars($w['title']) ?></span>
                            <span class="dash-recent-meta"><?= htmlspecialchars($w['held_on']) ?> · <?= (int)$w['total_participants'] ?> students</span>
                        </div>
                        <?php if ($w['certificate']): ?>
                            <span class="dash-cert-badge"><i class="fa-solid fa-certificate"></i></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; endif; ?>
            </div>
            <div class="dash-recent-footer">
                <a href="index.php?module=workshop" class="dash-see-all"><i class="fa-solid fa-list"></i> View All Workshops</a>
            </div>
        </div>

        <!-- Recent Internships -->
        <div class="card dash-recent-card">
            <div class="card-header">
                <div class="card-title">
                    <h2><i class="fa-solid fa-laptop-code"></i> Recent Internships</h2>
                </div>
            </div>
            <div class="dash-recent-list">
                <?php
                $internships = array_filter($recentStudents, fn($s) => ($s['placement_status'] ?? '') === 'Internship');
                if (empty($internships)) {
                    // show last 5 students regardless
                    $internships = $recentStudents;
                }
                ?>
                <?php if (empty($internships)): ?>
                    <div class="dash-empty">No student records yet</div>
                <?php else: foreach (array_slice($internships, 0, 5) as $s): ?>
                    <div class="dash-recent-item" onclick="viewStudent(<?= (int)$s['id'] ?>)" style="cursor: pointer;" title="Click to view details of <?= htmlspecialchars($s['name']) ?>">
                        <div class="dash-recent-icon dash-ri-amber">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                        <div class="dash-recent-info">
                            <span class="dash-recent-name"><?= htmlspecialchars($s['name']) ?></span>
                            <span class="dash-recent-meta"><?= htmlspecialchars($s['department']) ?> · <?= $s['passing_year'] ?></span>
                        </div>
                        <?php if (!empty($s['company_name'])): ?>
                            <span class="dash-company-tag"><?= htmlspecialchars(mb_strimwidth($s['company_name'], 0, 14, '…')) ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; endif; ?>
            </div>
            <div class="dash-recent-footer">
                <a href="index.php?module=internship" class="dash-see-all"><i class="fa-solid fa-list"></i> View All Internships</a>
            </div>
        </div>

    </div>

</div>

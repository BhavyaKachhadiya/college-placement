<?php
/**
 * Job Applications Admin View
 * Displays a list of all job applications from students and allows admins to change their status.
 */
$msg = isset($_GET['msg']) ? trim($_GET['msg']) : '';
$error = isset($_GET['error']) ? trim($_GET['error']) : '';
$statusFilter = isset($_GET['status']) ? trim($_GET['status']) : '';
?>

<div class="container py-4">

    <!-- Header & Action Bar -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.75rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-family: var(--font-display); font-size: 1.6rem; font-weight: 800; color: var(--neutral-900); margin: 0; display: flex; align-items: center; gap: 0.65rem;">
                <i class="fa-solid fa-file-signature" style="color: var(--brand-500);"></i> Job Applications
            </h2>
            <p style="color: var(--neutral-500); font-size: 0.9rem; margin-top: 0.25rem;">
                Review and manage student applications for company placement drives.
            </p>
        </div>
    </div>

    <!-- Notifications -->
    <?php if ($msg === 'status_updated'): ?>
        <div class="alert alert-success" style="background: rgba(22, 163, 74, 0.1); border: 1px solid var(--green-500); color: var(--green-600); padding: 0.9rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; font-weight: 600;">
            <i class="fa-solid fa-circle-check" style="font-size: 1.25rem;"></i>
            <span>Application status updated successfully!</span>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" style="background: rgba(225, 29, 72, 0.1); border: 1px solid var(--rose-600); color: var(--rose-600); padding: 0.9rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; font-weight: 600;">
            <i class="fa-solid fa-circle-exclamation" style="font-size: 1.25rem;"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <!-- Filter Controls -->
    <div style="background: var(--white); border: 1px solid var(--neutral-200); padding: 1.25rem 1.5rem; border-radius: var(--radius-xl); margin-bottom: 2rem; box-shadow: var(--shadow-sm);">
        <form method="GET" action="index.php" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; justify-content: flex-start;">
            <input type="hidden" name="module" value="application">
            <input type="hidden" name="action" value="index">

            <!-- Status Filter Tabs -->
            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                <span style="font-size: 0.9rem; font-weight: 600; color: var(--neutral-600); margin-right: 0.5rem;"><i class="fa-solid fa-filter"></i> Filter Status:</span>
                <a href="index.php?module=application" class="btn btn-sm <?= empty($statusFilter) ? 'btn-primary' : 'btn-light' ?>">All</a>
                <a href="index.php?module=application&status=Pending" class="btn btn-sm <?= $statusFilter === 'Pending' ? 'btn-primary' : 'btn-light' ?>">Pending</a>
                <a href="index.php?module=application&status=Reviewed" class="btn btn-sm <?= $statusFilter === 'Reviewed' ? 'btn-primary' : 'btn-light' ?>">Reviewed</a>
                <a href="index.php?module=application&status=Accepted" class="btn btn-sm <?= $statusFilter === 'Accepted' ? 'btn-primary' : 'btn-light' ?>">Accepted</a>
                <a href="index.php?module=application&status=Rejected" class="btn btn-sm <?= $statusFilter === 'Rejected' ? 'btn-primary' : 'btn-light' ?>">Rejected</a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="card table-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date Applied</th>
                        <th>Student Profile</th>
                        <th>Company & Job Role</th>
                        <th>CGPA / Dept</th>
                        <th>Resume</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($applications)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fa-solid fa-inbox empty-icon"></i>
                                    <h3>No Applications Found</h3>
                                    <p>There are no job applications matching your filter.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($applications as $app): ?>
                            <tr>
                                <td>
                                    <span style="font-weight: 600; color: var(--neutral-700);"><?= date('d M Y', strtotime($app['applied_at'])) ?></span><br>
                                    <span style="font-size: 0.8rem; color: var(--neutral-500);"><?= date('h:i A', strtotime($app['applied_at'])) ?></span>
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: var(--brand-700);"><?= htmlspecialchars($app['student_name']) ?></div>
                                    <div style="font-size: 0.85rem; color: var(--neutral-600);">Enroll: <?= htmlspecialchars($app['enroll_no']) ?></div>
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: var(--neutral-800);"><?= htmlspecialchars($app['company_name']) ?></div>
                                    <div style="font-size: 0.85rem; color: var(--neutral-600);"><i class="fa-solid fa-briefcase"></i> <?= htmlspecialchars($app['job_role']) ?></div>
                                </td>
                                <td>
                                    <span class="badge badge-info"><?= htmlspecialchars($app['cgpa']) ?></span><br>
                                    <span style="font-size: 0.8rem; color: var(--neutral-500);"><?= htmlspecialchars($app['department']) ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($app['resume_file'])): ?>
                                        <a href="uploads/placement_documents/<?= htmlspecialchars($app['resume_file']) ?>" target="_blank" class="report-link" style="color: var(--brand-600);" title="View Resume">
                                            <i class="fa-solid fa-file-lines"></i> View Resume
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted" style="font-size: 0.85rem;"><i class="fa-solid fa-file-excel"></i> Not Provided</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                        $badgeClass = 'badge-neutral';
                                        if ($app['status'] === 'Pending') $badgeClass = 'badge-warning';
                                        elseif ($app['status'] === 'Reviewed') $badgeClass = 'badge-info';
                                        elseif ($app['status'] === 'Accepted') $badgeClass = 'badge-success';
                                        elseif ($app['status'] === 'Rejected') $badgeClass = 'badge-danger';
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($app['status']) ?></span>
                                </td>
                                <td>
                                    <form action="index.php?module=application&action=updateStatus" method="POST" style="display: flex; gap: 0.5rem; margin: 0;">
                                        <input type="hidden" name="id" value="<?= (int)$app['id'] ?>">
                                        <select name="status" class="form-select" style="padding: 0.3rem 0.5rem; font-size: 0.85rem; border-radius: var(--radius-sm); width: 110px;" onchange="this.form.submit()">
                                            <option value="Pending" <?= $app['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="Reviewed" <?= $app['status'] === 'Reviewed' ? 'selected' : '' ?>>Reviewed</option>
                                            <option value="Accepted" <?= $app['status'] === 'Accepted' ? 'selected' : '' ?>>Accepted</option>
                                            <option value="Rejected" <?= $app['status'] === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

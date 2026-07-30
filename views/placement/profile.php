<?php
/**
 * Student Profile Dedicated View
 * Displays complete profile for logged-in student or selected student
 */
$isOwnProfile = isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] === (int)$student['id'];
$isAdmin = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';

$statusClass = 'badge-neutral';
if ($student['placement_status'] === 'Placed') $statusClass = 'badge-success';
elseif ($student['placement_status'] === 'Internship') $statusClass = 'badge-info';
elseif ($student['placement_status'] === 'Higher Studies') $statusClass = 'badge-purple';
elseif ($student['placement_status'] === 'Business') $statusClass = 'badge-warning';
elseif ($student['placement_status'] === 'Unplaced') $statusClass = 'badge-danger';
?>

<div class="container py-4">

    <!-- Back & Action Bar -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <?php if ($isAdmin): ?>
                <a href="index.php?module=placement" class="btn btn-light btn-sm">
                    <i class="fa-solid fa-arrow-left"></i> Students Directory
                </a>
                <span style="color: var(--neutral-400);">|</span>
            <?php endif; ?>
            <span style="font-weight: 600; color: var(--neutral-600); font-size: 0.9rem;">
                <i class="fa-solid fa-user-graduate" style="color: var(--brand-500);"></i> <?= $isOwnProfile ? 'My Student Profile' : 'Student Profile' ?>
            </span>
        </div>

        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <?php if ($isAdmin): ?>
                <button type="button" class="btn btn-primary btn-sm" onclick="editStudentFromProfile(<?= (int)$student['id'] ?>)">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Profile
                </button>
            <?php endif; ?>
            <button type="button" class="btn btn-light btn-sm" onclick="window.print()">
                <i class="fa-solid fa-print"></i> Print Profile
            </button>
        </div>
    </div>

    <!-- Student Hero Profile Banner -->
    <div class="report-hero-card" style="background: linear-gradient(135deg, var(--brand-950) 0%, var(--brand-900) 50%, var(--brand-800) 100%); padding: 2rem 2.25rem; border-radius: var(--radius-xl); box-shadow: var(--shadow-lg); color: var(--white); position: relative; overflow: hidden; margin-bottom: 2rem;">
        <div style="position: absolute; right: -50px; top: -50px; width: 220px; height: 220px; background: radial-gradient(circle, rgba(59,91,219,0.3) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
        
        <div style="display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap; position: relative; z-index: 2;">
            <div style="width: 80px; height: 80px; border-radius: 20px; background: linear-gradient(135deg, var(--brand-500) 0%, var(--accent-500) 100%); display: flex; align-items: center; justify-content: center; font-size: 2.25rem; color: #fff; font-weight: 800; box-shadow: 0 8px 24px rgba(0,0,0,0.3); flex-shrink: 0;">
                <?= strtoupper(substr($student['name'], 0, 1)) ?>
            </div>

            <div style="flex: 1; min-width: 260px;">
                <div style="display: flex; align-items: center; gap: 0.65rem; margin-bottom: 0.4rem; flex-wrap: wrap;">
                    <h2 style="font-family: var(--font-display); font-size: 1.65rem; font-weight: 800; margin: 0; color: #fff;">
                        <?= htmlspecialchars($student['name']) ?>
                    </h2>
                    <span class="badge <?= $statusClass ?>" style="font-size: 0.82rem; padding: 0.35rem 0.75rem; border-radius: 9999px;">
                        <?= htmlspecialchars($student['placement_status']) ?>
                    </span>
                </div>

                <div style="display: flex; align-items: center; gap: 1.25rem; flex-wrap: wrap; color: var(--brand-200); font-size: 0.9rem;">
                    <span><i class="fa-solid fa-id-card"></i> Enroll No: <strong style="color: #fff;"><?= htmlspecialchars($student['enroll_no']) ?></strong></span>
                    <?php if (!empty($student['gr_no'])): ?>
                        <span><i class="fa-solid fa-barcode"></i> GR No: <strong style="color: #fff;"><?= htmlspecialchars($student['gr_no']) ?></strong></span>
                    <?php endif; ?>
                    <span><i class="fa-solid fa-building-columns"></i> <?= htmlspecialchars($student['department']) ?></span>
                </div>
            </div>

            <div style="text-align: right; background: rgba(255,255,255,0.08); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.12); padding: 0.85rem 1.25rem; border-radius: var(--radius-lg); min-width: 140px;">
                <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brand-200); font-weight: 700;">Academic CGPA</div>
                <div style="font-family: var(--font-display); font-size: 1.75rem; font-weight: 800; color: #fbbf24;">
                    <?= number_format((float)$student['cgpa'], 2) ?> <span style="font-size: 0.9rem; color: var(--brand-200); font-weight: 400;">/ 10</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Grid Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">

        <!-- Academic & Personal Card -->
        <div class="card" style="background: var(--white); border-radius: var(--radius-xl); border: 1px solid var(--neutral-200); padding: 1.5rem; box-shadow: var(--shadow-sm);">
            <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--neutral-900); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.6rem; border-bottom: 1px solid var(--neutral-150); padding-bottom: 0.75rem;">
                <i class="fa-solid fa-graduation-cap" style="color: var(--brand-500);"></i> Academic &amp; Personal Info
            </h3>

            <div style="display: flex; flex-direction: column; gap: 0.9rem;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--neutral-500); font-size: 0.88rem;"><i class="fa-solid fa-id-card" style="width: 20px;"></i> Enrollment No</span>
                    <span style="font-weight: 700; color: var(--neutral-800); font-size: 0.9rem;"><?= htmlspecialchars($student['enroll_no']) ?></span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--neutral-500); font-size: 0.88rem;"><i class="fa-solid fa-barcode" style="width: 20px;"></i> GR Number</span>
                    <span style="font-weight: 700; color: var(--neutral-800); font-size: 0.9rem;"><?= htmlspecialchars($student['gr_no'] ?: 'N/A') ?></span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--neutral-500); font-size: 0.88rem;"><i class="fa-solid fa-building-columns" style="width: 20px;"></i> Department</span>
                    <span style="font-weight: 600; color: var(--neutral-800); font-size: 0.9rem;"><?= htmlspecialchars($student['department']) ?></span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--neutral-500); font-size: 0.88rem;"><i class="fa-solid fa-layer-group" style="width: 20px;"></i> Current Semester</span>
                    <span style="font-weight: 600; color: var(--neutral-800); font-size: 0.9rem;">Semester <?= (int)$student['semester'] ?></span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--neutral-500); font-size: 0.88rem;"><i class="fa-solid fa-calendar-check" style="width: 20px;"></i> Passing Year</span>
                    <span style="font-weight: 700; color: var(--brand-600); font-size: 0.9rem;"><?= (int)$student['passing_year'] ?> Batch</span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--neutral-500); font-size: 0.88rem;"><i class="fa-solid fa-venus-mars" style="width: 20px;"></i> Gender</span>
                    <span style="font-weight: 600; color: var(--neutral-800); font-size: 0.9rem;"><?= htmlspecialchars($student['gender']) ?></span>
                </div>

                <?php if (!empty($student['dob'])): ?>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: var(--neutral-500); font-size: 0.88rem;"><i class="fa-solid fa-cake-candles" style="width: 20px;"></i> Date of Birth</span>
                        <span style="font-weight: 600; color: var(--neutral-800); font-size: 0.9rem;"><?= date('d M Y', strtotime($student['dob'])) ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Career & Placement Outcome Card -->
        <div class="card" style="background: var(--white); border-radius: var(--radius-xl); border: 1px solid var(--neutral-200); padding: 1.5rem; box-shadow: var(--shadow-sm);">
            <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--neutral-900); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.6rem; border-bottom: 1px solid var(--neutral-150); padding-bottom: 0.75rem;">
                <i class="fa-solid fa-briefcase" style="color: var(--accent-500);"></i> Career &amp; Placement Outcome
            </h3>

            <div style="display: flex; flex-direction: column; gap: 0.9rem;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--neutral-500); font-size: 0.88rem;"><i class="fa-solid fa-flag" style="width: 20px;"></i> Status</span>
                    <span class="badge <?= $statusClass ?>" style="font-size: 0.82rem;">
                        <?= htmlspecialchars($student['placement_status']) ?>
                    </span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--neutral-500); font-size: 0.88rem;"><i class="fa-solid fa-building" style="width: 20px;"></i> Organization / University</span>
                    <span style="font-weight: 700; color: var(--neutral-900); font-size: 0.92rem;">
                        <?= htmlspecialchars($student['company_name'] ?: 'N/A') ?>
                    </span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--neutral-500); font-size: 0.88rem;"><i class="fa-solid fa-user-tag" style="width: 20px;"></i> Designation / Role</span>
                    <span style="font-weight: 600; color: var(--neutral-800); font-size: 0.9rem;">
                        <?= htmlspecialchars($student['designation'] ?: 'N/A') ?>
                    </span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--neutral-500); font-size: 0.88rem;"><i class="fa-solid fa-indian-rupee-sign" style="width: 20px;"></i> Package (LPA)</span>
                    <span style="font-weight: 800; color: var(--green-600); font-size: 1rem;">
                        <?= !empty($student['package_lpa']) ? '₹ ' . number_format((float)$student['package_lpa'], 2) . ' LPA' : 'N/A' ?>
                    </span>
                </div>

                <div style="margin-top: 0.5rem; padding-top: 0.75rem; border-top: 1px dashed var(--neutral-200);">
                    <div style="font-size: 0.82rem; font-weight: 700; color: var(--neutral-600); margin-bottom: 0.4rem;">
                        <i class="fa-solid fa-file-contract"></i> Offer Letter Document
                    </div>
                    <?php if (!empty($student['offer_letter_file'])): ?>
                        <a href="uploads/placement_documents/<?= htmlspecialchars($student['offer_letter_file']) ?>" target="_blank" class="btn btn-light btn-sm" style="width: 100%; justify-content: center; gap: 0.5rem;">
                            <i class="fa-solid fa-file-pdf" style="color: var(--rose-600);"></i> Download Offer Letter
                        </a>
                    <?php else: ?>
                        <div style="font-size: 0.82rem; color: var(--neutral-400); font-style: italic;">
                            No offer letter document uploaded yet.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <!-- Contact & Skills Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 1.5rem;">

        <!-- Contact Information -->
        <div class="card" style="background: var(--white); border-radius: var(--radius-xl); border: 1px solid var(--neutral-200); padding: 1.5rem; box-shadow: var(--shadow-sm);">
            <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--neutral-900); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.6rem; border-bottom: 1px solid var(--neutral-150); padding-bottom: 0.75rem;">
                <i class="fa-solid fa-address-book" style="color: var(--blue-600);"></i> Contact &amp; Location
            </h3>

            <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                <div>
                    <span style="font-size: 0.78rem; text-transform: uppercase; color: var(--neutral-500); font-weight: 700;">Email Address</span>
                    <div style="font-weight: 600; color: var(--neutral-800); font-size: 0.92rem; margin-top: 0.15rem;">
                        <i class="fa-solid fa-envelope" style="color: var(--brand-500); margin-right: 0.4rem;"></i>
                        <?= htmlspecialchars($student['email'] ?: 'N/A') ?>
                    </div>
                </div>

                <div>
                    <span style="font-size: 0.78rem; text-transform: uppercase; color: var(--neutral-500); font-weight: 700;">Phone Number</span>
                    <div style="font-weight: 600; color: var(--neutral-800); font-size: 0.92rem; margin-top: 0.15rem;">
                        <i class="fa-solid fa-phone" style="color: var(--green-600); margin-right: 0.4rem;"></i>
                        <?= htmlspecialchars($student['phone'] ?: 'N/A') ?>
                    </div>
                </div>

                <div>
                    <span style="font-size: 0.78rem; text-transform: uppercase; color: var(--neutral-500); font-weight: 700;">Residential Address</span>
                    <div style="font-weight: 500; color: var(--neutral-700); font-size: 0.9rem; margin-top: 0.15rem; line-height: 1.5;">
                        <i class="fa-solid fa-location-dot" style="color: var(--rose-600); margin-right: 0.4rem;"></i>
                        <?= htmlspecialchars($student['address'] ?: 'No address recorded.') ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Technical Skills -->
        <div class="card" style="background: var(--white); border-radius: var(--radius-xl); border: 1px solid var(--neutral-200); padding: 1.5rem; box-shadow: var(--shadow-sm);">
            <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--neutral-900); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.6rem; border-bottom: 1px solid var(--neutral-150); padding-bottom: 0.75rem;">
                <i class="fa-solid fa-code" style="color: var(--purple-600);"></i> Technical Competencies &amp; Skills
            </h3>

            <?php
            $skillsList = !empty($student['skills']) ? array_map('trim', explode(',', $student['skills'])) : [];
            ?>

            <?php if (!empty($skillsList)): ?>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    <?php foreach ($skillsList as $sk): ?>
                        <span style="background: var(--brand-50); color: var(--brand-600); border: 1px solid var(--brand-100); padding: 0.4rem 0.85rem; border-radius: var(--radius-md); font-weight: 600; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.35rem;">
                            <i class="fa-solid fa-check" style="font-size: 0.75rem; color: var(--brand-500);"></i> <?= htmlspecialchars($sk) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="color: var(--neutral-400); font-style: italic; font-size: 0.9rem;">
                    No technical skills listed yet.
                </div>
            <?php endif; ?>
        </div>

    </div>

</div>

<script>
    function editStudentFromProfile(id) {
        if (typeof openEditStudentModal === 'function') {
            openEditStudentModal(id);
        } else {
            // Fetch student JSON and populate modal
            fetch('index.php?module=placement&action=getJson&id=' + id)
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        const s = res.data;
                        document.getElementById('modalTitle').innerText = 'Edit Student Profile';
                        document.getElementById('formAction').value = 'update';
                        document.getElementById('studentId').value = s.id;
                        document.getElementById('gr_no').value = s.gr_no || '';
                        document.getElementById('enroll_no').value = s.enroll_no || '';
                        document.getElementById('name').value = s.name || '';
                        document.getElementById('email').value = s.email || '';
                        document.getElementById('phone').value = s.phone || '';
                        document.getElementById('gender').value = s.gender || 'Male';
                        document.getElementById('dob').value = s.dob || '';
                        document.getElementById('department').value = s.department || '';
                        document.getElementById('semester').value = s.semester || 8;
                        document.getElementById('cgpa').value = s.cgpa || 0.00;
                        document.getElementById('passing_year').value = s.passing_year || 2026;
                        document.getElementById('address').value = s.address || '';
                        document.getElementById('skills').value = s.skills || '';
                        document.getElementById('placement_status').value = s.placement_status || 'Unplaced';
                        document.getElementById('company_name').value = s.company_name || '';
                        document.getElementById('designation').value = s.designation || '';
                        document.getElementById('package_lpa').value = s.package_lpa || '';

                        document.getElementById('studentModal').style.display = 'flex';
                    }
                });
        }
    }
</script>

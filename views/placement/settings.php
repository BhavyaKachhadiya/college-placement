<?php
/**
 * Student Settings View
 * Personal, Academic, and Career Placement Status are read-only (College Admin Managed)
 * Students can edit their Technical Skills & Permanent Address.
 */
$msg = isset($_GET['msg']) ? trim($_GET['msg']) : '';
$error = isset($_GET['error']) ? trim($_GET['error']) : '';
?>

<div class="container py-4">

    <!-- Header / Title -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.75rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-family: var(--font-display); font-size: 1.5rem; font-weight: 800; color: var(--neutral-900); margin: 0; display: flex; align-items: center; gap: 0.6rem;">
                <i class="fa-solid fa-gear" style="color: var(--brand-500);"></i> Account Settings &amp; Profile Updates
            </h2>
            <p style="color: var(--neutral-500); font-size: 0.9rem; margin-top: 0.25rem;">
                View your profile details &amp; update your technical skills and permanent address.
            </p>
        </div>

        <a href="index.php?module=student&action=studentProfile" class="btn btn-light btn-sm">
            <i class="fa-solid fa-user-graduate"></i> View My Profile
        </a>
    </div>

    <!-- Alert Messages -->
    <?php if ($msg === 'updated'): ?>
        <div style="background: rgba(22, 163, 74, 0.1); border: 1px solid var(--green-500); color: var(--green-600); padding: 0.9rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; font-weight: 600;">
            <i class="fa-solid fa-circle-check" style="font-size: 1.25rem;"></i>
            <span>Your skills &amp; address settings have been updated successfully!</span>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div style="background: rgba(225, 29, 72, 0.1); border: 1px solid var(--rose-600); color: var(--rose-600); padding: 0.9rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; font-weight: 600;">
            <i class="fa-solid fa-circle-exclamation" style="font-size: 1.25rem;"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <!-- Settings Form Card -->
    <form action="index.php?module=student&action=updateSelf" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="module" value="student">
        <input type="hidden" name="action" value="updateSelf">

        <div class="card" style="background: var(--white); border-radius: var(--radius-xl); border: 1px solid var(--neutral-200); padding: 2rem; box-shadow: var(--shadow-sm); margin-bottom: 2rem;">

            <!-- Section 1: RESUME UPLOAD (TOP FEATURED SECTION) -->
            <div style="background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%); border: 1.5px solid #86efac; border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 2.25rem; box-shadow: var(--shadow-xs);">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 0.75rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #166534; margin: 0; display: flex; align-items: center; gap: 0.6rem;">
                        <i class="fa-solid fa-file-pdf" style="color: #22c55e; font-size: 1.3rem;"></i> Student Resume Upload (Required for Placements)
                    </h3>
                </div>

                <p style="font-size: 0.85rem; color: #15803d; margin-bottom: 1.25rem; line-height: 1.4;">
                    Upload your latest PDF or DOCX resume (max 5MB). Your resume will be automatically submitted when applying for company recruitment drives.
                </p>

                <div style="background: #ffffff; border: 1px dashed #4ade80; border-radius: var(--radius-md); padding: 1.25rem;">
                    <label style="display: block; font-weight: 700; font-size: 0.88rem; color: var(--neutral-800); margin-bottom: 0.5rem;">
                        Select Resume File (PDF / DOCX)
                    </label>
                    <input type="file" name="resume_file" accept=".pdf,.doc,.docx" class="form-control" style="width: 100%; padding: 0.75rem 0.9rem; border: 1px solid var(--neutral-300); border-radius: var(--radius-sm); background: #ffffff;">

                    <?php if (!empty($student['resume_file'])): ?>
                        <div style="margin-top: 1rem; padding-top: 0.85rem; border-top: 1px solid var(--neutral-200); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fa-solid fa-circle-check" style="color: #22c55e; font-size: 1.2rem;"></i>
                                <span style="font-size: 0.88rem; color: var(--neutral-700);">Current Resume: <strong style="color: var(--neutral-900);"><?= htmlspecialchars($student['resume_file']) ?></strong></span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <a href="uploads/placement_documents/<?= htmlspecialchars($student['resume_file']) ?>" target="_blank" class="btn btn-sm btn-primary" style="font-size: 0.82rem; padding: 0.4rem 0.9rem;">
                                    <i class="fa-solid fa-eye"></i> View Resume
                                </a>
                                <a href="index.php?module=student&action=deleteResume" onclick="return confirm('Are you sure you want to delete your resume?')" class="btn btn-sm" style="background: rgba(225,29,72,0.1); color: var(--rose-600); border: 1px solid rgba(225,29,72,0.2); font-size: 0.82rem; padding: 0.4rem 0.9rem;">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div style="margin-top: 0.75rem; font-size: 0.84rem; color: #b91c1c; font-weight: 600; display: flex; align-items: center; gap: 0.45rem;">
                            <i class="fa-solid fa-triangle-exclamation"></i> No resume uploaded yet. Select your PDF resume and click "Save My Settings" to upload.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Section 2: Technical Skills & Permanent Address (EDITABLE) -->
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--neutral-150); padding-bottom: 0.75rem; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--neutral-900); margin: 0; display: flex; align-items: center; gap: 0.6rem;">
                    <i class="fa-solid fa-code" style="color: var(--amber-600);"></i> Technical Skills &amp; Permanent Address
                </h3>
                <span style="font-size: 0.78rem; font-weight: 700; background: rgba(22, 163, 74, 0.12); color: var(--green-600); border: 1px solid rgba(22, 163, 74, 0.3); padding: 0.25rem 0.65rem; border-radius: 9999px;">
                    <i class="fa-solid fa-pen" style="font-size: 0.7rem;"></i> Editable
                </span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 1.25rem; margin-bottom: 2.25rem;">
                <!-- Skills -->
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                        Technical Skills (comma separated)
                    </label>
                    <input type="text" name="skills" value="<?= htmlspecialchars($student['skills'] ?? '') ?>" placeholder="e.g. Python, React, Node.js, AWS, Java" class="form-control" style="width: 100%; padding: 0.75rem 0.9rem; border: 1px solid var(--neutral-300); border-radius: var(--radius-sm);">
                </div>

                <!-- Permanent Address -->
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                        Permanent Residential Address
                    </label>
                    <textarea name="address" rows="3" class="form-control" placeholder="Enter your complete address..." style="width: 100%; padding: 0.75rem 0.9rem; border: 1px solid var(--neutral-300); border-radius: var(--radius-sm); font-family: var(--font-primary);"><?= htmlspecialchars($student['address'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- Section 3: Basic Profile Details (READ-ONLY) -->
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--neutral-150); padding-bottom: 0.75rem; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--neutral-900); margin: 0; display: flex; align-items: center; gap: 0.6rem;">
                    <i class="fa-solid fa-user-lock" style="color: var(--brand-500);"></i> Personal &amp; Basic Info
                </h3>
                <span style="font-size: 0.78rem; font-weight: 600; background: var(--neutral-100); color: var(--neutral-600); border: 1px solid var(--neutral-200); padding: 0.25rem 0.65rem; border-radius: 9999px;">
                    <i class="fa-solid fa-lock" style="font-size: 0.7rem; color: var(--neutral-400);"></i> Read-only (College Admin Managed)
                </span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem; margin-bottom: 2.25rem; background: var(--neutral-50); padding: 1.25rem; border-radius: var(--radius-lg); border: 1px solid var(--neutral-150);">
                <!-- Name -->
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                        Full Name
                    </label>
                    <input type="text" value="<?= htmlspecialchars($student['name'] ?? '') ?>" disabled style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-200); background: #ffffff; color: var(--neutral-700); border-radius: var(--radius-sm); font-weight: 600;">
                </div>

                <!-- Enrollment Number -->
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                        Enrollment Number (Username)
                    </label>
                    <input type="text" value="<?= htmlspecialchars($student['enroll_no'] ?? '') ?>" disabled style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-200); background: #ffffff; color: var(--brand-600); border-radius: var(--radius-sm); font-weight: 700;">
                </div>

                <!-- GR Number -->
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                        GR Number
                    </label>
                    <input type="text" value="<?= htmlspecialchars($student['gr_no'] ?? 'N/A') ?>" disabled style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-200); background: #ffffff; color: var(--neutral-700); border-radius: var(--radius-sm); font-weight: 600;">
                </div>

                <!-- Email -->
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                        Email Address
                    </label>
                    <input type="email" value="<?= htmlspecialchars($student['email'] ?? 'N/A') ?>" disabled style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-200); background: #ffffff; color: var(--neutral-700); border-radius: var(--radius-sm);">
                </div>

                <!-- Phone -->
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                        Phone Number
                    </label>
                    <input type="text" value="<?= htmlspecialchars($student['phone'] ?? 'N/A') ?>" disabled style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-200); background: #ffffff; color: var(--neutral-700); border-radius: var(--radius-sm);">
                </div>

                <!-- Gender -->
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                        Gender
                    </label>
                    <input type="text" value="<?= htmlspecialchars($student['gender'] ?? 'Male') ?>" disabled style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-200); background: #ffffff; color: var(--neutral-700); border-radius: var(--radius-sm);">
                </div>

                <!-- Date of Birth -->
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                        Date of Birth
                    </label>
                    <input type="text" value="<?= !empty($student['dob']) ? date('d M Y', strtotime($student['dob'])) : 'N/A' ?>" disabled style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-200); background: #ffffff; color: var(--neutral-700); border-radius: var(--radius-sm);">
                </div>
            </div>

            <!-- Section 4: Academic Info (READ-ONLY) -->
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--neutral-150); padding-bottom: 0.75rem; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--neutral-900); margin: 0; display: flex; align-items: center; gap: 0.6rem;">
                    <i class="fa-solid fa-graduation-cap" style="color: var(--purple-600);"></i> Academic Information
                </h3>
                <span style="font-size: 0.78rem; font-weight: 600; background: var(--neutral-100); color: var(--neutral-600); border: 1px solid var(--neutral-200); padding: 0.25rem 0.65rem; border-radius: 9999px;">
                    <i class="fa-solid fa-lock" style="font-size: 0.7rem; color: var(--neutral-400);"></i> Read-only (College Admin Managed)
                </span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 2.25rem; background: var(--neutral-50); padding: 1.25rem; border-radius: var(--radius-lg); border: 1px solid var(--neutral-150);">
                <!-- Department -->
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                        Department
                    </label>
                    <input type="text" value="<?= htmlspecialchars($student['department'] ?? '') ?>" disabled style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-200); background: #ffffff; color: var(--neutral-700); border-radius: var(--radius-sm); font-weight: 600;">
                </div>

                <!-- Semester -->
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                        Current Semester
                    </label>
                    <input type="text" value="Semester <?= (int)($student['semester'] ?? 8) ?>" disabled style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-200); background: #ffffff; color: var(--neutral-700); border-radius: var(--radius-sm);">
                </div>

                <!-- CGPA -->
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                        Academic CGPA
                    </label>
                    <input type="text" value="<?= number_format((float)($student['cgpa'] ?? 0.0), 2) ?> / 10" disabled style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-200); background: #ffffff; color: var(--amber-600); border-radius: var(--radius-sm); font-weight: 700;">
                </div>

                <!-- Passing Year -->
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                        Passing Batch Year
                    </label>
                    <input type="text" value="<?= (int)($student['passing_year'] ?? 2026) ?> Batch" disabled style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-200); background: #ffffff; color: var(--brand-600); border-radius: var(--radius-sm); font-weight: 700;">
                </div>
            </div>

            <!-- Section 5: Placement & Career Status (READ-ONLY) -->
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--neutral-150); padding-bottom: 0.75rem; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--neutral-900); margin: 0; display: flex; align-items: center; gap: 0.6rem;">
                    <i class="fa-solid fa-briefcase" style="color: var(--green-600);"></i> Career &amp; Placement Status
                </h3>
                <span style="font-size: 0.78rem; font-weight: 600; background: var(--neutral-100); color: var(--neutral-600); border: 1px solid var(--neutral-200); padding: 0.25rem 0.65rem; border-radius: 9999px;">
                    <i class="fa-solid fa-lock" style="font-size: 0.7rem; color: var(--neutral-400);"></i> Read-only (College Admin Managed)
                </span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem; margin-bottom: 2.25rem; background: var(--neutral-50); padding: 1.25rem; border-radius: var(--radius-lg); border: 1px solid var(--neutral-150);">
                <!-- Placement Status (READ-ONLY) -->
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                        Career Pathway Status
                    </label>
                    <input type="text" value="<?= htmlspecialchars($student['placement_status'] ?? 'Unplaced') ?>" disabled style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-200); background: #ffffff; color: var(--neutral-800); border-radius: var(--radius-sm); font-weight: 700;">
                </div>

                <!-- Company Name (READ-ONLY) -->
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                        Company / Organization / University
                    </label>
                    <input type="text" value="<?= htmlspecialchars($student['company_name'] ?: 'N/A') ?>" disabled style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-200); background: #ffffff; color: var(--neutral-800); border-radius: var(--radius-sm); font-weight: 600;">
                </div>

                <!-- Designation (READ-ONLY) -->
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                        Designation / Job Role
                    </label>
                    <input type="text" value="<?= htmlspecialchars($student['designation'] ?: 'N/A') ?>" disabled style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-200); background: #ffffff; color: var(--neutral-800); border-radius: var(--radius-sm);">
                </div>

                <!-- Package LPA (READ-ONLY) -->
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                        Package (LPA)
                    </label>
                    <input type="text" value="<?= !empty($student['package_lpa']) ? '₹ ' . number_format((float)$student['package_lpa'], 2) . ' LPA' : 'N/A' ?>" disabled style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-200); background: #ffffff; color: var(--green-600); border-radius: var(--radius-sm); font-weight: 700;">
                </div>

                <!-- Offer Letter Document (READ-ONLY) -->
                <div style="grid-column: 1 / -1;">
                    <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                        Offer Letter Document
                    </label>
                    <?php if (!empty($student['offer_letter_file'])): ?>
                        <div style="display: flex; align-items: center; gap: 0.75rem; background: #ffffff; border: 1px solid var(--neutral-200); padding: 0.65rem 1rem; border-radius: var(--radius-sm);">
                            <i class="fa-solid fa-file-pdf" style="color: var(--rose-600); font-size: 1.1rem;"></i>
                            <span style="font-size: 0.88rem; font-weight: 600; color: var(--neutral-800);"><?= htmlspecialchars($student['offer_letter_file']) ?></span>
                            <a href="uploads/placement_documents/<?= htmlspecialchars($student['offer_letter_file']) ?>" target="_blank" class="btn btn-light btn-sm" style="margin-left: auto;">
                                <i class="fa-solid fa-download"></i> View Document
                            </a>
                        </div>
                    <?php else: ?>
                        <input type="text" value="No offer letter document uploaded." disabled style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-200); background: #ffffff; color: var(--neutral-400); border-radius: var(--radius-sm); font-style: italic;">
                    <?php endif; ?>
                </div>
            </div>

            <!-- Submit Button -->
            <div style="display: flex; justify-content: flex-end; gap: 1rem; padding-top: 1rem; border-top: 1px solid var(--neutral-150);">
                <a href="index.php?module=student&action=studentProfile" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary" style="padding: 0.85rem 2rem; font-weight: 700;">
                    <i class="fa-solid fa-floppy-disk"></i> Save My Settings
                </button>
            </div>

        </div>
    </form>

</div>

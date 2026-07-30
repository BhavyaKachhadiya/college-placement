<!-- Modal Form for Create & Edit Student Placement Record -->
<div id="studentFormModal" class="modal-backdrop" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="studentModalTitle"><i class="fa-solid fa-user-plus"></i> Add Student Placement Profile</h3>
                <button type="button" class="close-btn" onclick="closeStudentFormModal()">&times;</button>
            </div>
            
            <form id="studentForm" action="index.php?module=placement" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" id="stFormAction" value="store">
                <input type="hidden" name="id" id="stId" value="">

                <div class="modal-body">
                    <h4 class="form-section-header"><i class="fa-solid fa-id-card"></i> Student Academic & Personal Details</h4>
                    
                    <div class="form-row">
                        <div class="form-group col-4">
                            <label for="st_gr_no" class="form-label">GR Number</label>
                            <input type="text" name="gr_no" id="st_gr_no" class="form-control" placeholder="e.g. 105488">
                            <small class="form-hint">General Register No. (e.g. 105488)</small>
                        </div>
                        <div class="form-group col-4">
                            <label for="st_enroll_no" class="form-label required">Enrollment Number</label>
                            <input type="text" name="enroll_no" id="st_enroll_no" class="form-control" placeholder="e.g. 250114305001" required>
                            <small class="form-hint">Format: 12-digit Enrollment Code</small>
                        </div>
                        <div class="form-group col-4">
                            <label for="st_name" class="form-label required">Full Student Name</label>
                            <input type="text" name="name" id="st_name" class="form-control" placeholder="e.g. Aarav Mehta" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="st_email" class="form-label">Email Address</label>
                            <input type="email" name="email" id="st_email" class="form-control" placeholder="e.g. aarav@college.edu">
                        </div>
                        <div class="form-group col-6">
                            <label for="st_phone" class="form-label">Phone Number</label>
                            <input type="text" name="phone" id="st_phone" class="form-control" placeholder="e.g. +91 98765 43210">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-4">
                            <label for="st_gender" class="form-label required">Gender</label>
                            <select name="gender" id="st_gender" class="form-select">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="form-group col-4">
                            <label for="st_dob" class="form-label">Date of Birth</label>
                            <input type="date" name="dob" id="st_dob" class="form-control">
                        </div>
                        <div class="form-group col-4">
                            <label for="st_department" class="form-label required">Department</label>
                            <input type="text" name="department" id="st_department" class="form-control" placeholder="e.g. Computer Engineering" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-4">
                            <label for="st_semester" class="form-label">Current Semester</label>
                            <input type="number" name="semester" id="st_semester" class="form-control" min="1" max="10" value="8">
                        </div>
                        <div class="form-group col-4">
                            <label for="st_cgpa" class="form-label">CGPA / Grade</label>
                            <input type="number" step="0.01" name="cgpa" id="st_cgpa" class="form-control" min="0" max="10" placeholder="e.g. 8.75">
                        </div>
                        <div class="form-group col-4">
                            <label for="st_passing_year" class="form-label required">Passing Batch Year</label>
                            <input type="number" name="passing_year" id="st_passing_year" class="form-control" placeholder="e.g. 2026" required>
                        </div>
                    </div>

                    <h4 class="form-section-header mt-4"><i class="fa-solid fa-briefcase"></i> Career Pathway & Placement Status</h4>

                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="st_placement_status" class="form-label required">Placement Status</label>
                            <select name="placement_status" id="st_placement_status" class="form-select" onchange="togglePlacementFields(this.value)">
                                <option value="Unplaced">Unplaced</option>
                                <option value="Placed">🟢 Placed (Job Offer)</option>
                                <option value="Internship">🔵 Internship</option>
                                <option value="Higher Studies">🟣 Higher Studies</option>
                                <option value="Business">🟠 Business / Entrepreneurship</option>
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label for="st_package_lpa" class="form-label">Package (in LPA)</label>
                            <input type="number" step="0.01" name="package_lpa" id="st_package_lpa" class="form-control" placeholder="e.g. 12.50">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="st_company_name" class="form-label" id="lblCompanyName">Company / Institution Name</label>
                            <input type="text" name="company_name" id="st_company_name" class="form-control" placeholder="e.g. Tech Corp Solutions / Stanford Univ">
                        </div>
                        <div class="form-group col-6">
                            <label for="st_designation" class="form-label" id="lblDesignation">Designation / Degree Program</label>
                            <input type="text" name="designation" id="st_designation" class="form-control" placeholder="e.g. Software Engineer / MS in CS">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="st_skills" class="form-label">Skills & Technical Competencies</label>
                            <input type="text" name="skills" id="st_skills" class="form-control" placeholder="e.g. Python, Machine Learning, React, AWS, Docker">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="st_offer_letter_file" class="form-label">Offer Letter / Certificate Upload</label>
                            <div class="file-upload-box">
                                <input type="file" name="offer_letter_file" id="st_offer_letter_file" class="file-input" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="upload-placeholder">
                                    <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                                    <span id="stFileNameDisplay">Click or drag Offer Letter (PDF, DOCX, PNG)</span>
                                </div>
                            </div>
                            <div id="stExistingFileContainer" class="existing-file-info" style="display:none; margin-top: 8px;">
                                <i class="fa-solid fa-paperclip"></i> Current file: <span id="stExistingFileName" class="text-primary font-weight-bold"></span>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="st_resume_file" class="form-label">Student Resume Upload</label>
                            <div class="file-upload-box">
                                <input type="file" name="resume_file" id="st_resume_file" class="file-input" accept=".pdf,.doc,.docx">
                                <div class="upload-placeholder">
                                    <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                                    <span id="stResumeNameDisplay">Click or drag Resume (PDF, DOCX)</span>
                                </div>
                            </div>
                            <div id="stExistingResumeContainer" class="existing-file-info" style="display:none; margin-top: 8px;">
                                <i class="fa-solid fa-paperclip"></i> Current file: <span id="stExistingResumeName" class="text-primary font-weight-bold"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeStudentFormModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmitStForm">
                        <i class="fa-solid fa-floppy-disk"></i> Save Student Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

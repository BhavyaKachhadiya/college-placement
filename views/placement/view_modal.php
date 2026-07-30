<!-- Modal for Displaying Detailed Student Profile & Placement Status -->
<div id="studentViewModal" class="modal-backdrop" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header header-view">
                <div class="view-header-title">
                    <span class="badge badge-outline-light" id="viewStGrNo" style="margin-right: 6px;">GR No</span>
                    <span class="badge badge-outline-light" id="viewStEnroll">Enroll #</span>
                    <h3 id="viewStName">Student Profile</h3>
                </div>
                <button type="button" class="close-btn" onclick="closeStudentViewModal()">&times;</button>
            </div>
            
            <div class="modal-body py-4">
                <div class="view-grid">
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-solid fa-id-card"></i> GR Number</div>
                        <div class="view-card-value" id="viewStGrNoVal">N/A</div>
                    </div>
                    <div class="view-card">
                        <div class="view-card-label"><div class="fa-solid fa-id-card"></div> Enrollment Number</div>
                        <div class="view-card-value" id="viewStEnrollVal">N/A</div>
                    </div>
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-solid fa-building-columns"></i> Department</div>
                        <div class="view-card-value" id="viewStDepartment">N/A</div>
                    </div>
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-solid fa-graduation-cap"></i> Semester / CGPA</div>
                        <div class="view-card-value" id="viewStCgpa">N/A</div>
                    </div>
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-solid fa-calendar"></i> Passing Batch</div>
                        <div class="view-card-value" id="viewStYear">N/A</div>
                    </div>
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-solid fa-venus-mars"></i> Gender & DOB</div>
                        <div class="view-card-value" id="viewStGenderDob">N/A</div>
                    </div>
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-solid fa-envelope"></i> Contact Email</div>
                        <div class="view-card-value" id="viewStEmail">N/A</div>
                    </div>
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-solid fa-phone"></i> Phone Number</div>
                        <div class="view-card-value" id="viewStPhone">N/A</div>
                    </div>
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-solid fa-route"></i> Career Pathway Status</div>
                        <div class="view-card-value" id="viewStStatus">N/A</div>
                    </div>
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-solid fa-indian-rupee-sign"></i> Package (LPA)</div>
                        <div class="view-card-value" id="viewStPackage">N/A</div>
                    </div>
                </div>

                <div class="view-section mt-4">
                    <h4><i class="fa-solid fa-briefcase"></i> Company / Organization / Institution Details</h4>
                    <div class="view-grid">
                        <div class="view-card">
                            <div class="view-card-label"><i class="fa-solid fa-building"></i> Company / Institution Name</div>
                            <div class="view-card-value" id="viewStCompany">N/A</div>
                        </div>
                        <div class="view-card">
                            <div class="view-card-label"><i class="fa-solid fa-user-tag"></i> Designation / Role</div>
                            <div class="view-card-value" id="viewStDesignation">N/A</div>
                        </div>
                    </div>
                </div>

                <div class="view-section mt-4">
                    <h4><i class="fa-solid fa-laptop-code"></i> Technical Skills</h4>
                    <div id="viewStSkills" class="view-box">No skills recorded.</div>
                </div>

                <div class="view-section mt-3">
                    <h4><i class="fa-solid fa-location-dot"></i> Permanent Address</h4>
                    <p id="viewStAddress" class="view-text">No address recorded.</p>
                </div>

                <div class="view-section mt-4">
                    <h4><i class="fa-solid fa-file-contract"></i> Uploaded Offer Letter / Certificate Document</h4>
                    <div id="viewStOfferContainer" class="view-report-box">
                        <span class="text-muted">No offer letter document uploaded.</span>
                    </div>
                </div>

                <div class="view-section mt-4">
                    <h4><i class="fa-solid fa-file-lines"></i> Uploaded Student Resume Document</h4>
                    <div id="viewStResumeContainer" class="view-report-box">
                        <span class="text-muted">No resume uploaded.</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeStudentViewModal()">Close</button>
                <button type="button" class="btn btn-primary" id="viewStEditBtn">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Profile
                </button>
            </div>
        </div>
    </div>
</div>

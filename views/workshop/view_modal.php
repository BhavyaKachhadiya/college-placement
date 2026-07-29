<!-- Modal for Displaying Detailed Workshop Information -->
<div id="workshopViewModal" class="modal-backdrop" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header header-view">
                <div class="view-header-title">
                    <span class="badge badge-outline-light" id="viewWsId">Activity #</span>
                    <h3 id="viewWsTitle">Workshop Title</h3>
                </div>
                <button type="button" class="close-btn" onclick="closeWorkshopViewModal()">&times;</button>
            </div>
            
            <div class="modal-body py-4">
                <div class="view-grid">
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-solid fa-user-tie"></i> Guest Expert / Instructor</div>
                        <div class="view-card-value" id="viewWsInstructor">N/A</div>
                    </div>
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-solid fa-building"></i> Host / Partner Company</div>
                        <div class="view-card-value" id="viewWsCompany">N/A</div>
                    </div>
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-solid fa-envelope"></i> Contact Email</div>
                        <div class="view-card-value" id="viewWsEmail">N/A</div>
                    </div>
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-solid fa-location-dot"></i> Venue / Hall</div>
                        <div class="view-card-value" id="viewWsVenue">N/A</div>
                    </div>
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-regular fa-calendar"></i> Date Held On</div>
                        <div class="view-card-value" id="viewWsHeldOn">N/A</div>
                    </div>
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-regular fa-clock"></i> Duration</div>
                        <div class="view-card-value" id="viewWsDuration">N/A</div>
                    </div>
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-solid fa-users"></i> Total Participants</div>
                        <div class="view-card-value" id="viewWsParticipants">N/A</div>
                    </div>
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-solid fa-certificate"></i> Certificate Issued</div>
                        <div class="view-card-value" id="viewWsCertificate">N/A</div>
                    </div>
                </div>

                <div class="view-section mt-4">
                    <h4><i class="fa-solid fa-align-left"></i> Topic Summary & Scope</h4>
                    <div id="viewWsDescription" class="view-box">No description provided.</div>
                </div>

                <div class="view-section mt-4">
                    <h4><i class="fa-solid fa-file-arrow-down"></i> Uploaded Summary Report Document</h4>
                    <div id="viewWsReportContainer" class="view-report-box">
                        <span class="text-muted">No summary report document uploaded.</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeWorkshopViewModal()">Close</button>
                <button type="button" class="btn btn-primary" id="viewWsEditBtn">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Activity
                </button>
            </div>
        </div>
    </div>
</div>

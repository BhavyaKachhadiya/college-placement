<!-- Modal for Displaying Detailed MOU Information -->
<div id="mouViewModal" class="modal-backdrop" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header header-view">
                <div class="view-header-title">
                    <span class="badge badge-outline-light" id="viewMouId">MOU #</span>
                    <h3 id="viewCompanyName">Company Details</h3>
                </div>
                <button type="button" class="close-btn" onclick="closeViewModal()">&times;</button>
            </div>
            
            <div class="modal-body py-4">
                <div class="view-grid">
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-solid fa-user-tie"></i> Contact Representative</div>
                        <div class="view-card-value" id="viewContactPerson">N/A</div>
                    </div>
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-solid fa-envelope"></i> Email Address</div>
                        <div class="view-card-value" id="viewEmail">N/A</div>
                    </div>
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-solid fa-phone"></i> Phone Number</div>
                        <div class="view-card-value" id="viewPhone">N/A</div>
                    </div>
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-solid fa-globe"></i> Website</div>
                        <div class="view-card-value" id="viewWebsite">N/A</div>
                    </div>
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-regular fa-calendar-check"></i> Date of Signing</div>
                        <div class="view-card-value" id="viewSignedDate">N/A</div>
                    </div>
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-regular fa-calendar-xmark"></i> Expiry Date</div>
                        <div class="view-card-value" id="viewExpiryDate">N/A</div>
                    </div>
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-solid fa-calendar-days"></i> Academic / Signing Year</div>
                        <div class="view-card-value" id="viewYear">N/A</div>
                    </div>
                    <div class="view-card">
                        <div class="view-card-label"><i class="fa-solid fa-signal"></i> Agreement Status</div>
                        <div class="view-card-value" id="viewStatus">N/A</div>
                    </div>
                </div>

                <div class="view-section mt-4">
                    <h4><i class="fa-solid fa-location-dot"></i> Address</h4>
                    <p id="viewAddress" class="view-text">No address provided.</p>
                </div>

                <div class="view-section mt-3">
                    <h4><i class="fa-solid fa-bullseye"></i> Purpose & Scope of MOU</h4>
                    <div id="viewDescription" class="view-box">No description provided.</div>
                </div>

                <div class="view-section mt-4">
                    <h4><i class="fa-solid fa-file-arrow-down"></i> Uploaded MOU Report Document</h4>
                    <div id="viewReportContainer" class="view-report-box">
                        <span class="text-muted">No report document uploaded.</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeViewModal()">Close</button>
                <button type="button" class="btn btn-primary" id="viewEditBtn">
                    <i class="fa-solid fa-pen-to-square"></i> Edit MOU
                </button>
            </div>
        </div>
    </div>
</div>

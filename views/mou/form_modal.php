<!-- Modal Form for Create and Edit MOU -->
<div id="mouFormModal" class="modal-backdrop" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle"><i class="fa-solid fa-file-signature"></i> Add New MOU</h3>
                <button type="button" class="close-btn" onclick="closeFormModal()">&times;</button>
            </div>
            
            <form id="mouForm" action="index.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" id="formAction" value="store">
                <input type="hidden" name="id" id="mouId" value="">

                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="company_name" class="form-label required">Partnering Company Name</label>
                            <input type="text" name="company_name" id="company_name" class="form-control" placeholder="e.g. Microsoft Corporation" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="contact_person" class="form-label">Contact Person / Representative</label>
                            <input type="text" name="contact_person" id="contact_person" class="form-control" placeholder="e.g. Dr. John Doe">
                        </div>
                        <div class="form-group col-6">
                            <label for="email" class="form-label">Contact Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="e.g. contact@company.com">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" name="phone" id="phone" class="form-control" placeholder="e.g. +91 98765 43210">
                        </div>
                        <div class="form-group col-6">
                            <label for="website" class="form-label">Company Website</label>
                            <input type="url" name="website" id="website" class="form-control" placeholder="e.g. https://company.com">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-4">
                            <label for="signed_date" class="form-label required">Date of Signing</label>
                            <input type="date" name="signed_date" id="signed_date" class="form-control" required onchange="calculateYearFromDate()">
                        </div>
                        <div class="form-group col-4">
                            <label for="expiry_date" class="form-label required">Expiry Date</label>
                            <input type="date" name="expiry_date" id="expiry_date" class="form-control" required>
                        </div>
                        <div class="form-group col-4">
                            <label for="status" class="form-label required">Agreement Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="Active">Active</option>
                                <option value="Expired">Expired</option>
                                <option value="Terminated">Terminated</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="description" class="form-label">Purpose & Scope of MOU</label>
                            <textarea name="description" id="description" class="form-control" rows="3" placeholder="Describe the objectives, research collaboration areas, internships, workshops, or training..."></textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="report_file" class="form-label">MOU Report / Document Upload</label>
                            <div class="file-upload-box">
                                <input type="file" name="report_file" id="report_file" class="file-input" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="upload-placeholder">
                                    <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                                    <span id="fileNameDisplay">Click or drag & drop MOU report (PDF, DOCX, PNG)</span>
                                </div>
                            </div>
                            <div id="existingFileContainer" class="existing-file-info" style="display:none; margin-top: 8px;">
                                <i class="fa-solid fa-paperclip"></i> Current file: <span id="existingFileName" class="text-primary font-weight-bold"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeFormModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmitForm">
                        <i class="fa-solid fa-floppy-disk"></i> Save Agreement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

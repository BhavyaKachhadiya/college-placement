<!-- Modal Form for Create & Edit Workshop -->
<div id="workshopFormModal" class="modal-backdrop" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="workshopModalTitle"><i class="fa-solid fa-graduation-cap"></i> Record Workshop / Seminar</h3>
                <button type="button" class="close-btn" onclick="closeWorkshopFormModal()">&times;</button>
            </div>
            
            <form id="workshopForm" action="index.php?module=workshop" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" id="wsFormAction" value="store">
                <input type="hidden" name="id" id="wsId" value="">

                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="ws_title" class="form-label required">Workshop / Seminar Topic Title</label>
                            <input type="text" name="title" id="ws_title" class="form-control" placeholder="e.g. Generative AI & Cloud Native Deployment Masterclass" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="ws_instructor_name" class="form-label">Guest Expert / Instructor Name</label>
                            <input type="text" name="instructor_name" id="ws_instructor_name" class="form-control" placeholder="e.g. Dr. Ramesh Sharma">
                        </div>
                        <div class="form-group col-6">
                            <label for="ws_company_name" class="form-label">Host / Partnering Company Name</label>
                            <input type="text" name="company_name" id="ws_company_name" class="form-control" placeholder="e.g. Tech Corp Solutions">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="ws_instructor_email" class="form-label">Instructor Email</label>
                            <input type="email" name="instructor_email" id="ws_instructor_email" class="form-control" placeholder="e.g. instructor@company.com">
                        </div>
                        <div class="form-group col-6">
                            <label for="ws_venue" class="form-label">Venue / Hall Location</label>
                            <input type="text" name="venue" id="ws_venue" class="form-control" placeholder="e.g. Auditorium 2 / Online Virtual">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-4">
                            <label for="ws_held_on" class="form-label required">Date Held On</label>
                            <input type="date" name="held_on" id="ws_held_on" class="form-control" required>
                        </div>
                        <div class="form-group col-4">
                            <label for="ws_duration" class="form-label">Duration (in hours)</label>
                            <input type="number" name="duration" id="ws_duration" class="form-control" min="1" value="4">
                        </div>
                        <div class="form-group col-4">
                            <label for="ws_total_participants" class="form-label">Total Participants</label>
                            <input type="number" name="total_participants" id="ws_total_participants" class="form-control" min="0" value="50">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="ws_certificate" class="form-label">Participation Certificate</label>
                            <select name="certificate" id="ws_certificate" class="form-select">
                                <option value="1">Yes - Certificates Provided</option>
                                <option value="0">No Certificate</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="ws_description" class="form-label">Topic Summary & Scope</label>
                            <textarea name="description" id="ws_description" class="form-control" rows="3" placeholder="Overview of key topics covered, hands-on labs, tools taught, learning outcomes..."></textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="ws_report_file" class="form-label">Summary Report Document Upload</label>
                            <div class="file-upload-box">
                                <input type="file" name="report_file" id="ws_report_file" class="file-input" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="upload-placeholder">
                                    <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                                    <span id="wsFileNameDisplay">Click or drag & drop Summary Report (PDF, DOCX, PNG)</span>
                                </div>
                            </div>
                            <div id="wsExistingFileContainer" class="existing-file-info" style="display:none; margin-top: 8px;">
                                <i class="fa-solid fa-paperclip"></i> Current summary report: <span id="wsExistingFileName" class="text-primary font-weight-bold"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeWorkshopFormModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmitWsForm">
                        <i class="fa-solid fa-floppy-disk"></i> Save Workshop Activity
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

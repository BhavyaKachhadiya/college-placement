<!-- Modal Dialog for CSV Bulk Upload -->
<div id="bulkUploadModal" class="modal-backdrop" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fa-solid fa-file-csv"></i> Bulk CSV Student Import</h3>
                <button type="button" class="close-btn" onclick="closeBulkModal()">&times;</button>
            </div>
            
            <form id="bulkForm" action="index.php?module=placement&action=bulkUpload" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p class="mb-3 text-muted">Upload a spreadsheet batch file (.CSV format) to import or update multiple student placement profiles at once.</p>

                    <div class="alert alert-info">
                        <i class="fa-solid fa-circle-info"></i> <strong>CSV Column Header Format:</strong><br>
                        <code>Enroll_No, Name, Email, Phone, Gender, Department, Semester, CGPA, Passing_Year, Skills, Placement_Status, Company_Name, Designation, Package_LPA</code>
                    </div>

                    <div class="text-right mb-3">
                        <a href="index.php?module=placement&action=sampleCsv" class="btn btn-light" style="font-size:0.85rem;" download>
                            <i class="fa-solid fa-download"></i> Download Sample CSV Template
                        </a>
                    </div>

                    <div class="form-group col-12">
                        <label for="csv_file" class="form-label required">Select CSV Data File</label>
                        <div class="file-upload-box">
                            <input type="file" name="csv_file" id="csv_file" class="file-input" accept=".csv" required>
                            <div class="upload-placeholder">
                                <i class="fa-solid fa-file-csv upload-icon" style="color: #0284c7;"></i>
                                <span id="csvFileNameDisplay">Click or drag & drop CSV file here</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeBulkModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmitBulk">
                        <i class="fa-solid fa-upload"></i> Start Import Process
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

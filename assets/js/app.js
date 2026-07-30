/**
 * MOU, Workshop & Student Placement Management - Front-end JavaScript Controller
 */

document.addEventListener('DOMContentLoaded', function () {
    // MOU File Upload Change Listener
    const fileInput = document.getElementById('report_file');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    if (fileInput && fileNameDisplay) {
        fileInput.addEventListener('change', function () {
            if (this.files && this.files.length > 0) {
                fileNameDisplay.innerHTML = `<i class="fa-solid fa-file-check" style="color: var(--success-text);"></i> ${this.files[0].name} (${(this.files[0].size / (1024 * 1024)).toFixed(2)} MB)`;
            } else {
                fileNameDisplay.innerHTML = 'Click or drag & drop MOU report (PDF, DOCX, PNG)';
            }
        });
    }

    // Workshop File Upload Change Listener
    const wsFileInput = document.getElementById('ws_report_file');
    const wsFileNameDisplay = document.getElementById('wsFileNameDisplay');
    if (wsFileInput && wsFileNameDisplay) {
        wsFileInput.addEventListener('change', function () {
            if (this.files && this.files.length > 0) {
                wsFileNameDisplay.innerHTML = `<i class="fa-solid fa-file-check" style="color: var(--success-text);"></i> ${this.files[0].name} (${(this.files[0].size / (1024 * 1024)).toFixed(2)} MB)`;
            } else {
                wsFileNameDisplay.innerHTML = 'Click or drag & drop Summary Report (PDF, DOCX, PNG)';
            }
        });
    }

    // Student Offer Letter File Listener
    const stFileInput = document.getElementById('st_offer_letter_file');
    const stFileNameDisplay = document.getElementById('stFileNameDisplay');
    if (stFileInput && stFileNameDisplay) {
        stFileInput.addEventListener('change', function () {
            if (this.files && this.files.length > 0) {
                stFileNameDisplay.innerHTML = `<i class="fa-solid fa-file-check" style="color: var(--success-text);"></i> ${this.files[0].name} (${(this.files[0].size / (1024 * 1024)).toFixed(2)} MB)`;
            } else {
                stFileNameDisplay.innerHTML = 'Click or drag & drop Offer Letter / Certificate (PDF, DOCX, PNG)';
            }
        });
    }

    // CSV File Listener
    const csvFileInput = document.getElementById('csv_file');
    const csvFileNameDisplay = document.getElementById('csvFileNameDisplay');
    if (csvFileInput && csvFileNameDisplay) {
        csvFileInput.addEventListener('change', function () {
            if (this.files && this.files.length > 0) {
                csvFileNameDisplay.innerHTML = `<i class="fa-solid fa-file-csv" style="color: #0284c7;"></i> ${this.files[0].name}`;
            } else {
                csvFileNameDisplay.innerHTML = 'Click or drag & drop CSV file here';
            }
        });
    }

    // Modal Backdrop click listeners
    const formModal = document.getElementById('mouFormModal');
    const viewModal = document.getElementById('mouViewModal');
    const wsFormModal = document.getElementById('workshopFormModal');
    const wsViewModal = document.getElementById('workshopViewModal');
    const stFormModal = document.getElementById('studentFormModal');
    const stViewModal = document.getElementById('studentViewModal');
    const bulkModal = document.getElementById('bulkUploadModal');

    if (formModal) formModal.addEventListener('click', function (e) { if (e.target === this) closeFormModal(); });
    if (viewModal) viewModal.addEventListener('click', function (e) { if (e.target === this) closeViewModal(); });
    if (wsFormModal) wsFormModal.addEventListener('click', function (e) { if (e.target === this) closeWorkshopFormModal(); });
    if (wsViewModal) wsViewModal.addEventListener('click', function (e) { if (e.target === this) closeWorkshopViewModal(); });
    if (stFormModal) stFormModal.addEventListener('click', function (e) { if (e.target === this) closeStudentFormModal(); });
    if (stViewModal) stViewModal.addEventListener('click', function (e) { if (e.target === this) closeStudentViewModal(); });
    if (bulkModal) bulkModal.addEventListener('click', function (e) { if (e.target === this) closeBulkModal(); });

    // Escape key press listener
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeFormModal();
            closeViewModal();
            closeWorkshopFormModal();
            closeWorkshopViewModal();
            closeStudentFormModal();
            closeStudentViewModal();
            closeBulkModal();
        }
    });

    // MOU Form Submit Handling
    const mouForm = document.getElementById('mouForm');
    if (mouForm) {
        mouForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const action = document.getElementById('formAction').value;
            const submitBtn = document.getElementById('btnSubmitForm');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

            fetch(`index.php?module=mou&action=${action}`, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                if (data.success) {
                    showToast(data.message, 'success');
                    closeFormModal();
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    showToast(data.message || 'Error saving MOU agreement.', 'error');
                }
            })
            .catch(err => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                showToast('Failed to connect to server.', 'error');
                console.error(err);
            });
        });
    }

    // Workshop Form Submit Handling
    const workshopForm = document.getElementById('workshopForm');
    if (workshopForm) {
        workshopForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const action = document.getElementById('wsFormAction').value;
            const submitBtn = document.getElementById('btnSubmitWsForm');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

            fetch(`index.php?module=workshop&action=${action}`, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                if (data.success) {
                    showToast(data.message, 'success');
                    closeWorkshopFormModal();
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    showToast(data.message || 'Error saving workshop.', 'error');
                }
            })
            .catch(err => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                showToast('Failed to connect to server.', 'error');
                console.error(err);
            });
        });
    }

    // Student Form Submit Handling
    const studentForm = document.getElementById('studentForm');
    if (studentForm) {
        studentForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const action = document.getElementById('stFormAction').value;
            const submitBtn = document.getElementById('btnSubmitStForm');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

            fetch(`index.php?module=placement&action=${action}`, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                if (data.success) {
                    showToast(data.message, 'success');
                    closeStudentFormModal();
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    showToast(data.message || 'Error saving student profile.', 'error');
                }
            })
            .catch(err => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                showToast('Failed to connect to server.', 'error');
                console.error(err);
            });
        });
    }

    // CSV Bulk Form Submit Handling
    const bulkForm = document.getElementById('bulkForm');
    if (bulkForm) {
        bulkForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitBtn = document.getElementById('btnSubmitBulk');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Importing Data...';

            fetch('index.php?module=placement&action=bulkUpload', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                if (data.success) {
                    showToast(data.message, 'success');
                    closeBulkModal();
                    setTimeout(() => window.location.reload(), 900);
                } else {
                    showToast(data.message || 'CSV Import failed.', 'error');
                }
            })
            .catch(err => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                showToast('Failed to process CSV file.', 'error');
                console.error(err);
            });
        });
    }
});

/* ==========================================================================
   MOU FUNCTIONS
   ========================================================================== */

function openAddModal() {
    const form = document.getElementById('mouForm');
    if (!form) return;
    form.reset();
    document.getElementById('modalTitle').innerHTML = '<i class="fa-solid fa-file-signature"></i> Add New MOU';
    document.getElementById('formAction').value = 'store';
    document.getElementById('mouId').value = '';
    document.getElementById('fileNameDisplay').innerHTML = 'Click or drag & drop MOU report (PDF, DOCX, PNG)';
    document.getElementById('existingFileContainer').style.display = 'none';

    const today = new Date();
    const expiry = new Date();
    expiry.setFullYear(today.getFullYear() + 3);
    document.getElementById('signed_date').value = formatDate(today);
    document.getElementById('expiry_date').value = formatDate(expiry);
    document.getElementById('status').value = 'Active';

    openModal('mouFormModal');
}

function closeFormModal() {
    closeModal('mouFormModal');
}

function viewMou(id) {
    fetch(`index.php?module=mou&action=getJson&id=${id}`)
        .then(res => res.json())
        .then(res => {
            if (!res.success) { showToast(res.message || 'Error fetching MOU data', 'error'); return; }
            const data = res.data;
            document.getElementById('viewMouId').innerText = `MOU #${data.id}`;
            document.getElementById('viewCompanyName').innerText = data.company_name;
            document.getElementById('viewContactPerson').innerText = data.contact_person || 'N/A';
            document.getElementById('viewEmail').innerText = data.email || 'N/A';
            document.getElementById('viewPhone').innerText = data.phone || 'N/A';
            document.getElementById('viewWebsite').innerHTML = data.website ? `<a href="${data.website}" target="_blank">${data.website} <i class="fa-solid fa-arrow-up-right-from-square"></i></a>` : 'N/A';
            document.getElementById('viewSignedDate').innerText = formatDateReadable(data.signed_date);
            document.getElementById('viewExpiryDate').innerText = formatDateReadable(data.expiry_date);
            document.getElementById('viewYear').innerHTML = `<span class="year-pill">${data.year}</span>`;
            document.getElementById('viewStatus').innerHTML = `<span class="status-badge status-${data.status.toLowerCase()}"><i class="fa-solid fa-circle status-dot"></i> ${data.status}</span>`;
            document.getElementById('viewAddress').innerText = data.address || 'No address provided.';
            document.getElementById('viewDescription').innerText = data.description || 'No specific purpose/description provided.';

            const reportContainer = document.getElementById('viewReportContainer');
            reportContainer.innerHTML = data.report_file ? `
                <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
                    <span style="font-weight:600; color:var(--neutral-800);"><i class="fa-solid fa-file-pdf" style="color:#ef4444; font-size:1.4rem;"></i> ${data.report_file}</span>
                    <a href="uploads/reports/${data.report_file}" target="_blank" class="btn btn-primary" style="padding:0.4rem 0.9rem; font-size:0.85rem;">
                        <i class="fa-solid fa-download"></i> View / Download Document
                    </a>
                </div>
            ` : `<span class="text-muted"><i class="fa-solid fa-circle-exclamation"></i> No report document uploaded for this MOU.</span>`;

            document.getElementById('viewEditBtn').onclick = function() { closeViewModal(); editMou(data.id); };
            openModal('mouViewModal');
        })
        .catch(err => { showToast('Unable to load MOU details.', 'error'); console.error(err); });
}

function closeViewModal() {
    closeModal('mouViewModal');
}

function editMou(id) {
    fetch(`index.php?module=mou&action=getJson&id=${id}`)
        .then(res => res.json())
        .then(res => {
            if (!res.success) { showToast(res.message || 'Error fetching MOU data', 'error'); return; }
            const data = res.data;
            document.getElementById('modalTitle').innerHTML = '<i class="fa-solid fa-pen-to-square"></i> Edit MOU Agreement';
            document.getElementById('formAction').value = 'update';
            document.getElementById('mouId').value = data.id;
            document.getElementById('company_name').value = data.company_name;
            document.getElementById('contact_person').value = data.contact_person || '';
            document.getElementById('email').value = data.email || '';
            document.getElementById('phone').value = data.phone || '';
            document.getElementById('website').value = data.website || '';
            document.getElementById('signed_date').value = data.signed_date;
            document.getElementById('expiry_date').value = data.expiry_date;
            document.getElementById('status').value = data.status;
            document.getElementById('description').value = data.description || '';
            document.getElementById('fileNameDisplay').innerHTML = 'Click or drag & drop to replace existing file';

            const existingContainer = document.getElementById('existingFileContainer');
            if (data.report_file) {
                document.getElementById('existingFileName').innerText = data.report_file;
                existingContainer.style.display = 'block';
            } else { existingContainer.style.display = 'none'; }

            openModal('mouFormModal');
        });
}

function confirmDelete(id, companyName) {
    if (confirm(`Are you sure you want to delete the MOU record for "${companyName}"?\nThis action cannot be undone.`)) {
        fetch(`index.php?module=mou&action=delete&id=${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json())
        .then(data => {
            if (data.success) { showToast(data.message, 'success'); setTimeout(() => window.location.reload(), 700); }
            else showToast(data.message || 'Failed to delete record.', 'error');
        });
    }
}

/* ==========================================================================
   WORKSHOP FUNCTIONS
   ========================================================================== */

function openAddWorkshopModal() {
    const form = document.getElementById('workshopForm');
    if (!form) return;
    form.reset();
    document.getElementById('workshopModalTitle').innerHTML = '<i class="fa-solid fa-graduation-cap"></i> Record Workshop / Seminar';
    document.getElementById('wsFormAction').value = 'store';
    document.getElementById('wsId').value = '';
    document.getElementById('wsFileNameDisplay').innerHTML = 'Click or drag & drop Summary Report (PDF, DOCX, PNG)';
    document.getElementById('wsExistingFileContainer').style.display = 'none';

    const today = new Date();
    document.getElementById('ws_held_on').value = formatDate(today);
    document.getElementById('ws_duration').value = '4';
    document.getElementById('ws_total_participants').value = '50';
    document.getElementById('ws_certificate').value = '1';

    openModal('workshopFormModal');
}

function closeWorkshopFormModal() {
    closeModal('workshopFormModal');
}

function viewWorkshop(id) {
    fetch(`index.php?module=workshop&action=getJson&id=${id}`)
        .then(res => res.json())
        .then(res => {
            if (!res.success) { showToast(res.message || 'Error fetching workshop data', 'error'); return; }
            const data = res.data;
            document.getElementById('viewWsId').innerText = `Activity #${data.id}`;
            document.getElementById('viewWsTitle').innerText = data.title;
            document.getElementById('viewWsInstructor').innerText = data.instructor_name || 'N/A';
            document.getElementById('viewWsCompany').innerText = data.company_name || 'N/A';
            document.getElementById('viewWsEmail').innerText = data.instructor_email || 'N/A';
            document.getElementById('viewWsVenue').innerText = data.venue || 'N/A';
            document.getElementById('viewWsHeldOn').innerText = formatDateReadable(data.held_on);
            document.getElementById('viewWsDuration').innerText = `${data.duration} hour(s)`;
            document.getElementById('viewWsParticipants').innerText = data.total_participants || '0';
            document.getElementById('viewWsCertificate').innerHTML = data.certificate == 1 
                ? '<span class="status-badge status-active"><i class="fa-solid fa-certificate"></i> Yes</span>'
                : '<span class="status-badge status-expired">No Certificate</span>';
            document.getElementById('viewWsDescription').innerText = data.description || 'No specific topic summary provided.';

            const reportContainer = document.getElementById('viewWsReportContainer');
            reportContainer.innerHTML = data.report_file ? `
                <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
                    <span style="font-weight:600; color:var(--neutral-800);"><i class="fa-solid fa-file-pdf" style="color:#ef4444; font-size:1.4rem;"></i> ${data.report_file}</span>
                    <a href="uploads/workshop_reports/${data.report_file}" target="_blank" class="btn btn-primary" style="padding:0.4rem 0.9rem; font-size:0.85rem;">
                        <i class="fa-solid fa-download"></i> View / Download Document
                    </a>
                </div>
            ` : `<span class="text-muted"><i class="fa-solid fa-circle-exclamation"></i> No summary report document uploaded.</span>`;

            document.getElementById('viewWsEditBtn').onclick = function() { closeWorkshopViewModal(); editWorkshop(data.id); };
            openModal('workshopViewModal');
        });
}

function closeWorkshopViewModal() {
    closeModal('workshopViewModal');
}

function editWorkshop(id) {
    fetch(`index.php?module=workshop&action=getJson&id=${id}`)
        .then(res => res.json())
        .then(res => {
            if (!res.success) { showToast(res.message || 'Error fetching workshop data', 'error'); return; }
            const data = res.data;
            document.getElementById('workshopModalTitle').innerHTML = '<i class="fa-solid fa-pen-to-square"></i> Edit Workshop / Seminar';
            document.getElementById('wsFormAction').value = 'update';
            document.getElementById('wsId').value = data.id;
            document.getElementById('ws_title').value = data.title;
            document.getElementById('ws_instructor_name').value = data.instructor_name || '';
            document.getElementById('ws_company_name').value = data.company_name || '';
            document.getElementById('ws_instructor_email').value = data.instructor_email || '';
            document.getElementById('ws_venue').value = data.venue || '';
            document.getElementById('ws_held_on').value = data.held_on;
            document.getElementById('ws_duration').value = data.duration;
            document.getElementById('ws_total_participants').value = data.total_participants;
            document.getElementById('ws_certificate').value = data.certificate;
            document.getElementById('ws_description').value = data.description || '';
            document.getElementById('wsFileNameDisplay').innerHTML = 'Click or drag & drop to replace summary report';

            const existingContainer = document.getElementById('wsExistingFileContainer');
            if (data.report_file) {
                document.getElementById('wsExistingFileName').innerText = data.report_file;
                existingContainer.style.display = 'block';
            } else { existingContainer.style.display = 'none'; }

            document.getElementById('workshopFormModal').style.display = 'flex';
        });
}

function confirmDeleteWorkshop(id, title) {
    if (confirm(`Are you sure you want to delete the workshop record "${title}"?\nThis action cannot be undone.`)) {
        fetch(`index.php?module=workshop&action=delete&id=${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json())
        .then(data => {
            if (data.success) { showToast(data.message, 'success'); setTimeout(() => window.location.reload(), 700); }
            else showToast(data.message || 'Failed to delete workshop.', 'error');
        });
    }
}

/* ==========================================================================
   STUDENT PLACEMENT FUNCTIONS
   ========================================================================== */

function openAddStudentModal() {
    const form = document.getElementById('studentForm');
    if (!form) return;
    form.reset();
    document.getElementById('studentModalTitle').innerHTML = '<i class="fa-solid fa-user-plus"></i> Add Student Placement Profile';
    document.getElementById('stFormAction').value = 'store';
    document.getElementById('stId').value = '';
    document.getElementById('stFileNameDisplay').innerHTML = 'Click or drag & drop Offer Letter / Certificate (PDF, DOCX, PNG)';
    document.getElementById('stExistingFileContainer').style.display = 'none';

    document.getElementById('st_gr_no').value = '';
    document.getElementById('st_passing_year').value = new Date().getFullYear();
    document.getElementById('st_semester').value = '8';
    document.getElementById('st_placement_status').value = 'Unplaced';
    togglePlacementFields('Unplaced');

    openModal('studentFormModal');
}

function closeStudentFormModal() {
    closeModal('studentFormModal');
}

function openBulkModal() {
    openModal('bulkUploadModal');
}

function closeBulkModal() {
    closeModal('bulkUploadModal');
}

function togglePlacementFields(status) {
    const lblCompany = document.getElementById('lblCompanyName');
    const lblDesignation = document.getElementById('lblDesignation');
    
    if (status === 'Higher Studies') {
        if (lblCompany) lblCompany.innerText = 'University / Institute Name';
        if (lblDesignation) lblDesignation.innerText = 'Degree Program / Specialization';
    } else if (status === 'Business') {
        if (lblCompany) lblCompany.innerText = 'Startup / Business Name';
        if (lblDesignation) lblDesignation.innerText = 'Role / Title (e.g. Founder)';
    } else {
        if (lblCompany) lblCompany.innerText = 'Company Name';
        if (lblDesignation) lblDesignation.innerText = 'Job Designation';
    }
}

function viewStudent(id) {
    fetch(`index.php?module=placement&action=getJson&id=${id}`)
        .then(res => res.json())
        .then(res => {
            if (!res.success) { showToast(res.message || 'Error fetching student profile', 'error'); return; }
            const data = res.data;
            document.getElementById('viewStGrNo').innerText = data.gr_no ? `GR: ${data.gr_no}` : 'No GR No';
            document.getElementById('viewStEnroll').innerText = `Enroll #${data.enroll_no}`;
            document.getElementById('viewStGrNoVal').innerText = data.gr_no ? data.gr_no : 'N/A';
            document.getElementById('viewStEnrollVal').innerText = data.enroll_no ? data.enroll_no : 'N/A';
            document.getElementById('viewStName').innerText = data.name;
            document.getElementById('viewStDepartment').innerText = data.department;
            document.getElementById('viewStCgpa').innerText = `Sem ${data.semester} | CGPA ${data.cgpa}`;
            document.getElementById('viewStYear').innerHTML = `<span class="year-pill">${data.passing_year}</span>`;
            document.getElementById('viewStGenderDob').innerText = `${data.gender} ${data.dob ? '| DOB: ' + formatDateReadable(data.dob) : ''}`;
            document.getElementById('viewStEmail').innerText = data.email || 'N/A';
            document.getElementById('viewStPhone').innerText = data.phone || 'N/A';

            let statusClass = 'status-expired';
            if (data.placement_status === 'Placed') statusClass = 'status-active';
            else if (data.placement_status === 'Internship') statusClass = 'status-internship';
            else if (data.placement_status === 'Higher Studies') statusClass = 'status-higher';
            else if (data.placement_status === 'Business') statusClass = 'status-business';

            document.getElementById('viewStStatus').innerHTML = `<span class="status-badge ${statusClass}"><i class="fa-solid fa-circle status-dot"></i> ${data.placement_status}</span>`;
            document.getElementById('viewStPackage').innerHTML = data.package_lpa ? `<span class="package-tag">${data.package_lpa} LPA</span>` : 'N/A';
            document.getElementById('viewStCompany').innerText = data.company_name || 'N/A';
            document.getElementById('viewStDesignation').innerText = data.designation || 'N/A';
            document.getElementById('viewStSkills').innerText = data.skills || 'No skills recorded.';
            document.getElementById('viewStAddress').innerText = data.address || 'No address recorded.';

            const reportContainer = document.getElementById('viewStOfferContainer');
            reportContainer.innerHTML = data.offer_letter_file ? `
                <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
                    <span style="font-weight:600; color:var(--neutral-800);"><i class="fa-solid fa-file-contract" style="color:#16a34a; font-size:1.4rem;"></i> ${data.offer_letter_file}</span>
                    <a href="uploads/placement_documents/${data.offer_letter_file}" target="_blank" class="btn btn-primary" style="padding:0.4rem 0.9rem; font-size:0.85rem;">
                        <i class="fa-solid fa-download"></i> View / Download Offer Document
                    </a>
                </div>
            ` : `<span class="text-muted"><i class="fa-solid fa-circle-exclamation"></i> No offer letter document uploaded.</span>`;

            document.getElementById('viewStEditBtn').onclick = function() { closeStudentViewModal(); editStudent(data.id); };
            openModal('studentViewModal');
        });
}

function closeStudentViewModal() {
    closeModal('studentViewModal');
}

function editStudent(id) {
    fetch(`index.php?module=placement&action=getJson&id=${id}`)
        .then(res => res.json())
        .then(res => {
            if (!res.success) { showToast(res.message || 'Error fetching student data', 'error'); return; }
            const data = res.data;
            document.getElementById('studentModalTitle').innerHTML = '<i class="fa-solid fa-pen-to-square"></i> Edit Student Profile';
            document.getElementById('stFormAction').value = 'update';
            document.getElementById('stId').value = data.id;

            document.getElementById('st_gr_no').value = data.gr_no || '';
            document.getElementById('st_enroll_no').value = data.enroll_no;
            document.getElementById('st_name').value = data.name;
            document.getElementById('st_email').value = data.email || '';
            document.getElementById('st_phone').value = data.phone || '';
            document.getElementById('st_gender').value = data.gender;
            document.getElementById('st_dob').value = data.dob || '';
            document.getElementById('st_department').value = data.department;
            document.getElementById('st_semester').value = data.semester;
            document.getElementById('st_cgpa').value = data.cgpa;
            document.getElementById('st_passing_year').value = data.passing_year;
            document.getElementById('st_placement_status').value = data.placement_status;
            togglePlacementFields(data.placement_status);

            document.getElementById('st_company_name').value = data.company_name || '';
            document.getElementById('st_designation').value = data.designation || '';
            document.getElementById('st_package_lpa').value = data.package_lpa || '';
            document.getElementById('st_skills').value = data.skills || '';
            document.getElementById('stFileNameDisplay').innerHTML = 'Click or drag & drop to replace existing offer letter';

            const existingContainer = document.getElementById('stExistingFileContainer');
            if (data.offer_letter_file) {
                document.getElementById('stExistingFileName').innerText = data.offer_letter_file;
                existingContainer.style.display = 'block';
            } else { existingContainer.style.display = 'none'; }

            openModal('studentFormModal');
        });
}

function confirmDeleteStudent(id, studentName) {
    if (confirm(`Are you sure you want to delete the student record for "${studentName}"?\nThis action cannot be undone.`)) {
        fetch(`index.php?module=placement&action=delete&id=${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json())
        .then(data => {
            if (data.success) { showToast(data.message, 'success'); setTimeout(() => window.location.reload(), 700); }
            else showToast(data.message || 'Failed to delete student.', 'error');
        });
    }
}

/* ==========================================================================
   UTILITY HELPERS & GLOBAL ATTACHMENTS
   ========================================================================== */

/** Lock page scroll and show a modal backdrop */
function openModal(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.style.display = 'flex';
    document.body.classList.add('modal-open');
}

/** Close a modal backdrop and restore page scroll */
function closeModal(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.style.display = 'none';
    // Only remove class if no other modals are open
    const anyOpen = document.querySelector('.modal-backdrop[style*="flex"]');
    if (!anyOpen) document.body.classList.remove('modal-open');
}

function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');
    if (!container) return;
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-toast`;
    toast.style.cssText = `
        position: fixed; bottom: 20px; right: 20px; z-index: 2000;
        min-width: 280px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); animation: slideUp 0.3s ease-out;
    `;
    toast.innerHTML = `<i class="fa-solid fa-${type === 'success' ? 'circle-check' : 'circle-exclamation'}"></i> ${message}`;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0'; toast.style.transition = 'opacity 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}

function formatDate(d) {
    let month = '' + (d.getMonth() + 1), day = '' + d.getDate(), year = d.getFullYear();
    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;
    return [year, month, day].join('-');
}

function formatDateReadable(dateStr) {
    if (!dateStr) return 'N/A';
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

// Explicit Window Bindings for Inline Event Handlers
window.openAddModal = openAddModal;
window.closeFormModal = closeFormModal;
window.viewMou = viewMou;
window.closeViewModal = closeViewModal;
window.editMou = editMou;
window.confirmDelete = confirmDelete;

window.openAddWorkshopModal = openAddWorkshopModal;
window.closeWorkshopFormModal = closeWorkshopFormModal;
window.viewWorkshop = viewWorkshop;
window.closeWorkshopViewModal = closeWorkshopViewModal;
window.editWorkshop = editWorkshop;
window.confirmDeleteWorkshop = confirmDeleteWorkshop;

window.openAddStudentModal = openAddStudentModal;
window.closeStudentFormModal = closeStudentFormModal;
window.openBulkModal = openBulkModal;
window.closeBulkModal = closeBulkModal;
window.togglePlacementFields = togglePlacementFields;
window.viewStudent = viewStudent;
window.closeStudentViewModal = closeStudentViewModal;
window.editStudent = editStudent;
window.confirmDeleteStudent = confirmDeleteStudent;
window.showToast = showToast;

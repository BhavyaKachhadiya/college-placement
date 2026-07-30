<?php
/**
 * Company Placement Drive Modal Form (Add & Edit)
 * Used by Admin to manage company vacancies details
 */
?>

<!-- Company Form Modal -->
<div class="modal-backdrop" id="companyModalBackdrop" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 1050; opacity: 0; transition: opacity 0.25s ease;" onclick="closeCompanyModal()"></div>

<div class="modal-dialog" id="companyModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -48%) scale(0.96); width: 92%; max-width: 680px; max-height: 90vh; background: #ffffff; border-radius: var(--radius-xl); box-shadow: var(--shadow-xl); border: 1px solid var(--neutral-200); z-index: 1051; overflow-y: auto; opacity: 0; transition: all 0.25s ease;">
    
    <!-- Modal Header -->
    <div style="padding: 1.25rem 1.75rem; border-bottom: 1px solid var(--neutral-200); display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; background: #ffffff; z-index: 10;">
        <h3 id="companyModalTitle" style="font-family: var(--font-display); font-size: 1.25rem; font-weight: 800; color: var(--neutral-900); margin: 0; display: flex; align-items: center; gap: 0.6rem;">
            <i class="fa-solid fa-building-circle-check" style="color: var(--brand-600);"></i> Add New Company Placement Drive
        </h3>
        <button type="button" class="btn-close" onclick="closeCompanyModal()" style="background: none; border: none; font-size: 1.25rem; color: var(--neutral-400); cursor: pointer; padding: 0.25rem; border-radius: var(--radius-sm); transition: color 0.15s;" onmouseover="this.style.color='var(--neutral-800)'" onmouseout="this.style.color='var(--neutral-400)'">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- Modal Form -->
    <form action="index.php?module=company&action=store" method="POST" id="companyForm" style="padding: 1.75rem;">
        <input type="hidden" name="module" value="company">
        <input type="hidden" name="action" id="companyFormAction" value="store">
        <input type="hidden" name="id" id="companyId" value="">

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
            
            <!-- Company Name -->
            <div style="grid-column: 1 / -1;">
                <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                    Company Name <span style="color: var(--rose-600);">*</span>
                </label>
                <input type="text" name="company_name" id="comp_name" class="form-control" placeholder="e.g. Tech Corp Solutions" required style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-300); border-radius: var(--radius-sm);">
            </div>

            <!-- Job Role / Title -->
            <div>
                <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                    Job Role / Position Title <span style="color: var(--rose-600);">*</span>
                </label>
                <input type="text" name="job_role" id="comp_job_role" class="form-control" placeholder="e.g. Software Engineer Trainee" required style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-300); border-radius: var(--radius-sm);">
            </div>

            <!-- Industry -->
            <div>
                <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                    Industry Sector
                </label>
                <select name="industry" id="comp_industry" class="form-control" style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-300); border-radius: var(--radius-sm);">
                    <option value="IT & Software">IT &amp; Software Services</option>
                    <option value="Data & AI">Data Analytics &amp; Artificial Intelligence</option>
                    <option value="Cybersecurity">Cybersecurity &amp; Cloud</option>
                    <option value="Robotics & Embedded Systems">Robotics &amp; Embedded Systems</option>
                    <option value="Electronics & Communication">Electronics &amp; Semiconductors</option>
                    <option value="Mechanical & Manufacturing">Mechanical &amp; Manufacturing</option>
                    <option value="Civil & Infrastructure">Civil &amp; Infrastructure</option>
                    <option value="Banking & Finance">Banking, Finance &amp; Fintech</option>
                </select>
            </div>

            <!-- Number of Vacancies -->
            <div>
                <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                    Open Vacancies Count <span style="color: var(--rose-600);">*</span>
                </label>
                <input type="number" name="vacancies" id="comp_vacancies" min="1" value="5" class="form-control" required style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-300); border-radius: var(--radius-sm);">
            </div>

            <!-- Package LPA -->
            <div>
                <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                    Package CTC (LPA)
                </label>
                <input type="number" step="0.01" min="0" name="package_lpa" id="comp_package_lpa" placeholder="e.g. 12.50" class="form-control" style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-300); border-radius: var(--radius-sm);">
            </div>

            <!-- Job Location -->
            <div>
                <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                    Job Location
                </label>
                <input type="text" name="location" id="comp_location" placeholder="e.g. GIFT City, Gandhinagar / Hybrid" class="form-control" style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-300); border-radius: var(--radius-sm);">
            </div>

            <!-- Drive Status -->
            <div>
                <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                    Placement Drive Status
                </label>
                <select name="status" id="comp_status" class="form-control" style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-300); border-radius: var(--radius-sm);">
                    <option value="Active">Active (Accepting Applications)</option>
                    <option value="Upcoming">Upcoming (Announcement Only)</option>
                    <option value="Closed">Closed (Registration Ended)</option>
                </select>
            </div>

            <!-- Application Deadline -->
            <div>
                <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                    Application Deadline
                </label>
                <input type="date" name="deadline" id="comp_deadline" class="form-control" style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-300); border-radius: var(--radius-sm);">
            </div>

            <!-- Contact Email -->
            <div>
                <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                    Contact HR / Recruitment Email
                </label>
                <input type="email" name="contact_email" id="comp_contact_email" placeholder="e.g. careers@company.com" class="form-control" style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-300); border-radius: var(--radius-sm);">
            </div>

            <!-- Apply Link -->
            <div style="grid-column: 1 / -1;">
                <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                    Online Application / Portal Link
                </label>
                <input type="url" name="apply_link" id="comp_apply_link" placeholder="https://company.com/careers/freshers" class="form-control" style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-300); border-radius: var(--radius-sm);">
            </div>

            <!-- Eligibility Criteria -->
            <div style="grid-column: 1 / -1;">
                <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                    Eligibility Criteria
                </label>
                <input type="text" name="eligibility" id="comp_eligibility" placeholder="e.g. B.E. Computer & IT, Minimum 6.5 CGPA, No Active Backlogs" class="form-control" style="width: 100%; padding: 0.7rem 0.9rem; border: 1px solid var(--neutral-300); border-radius: var(--radius-sm);">
            </div>

            <!-- Job Description -->
            <div style="grid-column: 1 / -1;">
                <label style="display: block; font-weight: 600; font-size: 0.85rem; color: var(--neutral-700); margin-bottom: 0.4rem;">
                    Job Description &amp; Drive Overview
                </label>
                <textarea name="description" id="comp_description" rows="3" placeholder="Provide details about responsibilities, selection process stages, technical requirements..." class="form-control" style="width: 100%; padding: 0.75rem 0.9rem; border: 1px solid var(--neutral-300); border-radius: var(--radius-sm); font-family: var(--font-primary);"></textarea>
            </div>

        </div>

        <!-- Form Buttons -->
        <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid var(--neutral-200); padding-top: 1.25rem;">
            <button type="button" class="btn btn-light" onclick="closeCompanyModal()">Cancel</button>
            <button type="submit" class="btn btn-primary" id="companyModalSubmitBtn">
                <i class="fa-solid fa-floppy-disk"></i> Save Drive
            </button>
        </div>
    </form>

</div>

<script>
function openAddCompanyModal() {
    document.getElementById('companyForm').reset();
    document.getElementById('companyForm').action = 'index.php?module=company&action=store';
    document.getElementById('companyFormAction').value = 'store';
    document.getElementById('companyId').value = '';
    document.getElementById('companyModalTitle').innerHTML = '<i class="fa-solid fa-building-circle-check" style="color: var(--brand-600);"></i> Add New Company Placement Drive';
    document.getElementById('companyModalSubmitBtn').innerHTML = '<i class="fa-solid fa-plus"></i> Save Drive';
    
    showCompanyModal();
}

function editCompanyDrive(id) {
    fetch('index.php?module=company&action=getJson&id=' + id)
        .then(response => response.json())
        .then(res => {
            if (res.success && res.data) {
                const data = res.data;
                document.getElementById('companyForm').action = 'index.php?module=company&action=update';
                document.getElementById('companyFormAction').value = 'update';
                document.getElementById('companyId').value = data.id;
                
                document.getElementById('comp_name').value = data.company_name || '';
                document.getElementById('comp_job_role').value = data.job_role || '';
                document.getElementById('comp_industry').value = data.industry || 'IT & Software';
                document.getElementById('comp_vacancies').value = data.vacancies || 1;
                document.getElementById('comp_package_lpa').value = data.package_lpa || '';
                document.getElementById('comp_location').value = data.location || '';
                document.getElementById('comp_status').value = data.status || 'Active';
                document.getElementById('comp_deadline').value = data.deadline || '';
                document.getElementById('comp_contact_email').value = data.contact_email || '';
                document.getElementById('comp_apply_link').value = data.apply_link || '';
                document.getElementById('comp_eligibility').value = data.eligibility || '';
                document.getElementById('comp_description').value = data.description || '';
                
                document.getElementById('companyModalTitle').innerHTML = '<i class="fa-solid fa-pen-to-square" style="color: var(--brand-600);"></i> Edit Company Placement Drive';
                document.getElementById('companyModalSubmitBtn').innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Update Drive';
                
                showCompanyModal();
            } else {
                alert('Error loading company drive details: ' + (res.message || 'Unknown error'));
            }
        })
        .catch(err => alert('Failed to fetch drive data.'));
}

function showCompanyModal() {
    const backdrop = document.getElementById('companyModalBackdrop');
    const dialog = document.getElementById('companyModal');
    
    backdrop.style.display = 'block';
    dialog.style.display = 'block';
    
    setTimeout(() => {
        backdrop.style.opacity = '1';
        dialog.style.opacity = '1';
        dialog.style.transform = 'translate(-50%, -50%) scale(1)';
    }, 10);
}

function closeCompanyModal() {
    const backdrop = document.getElementById('companyModalBackdrop');
    const dialog = document.getElementById('companyModal');
    
    backdrop.style.opacity = '0';
    dialog.style.opacity = '0';
    dialog.style.transform = 'translate(-50%, -48%) scale(0.96)';
    
    setTimeout(() => {
        backdrop.style.display = 'none';
        dialog.style.display = 'none';
    }, 250);
}
</script>

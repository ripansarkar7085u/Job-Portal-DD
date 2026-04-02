<?php
// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Get company info from session
$companyName = $_SESSION['company_name'] ?? 'Company';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Post New Job - CareerHunt</title>
    <link rel="stylesheet" href="css/company.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>
    <div class="company-container" id="companyDashboard">
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="main-header">
                <div class="header-left">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1 class="page-title" id="pageTitle">Post New Job</h1>
                </div>
                <div class="header-right">
                    <button class="notification-btn" id="notificationBtn">
                        <i class="bi bi-bell-fill"></i>
                        <span class="notification-badge" id="notificationCount">5</span>
                    </button>
                    <div class="profile-dropdown">
                        <button class="profile-btn" id="profileBtn">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($companyName); ?>&background=0d47a1&color=fff" alt="Company" id="headerAvatar">
                            <span id="headerCompanyName"><?php echo htmlspecialchars($companyName); ?></span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu" id="profileDropdown">
                            <a href="profile.php" class="dropdown-item">
                                <i class="bi bi-building"></i> Company Profile
                            </a>
                            <a href="settings.php" class="dropdown-item">
                                <i class="bi bi-gear"></i> Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <button class="dropdown-item text-danger" id="dropdownLogout">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Job Create Content -->
            <section class="content-section">
                <div class="page-header">
                    <div>
                        <h1 id="formTitle">Post New Job</h1>
                        <p>Fill in the details below to create a new job posting</p>
                    </div>
                    <a href="jobs.php" class="btn btn-outline">
                        <i class="bi bi-arrow-left"></i> Back to Jobs
                    </a>
                </div>

                <form id="jobForm" class="job-form">
                    <div class="form-grid">
                        <!-- Basic Information -->
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h2><i class="bi bi-info-circle"></i> Basic Information</h2>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="jobTitle">Job Title <span class="required">*</span></label>
                                    <input type="text" id="jobTitle" class="form-control" placeholder="e.g., Senior Frontend Developer" required>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="jobType">Employment Type <span class="required">*</span></label>
                                        <select id="jobType" class="form-control" required>
                                            <option value="">Select Type</option>
                                            <option value="full-time">Full-time</option>
                                            <option value="part-time">Part-time</option>
                                            <option value="contract">Contract</option>
                                            <option value="freelance">Freelance</option>
                                            <option value="internship">Internship</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="experienceLevel">Experience Level <span class="required">*</span></label>
                                        <select id="experienceLevel" class="form-control" required>
                                            <option value="">Select Level</option>
                                            <option value="entry">Entry Level</option>
                                            <option value="mid">Mid Level</option>
                                            <option value="senior">Senior Level</option>
                                            <option value="lead">Lead / Manager</option>
                                            <option value="executive">Executive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="category">Job Category <span class="required">*</span></label>
                                        <select id="category" class="form-control" required>
                                            <option value="">Select Category</option>
                                            <option value="engineering">Engineering</option>
                                            <option value="design">Design</option>
                                            <option value="product">Product</option>
                                            <option value="marketing">Marketing</option>
                                            <option value="sales">Sales</option>
                                            <option value="hr">Human Resources</option>
                                            <option value="finance">Finance</option>
                                            <option value="operations">Operations</option>
                                            <option value="customer-service">Customer Service</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="positions">Number of Positions</label>
                                        <input type="number" id="positions" class="form-control" value="1" min="1">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Location & Work Style -->
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h2><i class="bi bi-geo-alt"></i> Location & Work Style</h2>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="workStyle">Work Style <span class="required">*</span></label>
                                    <select id="workStyle" class="form-control" required>
                                        <option value="">Select Work Style</option>
                                        <option value="remote">Remote</option>
                                        <option value="onsite">On-site</option>
                                        <option value="hybrid">Hybrid</option>
                                    </select>
                                </div>

                                <div class="form-group" id="locationGroup">
                                    <label for="location">Location <span class="required">*</span></label>
                                    <input type="text" id="location" class="form-control" placeholder="e.g., San Francisco, CA">
                                    <p class="form-hint">Enter city, state or "Anywhere" for remote positions</p>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="timezone">Preferred Timezone</label>
                                        <select id="timezone" class="form-control">
                                            <option value="">Any Timezone</option>
                                            <option value="pst">PST (Pacific)</option>
                                            <option value="mst">MST (Mountain)</option>
                                            <option value="cst">CST (Central)</option>
                                            <option value="est">EST (Eastern)</option>
                                            <option value="gmt">GMT</option>
                                            <option value="cet">CET (Central Europe)</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="visaSponsorship">Visa Sponsorship</label>
                                        <select id="visaSponsorship" class="form-control">
                                            <option value="no">Not Available</option>
                                            <option value="yes">Available</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Compensation -->
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h2><i class="bi bi-currency-dollar"></i> Compensation</h2>
                            </div>
                            <div class="card-body">
                                <div class="form-check">
                                    <input type="checkbox" id="showSalary" checked>
                                    <label for="showSalary">Display salary on job posting</label>
                                </div>

                                <div class="form-row mt-3" id="salarySection">
                                    <div class="form-group">
                                        <label for="salaryMin">Minimum Salary <span class="required">*</span></label>
                                        <div class="input-with-icon">
                                            <i class="bi bi-currency-dollar"></i>
                                            <input type="number" id="salaryMin" class="form-control" placeholder="80000">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="salaryMax">Maximum Salary <span class="required">*</span></label>
                                        <div class="input-with-icon">
                                            <i class="bi bi-currency-dollar"></i>
                                            <input type="number" id="salaryMax" class="form-control" placeholder="120000">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="salaryPeriod">Salary Period</label>
                                        <select id="salaryPeriod" class="form-control">
                                            <option value="year">Per Year</option>
                                            <option value="month">Per Month</option>
                                            <option value="hour">Per Hour</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="currency">Currency</label>
                                        <select id="currency" class="form-control">
                                            <option value="USD">USD ($)</option>
                                            <option value="EUR">EUR (€)</option>
                                            <option value="GBP">GBP (£)</option>
                                            <option value="CAD">CAD ($)</option>
                                            <option value="AUD">AUD ($)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="benefits">Additional Benefits</label>
                                    <div class="benefits-checkboxes">
                                        <div class="form-check">
                                            <input type="checkbox" id="benefit1" value="health">
                                            <label for="benefit1">Health Insurance</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" id="benefit2" value="dental">
                                            <label for="benefit2">Dental & Vision</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" id="benefit3" value="401k">
                                            <label for="benefit3">401(k) Match</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" id="benefit4" value="pto">
                                            <label for="benefit4">Paid Time Off</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" id="benefit5" value="remote">
                                            <label for="benefit5">Remote Work</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" id="benefit6" value="equity">
                                            <label for="benefit6">Stock Options</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Job Description -->
                        <div class="dashboard-card full-width">
                            <div class="card-header">
                                <h2><i class="bi bi-file-text"></i> Job Description</h2>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="description">Job Description <span class="required">*</span></label>
                                    <textarea id="description" class="form-control" rows="8" placeholder="Describe the role, responsibilities, and what makes this opportunity exciting..." required></textarea>
                                    <p class="form-hint">Tip: Include information about day-to-day responsibilities, team structure, and growth opportunities</p>
                                </div>

                                <div class="form-group">
                                    <label for="requirements">Requirements <span class="required">*</span></label>
                                    <textarea id="requirements" class="form-control" rows="6" placeholder="List the required skills, experience, and qualifications..." required></textarea>
                                    <p class="form-hint">Use bullet points (start each line with •, -, or *)</p>
                                </div>

                                <div class="form-group">
                                    <label for="niceToHave">Nice to Have</label>
                                    <textarea id="niceToHave" class="form-control" rows="4" placeholder="List any preferred but not required skills..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Skills -->
                        <div class="dashboard-card full-width">
                            <div class="card-header">
                                <h2><i class="bi bi-tags"></i> Skills & Tags</h2>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="skillsInput">Required Skills</label>
                                    <div class="skills-input-wrapper">
                                        <input type="text" id="skillsInput" class="form-control" placeholder="Type a skill and press Enter">
                                        <div class="skills-tags" id="skillsTags">
                                            <span class="skill-tag">JavaScript <button type="button">&times;</button></span>
                                            <span class="skill-tag">React <button type="button">&times;</button></span>
                                            <span class="skill-tag">TypeScript <button type="button">&times;</button></span>
                                        </div>
                                    </div>
                                    <p class="form-hint">Press Enter to add a skill tag</p>
                                </div>

                                <div class="suggested-skills">
                                    <span class="suggested-label">Suggested:</span>
                                    <button type="button" class="suggested-skill">Node.js</button>
                                    <button type="button" class="suggested-skill">CSS</button>
                                    <button type="button" class="suggested-skill">HTML</button>
                                    <button type="button" class="suggested-skill">Git</button>
                                    <button type="button" class="suggested-skill">REST API</button>
                                    <button type="button" class="suggested-skill">GraphQL</button>
                                </div>
                            </div>
                        </div>

                        <!-- Application Settings -->
                        <div class="dashboard-card full-width">
                            <div class="card-header">
                                <h2><i class="bi bi-gear"></i> Application Settings</h2>
                            </div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="applicationDeadline">Application Deadline</label>
                                        <input type="date" id="applicationDeadline" class="form-control">
                                        <p class="form-hint">Leave empty for no deadline</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="applicationMethod">Application Method</label>
                                        <select id="applicationMethod" class="form-control">
                                            <option value="internal">Apply on CareerHunt</option>
                                            <option value="external">External URL</option>
                                            <option value="email">Email</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group" id="externalUrlGroup" style="display: none;">
                                    <label for="externalUrl">External Application URL</label>
                                    <input type="url" id="externalUrl" class="form-control" placeholder="https://...">
                                </div>

                                <div class="form-group">
                                    <label>Required Documents</label>
                                    <div class="documents-checkboxes">
                                        <div class="form-check">
                                            <input type="checkbox" id="doc1" value="resume" checked>
                                            <label for="doc1">Resume/CV</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" id="doc2" value="cover-letter">
                                            <label for="doc2">Cover Letter</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" id="doc3" value="portfolio">
                                            <label for="doc3">Portfolio</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions-sticky">
                        <div class="actions-left">
                            <button type="button" class="btn btn-outline" id="saveDraftBtn">
                                <i class="bi bi-save"></i> Save as Draft
                            </button>
                        </div>
                        <div class="actions-right">
                            <button type="button" class="btn btn-outline" id="previewBtn">
                                <i class="bi bi-eye"></i> Preview
                            </button>
                            <button type="submit" class="btn btn-accent">
                                <i class="bi bi-send"></i> Publish Job
                            </button>
                        </div>
                    </div>
                </form>
            </section>
        </main>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="modal fade" id="jobPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Job Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="jobPreviewBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/company.js?v=<?php echo filemtime(__DIR__ . '/js/company.js'); ?>"></script>
    <script>
        // Skills input functionality
        const skillsInput = document.getElementById('skillsInput');
        const skillsTags = document.getElementById('skillsTags');

        skillsInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const skill = this.value.trim();
                if (skill) {
                    addSkillTag(skill);
                    this.value = '';
                }
            }
        });

        function addSkillTag(skill) {
            const tag = document.createElement('span');
            tag.className = 'skill-tag';
            tag.innerHTML = `${skill} <button type="button">&times;</button>`;
            tag.querySelector('button').addEventListener('click', () => tag.remove());
            skillsTags.appendChild(tag);
        }

        // Suggested skills
        document.querySelectorAll('.suggested-skill').forEach(btn => {
            btn.addEventListener('click', function() {
                addSkillTag(this.textContent);
                this.remove();
            });
        });

        // Remove existing skill tags
        document.querySelectorAll('.skill-tag button').forEach(btn => {
            btn.addEventListener('click', () => btn.parentElement.remove());
        });

        // Show/hide salary section
        document.getElementById('showSalary').addEventListener('change', function() {
            document.getElementById('salarySection').style.display = this.checked ? 'grid' : 'none';
        });

        // Application method change
        document.getElementById('applicationMethod').addEventListener('change', function() {
            document.getElementById('externalUrlGroup').style.display = 
                this.value === 'external' ? 'block' : 'none';
        });

        // Work style change - update location field
        document.getElementById('workStyle').addEventListener('change', function() {
            const locationInput = document.getElementById('location');
            if (this.value === 'remote') {
                locationInput.value = 'Remote';
                locationInput.placeholder = 'Remote or specify region';
            } else {
                locationInput.value = '';
                locationInput.placeholder = 'e.g., San Francisco, CA';
            }
        });

        let editingJobId = null;

        function getFormPayload(status) {
            return {
                id: editingJobId,
                title: document.getElementById('jobTitle').value.trim(),
                employment_type: document.getElementById('jobType').value,
                experience_level: document.getElementById('experienceLevel').value,
                category: document.getElementById('category').value,
                work_style: document.getElementById('workStyle').value,
                location: document.getElementById('location').value.trim(),
                salary_min: document.getElementById('salaryMin').value.trim(),
                salary_max: document.getElementById('salaryMax').value.trim(),
                salary_period: document.getElementById('salaryPeriod').value,
                currency: document.getElementById('currency').value,
                salary_visible: document.getElementById('showSalary').checked ? 1 : 0,
                description: document.getElementById('description').value.trim(),
                requirements: document.getElementById('requirements').value.trim(),
                status
            };
        }

        function setFormDisabled(disabled) {
            document.querySelectorAll('#jobForm button, #jobForm input, #jobForm textarea, #jobForm select').forEach((element) => {
                element.disabled = disabled;
            });
        }

        function populateForm(job) {
            document.getElementById('jobTitle').value = job.title || '';
            document.getElementById('jobType').value = job.employment_type || '';
            document.getElementById('experienceLevel').value = job.experience_level || '';
            document.getElementById('category').value = job.category || '';
            document.getElementById('workStyle').value = job.work_style || '';
            document.getElementById('location').value = job.location || '';
            document.getElementById('salaryMin').value = job.salary_min ?? '';
            document.getElementById('salaryMax').value = job.salary_max ?? '';
            document.getElementById('salaryPeriod').value = job.salary_period || 'year';
            document.getElementById('currency').value = job.currency || 'USD';
            document.getElementById('showSalary').checked = !!job.salary_visible;
            document.getElementById('description').value = job.description || '';
            document.getElementById('requirements').value = job.requirements || '';

            document.getElementById('salarySection').style.display = document.getElementById('showSalary').checked ? 'grid' : 'none';
        }

        async function loadJobForEdit(jobId) {
            try {
                setFormDisabled(true);
                const response = await fetch(`../api/company_jobs.php?id=${encodeURIComponent(jobId)}`, {
                    method: 'GET',
                    credentials: 'include'
                });

                const data = await response.json();
                if (!response.ok || !data.success || !data.job) {
                    window.companyDashboard.showToast(data.message || 'Unable to load job for editing', 'error');
                    return;
                }

                populateForm(data.job);
            } catch (error) {
                window.companyDashboard.showToast('Unable to connect to server', 'error');
            } finally {
                setFormDisabled(false);
            }
        }

        function renderPreview() {
            const payload = getFormPayload('published');
            const escapeHtml = (value) => String(value || '').replace(/[&<>"']/g, (char) => {
                const entities = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                };
                return entities[char] || char;
            });

            const salaryText = payload.salary_visible && payload.salary_min && payload.salary_max
                ? `${escapeHtml(payload.currency)} ${escapeHtml(payload.salary_min)} - ${escapeHtml(payload.salary_max)} / ${escapeHtml(payload.salary_period)}`
                : 'Salary not displayed';

            const html = `
                <div class="preview-wrapper">
                    <h3 class="mb-2">${escapeHtml(payload.title || 'Untitled Job')}</h3>
                    <p class="text-muted mb-3">${escapeHtml(payload.employment_type || 'Not set')} | ${escapeHtml(payload.location || 'Location not set')}</p>
                    <div class="mb-3"><strong>Compensation:</strong> ${salaryText}</div>
                    <div class="mb-3"><strong>Description</strong><p class="mb-0">${escapeHtml(payload.description || 'No description yet').replace(/\n/g, '<br>')}</p></div>
                    <div><strong>Requirements</strong><p class="mb-0">${escapeHtml(payload.requirements || 'No requirements yet').replace(/\n/g, '<br>')}</p></div>
                </div>
            `;

            document.getElementById('jobPreviewBody').innerHTML = html;
            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('jobPreviewModal'));
            modal.show();
        }

        async function saveJobPosting(status) {
            const form = document.getElementById('jobForm');
            if (status === 'published' && !form.checkValidity()) {
                form.reportValidity();
                return false;
            }

            const payload = getFormPayload(status);

            let response = null;
            try {
                setFormDisabled(true);
                response = await fetch('../api/company_jobs.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include',
                    body: JSON.stringify(payload)
                });
            } catch (error) {
                window.companyDashboard.showToast('Unable to connect to server', 'error');
                setFormDisabled(false);
                return false;
            }

            let data = null;
            try {
                data = await response.json();
            } catch (e) {
                window.companyDashboard.showToast('Invalid server response', 'error');
                setFormDisabled(false);
                return false;
            }

            if (!response.ok || !data || !data.success) {
                window.companyDashboard.showToast((data && data.message) || 'Failed to save job', 'error');
                setFormDisabled(false);
                return false;
            }

            if (data.job_id) {
                editingJobId = data.job_id;
            }

            setFormDisabled(false);
            return true;
        }

        // Form submission
        document.getElementById('jobForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const saved = await saveJobPosting('published');
            if (!saved) {
                return;
            }

            Swal.fire({
                title: 'Success!',
                text: 'Job published successfully!',
                icon: 'success',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            }).then(() => {
                window.location.href = 'jobs.php';
            });
        });

        // Save draft
        document.getElementById('saveDraftBtn').addEventListener('click', async function() {
            const saved = await saveJobPosting('draft');
            if (!saved) {
                return;
            }
            window.companyDashboard.showToast(editingJobId ? 'Draft saved successfully!' : 'Draft created successfully!', 'success');
        });

        // Preview button
        document.getElementById('previewBtn').addEventListener('click', function() {
            renderPreview();
        });

        // Check for edit mode
        const urlParams = new URLSearchParams(window.location.search);
        const editId = urlParams.get('edit');
        if (editId) {
            editingJobId = Number(editId);
            document.getElementById('pageTitle').textContent = 'Edit Job';
            document.getElementById('formTitle').textContent = 'Edit Job Posting';
            document.querySelector('button[type="submit"]').innerHTML = '<i class="bi bi-check2-circle"></i> Update Job';
            loadJobForEdit(editId);
        }
    </script>

    <style>
        /* Job Create Page Specific Styles */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        .form-grid .full-width {
            grid-column: span 2;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .benefits-checkboxes,
        .documents-checkboxes {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .skills-input-wrapper {
            position: relative;
        }

        .skills-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
        }

        .skill-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: var(--info-light);
            color: var(--primary);
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .skill-tag button {
            background: none;
            border: none;
            color: var(--primary);
            cursor: pointer;
            font-size: 1rem;
            padding: 0;
            line-height: 1;
        }

        .skill-tag button:hover {
            color: var(--danger);
        }

        .suggested-skills {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
            margin-top: 16px;
        }

        .suggested-label {
            font-size: 0.875rem;
            color: var(--text-light);
        }

        .suggested-skill {
            padding: 4px 12px;
            background: var(--bg-main);
            border: 1px dashed var(--border-color);
            border-radius: 20px;
            font-size: 0.8rem;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s;
        }

        .suggested-skill:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--info-light);
        }

        .form-actions-sticky {
            position: sticky;
            bottom: 0;
            background: var(--bg-card);
            padding: 20px 30px;
            margin: 30px -30px -30px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.05);
        }

        .actions-left,
        .actions-right {
            display: flex;
            gap: 12px;
        }

        @media (max-width: 992px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-grid .full-width {
                grid-column: span 1;
            }
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .benefits-checkboxes,
            .documents-checkboxes {
                grid-template-columns: 1fr;
            }

            .form-actions-sticky {
                flex-direction: column;
                gap: 12px;
            }

            .actions-left,
            .actions-right {
                justify-content: center;
            }
        }
    </style>
</body>
</html>

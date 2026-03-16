<?php
// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Get job filter if provided
$jobFilter = isset($_GET['job']) ? $_GET['job'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications - CareerHunt</title>
    <link rel="stylesheet" href="css/company.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>
    <div class="company-container" id="companyDashboard">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="../index.php" class="logo">
                    <img src="..\photos\job_logo.png" alt="CareerHunt">
                </a>
                <span class="company-badge">Company</span>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li class="nav-item" data-page="index.php">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </li>
                    <li class="nav-item" data-page="job-create.php">
                        <i class="bi bi-plus-circle-fill"></i>
                        <span>Post Job</span>
                    </li>
                    <li class="nav-item" data-page="jobs.php">
                        <i class="bi bi-file-earmark-text-fill"></i>
                        <span>Manage Jobs</span>
                    </li>
                    <li class="nav-item active" data-page="applications.php">
                        <i class="bi bi-people-fill"></i>
                        <span>Applications</span>
                    </li>
                    <li class="nav-item" data-page="profile.php">
                        <i class="bi bi-building"></i>
                        <span>Company Profile</span>
                    </li>
                    <li class="nav-item" data-page="settings.php">
                        <i class="bi bi-gear-fill"></i>
                        <span>Settings</span>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <div class="company-profile">
                    <img id="companyAvatar" src="https://ui-avatars.com/api/?name=Company&background=0d47a1&color=fff" alt="Company">
                    <div class="company-info">
                        <span class="company-name" id="companyNameDisplay">TechCorp Inc.</span>
                        <span class="company-role">Business Account</span>
                    </div>
                </div>
                <button class="logout-btn" id="logoutBtn" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="main-header">
                <div class="header-left">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1 class="page-title">Applications</h1>
                </div>
                <div class="header-right">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Search applicants..." id="applicantSearch">
                    </div>
                    <button class="notification-btn" id="notificationBtn">
                        <i class="bi bi-bell-fill"></i>
                        <span class="notification-badge" id="notificationCount">5</span>
                    </button>
                    <div class="profile-dropdown">
                        <button class="profile-btn" id="profileBtn">
                            <img src="https://ui-avatars.com/api/?name=Company&background=0d47a1&color=fff" alt="Company" id="headerAvatar">
                            <span id="headerCompanyName">TechCorp Inc.</span>
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

            <!-- Applications Content -->
            <section class="content-section">
                <div class="page-header">
                    <div>
                        <h1>Applications</h1>
                        <p>Review and manage job applications</p>
                    </div>
                    <div class="header-actions">
                        <select class="form-control" id="jobFilter">
                            <option value="all">All Jobs</option>
                            <option value="1">Senior Frontend Developer</option>
                            <option value="2">UX Designer</option>
                            <option value="3">Backend Developer</option>
                            <option value="4">Product Manager</option>
                        </select>
                        <button class="btn btn-outline" id="exportBtn">
                            <i class="bi bi-download"></i> Export
                        </button>
                    </div>
                </div>

                <!-- Stats -->
                <div class="application-stats">
                    <div class="stat-item">
                        <span class="stat-number">156</span>
                        <span class="stat-label">Total Applications</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">42</span>
                        <span class="stat-label">New (This Week)</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">28</span>
                        <span class="stat-label">In Review</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">15</span>
                        <span class="stat-label">Shortlisted</span>
                    </div>
                </div>

                <!-- Filter Tabs -->
                <div class="tabs">
                    <div class="tab active" data-filter="all">All (156)</div>
                    <div class="tab" data-filter="new">New (42)</div>
                    <div class="tab" data-filter="reviewing">In Review (28)</div>
                    <div class="tab" data-filter="shortlisted">Shortlisted (15)</div>
                    <div class="tab" data-filter="rejected">Rejected (71)</div>
                </div>

                <!-- Applications Table -->
                <div class="dashboard-card">
                    <div class="card-body" style="padding: 0;">
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                        </th>
                                        <th>Applicant</th>
                                        <th>Position</th>
                                        <th>Experience</th>
                                        <th>Applied Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="applicationsTable">
                                    <tr data-application-id="1" data-status="new">
                                        <td><input type="checkbox" class="form-check-input row-select"></td>
                                        <td>
                                            <div class="applicant-info">
                                                <img src="https://ui-avatars.com/api/?name=John+Doe&background=0d47a1&color=fff" alt="John Doe">
                                                <div>
                                                    <span class="name">John Doe</span>
                                                    <span class="email">john.doe@email.com</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Senior Frontend Developer</td>
                                        <td>5 years</td>
                                        <td>Mar 11, 2026</td>
                                        <td><span class="status-badge new">New</span></td>
                                        <td>
                                            <button class="action-btn view" title="View Application"><i class="bi bi-eye"></i></button>
                                            <button class="action-btn accept" title="Shortlist"><i class="bi bi-check-lg"></i></button>
                                            <button class="action-btn delete" title="Reject"><i class="bi bi-x-lg"></i></button>
                                        </td>
                                    </tr>
                                    <tr data-application-id="2" data-status="new">
                                        <td><input type="checkbox" class="form-check-input row-select"></td>
                                        <td>
                                            <div class="applicant-info">
                                                <img src="https://ui-avatars.com/api/?name=Sarah+Miller&background=e91e63&color=fff" alt="Sarah Miller">
                                                <div>
                                                    <span class="name">Sarah Miller</span>
                                                    <span class="email">sarah.miller@email.com</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>UX Designer</td>
                                        <td>3 years</td>
                                        <td>Mar 10, 2026</td>
                                        <td><span class="status-badge new">New</span></td>
                                        <td>
                                            <button class="action-btn view" title="View Application"><i class="bi bi-eye"></i></button>
                                            <button class="action-btn accept" title="Shortlist"><i class="bi bi-check-lg"></i></button>
                                            <button class="action-btn delete" title="Reject"><i class="bi bi-x-lg"></i></button>
                                        </td>
                                    </tr>
                                    <tr data-application-id="3" data-status="reviewing">
                                        <td><input type="checkbox" class="form-check-input row-select"></td>
                                        <td>
                                            <div class="applicant-info">
                                                <img src="https://ui-avatars.com/api/?name=Michael+Chen&background=4caf50&color=fff" alt="Michael Chen">
                                                <div>
                                                    <span class="name">Michael Chen</span>
                                                    <span class="email">m.chen@email.com</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Backend Developer</td>
                                        <td>7 years</td>
                                        <td>Mar 9, 2026</td>
                                        <td><span class="status-badge reviewing">In Review</span></td>
                                        <td>
                                            <button class="action-btn view" title="View Application"><i class="bi bi-eye"></i></button>
                                            <button class="action-btn accept" title="Shortlist"><i class="bi bi-check-lg"></i></button>
                                            <button class="action-btn delete" title="Reject"><i class="bi bi-x-lg"></i></button>
                                        </td>
                                    </tr>
                                    <tr data-application-id="4" data-status="shortlisted">
                                        <td><input type="checkbox" class="form-check-input row-select"></td>
                                        <td>
                                            <div class="applicant-info">
                                                <img src="https://ui-avatars.com/api/?name=Emily+Johnson&background=ff9800&color=fff" alt="Emily Johnson">
                                                <div>
                                                    <span class="name">Emily Johnson</span>
                                                    <span class="email">emily.j@email.com</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Senior Frontend Developer</td>
                                        <td>6 years</td>
                                        <td>Mar 8, 2026</td>
                                        <td><span class="status-badge shortlisted">Shortlisted</span></td>
                                        <td>
                                            <button class="action-btn view" title="View Application"><i class="bi bi-eye"></i></button>
                                            <button class="action-btn edit" title="Schedule Interview"><i class="bi bi-calendar-check"></i></button>
                                            <button class="action-btn delete" title="Reject"><i class="bi bi-x-lg"></i></button>
                                        </td>
                                    </tr>
                                    <tr data-application-id="5" data-status="shortlisted">
                                        <td><input type="checkbox" class="form-check-input row-select"></td>
                                        <td>
                                            <div class="applicant-info">
                                                <img src="https://ui-avatars.com/api/?name=David+Wilson&background=9c27b0&color=fff" alt="David Wilson">
                                                <div>
                                                    <span class="name">David Wilson</span>
                                                    <span class="email">d.wilson@email.com</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Product Manager</td>
                                        <td>8 years</td>
                                        <td>Mar 7, 2026</td>
                                        <td><span class="status-badge shortlisted">Shortlisted</span></td>
                                        <td>
                                            <button class="action-btn view" title="View Application"><i class="bi bi-eye"></i></button>
                                            <button class="action-btn edit" title="Schedule Interview"><i class="bi bi-calendar-check"></i></button>
                                            <button class="action-btn delete" title="Reject"><i class="bi bi-x-lg"></i></button>
                                        </td>
                                    </tr>
                                    <tr data-application-id="6" data-status="rejected">
                                        <td><input type="checkbox" class="form-check-input row-select"></td>
                                        <td>
                                            <div class="applicant-info">
                                                <img src="https://ui-avatars.com/api/?name=Alex+Brown&background=607d8b&color=fff" alt="Alex Brown">
                                                <div>
                                                    <span class="name">Alex Brown</span>
                                                    <span class="email">alex.b@email.com</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Backend Developer</td>
                                        <td>2 years</td>
                                        <td>Mar 5, 2026</td>
                                        <td><span class="status-badge rejected">Rejected</span></td>
                                        <td>
                                            <button class="action-btn view" title="View Application"><i class="bi bi-eye"></i></button>
                                            <button class="action-btn edit" title="Reconsider"><i class="bi bi-arrow-counterclockwise"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Bulk Actions -->
                <div class="bulk-actions" id="bulkActions" style="display: none;">
                    <span class="selected-count"><span id="selectedCount">0</span> selected</span>
                    <button class="btn btn-sm btn-primary" id="bulkShortlist">
                        <i class="bi bi-check-lg"></i> Shortlist
                    </button>
                    <button class="btn btn-sm btn-outline" id="bulkReview">
                        <i class="bi bi-eye"></i> Mark as Reviewing
                    </button>
                    <button class="btn btn-sm btn-danger" id="bulkReject">
                        <i class="bi bi-x-lg"></i> Reject
                    </button>
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    <button class="pagination-btn" disabled><i class="bi bi-chevron-left"></i></button>
                    <button class="pagination-btn active">1</button>
                    <button class="pagination-btn">2</button>
                    <button class="pagination-btn">3</button>
                    <button class="pagination-btn">4</button>
                    <button class="pagination-btn"><i class="bi bi-chevron-right"></i></button>
                </div>
            </section>
        </main>
    </div>

    <!-- Application Detail Modal -->
    <div class="modal-overlay" id="applicationModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Application Details</h3>
                <button class="modal-close" id="closeModal"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-body">
                <div class="applicant-profile">
                    <img src="https://ui-avatars.com/api/?name=John+Doe&background=0d47a1&color=fff&size=100" alt="Applicant" id="modalPhoto">
                    <div class="applicant-details">
                        <h4 id="modalName">John Doe</h4>
                        <p id="modalEmail">john.doe@email.com</p>
                        <p id="modalPhone">+1 (555) 123-4567</p>
                    </div>
                </div>
                
                <div class="application-info">
                    <div class="info-row">
                        <span class="info-label">Applied for:</span>
                        <span class="info-value" id="modalPosition">Senior Frontend Developer</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Experience:</span>
                        <span class="info-value" id="modalExperience">5 years</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Applied on:</span>
                        <span class="info-value" id="modalDate">March 11, 2026</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="status-badge new" id="modalStatus">New</span>
                    </div>
                </div>

                <div class="cover-letter">
                    <h5>Cover Letter</h5>
                    <p id="modalCoverLetter">
                        I am excited to apply for the Senior Frontend Developer position at TechCorp Inc. With over 5 years of experience in building scalable web applications using React, TypeScript, and modern CSS frameworks, I believe I would be a great fit for your team.
                        
                        In my current role, I have led the development of several high-traffic applications and mentored junior developers. I am passionate about creating great user experiences and writing clean, maintainable code.
                    </p>
                </div>

                <div class="attachments">
                    <h5>Attachments</h5>
                    <div class="attachment-list">
                        <a href="#" class="attachment-item">
                            <i class="bi bi-file-pdf"></i>
                            <span>Resume_JohnDoe.pdf</span>
                        </a>
                        <a href="#" class="attachment-item">
                            <i class="bi bi-file-pdf"></i>
                            <span>CoverLetter_JohnDoe.pdf</span>
                        </a>
                        <a href="#" class="attachment-item">
                            <i class="bi bi-link-45deg"></i>
                            <span>Portfolio Website</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" id="modalReject">
                    <i class="bi bi-x-lg"></i> Reject
                </button>
                <button class="btn btn-outline" id="modalReview">
                    <i class="bi bi-eye"></i> Mark as Reviewing
                </button>
                <button class="btn btn-primary" id="modalShortlist">
                    <i class="bi bi-check-lg"></i> Shortlist
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/company.js?v=<?php echo filemtime(__DIR__ . '/js/company.js'); ?>"></script>
    <script>
        // Filter tabs functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                const filter = this.dataset.filter;
                filterApplications(filter);
            });
        });

        function filterApplications(status) {
            const rows = document.querySelectorAll('#applicationsTable tr');
            rows.forEach(row => {
                const rowStatus = row.dataset.status;
                if (status === 'all' || rowStatus === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Search functionality
        document.getElementById('applicantSearch').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const rows = document.querySelectorAll('#applicationsTable tr');
            rows.forEach(row => {
                const name = row.querySelector('.name')?.textContent.toLowerCase();
                const email = row.querySelector('.email')?.textContent.toLowerCase();
                const position = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase();
                if (name?.includes(query) || email?.includes(query) || position?.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Select all checkbox
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.row-select');
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulkActions();
        });

        // Row checkbox change
        document.querySelectorAll('.row-select').forEach(cb => {
            cb.addEventListener('change', updateBulkActions);
        });

        function updateBulkActions() {
            const selected = document.querySelectorAll('.row-select:checked').length;
            document.getElementById('selectedCount').textContent = selected;
            document.getElementById('bulkActions').style.display = selected > 0 ? 'flex' : 'none';
        }

        // View application modal
        document.querySelectorAll('.action-btn.view').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('applicationModal').classList.add('active');
            });
        });

        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('applicationModal').classList.remove('active');
        });

        document.getElementById('applicationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });

        // Status change actions
        document.querySelectorAll('.action-btn.accept').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                row.querySelector('.status-badge').className = 'status-badge shortlisted';
                row.querySelector('.status-badge').textContent = 'Shortlisted';
                row.dataset.status = 'shortlisted';
                window.companyDashboard.showToast('Application shortlisted!', 'success');
            });
        });

        document.querySelectorAll('.action-btn.delete').forEach(btn => {
            btn.addEventListener('click', function() {
                if (confirm('Are you sure you want to reject this application?')) {
                    const row = this.closest('tr');
                    row.querySelector('.status-badge').className = 'status-badge rejected';
                    row.querySelector('.status-badge').textContent = 'Rejected';
                    row.dataset.status = 'rejected';
                    window.companyDashboard.showToast('Application rejected', 'warning');
                }
            });
        });

        // Bulk actions
        document.getElementById('bulkShortlist').addEventListener('click', function() {
            const selected = document.querySelectorAll('.row-select:checked');
            selected.forEach(cb => {
                const row = cb.closest('tr');
                row.querySelector('.status-badge').className = 'status-badge shortlisted';
                row.querySelector('.status-badge').textContent = 'Shortlisted';
                row.dataset.status = 'shortlisted';
                cb.checked = false;
            });
            document.getElementById('selectAll').checked = false;
            updateBulkActions();
            window.companyDashboard.showToast(`${selected.length} applications shortlisted!`, 'success');
        });

        document.getElementById('bulkReject').addEventListener('click', function() {
            if (confirm('Are you sure you want to reject the selected applications?')) {
                const selected = document.querySelectorAll('.row-select:checked');
                selected.forEach(cb => {
                    const row = cb.closest('tr');
                    row.querySelector('.status-badge').className = 'status-badge rejected';
                    row.querySelector('.status-badge').textContent = 'Rejected';
                    row.dataset.status = 'rejected';
                    cb.checked = false;
                });
                document.getElementById('selectAll').checked = false;
                updateBulkActions();
                window.companyDashboard.showToast(`${selected.length} applications rejected`, 'warning');
            }
        });

        // Export functionality
        document.getElementById('exportBtn').addEventListener('click', function() {
            window.companyDashboard.showToast('Exporting applications...', 'info');
        });
    </script>

    <style>
        /* Applications Page Specific Styles */
        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .header-actions .form-control {
            width: 220px;
        }

        .application-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-item {
            background: var(--bg-card);
            padding: 20px;
            border-radius: var(--radius);
            text-align: center;
            box-shadow: var(--shadow-sm);
        }

        .stat-item .stat-number {
            display: block;
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
        }

        .stat-item .stat-label {
            font-size: 0.875rem;
            color: var(--text-light);
        }

        .applicant-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .applicant-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .applicant-info .name {
            display: block;
            font-weight: 600;
            color: var(--text-primary);
        }

        .applicant-info .email {
            display: block;
            font-size: 0.8rem;
            color: var(--text-light);
        }

        .status-badge.new {
            background: #e3f2fd;
            color: #1976d2;
        }

        .status-badge.reviewing {
            background: #fff3e0;
            color: #f57c00;
        }

        .status-badge.shortlisted {
            background: #e8f5e9;
            color: #388e3c;
        }

        .status-badge.rejected {
            background: #ffebee;
            color: #d32f2f;
        }

        .bulk-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            background: var(--bg-card);
            border-radius: var(--radius);
            margin-top: 16px;
            box-shadow: var(--shadow-sm);
        }

        .selected-count {
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: var(--bg-card);
            border-radius: var(--radius);
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            transform: translateY(-20px);
            transition: transform 0.3s;
        }

        .modal-overlay.active .modal-content {
            transform: translateY(0);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
        }

        .modal-header h3 {
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--text-light);
            cursor: pointer;
        }

        .modal-close:hover {
            color: var(--text-primary);
        }

        .modal-body {
            padding: 24px;
        }

        .applicant-profile {
            display: flex;
            gap: 20px;
            margin-bottom: 24px;
        }

        .applicant-profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }

        .applicant-details h4 {
            margin: 0 0 8px;
        }

        .applicant-details p {
            margin: 4px 0;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .application-info {
            background: var(--bg-main);
            padding: 16px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: var(--text-light);
        }

        .info-value {
            font-weight: 500;
        }

        .cover-letter h5,
        .attachments h5 {
            margin-bottom: 12px;
            font-size: 1rem;
        }

        .cover-letter p {
            color: var(--text-secondary);
            line-height: 1.6;
            white-space: pre-line;
        }

        .attachment-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .attachment-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            background: var(--bg-main);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            text-decoration: none;
            transition: background 0.3s;
        }

        .attachment-item:hover {
            background: var(--info-light);
            color: var(--primary);
        }

        .attachment-item i {
            font-size: 1.25rem;
            color: var(--primary);
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 16px 24px;
            border-top: 1px solid var(--border-color);
        }

        @media (max-width: 992px) {
            .application-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .header-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .header-actions .form-control {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .application-stats {
                grid-template-columns: 1fr;
            }

            .bulk-actions {
                flex-wrap: wrap;
            }

            .modal-content {
                width: 95%;
            }
        }
    </style>
</body>
</html>

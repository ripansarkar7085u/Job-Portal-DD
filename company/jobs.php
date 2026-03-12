<?php
// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs - CareerHunt</title>
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
                    <img src="../photos/job logo.png" alt="CareerHunt">
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
                    <li class="nav-item active" data-page="jobs.php">
                        <i class="bi bi-file-earmark-text-fill"></i>
                        <span>Manage Jobs</span>
                    </li>
                    <li class="nav-item" data-page="applications.php">
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
                    <h1 class="page-title">Manage Jobs</h1>
                </div>
                <div class="header-right">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Search jobs..." id="jobSearch">
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

            <!-- Jobs Content -->
            <section class="content-section">
                <div class="page-header">
                    <div>
                        <h1>Manage Jobs</h1>
                        <p>View and manage all your job postings</p>
                    </div>
                    <a href="job-create.php" class="btn btn-accent">
                        <i class="bi bi-plus-lg"></i> Post New Job
                    </a>
                </div>

                <!-- Filter Tabs -->
                <div class="tabs">
                    <div class="tab active" data-filter="all">All Jobs (24)</div>
                    <div class="tab" data-filter="active">Active (18)</div>
                    <div class="tab" data-filter="closed">Closed (4)</div>
                    <div class="tab" data-filter="draft">Drafts (2)</div>
                </div>

                <!-- Jobs Table -->
                <div class="dashboard-card">
                    <div class="card-body" style="padding: 0;">
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Location</th>
                                        <th>Type</th>
                                        <th>Applications</th>
                                        <th>Posted Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="jobsTable">
                                    <tr data-job-id="1">
                                        <td>
                                            <div class="job-title-cell">
                                                <span class="job-title">Senior Frontend Developer</span>
                                                <span class="job-salary">$120,000 - $150,000/year</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="location-badge"><i class="bi bi-geo-alt"></i> Remote</span>
                                        </td>
                                        <td>Full-time</td>
                                        <td>
                                            <a href="applications.php?job=1" class="applications-link">32 applicants</a>
                                        </td>
                                        <td>Mar 7, 2026</td>
                                        <td><span class="status-badge active">Active</span></td>
                                        <td>
                                            <button class="action-btn view" title="View"><i class="bi bi-eye"></i></button>
                                            <button class="action-btn edit" title="Edit"><i class="bi bi-pencil"></i></button>
                                            <button class="action-btn delete" title="Delete"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                    <tr data-job-id="2">
                                        <td>
                                            <div class="job-title-cell">
                                                <span class="job-title">UX Designer</span>
                                                <span class="job-salary">$90,000 - $110,000/year</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="location-badge"><i class="bi bi-geo-alt"></i> New York, NY</span>
                                        </td>
                                        <td>Full-time</td>
                                        <td>
                                            <a href="applications.php?job=2" class="applications-link">18 applicants</a>
                                        </td>
                                        <td>Mar 5, 2026</td>
                                        <td><span class="status-badge active">Active</span></td>
                                        <td>
                                            <button class="action-btn view" title="View"><i class="bi bi-eye"></i></button>
                                            <button class="action-btn edit" title="Edit"><i class="bi bi-pencil"></i></button>
                                            <button class="action-btn delete" title="Delete"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                    <tr data-job-id="3">
                                        <td>
                                            <div class="job-title-cell">
                                                <span class="job-title">Backend Developer</span>
                                                <span class="job-salary">$130,000 - $160,000/year</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="location-badge"><i class="bi bi-geo-alt"></i> San Francisco, CA</span>
                                        </td>
                                        <td>Full-time</td>
                                        <td>
                                            <a href="applications.php?job=3" class="applications-link">24 applicants</a>
                                        </td>
                                        <td>Feb 28, 2026</td>
                                        <td><span class="status-badge active">Active</span></td>
                                        <td>
                                            <button class="action-btn view" title="View"><i class="bi bi-eye"></i></button>
                                            <button class="action-btn edit" title="Edit"><i class="bi bi-pencil"></i></button>
                                            <button class="action-btn delete" title="Delete"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                    <tr data-job-id="4">
                                        <td>
                                            <div class="job-title-cell">
                                                <span class="job-title">Product Manager</span>
                                                <span class="job-salary">$140,000 - $170,000/year</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="location-badge"><i class="bi bi-geo-alt"></i> Austin, TX</span>
                                        </td>
                                        <td>Full-time</td>
                                        <td>
                                            <a href="applications.php?job=4" class="applications-link">15 applicants</a>
                                        </td>
                                        <td>Feb 20, 2026</td>
                                        <td><span class="status-badge active">Active</span></td>
                                        <td>
                                            <button class="action-btn view" title="View"><i class="bi bi-eye"></i></button>
                                            <button class="action-btn edit" title="Edit"><i class="bi bi-pencil"></i></button>
                                            <button class="action-btn delete" title="Delete"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                    <tr data-job-id="5">
                                        <td>
                                            <div class="job-title-cell">
                                                <span class="job-title">DevOps Engineer</span>
                                                <span class="job-salary">$125,000 - $155,000/year</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="location-badge"><i class="bi bi-geo-alt"></i> Remote</span>
                                        </td>
                                        <td>Full-time</td>
                                        <td>
                                            <a href="applications.php?job=5" class="applications-link">21 applicants</a>
                                        </td>
                                        <td>Feb 15, 2026</td>
                                        <td><span class="status-badge closed">Closed</span></td>
                                        <td>
                                            <button class="action-btn view" title="View"><i class="bi bi-eye"></i></button>
                                            <button class="action-btn edit" title="Reopen"><i class="bi bi-arrow-counterclockwise"></i></button>
                                            <button class="action-btn delete" title="Delete"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                    <tr data-job-id="6">
                                        <td>
                                            <div class="job-title-cell">
                                                <span class="job-title">Data Scientist</span>
                                                <span class="job-salary">$135,000 - $165,000/year</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="location-badge"><i class="bi bi-geo-alt"></i> Boston, MA</span>
                                        </td>
                                        <td>Full-time</td>
                                        <td>
                                            <span class="no-applicants">No applicants yet</span>
                                        </td>
                                        <td>-</td>
                                        <td><span class="status-badge draft">Draft</span></td>
                                        <td>
                                            <button class="action-btn edit" title="Edit"><i class="bi bi-pencil"></i></button>
                                            <button class="action-btn accept" title="Publish"><i class="bi bi-send"></i></button>
                                            <button class="action-btn delete" title="Delete"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    <button class="pagination-btn" disabled><i class="bi bi-chevron-left"></i></button>
                    <button class="pagination-btn active">1</button>
                    <button class="pagination-btn">2</button>
                    <button class="pagination-btn">3</button>
                    <button class="pagination-btn"><i class="bi bi-chevron-right"></i></button>
                </div>
            </section>
        </main>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/company.js"></script>
    <script>
        // Filter tabs functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                const filter = this.dataset.filter;
                filterJobs(filter);
            });
        });

        function filterJobs(status) {
            const rows = document.querySelectorAll('#jobsTable tr');
            rows.forEach(row => {
                const rowStatus = row.querySelector('.status-badge')?.textContent.toLowerCase();
                if (status === 'all' || rowStatus === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Search functionality
        document.getElementById('jobSearch').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const rows = document.querySelectorAll('#jobsTable tr');
            rows.forEach(row => {
                const title = row.querySelector('.job-title')?.textContent.toLowerCase();
                const location = row.querySelector('.location-badge')?.textContent.toLowerCase();
                if (title?.includes(query) || location?.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Action button handlers
        document.querySelectorAll('.action-btn.delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                const jobTitle = row.querySelector('.job-title').textContent;
                if (confirm(`Are you sure you want to delete "${jobTitle}"?`)) {
                    row.remove();
                    window.companyDashboard.showToast('Job deleted successfully', 'success');
                }
            });
        });

        document.querySelectorAll('.action-btn.edit').forEach(btn => {
            btn.addEventListener('click', function() {
                const jobId = this.closest('tr').dataset.jobId;
                window.location.href = `job-create.php?edit=${jobId}`;
            });
        });

        document.querySelectorAll('.action-btn.view').forEach(btn => {
            btn.addEventListener('click', function() {
                const jobId = this.closest('tr').dataset.jobId;
                window.location.href = `../index.php#job-${jobId}`;
            });
        });
    </script>

    <style>
        .job-title-cell {
            display: flex;
            flex-direction: column;
        }

        .job-title {
            font-weight: 600;
            color: var(--text-primary);
        }

        .job-salary {
            font-size: 0.8rem;
            color: var(--text-light);
        }

        .location-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .location-badge i {
            color: var(--primary);
        }

        .applications-link {
            color: var(--primary);
            font-weight: 500;
        }

        .applications-link:hover {
            color: var(--accent);
            text-decoration: underline;
        }

        .no-applicants {
            color: var(--text-light);
            font-size: 0.875rem;
        }
    </style>
</body>
</html>

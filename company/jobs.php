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
                    <div class="tab active" data-filter="all">All Jobs (<span id="countAll">0</span>)</div>
                    <div class="tab" data-filter="active">Active (<span id="countActive">0</span>)</div>
                    <div class="tab" data-filter="closed">Closed (<span id="countClosed">0</span>)</div>
                    <div class="tab" data-filter="draft">Drafts (<span id="countDraft">0</span>)</div>
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
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">Loading jobs...</td>
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
    <script src="js/company.js?v=<?php echo filemtime(__DIR__ . '/js/company.js'); ?>"></script>
    <script>
        const jobsTable = document.getElementById('jobsTable');
        const jobSearchInput = document.getElementById('jobSearch');
        const tabs = Array.from(document.querySelectorAll('.tab'));

        let allJobs = [];
        let activeFilter = 'all';
        let searchQuery = '';

        function showToast(message, type = 'info') {
            if (window.companyDashboard && typeof window.companyDashboard.showToast === 'function') {
                window.companyDashboard.showToast(message, type);
                return;
            }

            console[type === 'error' ? 'error' : 'log'](message);
        }

        function escapeHtml(value) {
            return String(value || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function toTitleCase(value) {
            return String(value || '')
                .split('-')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join('-');
        }

        function formatSalary(job) {
            if (!job.salary_visible) {
                return 'Salary not disclosed';
            }

            const min = job.salary_min !== null ? Number(job.salary_min) : null;
            const max = job.salary_max !== null ? Number(job.salary_max) : null;
            if (min === null && max === null) {
                return 'Salary not specified';
            }

            const currency = job.currency || 'USD';
            const formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency,
                maximumFractionDigits: 0
            });

            let range = '';
            if (min !== null && max !== null) {
                range = `${formatter.format(min)} - ${formatter.format(max)}`;
            } else if (min !== null) {
                range = `${formatter.format(min)}+`;
            } else {
                range = `Up to ${formatter.format(max)}`;
            }

            const periodSuffix = {
                year: '/year',
                month: '/month',
                hour: '/hour'
            };

            return `${range}${periodSuffix[job.salary_period] || '/year'}`;
        }

        function formatDate(dateString) {
            if (!dateString) {
                return '-';
            }

            const date = new Date(dateString);
            if (Number.isNaN(date.getTime())) {
                return '-';
            }

            return date.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
        }

        function statusMeta(status) {
            if (status === 'active') {
                return { label: 'Active', className: 'active' };
            }
            if (status === 'closed') {
                return { label: 'Closed', className: 'closed' };
            }
            return { label: 'Draft', className: 'draft' };
        }

        function computeCounts(jobs) {
            const counts = { all: jobs.length, active: 0, closed: 0, draft: 0 };
            jobs.forEach(job => {
                if (counts[job.status] !== undefined) {
                    counts[job.status] += 1;
                }
            });
            return counts;
        }

        function updateTabCounts(counts) {
            document.getElementById('countAll').textContent = counts.all || 0;
            document.getElementById('countActive').textContent = counts.active || 0;
            document.getElementById('countClosed').textContent = counts.closed || 0;
            document.getElementById('countDraft').textContent = counts.draft || 0;
        }

        function renderRows() {
            if (!allJobs.length) {
                jobsTable.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-muted">No jobs posted yet.</td></tr>';
                return;
            }

            const rows = allJobs.map(job => {
                const status = statusMeta(job.status);
                const searchKey = `${job.title} ${job.location}`.toLowerCase();

                return `
                    <tr data-job-id="${job.id}" data-status="${job.status}" data-search="${escapeHtml(searchKey)}">
                        <td>
                            <div class="job-title-cell">
                                <span class="job-title">${escapeHtml(job.title)}</span>
                                <span class="job-salary">${escapeHtml(formatSalary(job))}</span>
                            </div>
                        </td>
                        <td>
                            <span class="location-badge"><i class="bi bi-geo-alt"></i> ${escapeHtml(job.location || 'N/A')}</span>
                        </td>
                        <td>${escapeHtml(toTitleCase(job.employment_type || ''))}</td>
                        <td>
                            <span class="no-applicants">No applicants yet</span>
                        </td>
                        <td>${escapeHtml(formatDate(job.created_at))}</td>
                        <td><span class="status-badge ${status.className}">${status.label}</span></td>
                        <td>
                            <button class="action-btn view" title="View"><i class="bi bi-eye"></i></button>
                            <button class="action-btn edit" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button class="action-btn delete" title="Delete"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                `;
            }).join('');

            jobsTable.innerHTML = rows;
            applyFilters();
        }

        function applyFilters() {
            const rows = Array.from(jobsTable.querySelectorAll('tr[data-job-id]'));
            rows.forEach(row => {
                const rowStatus = row.dataset.status || '';
                const rowSearch = row.dataset.search || '';
                const matchesFilter = activeFilter === 'all' || rowStatus === activeFilter;
                const matchesSearch = searchQuery === '' || rowSearch.includes(searchQuery);
                row.style.display = matchesFilter && matchesSearch ? '' : 'none';
            });
        }

        async function loadJobs() {
            try {
                const response = await fetch('../api/company_jobs.php', {
                    method: 'GET',
                    credentials: 'include'
                });

                const data = await response.json();
                if (!response.ok || !data.success) {
                    jobsTable.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-danger">Unable to load jobs.</td></tr>';
                    showToast(data.message || 'Unable to load jobs', 'error');
                    return;
                }

                allJobs = Array.isArray(data.jobs) ? data.jobs : [];
                const counts = data.counts && typeof data.counts === 'object' ? data.counts : computeCounts(allJobs);
                updateTabCounts(counts);
                renderRows();
            } catch (error) {
                jobsTable.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-danger">Unable to load jobs.</td></tr>';
                showToast('Unable to connect to server', 'error');
            }
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(item => item.classList.remove('active'));
                this.classList.add('active');
                activeFilter = this.dataset.filter || 'all';
                applyFilters();
            });
        });

        jobSearchInput.addEventListener('input', function() {
            searchQuery = this.value.trim().toLowerCase();
            applyFilters();
        });

        jobsTable.addEventListener('click', function(event) {
            const button = event.target.closest('.action-btn');
            if (!button) {
                return;
            }

            const row = button.closest('tr[data-job-id]');
            if (!row) {
                return;
            }

            const jobId = row.dataset.jobId;

            if (button.classList.contains('edit')) {
                window.location.href = `job-create.php?edit=${jobId}`;
                return;
            }

            if (button.classList.contains('view')) {
                window.location.href = `../index.php#job-${jobId}`;
                return;
            }

            if (button.classList.contains('delete')) {
                const title = row.querySelector('.job-title')?.textContent || 'this job';
                if (!confirm(`Are you sure you want to delete "${title}"?`)) {
                    return;
                }

                allJobs = allJobs.filter(job => String(job.id) !== String(jobId));
                updateTabCounts(computeCounts(allJobs));
                renderRows();
                showToast('Job removed from list.', 'success');
            }
        });

        loadJobs();
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

<?php
// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../api/_auth_common.php';
require_once __DIR__ . '/../user/_user_common.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION['account_type'] ?? '') !== 'company' || !isset($_SESSION['company_id'])) {
    header('Location: ../login.php');
    exit();
}

auth_ensure_core_tables($conn);
auth_ensure_jobs_table($conn);
user_ensure_profiles_table($conn);
user_ensure_applications_table($conn);
user_ensure_resumes_table($conn);

// Get company info from session
$companyName = $_SESSION['company_name'] ?? 'Company';
$companyId = (int) $_SESSION['company_id'];

// Get job filter if provided
$jobFilter = isset($_GET['job']) ? (int) $_GET['job'] : 0;

$applications = [];
$jobs = [];

$jobsStmt = $conn->prepare('SELECT id, title FROM jobs WHERE company_id = ? ORDER BY created_at DESC');
if ($jobsStmt) {
    $jobsStmt->bind_param('i', $companyId);
    $jobsStmt->execute();
    $jobsResult = $jobsStmt->get_result();
    while ($jobsResult && ($jobRow = $jobsResult->fetch_assoc())) {
        $jobs[] = $jobRow;
    }
    $jobsStmt->close();
}

$sql = "SELECT a.id, a.user_id, a.job_id, a.status, a.applied_at, a.cover_letter, a.resume_path,
    j.title AS job_title,
    u.full_name, u.email, u.phone,
    p.job_title AS profile_job_title, p.experience AS profile_experience, p.location AS profile_location, p.profile_image,
    p.website AS profile_website, p.linkedin AS profile_linkedin, p.github AS profile_github,
    (SELECT file_name FROM user_resumes WHERE user_resumes.user_id = u.id AND user_resumes.status='Active' ORDER BY id DESC LIMIT 1) AS primary_resume_path,
    (SELECT display_name FROM user_resumes WHERE user_resumes.user_id = u.id AND user_resumes.status='Active' ORDER BY id DESC LIMIT 1) AS primary_resume_name
    FROM user_job_applications a
    INNER JOIN jobs j ON j.id = a.job_id
    INNER JOIN users u ON u.id = a.user_id
    LEFT JOIN profiles p ON p.user_id = u.id
    WHERE j.company_id = ?";

if ($jobFilter > 0) {
    $sql .= ' AND a.job_id = ?';
}

$sql .= ' ORDER BY a.applied_at DESC';

$stmt = $conn->prepare($sql);
if ($stmt) {
    if ($jobFilter > 0) {
        $stmt->bind_param('ii', $companyId, $jobFilter);
    } else {
        $stmt->bind_param('i', $companyId);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    while ($result && ($row = $result->fetch_assoc())) {
        $applications[] = $row;
    }
    $stmt->close();
}

$counts = [
    'all' => count($applications),
    'new' => 0,
    'reviewing' => 0,
    'shortlisted' => 0,
    'rejected' => 0,
];

foreach ($applications as $application) {
    $status = strtolower((string) $application['status']);
    if (isset($counts[$status])) {
        $counts[$status]++;
    }
}

function application_status_class(string $status): string
{
    $normalized = strtolower(trim($status));
    if ($normalized === 'reviewing') {
        return 'reviewing';
    }
    if ($normalized === 'shortlisted') {
        return 'shortlisted';
    }
    if ($normalized === 'rejected') {
        return 'rejected';
    }
    return 'new';
}

function application_status_label(string $status): string
{
    $normalized = strtolower(trim($status));
    if ($normalized === 'reviewing') {
        return 'In Review';
    }
    if ($normalized === 'shortlisted') {
        return 'Shortlisted';
    }
    if ($normalized === 'rejected') {
        return 'Rejected';
    }
    return 'New';
}
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
                     <li class="nav-item" data-page="messages.php">
                        <a href="messages.php"><i class="bi bi-chat-dots-fill"></i> <span>Messages</span></a>
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
                    <img id="companyAvatar" src="https://ui-avatars.com/api/?name=<?php echo urlencode($companyName); ?>&background=0d47a1&color=fff" alt="Company">
                    <div class="company-info">
                        <span class="company-name" id="companyNameDisplay"><?php echo htmlspecialchars($companyName); ?></span>
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
                            <?php foreach ($jobs as $job): ?>
                                <option value="<?php echo (int) $job['id']; ?>" <?php echo ($jobFilter === (int) $job['id']) ? 'selected' : ''; ?>><?php echo user_esc((string) $job['title']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-outline" id="exportBtn">
                            <i class="bi bi-download"></i> Export
                        </button>
                    </div>
                </div>

                <!-- Stats -->
                <div class="application-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?php echo (int) $counts['all']; ?></span>
                        <span class="stat-label">Total Applications</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?php echo (int) $counts['new']; ?></span>
                        <span class="stat-label">New</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?php echo (int) $counts['reviewing']; ?></span>
                        <span class="stat-label">In Review</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?php echo (int) $counts['shortlisted']; ?></span>
                        <span class="stat-label">Shortlisted</span>
                    </div>
                </div>

                <!-- Filter Tabs -->
                <div class="tabs">
                    <div class="tab active" data-filter="all">All (<?php echo (int) $counts['all']; ?>)</div>
                    <div class="tab" data-filter="new">New (<?php echo (int) $counts['new']; ?>)</div>
                    <div class="tab" data-filter="reviewing">In Review (<?php echo (int) $counts['reviewing']; ?>)</div>
                    <div class="tab" data-filter="shortlisted">Shortlisted (<?php echo (int) $counts['shortlisted']; ?>)</div>
                    <div class="tab" data-filter="rejected">Rejected (<?php echo (int) $counts['rejected']; ?>)</div>
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
                                    <?php if (empty($applications)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">No applications received yet.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($applications as $application): ?>
                                            <?php
                                                $status = strtolower((string) $application['status']);
                                                $badgeClass = application_status_class($status);
                                                $statusLabel = application_status_label($status);
                                                $avatarName = (string) ($application['full_name'] ?: 'Candidate');
                                                $avatarUrl = !empty($application['profile_image'])
                                                    ? ((strpos((string) $application['profile_image'], 'http') === 0) ? (string) $application['profile_image'] : '../user/uploads/' . (string) $application['profile_image'])
                                                    : 'https://ui-avatars.com/api/?name=' . urlencode($avatarName) . '&background=0d47a1&color=fff';
                                            ?>
                                            <tr data-application-id="<?php echo (int) $application['id']; ?>" data-status="<?php echo user_esc($badgeClass); ?>"
                                                data-name="<?php echo user_esc((string) $application['full_name']); ?>"
                                                data-email="<?php echo user_esc((string) $application['email']); ?>"
                                                data-phone="<?php echo user_esc((string) ($application['phone'] ?? '')); ?>"
                                                data-position="<?php echo user_esc((string) $application['job_title']); ?>"
                                                data-experience="<?php echo user_esc((string) ($application['profile_experience'] ?: 'Not specified')); ?>"
                                                data-date="<?php echo user_esc(date('F j, Y', strtotime((string) $application['applied_at']))); ?>"
                                                data-status-label="<?php echo user_esc($statusLabel); ?>"
                                                data-cover-letter="<?php echo user_esc((string) ($application['cover_letter'] ?: 'No cover letter provided.')); ?>"
                                                data-resume-path="<?php echo user_esc((string) ($application['resume_path'] ?: $application['primary_resume_path'])); ?>"
                                                data-resume-name="<?php echo user_esc((string) $application['primary_resume_name']); ?>"
                                                data-website="<?php echo user_esc((string) ($application['profile_website'] ?? '')); ?>"
                                                data-linkedin="<?php echo user_esc((string) ($application['profile_linkedin'] ?? '')); ?>"
                                                data-github="<?php echo user_esc((string) ($application['profile_github'] ?? '')); ?>"
                                                data-photo="<?php echo user_esc($avatarUrl); ?>">
                                                <td><input type="checkbox" class="form-check-input row-select"></td>
                                                <td>
                                                    <div class="applicant-info">
                                                        <img src="<?php echo user_esc($avatarUrl); ?>" alt="<?php echo user_esc($avatarName); ?>">
                                                        <div>
                                                            <span class="name"><?php echo user_esc((string) $application['full_name']); ?></span>
                                                            <span class="email"><?php echo user_esc((string) $application['email']); ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php echo user_esc((string) $application['job_title']); ?></td>
                                                <td><?php echo user_esc((string) ($application['profile_experience'] ?: 'Not specified')); ?></td>
                                                <td><?php echo user_esc(date('M j, Y', strtotime((string) $application['applied_at']))); ?></td>
                                                <td><span class="status-badge <?php echo user_esc($badgeClass); ?>"><?php echo user_esc($statusLabel); ?></span></td>
                                                <td>
                                                    <button class="action-btn app-view" title="View Application"><i class="bi bi-eye"></i></button>
                                                    <button class="action-btn app-review" title="Mark as Reviewing"><i class="bi bi-eye"></i></button>
                                                    <button class="action-btn app-shortlist" title="Shortlist"><i class="bi bi-check-lg"></i></button>
                                                    <button class="action-btn app-reject" title="Reject"><i class="bi bi-x-lg"></i></button>
                                                    <a href="messages.php?user_id=<?php echo (int)$application['user_id']; ?>" class="btn btn-sm btn-primary ms-1" title="Message Applicant">
                                                        <i class="bi bi-chat-dots"></i> Message
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
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
                <div class="pagination"></div>
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
                        <!-- Attachments will be loaded dynamically via JS -->
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
        let currentPage = 1;
        const perPage = 5;
        let activeFilter = 'all';
        let searchQuery = '';

        // Filter tabs functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                activeFilter = this.dataset.filter;
                currentPage = 1;
                applyFilters();
            });
        });

        // Search functionality
        document.getElementById('applicantSearch').addEventListener('input', function() {
            searchQuery = this.value.toLowerCase();
            currentPage = 1;
            applyFilters();
        });

        function applyFilters() {
            const rows = Array.from(document.querySelectorAll('#applicationsTable tr[data-application-id]'));
            let visibleRows = [];
            
            rows.forEach(row => {
                const rowStatus = row.dataset.status || '';
                const name = row.querySelector('.name')?.textContent.toLowerCase() || '';
                const email = row.querySelector('.email')?.textContent.toLowerCase() || '';
                const position = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
                
                const matchesFilter = activeFilter === 'all' || rowStatus === activeFilter;
                const matchesSearch = searchQuery === '' || name.includes(searchQuery) || email.includes(searchQuery) || position.includes(searchQuery);
                
                if (matchesFilter && matchesSearch) {
                    visibleRows.push(row);
                } else {
                    row.style.display = 'none';
                }
            });
            
            const totalMatches = visibleRows.length;
            const startIndex = (currentPage - 1) * perPage;
            const endIndex = startIndex + perPage;
            
            visibleRows.forEach((row, index) => {
                if (index >= startIndex && index < endIndex) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            
            renderPagination(totalMatches);
        }

        function renderPagination(totalMatches) {
            const paginationContainer = document.querySelector('.pagination');
            if (!paginationContainer) return;
            
            paginationContainer.innerHTML = '';
            
            let totalPages = Math.ceil(totalMatches / perPage);
            if (totalPages < 1) totalPages = 1;
            // Always show pagination container even for 1 page
            
            const prevBtn = document.createElement('button');
            prevBtn.className = 'pagination-btn';
            prevBtn.innerHTML = '<i class="bi bi-chevron-left"></i>';
            prevBtn.disabled = currentPage === 1;
            prevBtn.onclick = () => {
                if (currentPage > 1) {
                    currentPage--;
                    applyFilters();
                }
            };
            paginationContainer.appendChild(prevBtn);
            
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }

            for (let i = startPage; i <= endPage; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = `pagination-btn ${i === currentPage ? 'active' : ''}`;
                pageBtn.textContent = i;
                pageBtn.onclick = () => {
                    currentPage = i;
                    applyFilters();
                };
                paginationContainer.appendChild(pageBtn);
            }
            
            const nextBtn = document.createElement('button');
            nextBtn.className = 'pagination-btn';
            nextBtn.innerHTML = '<i class="bi bi-chevron-right"></i>';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.onclick = () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    applyFilters();
                }
            };
            paginationContainer.appendChild(nextBtn);
        }

        document.addEventListener('DOMContentLoaded', () => {
            applyFilters();
        });

        // Job filter change
        document.getElementById('jobFilter').addEventListener('change', function() {
            const value = this.value;
            if (value === 'all') {
                window.location.href = 'applications.php';
                return;
            }
            window.location.href = `applications.php?job=${encodeURIComponent(value)}`;
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

        const modal = document.getElementById('applicationModal');
        let activeApplicationId = 0;

        function updateRowStatus(row, status) {
            const badge = row.querySelector('.status-badge');
            if (!badge) {
                return;
            }

            const map = {
                new: { label: 'New', className: 'new' },
                applied: { label: 'New', className: 'new' },
                reviewing: { label: 'In Review', className: 'reviewing' },
                shortlisted: { label: 'Shortlisted', className: 'shortlisted' },
                rejected: { label: 'Rejected', className: 'rejected' }
            };

            const item = map[status] || map.new;
            row.dataset.status = status;
            badge.className = `status-badge ${item.className}`;
            badge.textContent = item.label;
        }

        async function updateApplicationStatus(applicationId, action, rowToUpdate) {
            try {
                const response = await fetch('../api/company_application_action.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'include',
                    body: JSON.stringify({ applicationId, action })
                });

                const data = await response.json();
                if (!response.ok || !data.success) {
                    window.companyDashboard.showToast(data.message || 'Failed to update status', 'error');
                    return false;
                }

                if (rowToUpdate && data.status) {
                    updateRowStatus(rowToUpdate, data.status);
                }

                window.companyDashboard.showToast('Application status updated successfully.', 'success');
                return true;
            } catch (error) {
                window.companyDashboard.showToast('Unable to connect to server', 'error');
                return false;
            }
        }

        // View application modal
        document.querySelectorAll('.action-btn.app-view').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                if (!row) {
                    return;
                }

                activeApplicationId = Number(row.dataset.applicationId || 0);
                document.getElementById('modalPhoto').src = row.dataset.photo || 'https://ui-avatars.com/api/?name=Candidate&background=0d47a1&color=fff&size=100';
                document.getElementById('modalName').textContent = row.dataset.name || 'Candidate';
                document.getElementById('modalEmail').textContent = row.dataset.email || '';
                document.getElementById('modalPhone').textContent = row.dataset.phone || 'Phone not provided';
                document.getElementById('modalPosition').textContent = row.dataset.position || 'N/A';
                document.getElementById('modalExperience').textContent = row.dataset.experience || 'Not specified';
                document.getElementById('modalDate').textContent = row.dataset.date || '';
                document.getElementById('modalStatus').textContent = row.dataset.statusLabel || 'New';
                document.getElementById('modalCoverLetter').textContent = row.dataset.coverLetter || 'No cover letter provided.';

                const attachmentsDiv = document.querySelector('#applicationModal .attachment-list');
                if (attachmentsDiv) {
                    attachmentsDiv.innerHTML = '';
                    let hasAttachments = false;
                    
                    if (row.dataset.resumePath) {
                        const resumeUrl = '../user_uploads/' + row.dataset.resumePath;
                        const resumeName = row.dataset.resumeName || row.dataset.resumePath.split('/').pop() || 'Resume.pdf';
                        attachmentsDiv.innerHTML += `
                            <a href="${resumeUrl}" target="_blank" class="attachment-item">
                                <i class="bi bi-file-pdf"></i>
                                <span>${resumeName}</span>
                            </a>
                        `;
                        hasAttachments = true;
                    }
                    
                    if (row.dataset.website) {
                        const websiteUrl = row.dataset.website.startsWith('http') ? row.dataset.website : 'https://' + row.dataset.website;
                        attachmentsDiv.innerHTML += `
                            <a href="${websiteUrl}" target="_blank" class="attachment-item">
                                <i class="bi bi-link-45deg"></i>
                                <span>Portfolio Website</span>
                            </a>
                        `;
                        hasAttachments = true;
                    }
                    
                    if (row.dataset.linkedin) {
                        const linkedinUrl = row.dataset.linkedin.startsWith('http') ? row.dataset.linkedin : 'https://' + row.dataset.linkedin;
                        attachmentsDiv.innerHTML += `
                            <a href="${linkedinUrl}" target="_blank" class="attachment-item">
                                <i class="bi bi-linkedin"></i>
                                <span>LinkedIn Profile</span>
                            </a>
                        `;
                        hasAttachments = true;
                    }
                    
                    if (row.dataset.github) {
                        const githubUrl = row.dataset.github.startsWith('http') ? row.dataset.github : 'https://' + row.dataset.github;
                        attachmentsDiv.innerHTML += `
                            <a href="${githubUrl}" target="_blank" class="attachment-item">
                                <i class="bi bi-github"></i>
                                <span>GitHub Profile</span>
                            </a>
                        `;
                        hasAttachments = true;
                    }
                    
                    if (!hasAttachments) {
                        attachmentsDiv.innerHTML = '<span class="text-muted" style="display: block; padding: 10px 0;">No attachments provided.</span>';
                    }
                }

                modal.classList.add('active');
            });
        });

        document.getElementById('closeModal').addEventListener('click', function() {
            modal.classList.remove('active');
        });

        document.getElementById('applicationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });

        // Status change actions
        document.querySelectorAll('.action-btn.app-review').forEach(btn => {
            btn.addEventListener('click', async function() {
                const row = this.closest('tr');
                const applicationId = Number(row?.dataset.applicationId || 0);
                if (!applicationId) {
                    return;
                }
                await updateApplicationStatus(applicationId, 'review', row);
            });
        });

        document.querySelectorAll('.action-btn.app-shortlist').forEach(btn => {
            btn.addEventListener('click', async function() {
                const row = this.closest('tr');
                const applicationId = Number(row?.dataset.applicationId || 0);
                if (!applicationId) {
                    return;
                }
                await updateApplicationStatus(applicationId, 'shortlist', row);
            });
        });

        document.querySelectorAll('.action-btn.app-reject').forEach(btn => {
            btn.addEventListener('click', async function() {
                if (confirm('Are you sure you want to reject this application?')) {
                    const row = this.closest('tr');
                    const applicationId = Number(row?.dataset.applicationId || 0);
                    if (!applicationId) {
                        return;
                    }
                    await updateApplicationStatus(applicationId, 'reject', row);
                }
            });
        });

        document.getElementById('modalReview').addEventListener('click', async function() {
            if (!activeApplicationId) {
                return;
            }
            const row = document.querySelector(`tr[data-application-id="${activeApplicationId}"]`);
            const ok = await updateApplicationStatus(activeApplicationId, 'review', row);
            if (ok) {
                modal.classList.remove('active');
            }
        });

        document.getElementById('modalShortlist').addEventListener('click', async function() {
            if (!activeApplicationId) {
                return;
            }
            const row = document.querySelector(`tr[data-application-id="${activeApplicationId}"]`);
            const ok = await updateApplicationStatus(activeApplicationId, 'shortlist', row);
            if (ok) {
                modal.classList.remove('active');
            }
        });

        document.getElementById('modalReject').addEventListener('click', async function() {
            if (!activeApplicationId) {
                return;
            }
            const row = document.querySelector(`tr[data-application-id="${activeApplicationId}"]`);
            const ok = await updateApplicationStatus(activeApplicationId, 'reject', row);
            if (ok) {
                modal.classList.remove('active');
            }
        });

        // Bulk actions
        document.getElementById('bulkShortlist').addEventListener('click', async function() {
            const selected = document.querySelectorAll('.row-select:checked');
            for (const cb of selected) {
                const row = cb.closest('tr');
                const applicationId = Number(row?.dataset.applicationId || 0);
                if (applicationId) {
                    await updateApplicationStatus(applicationId, 'shortlist', row);
                }
                cb.checked = false;
            }
            document.getElementById('selectAll').checked = false;
            updateBulkActions();
            window.companyDashboard.showToast(`${selected.length} applications shortlisted!`, 'success');
        });

        document.getElementById('bulkReject').addEventListener('click', async function() {
            if (confirm('Are you sure you want to reject the selected applications?')) {
                const selected = document.querySelectorAll('.row-select:checked');
                for (const cb of selected) {
                    const row = cb.closest('tr');
                    const applicationId = Number(row?.dataset.applicationId || 0);
                    if (applicationId) {
                        await updateApplicationStatus(applicationId, 'reject', row);
                    }
                    cb.checked = false;
                }
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

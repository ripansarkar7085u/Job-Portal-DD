<?php
// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if company is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: /index.php');
    exit;
}

// Redirect user accounts to user dashboard
if (!isset($_SESSION['account_type']) || $_SESSION['account_type'] !== 'company') {
    header('Location: /user/dashboard.php');
    exit;
}

// Get company info from session
$companyName = $_SESSION['company_name'] ?? 'Company';
$companyEmail = $_SESSION['company_email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard - CareerHunt</title>
    <link rel="stylesheet" href="css/company.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>
    <!-- Company Dashboard -->
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
                    <li class="nav-item active" data-page="index.php">
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
                    <img id="companyAvatar" src="https://ui-avatars.com/api/?name=<?php echo urlencode($companyName); ?>&background=0d47a1&color=fff" alt="Company">
                    <div class="company-info">
                        <span class="company-name" id="companyNameDisplay"><?php echo htmlspecialchars($companyName); ?></span>
                        <span class="company-role">Business Account</span>
                    </div>
                </div>
                <a href="../api/logout.php" class="logout-btn" id="logoutBtn" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
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
                    <h1 class="page-title">Dashboard</h1>
                </div>
                <div class="header-right">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Search..." id="globalSearch">
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
                            <a href="../api/logout.php" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <section class="content-section">
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon jobs">
                            <i class="bi bi-briefcase-fill"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Total Jobs Posted</h3>
                            <p class="stat-number" id="totalJobs">24</p>
                            <span class="stat-change positive">
                                <i class="bi bi-arrow-up"></i> 4 this month
                            </span>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon active-jobs">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Active Jobs</h3>
                            <p class="stat-number" id="activeJobs">18</p>
                            <span class="stat-change positive">
                                <i class="bi bi-arrow-up"></i> 2 new this week
                            </span>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon applications">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Total Applications</h3>
                            <p class="stat-number" id="totalApplications">156</p>
                            <span class="stat-change positive">
                                <i class="bi bi-arrow-up"></i> 23 this week
                            </span>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon views">
                            <i class="bi bi-eye-fill"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Profile Views</h3>
                            <p class="stat-number" id="profileViews">1,248</p>
                            <span class="stat-change positive">
                                <i class="bi bi-arrow-up"></i> 15% from last month
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Main Dashboard Grid -->
                <div class="dashboard-grid">
                    <!-- Recent Applications -->
                    <div class="dashboard-card recent-applications">
                        <div class="card-header">
                            <h2><i class="bi bi-people"></i> Recent Applications</h2>
                            <a href="applications.php" class="view-all-btn">View All <i class="bi bi-arrow-right"></i></a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Applicant</th>
                                            <th>Position</th>
                                            <th>Applied Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="recentApplicationsTable">
                                        <tr>
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
                                            <td>Mar 11, 2026</td>
                                            <td><span class="status-badge pending">Pending</span></td>
                                            <td>
                                                <button class="action-btn view" title="View"><i class="bi bi-eye"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/company.js?v=<?php echo filemtime(__DIR__ . '/js/company.js'); ?>"></script>
</body>
</html>

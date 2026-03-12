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
    <title>Super Admin Dashboard - CareerHunt</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>
    <!-- Admin Login Page -->
    <div class="admin-login-container" id="adminLoginContainer">
        <div class="admin-login-card">
            <div class="login-header">
                <a href="../index.php" class="logo">
                    <i class="bi bi-briefcase-fill"></i>
                    <span>CareerHunt</span>
                </a>
                <h1>Admin Login</h1>
                <p>Enter your credentials to access the admin panel</p>
            </div>
            <form id="adminLoginForm" class="login-form">
                <div class="form-group">
                    <label for="adminUsername">Username</label>
                    <div class="input-wrapper">
                        <i class="bi bi-person-fill"></i>
                        <input type="text" id="adminUsername" name="username" placeholder="Enter your username" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="adminPassword">Password</label>
                    <div class="input-wrapper">
                        <i class="bi bi-lock-fill"></i>
                        <input type="password" id="adminPassword" name="password" placeholder="Enter your password" required>
                        <button type="button" class="toggle-password" id="togglePassword">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                    </div>
                </div>
                <div class="form-error" id="loginError"></div>
                <button type="submit" class="btn-login" id="loginBtn">
                    <span>Login</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>
            <div class="login-footer">
                <a href="../index.php"><i class="bi bi-arrow-left"></i> Back to Website</a>
            </div>
        </div>
    </div>

    <!-- Admin Dashboard (hidden until login) -->
    <div class="admin-container" id="adminDashboard" style="display: none;">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="../index.php" class="logo">
                    <img src="/photos/job logo.png" alt="CareerHunt">
                </a>
                <span class="admin-badge" id="roleBadge">Super Admin</span>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li class="nav-item active" data-section="dashboard">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </li>
                    <li class="nav-item" data-section="users">
                        <i class="bi bi-people-fill"></i>
                        <span>Users</span>
                    </li>
                    <li class="nav-item" data-section="companies">
                        <i class="bi bi-building"></i>
                        <span>Companies</span>
                    </li>
                    <li class="nav-item" data-section="jobs">
                        <i class="bi bi-file-earmark-text-fill"></i>
                        <span>Jobs</span>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <div class="admin-profile">
                    <img id="adminAvatar" src="https://ui-avatars.com/api/?name=Admin&background=0d47a1&color=fff" alt="Admin">
                    <div class="admin-info">
                        <span class="admin-name" id="adminNameDisplay">Admin</span>
                        <span class="admin-role" id="adminRoleDisplay">Super Admin</span>
                    </div>
                </div>
                <button class="logout-btn" id="adminLogoutBtn">
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
                    <h1 class="page-title">Dashboard</h1>
                </div>
                <div class="header-right">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Search..." id="globalSearch">
                    </div>
                    <button class="notification-btn">
                        <i class="bi bi-bell-fill"></i>
                        <span class="notification-badge">3</span>
                    </button>
                </div>
            </header>

            <!-- Dashboard Section -->
            <section class="content-section active" id="dashboard">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon users">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Total Users</h3>
                            <p class="stat-number" id="totalUsers">0</p>
                            <span class="stat-change positive">
                                <i class="bi bi-arrow-up"></i> 12% from last month
                            </span>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon companies">
                            <i class="bi bi-building"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Total Companies</h3>
                            <p class="stat-number" id="totalCompanies">0</p>
                            <span class="stat-change positive">
                                <i class="bi bi-arrow-up"></i> 8% from last month
                            </span>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon jobs">
                            <i class="bi bi-briefcase-fill"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Active Jobs</h3>
                            <p class="stat-number" id="totalJobs">0</p>
                            <span class="stat-change positive">
                                <i class="bi bi-arrow-up"></i> 15% from last month
                            </span>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon blocked">
                            <i class="bi bi-slash-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Blocked Accounts</h3>
                            <p class="stat-number" id="blockedCount">0</p>
                            <span class="stat-change negative">
                                <i class="bi bi-arrow-down"></i> 3% from last month
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="dashboard-grid">
                    <div class="card recent-users">
                        <div class="card-header">
                            <h2>Recent Users</h2>
                            <a href="#" class="view-all" data-target="users">View All</a>
                        </div>
                        <div class="card-body">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Joined</th>
                                    </tr>
                                </thead>
                                <tbody id="recentUsersTable">
                                    <!-- Populated by JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="card recent-companies">
                        <div class="card-header">
                            <h2>Recent Companies</h2>
                            <a href="#" class="view-all" data-target="companies">View All</a>
                        </div>
                        <div class="card-body">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Company</th>
                                        <th>Industry</th>
                                        <th>Status</th>
                                        <th>Jobs</th>
                                    </tr>
                                </thead>
                                <tbody id="recentCompaniesTable">
                                    <!-- Populated by JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Users Section -->
            <section class="content-section" id="users">
                <div class="section-header">
                    <h2>User Management</h2>
                    <div class="section-actions">
                        <div class="filter-dropdown">
                            <select id="userFilter">
                                <option value="all">All Users</option>
                                <option value="active">Active</option>
                                <option value="blocked">Blocked</option>
                            </select>
                        </div>
                        <div class="search-box">
                            <i class="bi bi-search"></i>
                            <input type="text" placeholder="Search users..." id="userSearch">
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <table class="data-table full-table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
                                <!-- Populated by JS -->
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <div class="pagination">
                            <button class="page-btn" id="userPrevPage"><i class="bi bi-chevron-left"></i></button>
                            <span class="page-info" id="userPageInfo">Page 1 of 1</span>
                            <button class="page-btn" id="userNextPage"><i class="bi bi-chevron-right"></i></button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Companies Section -->
            <section class="content-section" id="companies">
                <div class="section-header">
                    <h2>Company Management</h2>
                    <div class="section-actions">
                        <div class="filter-dropdown">
                            <select id="companyFilter">
                                <option value="all">All Companies</option>
                                <option value="active">Active</option>
                                <option value="blocked">Blocked</option>
                            </select>
                        </div>
                        <div class="search-box">
                            <i class="bi bi-search"></i>
                            <input type="text" placeholder="Search companies..." id="companySearch">
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <table class="data-table full-table">
                            <thead>
                                <tr>
                                    <th>Company</th>
                                    <th>Industry</th>
                                    <th>Email</th>
                                    <th>Jobs Posted</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="companiesTableBody">
                                <!-- Populated by JS -->
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <div class="pagination">
                            <button class="page-btn" id="companyPrevPage"><i class="bi bi-chevron-left"></i></button>
                            <span class="page-info" id="companyPageInfo">Page 1 of 1</span>
                            <button class="page-btn" id="companyNextPage"><i class="bi bi-chevron-right"></i></button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Jobs Section -->
            <section class="content-section" id="jobs">
                <div class="section-header">
                    <h2>Job Management</h2>
                    <div class="section-actions">
                        <div class="filter-dropdown">
                            <select id="jobFilter">
                                <option value="all">All Jobs</option>
                                <option value="active">Active</option>
                                <option value="expired">Expired</option>
                            </select>
                        </div>
                        <div class="search-box">
                            <i class="bi bi-search"></i>
                            <input type="text" placeholder="Search jobs..." id="jobSearch">
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <table class="data-table full-table">
                            <thead>
                                <tr>
                                    <th>Job Title</th>
                                    <th>Company</th>
                                    <th>Location</th>
                                    <th>Type</th>
                                    <th>Posted</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="jobsTableBody">
                                <!-- Populated by JS -->
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <div class="pagination">
                            <button class="page-btn" id="jobPrevPage"><i class="bi bi-chevron-left"></i></button>
                            <span class="page-info" id="jobPageInfo">Page 1 of 1</span>
                            <button class="page-btn" id="jobNextPage"><i class="bi bi-chevron-right"></i></button>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal" id="confirmModal">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Confirm Action</h3>
                <button class="modal-close" id="modalClose">&times;</button>
            </div>
            <div class="modal-body">
                <p id="modalMessage">Are you sure you want to proceed?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="modalCancel">Cancel</button>
                <button class="btn btn-danger" id="modalConfirm">Confirm</button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <i class="toast-icon bi bi-check-circle-fill"></i>
        <span class="toast-message">Action completed successfully</span>
    </div>

    <script src="js/admin.js"></script>
</body>
</html>

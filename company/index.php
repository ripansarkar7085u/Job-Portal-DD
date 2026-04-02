<?php
require_once __DIR__ . '/../config/database.php';
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
    header('Location: /user/index.php');
    exit;
}

// Get company info from session
$companyName = $_SESSION['company_name'] ?? 'Company';
$companyEmail = $_SESSION['company_email'] ?? '';

// Fetch recent applicants 
$recentApplications = [];
$companyId = $_SESSION['company_id'] ?? 0;
if ($companyId) {
    $sql = "SELECT a.id, a.user_id, a.job_id, a.status, a.applied_at, j.title AS job_title, u.full_name, u.email, p.profile_image
            FROM user_job_applications a
            INNER JOIN jobs j ON j.id = a.job_id
            INNER JOIN users u ON u.id = a.user_id
            LEFT JOIN profiles p ON p.user_id = u.id
            WHERE j.company_id = ?
            ORDER BY a.applied_at DESC
            LIMIT 5";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('i', $companyId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($result && ($row = $result->fetch_assoc())) {
            $recentApplications[] = $row;
        }
        $stmt->close();
    }

    $companyStats = [
        'total_jobs' => 0,
        'active_jobs' => 0,
        'total_applications' => 0,
        'profile_views' => rand(100, 500) // Aesthetic placeholder
    ];

    $stmtStats = $conn->prepare("SELECT 
        (SELECT COUNT(*) FROM jobs WHERE company_id = ?) AS total_jobs,
        (SELECT COUNT(*) FROM jobs WHERE company_id = ? AND status = 'open') AS active_jobs,
        (SELECT COUNT(*) FROM user_job_applications a INNER JOIN jobs j ON j.id = a.job_id WHERE j.company_id = ?) AS total_apps");

    if ($stmtStats) {
        $stmtStats->bind_param('iii', $companyId, $companyId, $companyId);
        $stmtStats->execute();
        $resultStats = $stmtStats->get_result();
        if ($resultStats && $statsRow = $resultStats->fetch_assoc()) {
            $companyStats['total_jobs'] = (int)$statsRow['total_jobs'];
            $companyStats['active_jobs'] = (int)$statsRow['active_jobs'];
            $companyStats['total_applications'] = (int)$statsRow['total_apps'];
        }
        $stmtStats->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Company Dashboard - CareerHunt</title>
    <link rel="stylesheet" href="css/company.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>
    <!-- Company Dashboard -->
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
                            <button class="dropdown-item text-danger" id="dropdownLogout">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <section class="content-section">
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <!-- Total Jobs -->
                    <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border: none; box-shadow: 0 10px 20px rgba(245,87,108,0.3); border-radius: 16px; position: relative; overflow: hidden; padding: 30px; display: block;">
                        <div style="position: absolute; top: -30px; right: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.15); border-radius: 50%;"></div>
                        <div style="position: absolute; bottom: -20px; right: 20px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                        <div class="stat-icon" style="background: rgba(255,255,255,0.25); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); color: white; width: 64px; height: 64px; font-size: 1.8rem;"><i class="bi bi-briefcase-fill"></i></div>
                        <div class="stat-info" style="margin-top: 15px;">
                            <p style="color: rgba(255,255,255,0.9); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; margin: 0;">Total Jobs Posted</p>
                            <h4 style="color: white; font-size: 2.5rem; font-weight: 700; margin: 5px 0 0 0; text-shadow: 0 2px 4px rgba(0,0,0,0.1);"><?php echo (int)($companyStats['total_jobs'] ?? 0); ?></h4>
                        </div>
                    </div>
                    
                    <!-- Active Jobs -->
                    <div class="stat-card" style="background: linear-gradient(135deg, #4ef58f 0%, #00a454 100%); border: none; box-shadow: 0 10px 20px rgba(0,164,84,0.3); border-radius: 16px; position: relative; overflow: hidden; padding: 30px; display: block;">
                        <div style="position: absolute; top: -30px; right: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.15); border-radius: 50%;"></div>
                        <div style="position: absolute; bottom: -20px; right: 20px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                        <div class="stat-icon" style="background: rgba(255,255,255,0.25); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); color: white; width: 64px; height: 64px; font-size: 1.8rem;"><i class="bi bi-check-circle-fill"></i></div>
                        <div class="stat-info" style="margin-top: 15px;">
                            <p style="color: rgba(255,255,255,0.9); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; margin: 0;">Active Jobs</p>
                            <h4 style="color: white; font-size: 2.5rem; font-weight: 700; margin: 5px 0 0 0; text-shadow: 0 2px 4px rgba(0,0,0,0.1);"><?php echo (int)($companyStats['active_jobs'] ?? 0); ?></h4>
                        </div>
                    </div>
                    
                    <!-- Applications -->
                    <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none; box-shadow: 0 10px 20px rgba(0,242,254,0.3); border-radius: 16px; position: relative; overflow: hidden; padding: 30px; display: block;">
                        <div style="position: absolute; top: -30px; right: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.15); border-radius: 50%;"></div>
                        <div style="position: absolute; bottom: -20px; right: 20px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                        <div class="stat-icon" style="background: rgba(255,255,255,0.25); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); color: white; width: 64px; height: 64px; font-size: 1.8rem;"><i class="bi bi-people-fill"></i></div>
                        <div class="stat-info" style="margin-top: 15px;">
                            <p style="color: rgba(255,255,255,0.9); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; margin: 0;">Total Applications</p>
                            <h4 style="color: white; font-size: 2.5rem; font-weight: 700; margin: 5px 0 0 0; text-shadow: 0 2px 4px rgba(0,0,0,0.1);"><?php echo (int)($companyStats['total_applications'] ?? 0); ?></h4>
                        </div>
                    </div>
                    
                    <!-- Views -->
                    <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; box-shadow: 0 10px 20px rgba(102,126,234,0.3); border-radius: 16px; position: relative; overflow: hidden; padding: 30px; display: block;">
                        <div style="position: absolute; top: -30px; right: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.15); border-radius: 50%;"></div>
                        <div style="position: absolute; bottom: -20px; right: 20px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                        <div class="stat-icon" style="background: rgba(255,255,255,0.25); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); color: white; width: 64px; height: 64px; font-size: 1.8rem;"><i class="bi bi-eye-fill"></i></div>
                        <div class="stat-info" style="margin-top: 15px;">
                            <p style="color: rgba(255,255,255,0.9); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; margin: 0;">Company Profile Views</p>
                            <h4 style="color: white; font-size: 2.5rem; font-weight: 700; margin: 5px 0 0 0; text-shadow: 0 2px 4px rgba(0,0,0,0.1);"><?php echo number_format($companyStats['profile_views'] ?? 0); ?></h4>
                        </div>
                    </div>
                </div>

                <!-- Main Dashboard Grid -->
                <div class="dashboard-grid">
                    <!-- Recent Applications -->
                    <div class="dashboard-card recent-applications">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h2 class="mb-0"><i class="bi bi-people"></i> Recent Applications</h2>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="date" id="dateFilter" class="form-control form-control-sm" title="Filter by Application Date" style="max-width: 150px;">
                                <a href="applications.php" class="view-all-btn text-nowrap">View All <i class="bi bi-arrow-right"></i></a>
                            </div>
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
                                        <?php if (empty($recentApplications)): ?>
                                            <tr><td colspan="5" class="text-center text-muted">No recent applicants found.</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($recentApplications as $app): ?>
                                                <tr class="app-row" data-raw-date="<?php echo date('Y-m-d', strtotime($app['applied_at'])); ?>" data-status="<?php echo strtolower($app['status']); ?>">
                                                    <td data-label="Applicant">
                                                        <div class="applicant-info">
                                                            <img src="<?php echo !empty($app['profile_image']) ? (strpos($app['profile_image'], 'http') === 0 ? $app['profile_image'] : '../user/uploads/' . $app['profile_image']) : 'https://ui-avatars.com/api/?name=' . urlencode($app['full_name']) . '&background=0d47a1&color=fff'; ?>" alt="<?php echo htmlspecialchars($app['full_name']); ?>">
                                                            <div>
                                                                <span class="name"><?php echo htmlspecialchars($app['full_name']); ?></span>
                                                                <span class="email"><?php echo htmlspecialchars($app['email']); ?></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td data-label="Position"><?php echo htmlspecialchars($app['job_title']); ?></td>
                                                    <td data-label="Applied Date"><?php echo date('M d, Y', strtotime($app['applied_at'])); ?></td>
                                                    <td data-label="Status"><span class="status-badge <?php echo strtolower($app['status']); ?>"><?php echo htmlspecialchars($app['status']); ?></span></td>
                                                    <td data-label="Action">
                                                        <a href="applications.php?app_id=<?php echo $app['id']; ?>" class="action-btn view" title="View"><i class="bi bi-eye"></i></a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
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
    <script>
    // Sidebar/menu/profile dropdown logic (ensure always initialized)
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof handleNavigation === 'function') handleNavigation();
        if (typeof menuToggle !== 'undefined' && typeof sidebar !== 'undefined' && typeof sidebarOverlay !== 'undefined') {
            menuToggle.addEventListener('click', toggleSidebar);
            sidebarOverlay.addEventListener('click', closeSidebar);
        }
        if (typeof profileBtn !== 'undefined' && typeof profileDropdown !== 'undefined') {
            profileBtn.addEventListener('click', toggleProfileDropdown);
            document.addEventListener('click', closeProfileDropdown);
        }

        // Search and Filter implementation
        const globalSearch = document.getElementById('globalSearch');
        const dateFilter = document.getElementById('dateFilter');
        const rows = document.querySelectorAll('.app-row');
        
        function applyFilters() {
            const query = globalSearch ? globalSearch.value.toLowerCase() : '';
            const dateQuery = dateFilter ? dateFilter.value : '';
            
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const rawDate = row.dataset.rawDate || '';
                
                const matchSearch = query === '' || text.includes(query);
                const matchDate = dateQuery === '' || rawDate === dateQuery;
                
                if (matchSearch && matchDate) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        if (globalSearch) globalSearch.addEventListener('input', applyFilters);
        if (dateFilter) dateFilter.addEventListener('change', applyFilters);
    });
    </script>
</body>
</html>

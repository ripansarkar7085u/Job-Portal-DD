<?php
require_once __DIR__ . '/_user_common.php';

$userId = user_require_login();
user_ensure_applications_table($conn);
user_ensure_messages_table($conn);
user_ensure_alerts_table($conn);


$stats = [
    'applied_jobs' => 0,
    'shortlisted' => 0,
    'alerts' => 0,
    'messages' => 0,
];

// Fetch user profile image
$profile_image_src = 'https://ui-avatars.com/api/?name=User&background=0d47a1&color=fff';
$stmtProfile = $conn->prepare('SELECT profile_image, full_name FROM profiles WHERE user_id = ? LIMIT 1');
if ($stmtProfile) {
    $stmtProfile->bind_param('i', $userId);
    $stmtProfile->execute();
    $resultProfile = $stmtProfile->get_result();
    $profileData = $resultProfile ? $resultProfile->fetch_assoc() : null;
    if ($profileData && !empty($profileData['profile_image'])) {
        $profile_image_src = (strpos($profileData['profile_image'], 'http') !== false)
            ? $profileData['profile_image']
            : 'uploads/' . $profileData['profile_image'];
    }
    $profile_full_name = $profileData && !empty($profileData['full_name']) ? $profileData['full_name'] : 'Candidate';
    $stmtProfile->close();
} else {
    $profile_full_name = 'Candidate';
}

$stmtStats = $conn->prepare("SELECT
    (SELECT COUNT(*) FROM user_job_applications WHERE user_id = ?) AS applied_jobs,
    (SELECT COUNT(*) FROM user_job_applications WHERE user_id = ? AND LOWER(status) = 'shortlisted') AS shortlisted,
    (SELECT COUNT(*) FROM user_alerts WHERE user_id = ? AND is_read = 0) AS alerts,
    (SELECT COUNT(*) FROM user_messages WHERE user_id = ? AND sender_type = 'company' AND is_read = 0) AS messages");

if ($stmtStats) {
    $stmtStats->bind_param('iiii', $userId, $userId, $userId, $userId);
    $stmtStats->execute();
    $resultStats = $stmtStats->get_result();
    $row = $resultStats ? $resultStats->fetch_assoc() : null;
    if ($row) {
        $stats = [
            'applied_jobs' => (int) $row['applied_jobs'],
            'shortlisted' => (int) $row['shortlisted'],
            'alerts' => (int) $row['alerts'],
            'messages' => (int) $row['messages'],
        ];
    }
    $stmtStats->close();
}

$recentApplied = [];
$stmtRecent = $conn->prepare("SELECT a.applied_at, a.status, j.id AS job_id, j.title, c.company_name
    FROM user_job_applications a
    INNER JOIN jobs j ON j.id = a.job_id
    INNER JOIN companies c ON c.id = j.company_id
    WHERE a.user_id = ?
    ORDER BY a.applied_at DESC");

if ($stmtRecent) {
    $stmtRecent->bind_param('i', $userId);
    $stmtRecent->execute();
    $resultRecent = $stmtRecent->get_result();
    while ($resultRecent && ($row = $resultRecent->fetch_assoc())) {
        $recentApplied[] = $row;
    }
    $stmtRecent->close();
}

function dashboard_status_class(string $status): string
{
    $normalized = strtolower(trim($status));
    if ($normalized === 'shortlisted') {
        return 'status-active';
    }
    if (in_array($normalized, ['rejected', 'closed'], true)) {
        return 'status-rejected';
    }
    return 'status-pending';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta charset="UTF-8">
    <title>CareerHunt Dashboard</title>
    <link rel="stylesheet" href="user.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body>
    <div class="user-container" id="userDashboard">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <header class="main-header">
                <div class="header-left">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1 class="page-title">Dashboard</h1>
                </div>
                <div class="header-right">
                    <div class="profile-dropdown">
                        <button class="profile-btn" id="profileBtn" onclick="window.location.href='profile.php'">
                            <img src="<?php echo htmlspecialchars($profile_image_src); ?>" alt="User" id="headerAvatar">
                            <span id="headerUserName"><?php echo htmlspecialchars($profile_full_name); ?></span>
                        </button>
                    </div>
                </div>
            </header>

            <section class="content-section">
            <!-- Welcome Section -->
            <div class="mb-4">
                <h3 class="fw-bold">Welcome back, Candidate! 👋</h3>
                <p class="text-muted">Here is what's happening with your job applications today.</p>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <!-- Applied Jobs -->
                <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none; box-shadow: 0 10px 20px rgba(0,242,254,0.3); border-radius: 16px; position: relative; overflow: hidden; padding: 30px;">
                    <div style="position: absolute; top: -30px; right: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.15); border-radius: 50%;"></div>
                    <div style="position: absolute; bottom: -20px; right: 20px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                    <div class="stat-icon" style="background: rgba(255,255,255,0.25); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); color: white; width: 64px; height: 64px; font-size: 1.8rem;"><i class="bi bi-briefcase"></i></div>
                    <div class="stat-info" style="margin-top: 15px;">
                        <p style="color: rgba(255,255,255,0.9); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; margin: 0;">Applied Jobs</p>
                        <h4 style="color: white; font-size: 2.5rem; font-weight: 700; margin: 5px 0 0 0; text-shadow: 0 2px 4px rgba(0,0,0,0.1);"><?php echo (int) $stats['applied_jobs']; ?></h4>
                    </div>
                </div>

                <!-- Shortlisted -->
                <div class="stat-card" style="background: linear-gradient(135deg, #ff0844 0%, #ffb199 100%); border: none; box-shadow: 0 10px 20px rgba(255,8,68,0.3); border-radius: 16px; position: relative; overflow: hidden; padding: 30px;">
                    <div style="position: absolute; top: -30px; right: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.15); border-radius: 50%;"></div>
                    <div style="position: absolute; bottom: -20px; right: 20px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                    <div class="stat-icon" style="background: rgba(255,255,255,0.25); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); color: white; width: 64px; height: 64px; font-size: 1.8rem;"><i class="bi bi-star-fill"></i></div>
                    <div class="stat-info" style="margin-top: 15px;">
                        <p style="color: rgba(255,255,255,0.9); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; margin: 0;">Shortlisted</p>
                        <h4 style="color: white; font-size: 2.5rem; font-weight: 700; margin: 5px 0 0 0; text-shadow: 0 2px 4px rgba(0,0,0,0.1);"><?php echo (int) $stats['shortlisted']; ?></h4>
                    </div>
                </div>

                <!-- Job Alerts -->
                <div class="stat-card" style="background: linear-gradient(135deg, #0ba360 0%, #3cba92 100%); border: none; box-shadow: 0 10px 20px rgba(11,163,96,0.3); border-radius: 16px; position: relative; overflow: hidden; padding: 30px;">
                    <div style="position: absolute; top: -30px; right: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.15); border-radius: 50%;"></div>
                    <div style="position: absolute; bottom: -20px; right: 20px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                    <div class="stat-icon" style="background: rgba(255,255,255,0.25); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); color: white; width: 64px; height: 64px; font-size: 1.8rem;"><i class="bi bi-bell-fill"></i></div>
                    <div class="stat-info" style="margin-top: 15px;">
                        <p style="color: rgba(255,255,255,0.9); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; margin: 0;">Job Alerts</p>
                        <h4 style="color: white; font-size: 2.5rem; font-weight: 700; margin: 5px 0 0 0; text-shadow: 0 2px 4px rgba(0,0,0,0.1);"><?php echo (int) $stats['alerts']; ?></h4>
                    </div>
                </div>

                <!-- Messages -->
                <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; box-shadow: 0 10px 20px rgba(102,126,234,0.3); border-radius: 16px; position: relative; overflow: hidden; padding: 30px;">
                    <div style="position: absolute; top: -30px; right: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.15); border-radius: 50%;"></div>
                    <div style="position: absolute; bottom: -20px; right: 20px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                    <div class="stat-icon" style="background: rgba(255,255,255,0.25); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); color: white; width: 64px; height: 64px; font-size: 1.8rem;"><i class="bi bi-chat-dots-fill"></i></div>
                    <div class="stat-info" style="margin-top: 15px;">
                        <p style="color: rgba(255,255,255,0.9); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; margin: 0;">Messages</p>
                        <h4 style="color: white; font-size: 2.5rem; font-weight: 700; margin: 5px 0 0 0; text-shadow: 0 2px 4px rgba(0,0,0,0.1);"><?php echo (int) $stats['messages']; ?></h4>
                    </div>
                </div>
            </div>

            <!-- Recent Jobs Table -->
            <div class="dashboard-card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h2 class="mb-0"><i class="bi bi-clock-history"></i> Recent Applied Jobs</h2>
                    <div class="d-flex gap-2 align-items-center">
                        <input type="text" id="appliedJobSearch" class="form-control form-control-sm" placeholder="Search jobs/companies..." style="max-width: 200px;">
                        <a href="applied.php" class="view-all-btn text-nowrap">View All <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <div class="table-responsive card-body p-0">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>Company</th>
                                <th>Date Applied</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentApplied)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No applications found for your
                                        account.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentApplied as $application): ?>
                                    <tr class="job-row">
                                        <td data-label="Job Title"><strong><?php echo user_esc((string) $application['title']); ?></strong></td>
                                        <td data-label="Company"><?php echo user_esc((string) $application['company_name']); ?></td>
                                        <td data-label="Date Applied"><?php echo user_esc(date('M j, Y', strtotime((string) $application['applied_at']))); ?></td>
                                        <td data-label="Status"><span class="status-badge <?php echo dashboard_status_class((string) $application['status']); ?>"><?php echo user_esc(ucfirst((string) $application['status'])); ?></span></td>
                                        <td data-label="Action"><a class="action-btn view" href="../job-details.php?id=<?php echo (int) $application['job_id']; ?>"><i class="bi bi-eye"></i></a></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="pagination"></div>
            </div>
            </section>
        </main>
    </div>

    <!-- No user.js here because pagination is self-contained -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const rows = Array.from(document.querySelectorAll('.job-row'));
            if (!rows.length) return;
            
            const perPage = 5;
            let currentPage = 1;
            const paginationContainer = document.querySelector('.pagination');
            
            const searchInput = document.getElementById('appliedJobSearch');
            let searchQuery = '';
            let filteredRows = [...rows];
            
            function render() {
                const start = (currentPage - 1) * perPage;
                const end = start + perPage;
                
                rows.forEach(row => row.style.display = 'none');
                
                filteredRows.forEach((row, idx) => {
                    if (idx >= start && idx < end) row.style.display = '';
                });
                
                renderPagination();
            }
            
            function applySearch() {
                filteredRows = rows.filter(row => row.innerText.toLowerCase().includes(searchQuery));
                currentPage = 1;
                render();
            }

            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    searchQuery = e.target.value.toLowerCase();
                    applySearch();
                });
            }
            
            function renderPagination() {
                if (!paginationContainer) return;
                paginationContainer.innerHTML = '';
                
                let totalPages = Math.ceil(filteredRows.length / perPage);
                if (totalPages < 1) totalPages = 1;

                const prevBtn = document.createElement('button');
                prevBtn.className = 'pagination-btn';
                prevBtn.innerHTML = '<i class="bi bi-chevron-left"></i>';
                prevBtn.disabled = currentPage === 1;
                prevBtn.onclick = () => { if (currentPage > 1) { currentPage--; render(); } };
                paginationContainer.appendChild(prevBtn);
                
                for (let i = 1; i <= totalPages; i++) {
                    const pageBtn = document.createElement('button');
                    pageBtn.className = `pagination-btn ${i === currentPage ? 'active' : ''}`;
                    pageBtn.innerText = i;
                    pageBtn.onclick = () => { currentPage = i; render(); };
                    paginationContainer.appendChild(pageBtn);
                }
                
                const nextBtn = document.createElement('button');
                nextBtn.className = 'pagination-btn';
                nextBtn.innerHTML = '<i class="bi bi-chevron-right"></i>';
                nextBtn.disabled = currentPage === totalPages;
                nextBtn.onclick = () => { if (currentPage < totalPages) { currentPage++; render(); } };
                paginationContainer.appendChild(nextBtn);
            }
            
            render();
        });
    </script>
</body>
</html>

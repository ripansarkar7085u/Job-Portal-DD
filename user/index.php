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
    (SELECT COUNT(*) FROM messages WHERE receiver_id = ? AND receiver_type = 'user' AND is_read = 0) AS messages");


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
                <div class="stat-card" style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); border: none; box-shadow: 0 4px 15px rgba(25,118,210,0.15); transform: translateY(-5px); transition: all 0.3s ease;">
                    <div class="stat-icon blue" style="background: rgba(255,255,255,0.7); box-shadow: 0 2px 10px rgba(0,0,0,0.05);"><i class="bi bi-briefcase"></i></div>
                    <div class="stat-info">
                        <h4 style="color: #0d47a1;"><?php echo (int) $stats['applied_jobs']; ?></h4>
                        <p style="color: #1565c0; font-weight: 500;">Applied Jobs</p>
                    </div>
                </div>
                <div class="stat-card" style="background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%); border: none; box-shadow: 0 4px 15px rgba(245,124,0,0.15); transform: translateY(-5px); transition: all 0.3s ease;">
                    <div class="stat-icon orange" style="background: rgba(255,255,255,0.7); box-shadow: 0 2px 10px rgba(0,0,0,0.05);"><i class="bi bi-star"></i></div>
                    <div class="stat-info">
                        <h4 style="color: #e65100;"><?php echo (int) $stats['shortlisted']; ?></h4>
                        <p style="color: #ef6c00; font-weight: 500;">Shortlisted</p>
                    </div>
                </div>
                <div class="stat-card" style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); border: none; box-shadow: 0 4px 15px rgba(56,142,60,0.15); transform: translateY(-5px); transition: all 0.3s ease;">
                    <div class="stat-icon green" style="background: rgba(255,255,255,0.7); box-shadow: 0 2px 10px rgba(0,0,0,0.05);"><i class="bi bi-bell"></i></div>
                    <div class="stat-info">
                        <h4 style="color: #1b5e20;"><?php echo (int) $stats['alerts']; ?></h4>
                        <p style="color: #2e7d32; font-weight: 500;">Job Alerts</p>
                    </div>
                </div>
                <div class="stat-card" style="background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%); border: none; box-shadow: 0 4px 15px rgba(123,31,162,0.15); transform: translateY(-5px); transition: all 0.3s ease;">
                    <div class="stat-icon purple" style="background: rgba(255,255,255,0.7); box-shadow: 0 2px 10px rgba(0,0,0,0.05);"><i class="bi bi-chat-dots"></i></div>
                    <div class="stat-info">
                        <h4 style="color: #4a148c;"><?php echo (int) $stats['messages']; ?></h4>
                        <p style="color: #6a1b9a; font-weight: 500;">Messages</p>
                    </div>
                </div>
            </div>

            <!-- Recent Jobs Table -->
            <div class="dashboard-card mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-clock-history"></i> Recent Applied Jobs</h2>
                    <a href="applied.php" class="view-all-btn">View All <i class="bi bi-arrow-right"></i></a>
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
                                        <td><strong><?php echo user_esc((string) $application['title']); ?></strong></td>
                                        <td><?php echo user_esc((string) $application['company_name']); ?></td>
                                        <td><?php echo user_esc(date('M j, Y', strtotime((string) $application['applied_at']))); ?></td>
                                        <td><span class="status-badge <?php echo dashboard_status_class((string) $application['status']); ?>"><?php echo user_esc(ucfirst((string) $application['status'])); ?></span></td>
                                        <td><a class="action-btn view" href="../job-details.php?id=<?php echo (int) $application['job_id']; ?>"><i class="bi bi-eye"></i></a></td>
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
            
            function render() {
                const start = (currentPage - 1) * perPage;
                const end = start + perPage;
                
                rows.forEach((row, idx) => {
                    row.style.display = (idx >= start && idx < end) ? '' : 'none';
                });
                
                renderPagination();
            }
            
            function renderPagination() {
                if (!paginationContainer) return;
                paginationContainer.innerHTML = '';
                
                let totalPages = Math.ceil(rows.length / perPage);
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

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
    ORDER BY a.applied_at DESC
    LIMIT 5");

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
    <meta charset="UTF-8">
    <title>CareerHunt Dashboard</title>
    <link rel="stylesheet" href="user.css">
    <link rel="stylesheet" href="user\css\index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <nav class="top-navbar">
            <div class="nav-left">
                <h5 class="mb-0">CareerHunt Dashboard</h5>
            </div>
            <div class="nav-right">
                <i class="bi bi-search"></i>
                <i class="bi bi-bell"></i>
                <div class="profile-box">
                    <img src="<?php echo htmlspecialchars($profile_image_src); ?>" class="nav-profile"
                        style="object-fit:cover;width:40px;height:40px;">
                    <span><?php echo htmlspecialchars($profile_full_name); ?></span>
                </div>
            </div>
        </nav>

        <div class="container-fluid p-4">
            <!-- Welcome Section -->
            <div class="mb-4">
                <h3 class="fw-bold">Welcome back, Candidate! 👋</h3>
                <p class="text-muted">Here is what's happening with your job applications today.</p>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="bi bi-briefcase"></i></div>
                    <div class="stat-info">
                        <h4><?php echo (int) $stats['applied_jobs']; ?></h4>
                        <p>Applied Jobs</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange"><i class="bi bi-star"></i></div>
                    <div class="stat-info">
                        <h4><?php echo (int) $stats['shortlisted']; ?></h4>
                        <p>Shortlisted</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><i class="bi bi-bell"></i></div>
                    <div class="stat-info">
                        <h4><?php echo (int) $stats['alerts']; ?></h4>
                        <p>Job Alerts</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="bi bi-chat-dots"></i></div>
                    <div class="stat-info">
                        <h4><?php echo (int) $stats['messages']; ?></h4>
                        <p>Messages</p>
                    </div>
                </div>
            </div>

            <!-- Recent Jobs Table -->
            <div class="content-card mt-4">
                <div class="card-header-flex">
                    <h5>Recent Applied Jobs</h5>
                    <a href="applied.php" class="btn-view-all">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table custom-table">
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
                                    <tr>
                                        <td><strong><?php echo user_esc((string) $application['title']); ?></strong></td>
                                        <td><?php echo user_esc((string) $application['company_name']); ?></td>
                                        <td><?php echo user_esc(date('M j, Y', strtotime((string) $application['applied_at']))); ?>
                                        </td>
                                        <td><span
                                                class="badge <?php echo dashboard_status_class((string) $application['status']); ?>"><?php echo user_esc(ucfirst((string) $application['status'])); ?></span>
                                        </td>
                                        <td><a class="btn-action"
                                                href="../job-details.php?id=<?php echo (int) $application['job_id']; ?>"><i
                                                    class="bi bi-eye"></i></a></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="user.js"></script>
</body>

</html>
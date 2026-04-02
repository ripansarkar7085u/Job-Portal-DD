<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$companyName = $_SESSION['company_name'] ?? 'Company';
$companyEmail = $_SESSION['company_email'] ?? '';
$sidebarAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($companyName) . '&background=0d47a1&color=fff';
$companyId = $_SESSION['company_id'] ?? 0;
$currentPage = basename($_SERVER['PHP_SELF'] ?? '');

$unreadCount = 0;
if ($companyId) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, '../api/get_unread_count.php?user_id=' . $companyId . '&user_type=company');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($result, true);
    if (!empty($data['unread'])) {
        $unreadCount = (int)$data['unread'];
    }
}
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="../index.php" class="logo">
            <img src="../photos/job_logo.png" alt="CareerHunt">
        </a>
        <span class="company-badge">Company</span>
    </div>

    <nav class="sidebar-nav">
        <ul>
            <li class="nav-item <?php echo $currentPage === 'index.php' ? 'active' : ''; ?>" data-page="index.php">
                <a href="index.php"><i class="bi bi-grid-1x2-fill"></i> <span>Dashboard</span></a>
            </li>
            <li class="nav-item <?php echo $currentPage === 'job-create.php' ? 'active' : ''; ?>" data-page="job-create.php">
                <a href="job-create.php"><i class="bi bi-plus-circle-fill"></i> <span>Post Job</span></a>
            </li>
            <li class="nav-item <?php echo $currentPage === 'jobs.php' ? 'active' : ''; ?>" data-page="jobs.php">
                <a href="jobs.php"><i class="bi bi-file-earmark-text-fill"></i> <span>Manage Jobs</span></a>
            </li>
            <li class="nav-item <?php echo $currentPage === 'applications.php' ? 'active' : ''; ?>" data-page="applications.php">
                <a href="applications.php"><i class="bi bi-people-fill"></i> <span>Applications</span></a>
            </li>
            <li class="nav-item <?php echo $currentPage === 'messages.php' ? 'active' : ''; ?>" data-page="messages.php">
                <a href="messages.php">
                    <i class="bi bi-chat-dots-fill"></i>
                    <span>Messages<?php if ($unreadCount > 0) { echo ' <span class="badge bg-danger">' . $unreadCount . '</span>'; } ?></span>
                </a>
            </li>
            <li class="nav-item <?php echo $currentPage === 'profile.php' ? 'active' : ''; ?>" data-page="profile.php">
                <a href="profile.php"><i class="bi bi-building"></i> <span>Company Profile</span></a>
            </li>
            <li class="nav-item <?php echo $currentPage === 'settings.php' ? 'active' : ''; ?>" data-page="settings.php">
                <a href="settings.php"><i class="bi bi-gear-fill"></i> <span>Settings</span></a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <div class="company-profile">
            <img id="companyAvatar" src="<?php echo htmlspecialchars($sidebarAvatar); ?>" alt="Company">
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

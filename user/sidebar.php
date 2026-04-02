<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$sidebarUserName = $_SESSION['user_name'] ?? 'Candidate';
$sidebarEmail = $_SESSION['user_email'] ?? '';
$sidebarAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($sidebarUserName) . '&background=0d47a1&color=fff';

// Ensure $conn is available if we want to fetch the real uploaded image
if (isset($conn) && isset($_SESSION['user_id'])) {
    $sidebarId = (int)$_SESSION['user_id'];
    $stmtSidebar = $conn->prepare('SELECT profile_image FROM profiles WHERE user_id = ? LIMIT 1');
    if ($stmtSidebar) {
        $stmtSidebar->bind_param('i', $sidebarId);
        $stmtSidebar->execute();
        $resSidebar = $stmtSidebar->get_result();
        if ($resSidebar && $rowSidebar = $resSidebar->fetch_assoc()) {
            if (!empty($rowSidebar['profile_image'])) {
                $sidebarAvatar = (strpos($rowSidebar['profile_image'], 'http') !== false)
                    ? $rowSidebar['profile_image']
                    : 'uploads/' . $rowSidebar['profile_image'];
            }
        }
        $stmtSidebar->close();
    }
}
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="../index.php" class="logo" style="text-decoration:none;">
            <img src="..\photos\job_logo.png" alt="CareerHunt">
        </a>
        <span class="user-badge">Candidate</span>
    </div>
    
    <nav class="sidebar-nav">
        <ul>
            <a href="index.php" class="nav-item" data-page="index.php">
                <i class="bi bi-house"></i>
                <span>Dashboard</span>
            </a>
            <a href="profile.php" class="nav-item" data-page="profile.php">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
            </a>
            <a href="resume.php" class="nav-item" data-page="resume.php">
                <i class="bi bi-file-earmark-text"></i>
                <span>My CV</span>
            </a>
            <a href="applied.php" class="nav-item" data-page="applied.php">
                <i class="bi bi-briefcase"></i>
                <span>Applied Jobs</span>
            </a>
            <a href="alerts.php" class="nav-item" data-page="alerts.php">
                <i class="bi bi-bell"></i>
                <span>Job Alerts</span>
            </a>

            <a href="messages.php" class="nav-item" data-page="messages.php">
                <i class="bi bi-chat"></i>
                <span>Messages</span>
            </a>
            <a href="password.php" class="nav-item" data-page="password.php">
                <i class="bi bi-lock"></i>
                <span>Change Password</span>
            </a>
            <a href="delete.php" class="nav-item text-danger" data-page="delete.php">
                <i class="bi bi-trash"></i>
                <span>Delete Profile</span>
            </a>
        </ul>
    </nav>
    
    <div class="sidebar-footer">
        <div class="company-profile">
            <img id="sidebarAvatar" src="<?php echo htmlspecialchars($sidebarAvatar); ?>" alt="User" style="object-fit:cover;">
            <div class="company-info">
                <span class="company-name" style="color:var(--text-primary);"><?php echo htmlspecialchars($sidebarUserName); ?></span>
                <span class="company-role" style="color:#666;font-size:0.75rem;">Account</span>
            </div>
        </div>
        <button class="logout-btn" id="sidebarLogoutBtn" title="Logout" onclick="window.location.href='logout.php';">
            <i class="bi bi-box-arrow-right"></i>
        </button>
    </div>
</aside>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let currentLocation = window.location.pathname;
    
    // Default to index.php if at the root user directory
    if (currentLocation.endsWith('/user/')) {
        currentLocation += 'index.php';
    } else if (currentLocation.endsWith('/user')) {
        currentLocation += '/index.php';
    }
    
    const menuItems = document.querySelectorAll(".sidebar-nav .nav-item");
    
    menuItems.forEach(item => {
        item.classList.remove("active");
        
        const href = item.getAttribute("href");
        if (href && href !== "javascript:void(0)" && currentLocation.includes(href)) {
            item.classList.add("active");
        }
    });

    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', () => {
             sidebar.classList.toggle('show');
             if(overlay) overlay.classList.toggle('show');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }
});
</script>

<button class="mobile-toggle" onclick="toggleSidebar()">
    <i class="bi bi-list"></i>
</button>

<div class="sidebar" id="sidebar">
    <div class="image">
        <a href="../index.php">
            <img src="../photos/job_logo.png" alt="CareerHunt" width="120">
        </a>
    </div>

    <div class="sidebar-menu">
        <a href="index.php" class="active"><i class="bi bi-house"></i> Dashboard</a>
        <a href="profile.php"><i class="bi bi-person"></i> My Profile</a>
        <a href="resume.php"><i class="bi bi-file-earmark-text"></i> My CV</a>
        <a href="applied.php"><i class="bi bi-briefcase"></i> Applied Jobs</a>
        <a href="alerts.php"><i class="bi bi-bell"></i> Job Alerts</a>
        <a href="cv.php"><i class="bi bi-file-earmark"></i> CV Manager</a>
        <a href="messages.php"><i class="bi bi-chat"></i> Messages</a>
        <a href="password.php"><i class="bi bi-lock"></i> Change Password</a>
        <a href="logout.php">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
        <a href="delete.php" class="text-danger"><i class="bi bi-trash"></i> Delete Profile</a>
    </div>
</div>

<script>
// 1. Toggle Sidebar
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    if (window.innerWidth <= 768) {
        sidebar.classList.toggle('active');
    } else {
        sidebar.classList.toggle('collapsed');
    }
}

// 2. Auto-Highlight Active Menu Option
document.addEventListener("DOMContentLoaded", function() {
    let currentLocation = window.location.pathname;
    
    // Default to index.php if at the root user directory
    if (currentLocation.endsWith('/user/') || currentLocation.endsWith('/user')) {
        currentLocation += 'index.php';
    }
    
    const menuItems = document.querySelectorAll(".sidebar-menu a.nav-item");
    
    menuItems.forEach(item => {
        item.classList.remove("active");
        
        const href = item.getAttribute("href");
        if (href && href !== "javascript:void(0)" && currentLocation.includes(href)) {
            item.classList.add("active");
        }
    });
});
</script>

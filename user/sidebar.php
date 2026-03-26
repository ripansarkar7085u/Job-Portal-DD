<<<<<<< Updated upstream
<style>

.sidebar {
    width: 250px;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    background: #fff;
    display: flex; /* Forces vertical alignment */
    flex-direction: column;
    border-right: 1px solid #ddd;
    transition: 0.3s;
    z-index: 1000;
}

.sidebar-menu {
    display: flex;
    flex-direction: column; /* Stack links vertically */
    padding: 10px 0;
}

/* Sidebar Links */
.sidebar-menu a {
    padding: 12px 20px;
    color: #444;
    text-decoration: none;
    display: flex;
    align-items: center;
    transition: 0.2s;
    border-left: 4px solid transparent;
}

/* HOVER State */
.sidebar-menu a:hover {
    background: #f0f4ff;
    color: #0d47a1;
}

/* ACTIVE State (Blue bar and background) */
.sidebar-menu a.active {
    background: #eef3ff;
    color: #0d47a1;
    font-weight: 600;
    border-left: 4px solid #0d47a1;
}

.sidebar-menu a i {
    margin-right: 12px;
    font-size: 18px;
}

/* Content Spacing */
.main-content {
    margin-left: 250px; /* This must match sidebar width */
    padding: 30px;
    transition: 0.3s;
}

/* Back Button Styling */
.btn-outline-light:hover {
    background: rgba(255,255,255,0.2);
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar { left: -250px; }
    .sidebar.active { left: 0; }
    .main-content { margin-left: 0; }
}
</style>

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

        <a href="javascript:void(0)" class="d-none d-md-flex" onclick="toggleSidebar()">
            <i class="bi bi-arrow-left-right"></i> <span>Collapse Menu</span>
        </a>
        <hr>

        <a href="index.php" class="nav-item active"><i class="bi bi-house"></i> <span>Dashboard</span></a>
        <a href="profile.php" class="nav-item"><i class="bi bi-person"></i> <span>My Profile</span></a>
        <a href="resume.php" class="nav-item"><i class="bi bi-file-earmark-text"></i> <span>My CV</span></a>
        <a href="applied.php" class="nav-item"><i class="bi bi-briefcase"></i> <span>Applied Jobs</span></a>
        <a href="alerts.php" class="nav-item"><i class="bi bi-bell"></i> <span>Job Alerts</span></a>
        <a href="shortlisted.php" class="nav-item"><i class="bi bi-bookmark"></i> <span>Shortlisted Jobs</span></a>
        <a href="cv.php" class="nav-item"><i class="bi bi-file-earmark"></i> <span>CV Manager</span></a>
        <a href="messages.php" class="nav-item"><i class="bi bi-chat"></i> <span>Messages</span></a>
        <a href="password.php" class="nav-item"><i class="bi bi-lock"></i> <span>Change Password</span></a>
        
        <hr class="mx-3 my-2 text-secondary">
        
        <a href="logout.php" class="nav-item"><i class="bi bi-box-arrow-right"></i> <span>Logout</span></a>
        <a href="delete.php" class="nav-item text-danger"><i class="bi bi-trash"></i> <span>Delete Profile</span></a>
    </div>
</div>

<script>
    // 1. Toggle Sidebar (Mobile)
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('active');
}

// 2. Auto-Highlight Active Menu Option
document.addEventListener("DOMContentLoaded", function() {
    const currentLocation = window.location.href;
    const menuItems = document.querySelectorAll(".sidebar-menu a");
    
    menuItems.forEach(item => {
        // Remove default active class
        item.classList.remove("active");
        
        // If the link URL matches current page URL, add active class
        if(currentLocation.includes(item.getAttribute("href"))) {
            item.classList.add("active");
        }
    });
});
</script>
=======
<!-- sidebar.php -->
<div class="sidebar">
    <a class="navbar-brand text-center d-block mb-4" href="#">
        <img src="../photos/job logo.png" alt="CareerHunt" width="120">
    </a>

    <div class="sidebar-menu">
        <a href="index.php" class="active"><i class="bi bi-house"></i> Dashboard</a>
        <a href="profile.php"><i class="bi bi-person"></i> My Profile</a>
        <a href="resume.php"><i class="bi bi-file-earmark-text"></i> My Resume</a>
        <a href="applied.php"><i class="bi bi-briefcase"></i> Applied Jobs</a>
        <a href="alerts.php"><i class="bi bi-bell"></i> Job Alerts</a>
        <a href="shortlisted.php"><i class="bi bi-bookmark"></i> Shortlisted Jobs</a>
        <a href="cv.php"><i class="bi bi-file-earmark"></i> CV Manager</a>
        <a href="messages.php"><i class="bi bi-chat"></i> Messages</a>
        <a href="password.php"><i class="bi bi-lock"></i> Change Password</a>
        <a href="logout.php">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
        <a href="delete.php" class="text-danger"><i class="bi bi-trash"></i> Delete Profile</a>
    </div>
</div>
>>>>>>> Stashed changes

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CareerHunt Dashboard 6666666</title>
    <link rel="stylesheet" href="user.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
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
                    <img src="https://i.pravatar.cc/40" class="nav-profile">
                    <span>Candidate</span>
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
                    <h4>12</h4>
                    <p>Applied Jobs</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange"><i class="bi bi-star"></i></div>
                <div class="stat-info">
                    <h4>05</h4>
                    <p>Shortlisted</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class="bi bi-bell"></i></div>
                <div class="stat-info">
                    <h4>08</h4>
                    <p>Job Alerts</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple"><i class="bi bi-chat-dots"></i></div>
                <div class="stat-info">
                    <h4>03</h4>
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
                        <tr>
                            <td><strong>UI/UX Designer</strong></td>
                            <td>TechFlow Solutions</td>
                            <td>Oct 24, 2023</td>
                            <td><span class="badge status-pending">Pending</span></td>
                            <td><button class="btn-action"><i class="bi bi-eye"></i></button></td>
                        </tr>
                        <tr>
                            <td><strong>Frontend Developer</strong></td>
                            <td>Creative Agency</td>
                            <td>Oct 20, 2023</td>
                            <td><span class="badge status-active">Interview</span></td>
                            <td><button class="btn-action"><i class="bi bi-eye"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

    <script src="user.js"></script>
</body>
</html>
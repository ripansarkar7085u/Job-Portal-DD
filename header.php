    <header class="header">

        <nav class="navbar navbar-expand-lg fixed-top">

            <a class="navbar-brand" href="index.php">
                <img src="photos\job_logo-remove.png" alt="CareerHunt">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">

                <ul class="navbar-nav ms-auto align-items-center">

                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="job.php">Jobs</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="companies.php">Companies</a>
                    </li>

                     <li class="nav-item">
                        <a class="nav-link" href="recent_news.php">News</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                </ul>

                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <?php if (isset($_SESSION['account_type']) && $_SESSION['account_type'] === 'company'): ?>
                        <a href="company/index.php" class="btn login-btn">
                            <i class="bi bi-building me-1"></i><?php echo htmlspecialchars($_SESSION['company_name']); ?>
                        </a>
                    <?php else: ?>
                        <a href="user/index.php" class="btn login-btn">
                            <i class="bi bi-person me-1"></i><?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="login.php" class="btn login-btn me-2">Login</a>
                    <a href="register.php" class="btn login-btn">Register</a>
                <?php endif; ?>
            </div>

        </nav>

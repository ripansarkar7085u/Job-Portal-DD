<<<<<<< Updated upstream
    <header class="header">

        <nav class="navbar navbar-expand-lg fixed-top">

            <a class="navbar-brand" href="index.php">
                <img src="photos\job_logo.png" alt="CareerHunt">
=======
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CareerHunt</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <header class="header">

        <nav id="nav" class="navbar navbar-expand-lg container navbar-light bg-white">

            <a class="navbar-brand" href="#">
                <img src="photos/job logo.png" alt="CareerHunt">
>>>>>>> Stashed changes
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">

                <ul class="navbar-nav ms-auto align-items-center">

                    <li class="nav-item">
<<<<<<< Updated upstream
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
=======
                        <a class="nav-link" href="index.php">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#job">Jobs</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#company">Companies</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#news">News</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                </ul>

                <button class="btn login-btn" data-bs-toggle="modal" data-bs-target="#authModal">
                    Login / Register
                </button>
            </div>

        </nav>

    </header>
>>>>>>> Stashed changes

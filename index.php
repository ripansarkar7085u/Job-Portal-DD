<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CareerHuntt</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <header class="header">

        <nav class="navbar navbar-expand-lg container">

            <a class="navbar-brand" href="index.php">
                <img src="photos\job_logo.png" alt="CareerHunt">
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
                        <a class="nav-link" href="#">Jobs</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="companies.php">Companies</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                </ul>

                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <?php if (isset($_SESSION['account_type']) && $_SESSION['account_type'] === 'company'): ?>
                        <a href="company/index.php" class="btn login-btn">
                            <i class="bi bi-building me-1"></i><?php echo htmlspecialchars($_SESSION['company_name']); ?>
                        </a>
                    <?php else: ?>
                        <a href="user/dashboard.php" class="btn login-btn">
                            <i class="bi bi-person me-1"></i><?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="login.php" class="btn login-btn me-2">Login</a>
                    <a href="register.php" class="btn login-btn">Register</a>
                <?php endif; ?>
            </div>

        </nav>

        <!-- HERO SECTION -->

        <section class="banner-section">

            <div class="container">

                <div class="row align-items-center">

                    <!-- LEFT SIDE -->

                    <div class="col-lg-7">

                        <h1 class="banner-title">
                            There Are <span>93,178</span> Postings Here <br> For you!
                        </h1>

                        <p class="banner-text">
                            Find Jobs, Employment & Career Opportunities
                        </p>

                        <div class="job-search-box">

                            <div class="search-field">
                                <i class="bi bi-search"></i>
                                <input type="text" placeholder="Job title, keywords, or company">
                            </div>

                            <div class="search-field">
                                <i class="bi bi-geo-alt"></i>
                                <input type="text" placeholder="City or postcode">
                            </div>

                            <button class="search-btn">
                                Find Jobs
                            </button>

                        </div>

                        <p class="popular">
                            <b>Popular Searches :</b>
                            Designer, Developer, Web, IOS, PHP, Senior, Engineer
                        </p>

                    </div>


                    <!-- RIGHT SIDE -->

                    <div class="col-lg-5 hero-right">

                        <img src="photos/banner-img.webp" class="hero-man">

                        <!-- card 1 -->

                        <div class="info-card card-mail">

                            <div class="icon orange">
                                <i class="bi bi-envelope"></i>
                            </div>

                            <div>
                                <p>Work Inquiry From</p>
                                <b>Ali Tufan</b>
                            </div>

                        </div>


                        <!-- card 2 -->

                        <div class="info-card card-candidate">

                            <p><b>10k+ Candidates</b></p>

                            <div class="avatars">
                                <img src="photos/user1.jpg">
                                <img src="photos/user2.jpg">
                                <img src="photos/user3.jpg">
                                <img src="photos/user4.jpg">
                                <span class="plus">+</span>
                            </div>

                        </div>


                        <!-- card 3 -->

                        <div class="info-card card-agency">

                            <div class="icon red">
                                <i class="bi bi-briefcase"></i>
                            </div>

                            <div>
                                <b>Creative Agency</b>
                                <p>Startup</p>
                            </div>

                            <span class="check">
                                <i class="bi bi-check"></i>
                            </span>

                        </div>


                        <!-- card 4 -->

                        <div class="info-card card-cv">

                            <div class="icon blue">
                                <i class="bi bi-file-earmark-arrow-up"></i>
                            </div>

                            <div>
                                <b>Upload Your CV</b>
                                <p>It only takes a few seconds</p>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

        <section class="job-categories">

            <div class="container">

                <div class="section-title text-center">

                    <h2>Popular Job Categories</h2>

                    <p>2020 jobs live - 293 added today.</p>

                </div>

                <div class="row">

                    <div class="col-lg-3 col-md-6">
                        <div class="category-card">
                            <i class="bi bi-code-slash"></i>
                            <h5>Development</h5>
                            <p>(120 Jobs)</p>
                            <a href="#">Learn More</a>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <div class="category-card">
                            <i class="bi bi-palette"></i>
                            <h5>Design</h5>
                            <p>(85 Jobs)</p>
                            <a href="#">Learn More</a>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <div class="category-card">
                            <i class="bi bi-megaphone"></i>
                            <h5>Marketing</h5>
                            <p>(70 Jobs)</p>
                            <a href="#">Learn More</a>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <div class="category-card">
                            <i class="bi bi-bar-chart"></i>
                            <h5>Finance</h5>
                            <p>(60 Jobs)</p>
                            <a href="#">Learn More</a>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <div class="category-card">
                            <i class="bi bi-people"></i>
                            <h5>Human Resource</h5>
                            <p>(45 Jobs)</p>
                            <a href="#">Learn More</a>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <div class="category-card">
                            <i class="bi bi-headset"></i>
                            <h5>Customer Support</h5>
                            <p>(50 Jobs)</p>
                            <a href="#">Learn More</a>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <div class="category-card">
                            <i class="bi bi-gear"></i>
                            <h5>Engineering</h5>
                            <p>(40 Jobs)</p>
                            <a href="#">Learn More</a>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <div class="category-card">
                            <i class="bi bi-briefcase"></i>
                            <h5>Business</h5>
                            <p>(30 Jobs)</p>
                            <a href="#">Learn More</a>
                        </div>
                    </div>

                </div>

            </div>

        </section>

        <section class="featured-jobs">

            <div class="container">

                <div class="section-title text-center">
                    <h2>Featured Jobs</h2>
                    <p>Know your worth and find the job that qualify your life</p>
                </div>

                <div class="row">

                    <!-- Job 1 -->

                    <div class="col-lg-4 col-md-6">

                        <div class="job-card">

                            <div class="job-top">

                                <img src="photos/ui ux.png" class="company-logo">

                                <div>
                                    <h5>UI / UX Designer</h5>
                                    <span>Creative Agency</span>
                                </div>

                            </div>

                            <div class="job-info">

                                <span><i class="bi bi-geo-alt"></i> New York</span>
                                <span><i class="bi bi-clock"></i> Full Time</span>

                            </div>

                            <div class="job-salary">

                                $3000 - $5000 / Month

                            </div>

                            <a href="#" class="apply-btn">Apply Now</a>

                        </div>

                    </div>


                    <!-- Job 2 -->

                    <div class="col-lg-4 col-md-6">

                        <div class="job-card">

                            <div class="job-top">

                                <img src="photos/web.webp" class="company-logo">

                                <div>
                                    <h5>Web Developer</h5>
                                    <span>Tech Company</span>
                                </div>

                            </div>

                            <div class="job-info">

                                <span><i class="bi bi-geo-alt"></i> London</span>
                                <span><i class="bi bi-clock"></i> Full Time</span>

                            </div>

                            <div class="job-salary">

                                $4000 - $7000 / Month

                            </div>

                            <a href="#" class="apply-btn">Apply Now</a>

                        </div>

                    </div>


                    <!-- Job 3 -->

                    <div class="col-lg-4 col-md-6">

                        <div class="job-card">

                            <div class="job-top">

                                <img src="photos/digital.webp" class="company-logo">

                                <div>
                                    <h5>Digital Marketer</h5>
                                    <span>Marketing Agency</span>
                                </div>

                            </div>

                            <div class="job-info">

                                <span><i class="bi bi-geo-alt"></i> California</span>
                                <span><i class="bi bi-clock"></i> Part Time</span>

                            </div>

                            <div class="job-salary">

                                $2500 - $4500 / Month

                            </div>

                            <a href="#" class="apply-btn">Apply Now</a>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        <section class="testimonials">

            <div class="container">

                <div class="section-title text-center">
                    <h2>User Testimonials</h2>
                    <p>What people say about CareerHunt</p>
                </div>

                <div id="testimonialSlider" class="carousel slide" data-bs-ride="carousel">

                    <div class="carousel-inner">

                        <!-- Testimonial 1 -->
                        <div class="carousel-item active">

                            <div class="testimonial-card">

                                <p class="testimonial-text">
                                    "CareerHunt helped me land my first developer job in just 10 days.
                                    The platform is simple, fast, and connects you with amazing companies."
                                </p>

                                <div class="testimonial-user">

                                    <img src="photos\HR maneger.jpg" alt="user">

                                    <div>
                                        <h6>Rahul Sharma</h6>
                                        <span>Software Developer</span>
                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- Testimonial 2 -->
                        <div class="carousel-item">

                            <div class="testimonial-card">

                                <p class="testimonial-text">
                                    "I uploaded my resume and within a week I received interview calls.
                                    CareerHunt is one of the best job search platforms."
                                </p>

                                <div class="testimonial-user">

                                    <img src="photos\HR maneger.jpg" alt="user">

                                    <div>
                                        <h6>Priya Das</h6>
                                        <span>UI/UX Designer</span>
                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- Testimonial 3 -->
                        <div class="carousel-item">

                            <div class="testimonial-card">

                                <p class="testimonial-text">
                                    "We use CareerHunt to hire talented candidates quickly.
                                    The platform makes recruitment very easy."
                                </p>

                                <div class="testimonial-user">

                                    <img src="photos\HR maneger.jpg" alt="user">

                                    <div>
                                        <h6>Arjun Mehta</h6>
                                        <span>HR Manager</span>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- Slider Buttons -->

                    <button class="carousel-control-prev" type="button" data-bs-target="#testimonialSlider"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>

                    <button class="carousel-control-next" type="button" data-bs-target="#testimonialSlider"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>

                </div>

            </div>

        </section>
        <section class="top-companies">

            <div class="container">

                <div class="section-title text-center">
                    <h2>Top Companies Hiring Now</h2>
                    <p>Trusted by thousands of companies around the world</p>
                </div>

                <div class="row">

                    <div class="col-lg-3 col-md-6">
                        <div class="company-card">
                            <img src="photos\google.webp" alt="Company Logo">
                            <h5>Google</h5>
                            <p>15 Open Jobs</p>
                            <a href="#">View Jobs</a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="company-card">
                            <img src="photos\Microsoft.png" alt="Company Logo">
                            <h5>Microsoft</h5>
                            <p>12 Open Jobs</p>
                            <a href="#">View Jobs</a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="company-card">
                            <img src="photos\amazon.png" alt="Company Logo">
                            <h5>Amazon</h5>
                            <p>10 Open Jobs</p>
                            <a href="#">View Jobs</a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="company-card">
                            <img src="photos/Facebook.avif">
                            <h5>Facebook</h5>
                            <p>8 Open Jobs</p>
                            <a href="#">View Jobs</a>
                        </div>
                    </div>

                </div>

            </div>

        </section>

        <section class="job-stats">

            <div class="container">

                <div class="row text-center">

                    <div class="col-lg-3 col-md-6">
                        <div class="stat-box">
                            <i class="bi bi-briefcase"></i>
                            <h2>10K+</h2>
                            <p>Jobs Posted</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="stat-box">
                            <i class="bi bi-people"></i>
                            <h2>8K+</h2>
                            <p>Candidates</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="stat-box">
                            <i class="bi bi-building"></i>
                            <h2>5K+</h2>
                            <p>Companies</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="stat-box">
                            <i class="bi bi-file-earmark-check"></i>
                            <h2>20K+</h2>
                            <p>Applications</p>
                        </div>
                    </div>

                </div>

            </div>

        </section>

        <footer class="footer">

            <div class="container">

                <div class="row">

                    <div class="col-lg-4">
                        <h4>CareerHunt</h4>
                        <p>
                            Find your dream job with CareerHunt. Search thousands of jobs and connect with top
                            companies.
                        </p>
                    </div>

                    <div class="col-lg-2">
                        <h5>Quick Links</h5>
                        <ul>
                            <li><a href="index.php">Home</a></li>
                            <li><a href="#">Jobs</a></li>
                            <li><a href="companies.php">Companies</a></li>
                            <li><a href="#">About</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-3">
                        <h5>Job Categories</h5>
                        <ul>
                            <li><a href="#">Development</a></li>
                            <li><a href="#">Design</a></li>
                            <li><a href="#">Marketing</a></li>
                            <li><a href="#">Finance</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-3">
                        <h5>Contact</h5>
                        <p>Email: support@careerhunt.com</p>
                        <p>Phone: +91 9876543210</p>
                    </div>

                </div>

                <hr>

                <p class="text-center copyright">
                    &copy; <?php echo date('Y'); ?> CareerHunt. All rights reserved.
                </p>

            </div>

        </footer>

    </header>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>


</body>

<<<<<<< HEAD
<<<<<<< Updated upstream:index.php
</html>
=======
<!-- LOGIN / REGISTER MODAL -->

<div class="modal fade" id="authModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content auth-box">

            <button class="btn-close close-btn" data-bs-dismiss="modal"></button>

            <div class="modal-body">

                <h3 class="text-center mb-4" id="authTitle">Login to CareerHunt</h3>

                <!-- LOGIN FORM -->

                <form id="loginForm">

                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" class="form-control" placeholder="Username">
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" class="form-control" placeholder="Password">
                    </div>

                    <div class="d-flex justify-content-between mb-3 small">
                        <div>
                            <input type="checkbox"> Remember me
                        </div>

                        <a href="#">Forgot password?</a>
                    </div>

                    <button type="button" class="btn auth-btn" onclick="loginUser()">Log In</button>


                    <p class="switch-text">
                        Don't have an account?
                        <a onclick="showRegister()">Signup</a>
                    </p>

                </form>


                <!-- REGISTER FORM -->

                <form id="registerForm" style="display:none">

                    <!-- USER TYPE -->

                    <div class="user-type">

                        <button type="button" class="type-btn active" onclick="selectType(this)">
                            Candidate
                        </button>

                        <button type="button" class="type-btn" onclick="selectType(this)">
                            Employer
                        </button>

                    </div>

                    <div class="mb-3">
                        <label>Email Address</label>
                        <input type="email" class="form-control" placeholder="Email">
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" class="form-control" placeholder="Password">
                    </div>

                    <button class="btn auth-btn">Register</button>

                    

                </form>

            </div>
        </div>
    </div>
</div>

</html>
>>>>>>> Stashed changes:index.html
=======
</html>
>>>>>>> 3d1379c7cc6773770df0a0521b3e1256cdf042b6

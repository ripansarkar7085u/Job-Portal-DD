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

    <link rel="stylesheet" href="css\main.css">

    <link rel="stylesheet" href="css\companies.css">
    <link rel="stylesheet" href="css\company-detail.css">



</head>

<body>
    <?php include("header.php") ?>

    <!-- HERO SECTION -->

    <section id="home" class="banner-section ">

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

    <section id="job" class="job-categories">

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

            <div class="row" id="featuredJobsList">

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
    <section id="company" class="top-companies">

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
                        <img src="photos\facebook.webp">
                        <h5>Facebook</h5>
                        <p>8 Open Jobs</p>
                        <a href="#">View Jobs</a>
                    </div>
                </div>

            </div>

        </div>

    </section>

    <section id="news" class="job-news py-5">
        <div class="container">

            <!-- CENTERED HEADING -->
            <div class="text-center mb-5">
                <h2 class="fw-bold">Recent Job News</h2>
            </div>

            <!-- ROW -->
            <div class="row align-items-stretch">

                <!-- CARD 1 -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="news-card">
                        <img src="photos\SSB-Recruitment-2023-01.png" alt="news">
                        <div class="p-3">
                            <h5>SSB Recruitment 2026: Apply for 233 Posts</h5>
                            <p class="text-muted small">Posted on March 17, 2026</p>
                            <p class="news-desc">
                                SSB has released new vacancies for Head Constable posts. Check eligibility and apply
                                online.
                            </p>
                            <a href="#" class="read-more">Read More →</a>
                        </div>
                    </div>
                </div>

                <!-- CARD 2 -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="news-card">
                        <img src="photos\Fresher-Jobs-2025-Salary-25000.webp" alt="news">
                        <div class="p-3">
                            <h5>25,000 New Jobs Announced for Youth</h5>
                            <p class="text-muted small">Posted on March 16, 2026</p>
                            <p class="news-desc">
                                CII plans massive hiring drive with skill training programs for freshers across
                                India.
                            </p>
                            <a href="#" class="read-more">Read More →</a>
                        </div>
                    </div>
                </div>

                <!-- CARD 3 -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="news-card">
                        <img src="photos\blog-images-37.webp" alt="news">
                        <div class="p-3">
                            <h5>Private Jobs vs Govt Jobs Debate Rising</h5>
                            <p class="text-muted small">Posted on March 15, 2026</p>
                            <p class="news-desc">
                                Youth demand secure jobs as contract-based hiring increases across India job market
                                trends.
                            </p>
                            <a href="#" class="read-more">Read More →</a>
                        </div>
                    </div>
                </div>

            </div>
            <a href="recent_news.php" class="btn  view-all">View All</a>
        </div>
    </section>

    <section id="about" class="about-section">
        <div class="container">
            <div class="row align-items-center">

                <!-- LEFT IMAGE -->
                <div class="col-lg-6">
                    <div class="about-img">
                        <img src="photos\about.webp" alt="About CareerHunt">
                    </div>
                </div>

                <!-- RIGHT CONTENT -->
                <div class="col-lg-6">
                    <div class="about-content">

                        <h2>About CareerHunt</h2>

                        <p class="about-text">
                            CareerHunt is a modern job portal designed to connect talented individuals with top
                            companies.
                            Whether you're a fresher or an experienced professional, we help you find the perfect
                            job faster
                            and easier.
                        </p>

                        <div class="about-features">

                            <div class="feature">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>1000+ Verified Jobs</span>
                            </div>

                            <div class="feature">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Top Companies Hiring</span>
                            </div>

                            <div class="feature">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Easy Apply System</span>
                            </div>

                            <div class="feature">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Fast & Secure Platform</span>
                            </div>

                        </div>

                        <a href="job.php" class="about-btn">Explore Jobs</a>

                    </div>
                </div>

            </div>
        </div>
    </section>


    <?php include("footer.php") ?>
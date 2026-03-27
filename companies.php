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
    <button onclick="history.back()" class="back-btn">
        <i class="bi bi-arrow-left"></i>
    </button>

    <!-- COMPANIES LISTING -->
    <section class="companies-section">
        <div class="container">
            <div class="page-header-content">
                <h1>Browse Companies</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Companies</li>
                    </ol>
                </nav>
            </div>
            <div class="company-search-box">
                <div class="row g-3 align-items-center">
                    <div class="col-lg-4 col-md-6">
                        <div class="search-field">
                            <i class="bi bi-search"></i>
                            <input type="text" placeholder="Company name or keyword" id="searchInput">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="search-field">
                            <i class="bi bi-geo-alt"></i>
                            <select id="locationFilter">
                                <option value="">All Locations</option>
                                <option value="new-york">New York, USA</option>
                                <option value="london">London, UK</option>
                                <option value="san-francisco">San Francisco, USA</option>
                                <option value="singapore">Singapore</option>
                                <option value="bangalore">Bangalore, India</option>
                                <option value="remote">Remote</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="search-field">
                            <i class="bi bi-briefcase"></i>
                            <select id="industryFilter">
                                <option value="">All Industries</option>
                                <option value="technology">Technology</option>
                                <option value="finance">Finance & Banking</option>
                                <option value="healthcare">Healthcare</option>
                                <option value="education">Education</option>
                                <option value="retail">Retail & E-commerce</option>
                                <option value="manufacturing">Manufacturing</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <button class="search-btn w-100" id="searchBtn">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- SIDEBAR FILTERS -->
                <div class="col-lg-3">
                    <div class="filter-sidebar">
                        <!-- Company Size Filter -->
                        <div class="filter-widget">
                            <h5 class="filter-title">Company Size</h5>
                            <div class="filter-options">
                                <label class="filter-option">
                                    <input type="checkbox" value="1-50"> 1-50 employees
                                    <span class="count">(24)</span>
                                </label>
                                <label class="filter-option">
                                    <input type="checkbox" value="51-200"> 51-200 employees
                                    <span class="count">(18)</span>
                                </label>
                                <label class="filter-option">
                                    <input type="checkbox" value="201-500"> 201-500 employees
                                    <span class="count">(12)</span>
                                </label>
                                <label class="filter-option">
                                    <input type="checkbox" value="501-1000"> 501-1000 employees
                                    <span class="count">(8)</span>
                                </label>
                                <label class="filter-option">
                                    <input type="checkbox" value="1000+"> 1000+ employees
                                    <span class="count">(15)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Industry Filter -->
                        <div class="filter-widget">
                            <h5 class="filter-title">Industry</h5>
                            <div class="filter-options">
                                <label class="filter-option">
                                    <input type="checkbox" value="technology"> Technology
                                    <span class="count">(69)</span>
                                </label>
                                <label class="filter-option">
                                    <input type="checkbox" value="finance"> Finance
                                    <span class="count">(15)</span>
                                </label>
                                <label class="filter-option">
                                    <input type="checkbox" value="healthcare"> Healthcare
                                    <span class="count">(10)</span>
                                </label>
                                <label class="filter-option">
                                    <input type="checkbox" value="education"> Education
                                    <span class="count">(8)</span>
                                </label>
                                <label class="filter-option">
                                    <input type="checkbox" value="retail"> Retail
                                    <span class="count">(12)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Founded Year Filter -->
                        <div class="filter-widget">
                            <h5 class="filter-title">Founded</h5>
                            <div class="filter-options">
                                <label class="filter-option">
                                    <input type="checkbox" value="2020+"> 2020 - Present
                                    <span class="count">(20)</span>
                                </label>
                                <label class="filter-option">
                                    <input type="checkbox" value="2015-2019"> 2015 - 2019
                                    <span class="count">(25)</span>
                                </label>
                                <label class="filter-option">
                                    <input type="checkbox" value="2010-2014"> 2010 - 2014
                                    <span class="count">(15)</span>
                                </label>
                                <label class="filter-option">
                                    <input type="checkbox" value="before-2010"> Before 2010
                                    <span class="count">(17)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COMPANIES GRID -->
                <div class="col-lg-9">
                    <!-- Results Header -->
                    <div class="results-header">
                        <div class="results-count">
                            <span>Showing <strong>1-12</strong> of <strong>77</strong> companies</span>
                        </div>
                        <div class="results-sort">
                            <label>Sort by:</label>
                            <select id="sortBy">
                                <option value="newest">Newest First</option>
                                <option value="name-asc">Name A-Z</option>
                                <option value="name-desc">Name Z-A</option>
                                <option value="jobs">Most Jobs</option>
                            </select>
                            <div class="view-toggle">
                                <button class="view-btn active" data-view="grid"><i
                                        class="bi bi-grid-3x3-gap"></i></button>
                                <button class="view-btn" data-view="list"><i class="bi bi-list-ul"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Companies Grid -->
                    <div class="companies-grid" id="companiesGrid">
                        <div class="text-center w-100 py-4" id="companiesLoading">Loading companies...</div>
                    </div>

                    <!-- Pagination -->
                    <nav class="pagination-wrapper">
                        <ul class="pagination">
                            <li class="page-item disabled">
                                <a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">4</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="newsletter-box">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h3>Subscribe to Our Newsletter</h3>
                        <p>Get the latest job listings and company updates delivered to your inbox.</p>
                    </div>
                    <div class="col-lg-6">
                        <form class="newsletter-form">
                            <input type="email" placeholder="Enter your email address">
                            <button type="submit">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-widget">
                        <img src="/photos/job logo.png" alt="CareerHunt" class="footer-logo">
                        <p>CareerHunt is a leading job portal connecting talented professionals with top companies
                            worldwide.</p>
                        <div class="social-links">
                            <a href="#"><i class="bi bi-facebook"></i></a>
                            <a href="#"><i class="bi bi-twitter-x"></i></a>
                            <a href="#"><i class="bi bi-linkedin"></i></a>
                            <a href="#"><i class="bi bi-instagram"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="footer-widget">
                        <h5>For Candidates</h5>
                        <ul>
                            <li><a href="#">Browse Jobs</a></li>
                            <li><a href="companies.php">Browse Companies</a></li>
                            <li><a href="#">Candidate Dashboard</a></li>
                            <li><a href="#">Job Alerts</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="footer-widget">
                        <h5>For Employers</h5>
                        <ul>
                            <li><a href="#">Post a Job</a></li>
                            <li><a href="#">Browse Candidates</a></li>
                            <li><a href="#">Employer Dashboard</a></li>
                            <li><a href="#">Pricing</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="footer-widget">
                        <h5>Contact Us</h5>
                        <ul class="contact-info">
                            <li><i class="bi bi-geo-alt"></i> 123 Business Street, NY 10001</li>
                            <li><i class="bi bi-envelope"></i> contact@careerhunt.com</li>
                            <li><i class="bi bi-telephone"></i> +1 (555) 123-4567</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> CareerHunt. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Auth Modal -->
    <div class="modal fade" id="authModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <ul class="nav nav-tabs" id="authTabs">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab"
                                data-bs-target="#loginTab">Login</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#registerTab">Register</button>
                        </li>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="loginTab">
                            <form id="loginForm">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                                <div class="form-error" id="loginError"></div>
                                <button type="submit" class="btn btn-primary w-100">Login</button>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="registerTab">
                            <form id="registerForm">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" name="full_name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">I am a</label>
                                    <select class="form-select" name="user_type">
                                        <option value="candidate">Job Seeker</option>
                                        <option value="employer">Employer</option>
                                    </select>
                                </div>
                                <div class="form-error" id="registerError"></div>
                                <button type="submit" class="btn btn-primary w-100">Register</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/auth.js"></script>
    <script src="js\companies.js"></script>
</body>

</html>
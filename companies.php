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
            <div class="page-header-content mt-4">
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
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="search-field">
                            <i class="bi bi-briefcase"></i>
                            <select id="industryFilter">
                                <option value="">All Industries</option>
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
                            <div class="filter-options" id="sizeFilterOptions"></div>
                        </div>

                        <!-- Industry Filter -->
                        <div class="filter-widget">
                            <h5 class="filter-title">Industry</h5>
                            <div class="filter-options" id="industryFilterOptions"></div>
                        </div>

                        <!-- Founded Year Filter -->
                        <div class="filter-widget">
                            <h5 class="filter-title">Founded</h5>
                            <div class="filter-options" id="foundedFilterOptions"></div>
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
     <?php include("footer.php")?>

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
    <script src="js/companies.js"></script>
</body>

</html>

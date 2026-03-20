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



</head>

<body>
    <?php include("header.php") ?>
    <button onclick="history.back()" class="back-btn">
        <i class="bi bi-arrow-left"></i>
    </button>
    <section class="jobs-section py-5">

        <div class="container">

            <!-- Title -->
            <div class="page-header-content">
                <h2 class="job">Browse Jobs</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Jobsf</li>
                </ol>
            </div>

            <!-- Search -->
            <div class="job-search-box mb-4 p-3 shadow-sm rounded">
                <div class="row g-3">

                    <div class="col-md-4">
                        <input type="text" id="searchInput" class="form-control" placeholder="Job title">
                    </div>

                    <div class="col-md-3">
                        <select id="locationFilter" class="form-select">
                            <option value="">All Locations</option>
                            <option value="Delhi">Delhi</option>
                            <option value="Mumbai">Mumbai</option>
                            <option value="Bangalore">Bangalore</option>
                            <option value="Hyderabad">Hyderabad</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select id="categoryFilter" class="form-select">
                            <option value="">All Categories</option>
                            <option value="IT">IT</option>
                            <option value="Finance">Finance</option>
                            <option value="Healthcare">Healthcare</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="row">

                <!-- Sidebar -->
                <div class="col-lg-3">

                    <div class="filter-box p-3 shadow-sm rounded mb-4">

                        <!-- Job Type -->
                        <h5>Job Type</h5>
                        <input type="checkbox" class="typeFilter" value="Full Time"> Full Time <br>
                        <input type="checkbox" class="typeFilter" value="Part Time"> Part Time <br>
                        <input type="checkbox" class="typeFilter" value="Internship"> Internship <br>
                        <input type="checkbox" class="typeFilter" value="Remote"> Remote

                        <hr>

                        <!-- Experience -->
                        <h5>Experience</h5>
                        <input type="checkbox" class="expFilter" value="Fresher"> Fresher <br>
                        <input type="checkbox" class="expFilter" value="1-3 Years"> 1-3 Years <br>
                        <input type="checkbox" class="expFilter" value="3-5 Years"> 3-5 Years

                        <hr>

                        <!-- Salary -->
                        <h5>Salary</h5>
                        <input type="checkbox" class="salaryFilter" value="0-3"> 0-3 LPA <br>
                        <input type="checkbox" class="salaryFilter" value="3-6"> 3-6 LPA <br>
                        <input type="checkbox" class="salaryFilter" value="6+"> 6+ LPA

                    </div>

                </div>

                <!-- Job Cards -->
                <div class="col-lg-9">

                    <div class="d-flex justify-content-between mb-3">
                        <p id="jobCount">Showing jobs</p>
                    </div>

                    <div class="row g-4" id="jobContainer">

                        <!-- Job 1 -->
                        <div class="col-md-6 job-item" data-title="Frontend Developer" data-location="Bangalore"
                            data-category="IT" data-type="Full Time" data-exp="1-3 Years" data-salary="8"
                            data-desc="Work on UI using React.">

                            <div class="job-card p-3 shadow-sm rounded">
                                <h5>Frontend Developer</h5>
                                <small>Google</small>
                                <p>📍 Bangalore</p>
                                <p>💰 ₹8 LPA</p>

                                <div class="d-flex justify-content-between">
                                    <span class="badge bg-success">Full Time</span>
                                    <a href="job-details.php">Get Details</a>
                                </div>
                            </div>
                        </div>

                        <!-- Job 2 -->
                        <div class="col-md-6 job-item" data-title="Backend Developer" data-location="Hyderabad"
                            data-category="IT" data-type="Remote" data-exp="3-5 Years" data-salary="10"
                            data-desc="Build APIs using Node.js">

                            <div class="job-card p-3 shadow-sm rounded">
                                <h5>Backend Developer</h5>
                                <small>Microsoft</small>
                                <p>📍 Hyderabad</p>
                                <p>💰 ₹10 LPA</p>

                                <div class="d-flex justify-content-between">
                                    <span class="badge bg-info">Remote</span>

                                    <a href="job-details.php">Get Details</a>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Pagination -->
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center" id="pagination"></ul>
                    </nav>

                </div>

            </div>
        </div>
    </section>

    <?php include("footer.php") ?>

    <script src="js\job.js"></script>
    <div class="modal fade" id="jobModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 id="modalTitle"></h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p id="modalCompany"></p>
                    <p id="modalLocation"></p>
                    <p id="modalSalary"></p>
                    <p id="modalDesc"></p>
                </div>

            </div>
        </div>
    </div>
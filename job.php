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
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select id="categoryFilter" class="form-select">
                            <option value="">All Categories</option>
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
                        <input type="checkbox" class="typeFilter" value="full-time"> Full Time <br>
                        <input type="checkbox" class="typeFilter" value="part-time"> Part Time <br>
                        <input type="checkbox" class="typeFilter" value="internship"> Internship <br>
                        <input type="checkbox" class="typeFilter" value="contract"> Contract <br>
                        <input type="checkbox" class="typeFilter" value="freelance"> Freelance

                        <hr>

                        <!-- Experience -->
                        <h5>Experience</h5>
                        <input type="checkbox" class="expFilter" value="entry"> Entry Level <br>
                        <input type="checkbox" class="expFilter" value="mid"> Mid Level <br>
                        <input type="checkbox" class="expFilter" value="senior"> Senior Level <br>
                        <input type="checkbox" class="expFilter" value="lead"> Lead / Manager <br>
                        <input type="checkbox" class="expFilter" value="executive"> Executive

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

                    <div class="row g-4" id="jobContainer"></div>

                    <div id="noJobsMessage" class="alert alert-light border mt-3" style="display:none;">
                        No jobs under this category.
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
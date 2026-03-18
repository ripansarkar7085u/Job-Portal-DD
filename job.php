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

    <link rel="stylesheet" href="css\companies.css">



</head>

<body>
<?php include("header.php") ?>
<section class="jobs-section py-5">

    <div class="container">

        <!-- Title -->
        <div class="section-title text-center mb-4">
            <h2>Browse Jobs</h2>
            <p>Find your dream job from top companies</p>
            <p><a href="index.php">Home</a>/Job</p>
        </div>

        <!-- Search Bar -->
        <div class="job-search-box mb-4 p-3 shadow-sm rounded">
            <div class="row g-3">

                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Job title or keyword">
                </div>

                <div class="col-md-3">
                    <select class="form-select">
                        <option>All Locations</option>
                        <option>Delhi</option>
                        <option>Mumbai</option>
                        <option>Bangalore</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select class="form-select">
                        <option>All Categories</option>
                        <option>IT</option>
                        <option>Finance</option>
                        <option>Healthcare</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Search</button>
                </div>

            </div>
        </div>

        <div class="row">

            <!-- Sidebar Filters -->
            <div class="col-lg-3">

                <div class="filter-box p-3 shadow-sm rounded mb-4">

                    <h5>Job Type</h5>
                    <div>
                        <input type="checkbox"> Full Time <br>
                        <input type="checkbox"> Part Time <br>
                        <input type="checkbox"> Internship <br>
                        <input type="checkbox"> Remote
                    </div>

                    <hr>

                    <h5>Experience</h5>
                    <div>
                        <input type="checkbox"> Fresher <br>
                        <input type="checkbox"> 1-3 Years <br>
                        <input type="checkbox"> 3-5 Years
                    </div>

                    <hr>

                    <h5>Salary</h5>
                    <div>
                        <input type="checkbox"> 0-3 LPA <br>
                        <input type="checkbox"> 3-6 LPA <br>
                        <input type="checkbox"> 6+ LPA
                    </div>

                </div>

            </div>

            <!-- Job Cards -->
            <div class="col-lg-9">

                <div class="d-flex justify-content-between mb-3">
                    <p>Showing 1-6 of 50 jobs</p>

                    <select class="form-select w-auto">
                        <option>Newest First</option>
                        <option>Oldest</option>
                    </select>
                </div>

                <div class="row g-4">

                    <!-- Job Card -->
                    <div class="col-md-6">
                        <div class="job-card p-3 shadow-sm rounded">

                            <div class="d-flex align-items-center mb-3">
                                <div class="job-logo me-3">GO</div>
                                <div>
                                    <h5 class="mb-0">Frontend Developer</h5>
                                    <small>Google</small>
                                </div>
                            </div>

                            <p class="text-muted">Location: Bangalore</p>
                            <p class="text-muted">Salary: ₹8 LPA</p>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="badge bg-success">Full Time</span>
                                <a href="job-details.php" class="btn btn-sm btn-outline-primary">Apply</a>
                            </div>

                        </div>
                    </div>

                    <!-- Job Card -->
                    <div class="col-md-6">
                        <div class="job-card p-3 shadow-sm rounded">

                            <div class="d-flex align-items-center mb-3">
                                <div class="job-logo me-3">MI</div>
                                <div>
                                    <h5 class="mb-0">Backend Developer</h5>
                                    <small>Microsoft</small>
                                </div>
                            </div>

                            <p class="text-muted">Location: Hyderabad</p>
                            <p class="text-muted">Salary: ₹10 LPA</p>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="badge bg-info">Remote</span>
                                <a href="#" class="btn btn-sm btn-outline-primary">Apply</a>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- Pagination -->
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item active"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                    </ul>
                </nav>

            </div>

        </div>

    </div>

</section>

<?php include("footer.php")?>

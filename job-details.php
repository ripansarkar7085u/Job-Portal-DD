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

    <link rel="stylesheet" href="css\job-details.css">

    <link rel="stylesheet" href="css\companies.css">



</head>

<body>
    <?php include("header.php") ?>
    <button onclick="history.back()" class="back-btn">
        <i class="bi bi-arrow-left"></i>
    </button>

    <div class="container my-5">

        <!-- Banner -->
        <div class="job-banner"></div>

        <!-- Card -->
        <div class="job-card">

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-start">

                <div class="d-flex gap-3">
                    <img src="photos\font.jpg" class="company-logo">

                    <div>
                        <h3>Senior Frontend Developer</h3>
                        <p class="text-muted mb-1">TechCorp Inc.</p>

                        <div class="meta">
                            <span><i class="bi bi-geo-alt"></i> Remote</span>
                            <span><i class="bi bi-laptop"></i> Hybrid</span>
                            <span><i class="bi bi-briefcase"></i> Mid Level</span>
                        </div>
                    </div>
                </div>

                <span class="badge bg-primary fs-6">Full-Time</span>
            </div>

            <!-- Salary -->
            <div class="section">
                <h5><i class="bi bi-cash"></i> Salary</h5>
                <p class="fw-bold text-success">$80,000 - $120,000 / Year</p>
            </div>

            <!-- Description -->
            <div class="section">
                <h5>Job Description</h5>
                <p>
                    We are looking for a passionate frontend developer to build modern,
                    responsive web applications. You will work closely with designers,
                    backend developers, and product managers.
                </p>
            </div>

            <!-- Requirements -->
            <div class="section">
                <h5>Requirements</h5>
                <ul>
                    <li>2+ years experience in frontend development</li>
                    <li>Strong knowledge of JavaScript & React</li>
                    <li>Experience with REST APIs</li>
                </ul>
            </div>

            <!-- Nice to Have -->
            <div class="section">
                <h5>Nice to Have</h5>
                <ul>
                    <li>TypeScript</li>
                    <li>GraphQL</li>
                </ul>
            </div>

            <!-- Skills -->
            <div class="section">
                <h5>Skills</h5>
                <span class="tag">JavaScript</span>
                <span class="tag">React</span>
                <span class="tag">CSS</span>
                <span class="tag">HTML</span>
            </div>

            <!-- Benefits -->
            <div class="section">
                <h5>Benefits</h5>
                <span class="tag">Health Insurance</span>
                <span class="tag">Remote Work</span>
                <span class="tag">Paid Time Off</span>
            </div>

            <!-- Extra Info -->
            <div class="section">
                <h5>Additional Info</h5>
                <p><strong>Category:</strong> Engineering</p>
                <p><strong>Positions:</strong> 2</p>
                <p><strong>Timezone:</strong> IST</p>
                <p><strong>Visa:</strong> Not Available</p>
            </div>

            <!-- Apply Box -->
            <div class="apply-box mt-4 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Interested in this job?</h6>
                    <small>Apply now and join our team 🚀</small>
                </div>

                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#applyModal">
                    <i class="bi bi-send"></i> Apply Now
                </button>
            </div>

        </div>
    </div>

    <!-- Apply Modal -->
    <div class="modal fade" id="applyModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Apply for Job</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="applyForm">
                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Upload CV</label>
                            <input type="file" class="form-control" accept=".pdf,.doc,.docx" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cover Letter</label>
                            <textarea class="form-control" rows="3"></textarea>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            Submit Application
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <?php include("footer.php") ?>
    <script>
        document.getElementById('applyForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // Simulate form submission
            alert("Application submitted successfully!");

            // Redirect to applied page
            window.location.href = "user/applied.php";
        });
    </script>
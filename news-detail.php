<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>CareerHuntt</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />

    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/news-detail.css" />
    <link rel="stylesheet" href="css/companies.css" />

</head>

<body>

    <?php include("header.php") ?>
    <button onclick="history.back()" class="back-btn">
        <i class="bi bi-arrow-left"></i>
    </button>

    <!-- HEADER -->
    <div class="job-header">
        <h1>SSC Recruitment 2026</h1>
        <p>Apply for multiple government posts</p>
    </div>

    <div class="container">
        <div class="job-container">

            <!-- BACK BUTTON -->
            <a href="index.php" class="btn btn-outline-secondary back-btn">← Back</a>

            <!-- IMAGE -->
            <img src="photos/SSB-Recruitment-2023-01.png" class="job-img" alt="SSC Recruitment" />

            <!-- DATE -->
            <p class="text-muted">📅 10 March 2026</p>

            <!-- TITLE -->
            <h3>SSC Recruitment 2026 Released</h3>

            <!-- DESCRIPTION -->
            <p>
                Staff Selection Commission (SSC) has released the official notification for Recruitment 2026.
                Candidates can apply for multiple posts in various departments under the Government of India.
            </p>

            <!-- DETAILS -->
            <h5 class="mt-4">📌 Job Details</h5>
            <ul>
                <li><strong>Organization:</strong> SSC</li>
                <li><strong>Post Name:</strong> Multiple Posts</li>
                <li><strong>Qualification:</strong> 10th / 12th / Graduate</li>
                <li><strong>Salary:</strong> ₹25,000 - ₹81,000/month</li>
                <li><strong>Location:</strong> All India</li>
            </ul>

            <!-- IMPORTANT DATES -->
            <h5 class="mt-4">📅 Important Dates</h5>
            <ul>
                <li>Application Start: March 2026</li>
                <li>Last Date: April 2026</li>
                <li>Exam Date: To be announced</li>
            </ul>

            <!-- 📄 NOTICE PDF SECTION -->
            <div class="mt-4">
                <h5>📄 Official Notices</h5>

                <ul class="list-group">

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        SSC Official Notification 2026
                        <div>
                            <a href="pdf\application.pdf" target="_blank" class="btn btn-sm btn-primary">
                                View
                            </a>
                            <a href="pdf\application.pdf" download class="btn btn-sm btn-success">
                                Download
                            </a>
                        </div>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        SSC Detailed Advertisement
                        <div>
                            <a href="pdf\application.pdf" target="_blank" class="btn btn-sm btn-primary">
                                View
                            </a>
                            <a href="pdf\application.pdf" download class="btn btn-sm btn-success">
                                Download
                            </a>
                        </div>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        SSC Syllabus & Exam Pattern
                        <div>
                            <a href="pdf\application.pdf" target="_blank" class="btn btn-sm btn-primary">
                                View
                            </a>
                            <a href="pdf\application.pdf" download class="btn btn-sm btn-success">
                                Download
                            </a>
                        </div>
                    </li>

                </ul>

                <!-- 📑 OPTIONAL: PDF Viewer -->
                <iframe src="pdf\application.pdf" width="100%" height="500px" class="mt-3"></iframe>

            </div>

        </div>
    </div>

    <?php include("footer.php") ?>

</body>

</html>
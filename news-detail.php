<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSC Recruitment 2026</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
        }

        .job-header {
            background: linear-gradient(to right, #b30000, #ff4d4d);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .job-header h1 {
            font-weight: bold;
        }

        .job-container {
            background: #fff;
            margin-top: -40px;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .job-img {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .badge-custom {
            background: #0d6efd;
        }

        .apply-btn {
            background: #28a745;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
        }

        .apply-btn:hover {
            background: #218838;
        }

        .back-btn {
            text-decoration: none;
            display: inline-block;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

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
        <img src="photos/ssc.jpg" class="job-img" alt="SSC Recruitment">

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

        <!-- APPLY BUTTON -->
        <div class="mt-4">
            <a href="#" class="apply-btn">Apply Now</a>
        </div>

    </div>
</div>

</body>
</html>
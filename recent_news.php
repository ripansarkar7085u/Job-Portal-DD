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
</button><!-- NEWS SECTION -->
<section class="py-5">
    <div class="container">
        <div class="page-header-content">
            <h1>Recent Job News</h1>
            <p>Stay updated with latest government & private job updates</p>
            <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">News</li>
                    </ol>

        </div>
        <div class="row g-4">

            <!-- NEWS CARD 1 -->
            <div class="col-lg-4 col-md-6">
                <div class="news-card">
                    <img src="photos\SSB-Recruitment-2023-01.png" class="w-100 news-img">
                    <div class="news-content">
                        <p class="news-date"><i class="bi bi-calendar"></i> 10 March 2026</p>
                        <h5>SSC Recruitment 2026 Released</h5>
                        <p>Apply for multiple posts in SSC with great salary packages.</p>
                        <a href="news-detail.php" class="read-btn">Read More</a>
                    </div>
                </div>
            </div>

            <!-- NEWS CARD 2 -->
            <div class="col-lg-4 col-md-6">
                <div class="news-card">
                    <img src="photos\Fresher-Jobs-2025-Salary-25000.webp" class="w-100 news-img">
                    <div class="news-content">
                        <p class="news-date"><i class="bi bi-calendar"></i> 12 March 2026</p>
                        <h5>Bank Jobs Open 2026</h5>
                        <p>New vacancies announced in banking sector across India.</p>
                        <a href="#" class="read-btn">Read More</a>
                    </div>
                </div>
            </div>

            <!-- NEWS CARD 3 -->
            <div class="col-lg-4 col-md-6">
                <div class="news-card">
                    <img src="photos\blog-images-37.webp" class="w-100 news-img">
                    <div class="news-content">
                        <p class="news-date"><i class="bi bi-calendar"></i> 15 March 2026</p>
                        <h5>IT Company Hiring Freshers</h5>
                        <p>Top IT companies hiring fresh graduates with good packages.</p>
                        <a href="#" class="read-btn">Read More</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include("footer.php")?>
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
    <title>About CareerHunt</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css\main.css">

    <style>
        /* SINGLE SECTION DESIGN */
        .about-full {
            background: linear-gradient(135deg, #eef4ff, #ffffff);
            padding: 80px 0;
        }

        /* HEADINGS */
        .about-full h1 {
            font-weight: 700;
            color: #0d48a1;
        }

        .about-full h3 {
            color: #0d48a1;
        }

        /* IMAGE */
        .about-img {
            max-height: 350px;
        }

        /* BOX */
        .box {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        /* FEATURES */
        .feature i {
            color: #198754;
            margin-right: 6px;
        }

        /* CTA */
        .cta-btn {
            background: #0d48a1;
            color: white;
            border-radius: 30px;
            padding: 10px 25px;
        }

        .cta-btn:hover {
            background: #ff7a00;
        }

        /* FOOTER */
        .footer {
            background: #212529;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
    </style>

</head>

<body>

    <?php include("header.php") ?>
    <button onclick="history.back()" class="back-btn">
        <i class="bi bi-arrow-left"></i>
    </button>

    <!-- ===== ONE SINGLE SECTION START ===== -->
    <section class="about-full">

        <div class="container">

            <!-- HERO + INTRO -->
            <div class="row align-items-center mb-5">

                <div class="col-lg-6">
                    <h1>About CareerHunt</h1>

                    <p class="mt-3">
                        CareerHunt is a modern job portal that connects talented professionals
                        with top companies worldwide. We provide a smart, fast, and reliable
                        platform for job seekers and recruiters.
                    </p>

                    <p>
                        Our goal is to simplify the hiring process and help individuals
                        build successful careers with ease.
                    </p>

                    <a href="job.php" class="btn cta-btn mt-3">Explore Jobs</a>
                </div>

                <div class="col-lg-6 text-center mt-4 mt-lg-0">
                    <img src="https://img.freepik.com/free-vector/job-interview-concept-illustration_114360-244.jpg"
                        class="img-fluid about-img">
                </div>

            </div>

            <!-- MISSION + VISION -->
            <div class="row mb-5 g-4">

                <div class="col-md-6">
                    <div class="box">
                        <h3><i class="bi bi-bullseye"></i> Mission</h3>
                        <p>
                            To bridge the gap between job seekers and employers through a seamless
                            and efficient platform.
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="box">
                        <h3><i class="bi bi-eye"></i> Vision</h3>
                        <p>
                            To become the most trusted and innovative job portal globally.
                        </p>
                    </div>
                </div>

            </div>

            <!-- FEATURES -->
            <div class="row mb-5 text-center">

                <h3 class="mb-4">Why Choose CareerHunt?</h3>

                <div class="col-md-3 feature">
                    <p><i class="bi bi-check-circle"></i> Smart Job Search</p>
                </div>

                <div class="col-md-3 feature">
                    <p><i class="bi bi-check-circle"></i> Verified Companies</p>
                </div>

                <div class="col-md-3 feature">
                    <p><i class="bi bi-check-circle"></i> Easy Apply</p>
                </div>

                <div class="col-md-3 feature">
                    <p><i class="bi bi-check-circle"></i> Career Growth</p>
                </div>

            </div>

            <!-- STATS -->
            <div class="row text-center mb-5 g-4">

                <div class="col-md-3">
                    <div class="box">
                        <h2>10K+</h2>
                        <p>Jobs Posted</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box">
                        <h2>5K+</h2>
                        <p>Companies</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box">
                        <h2>20K+</h2>
                        <p>Users</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box">
                        <h2>95%</h2>
                        <p>Success Rate</p>
                    </div>
                </div>

            </div>

            <!-- CTA -->
            <div class="text-center mb-5">
                <h3>Start Your Career Journey Today 🚀</h3>
                <p>Join thousands of professionals finding jobs on CareerHunt</p>
                <a href="jobs.php" class="btn cta-btn">Get Started</a>
            </div>

            <!-- FOOTER INSIDE SAME SECTION -->
            <div class="footer">
                <p>&copy; <?php echo date('Y'); ?> CareerHunt. All rights reserved.</p>
            </div>

        </div>

    </section>
    <!-- ===== ONE SINGLE SECTION END ===== -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
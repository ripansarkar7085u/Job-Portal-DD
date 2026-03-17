<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="user.css">
</head>
<body>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <div class="content">

        <h2 class="page-title">My Profile</h2>
        <p class="text-muted">Manage your personal information</p>

        <!-- PROFILE SECTION -->

        <div class="card-box">

            <div class="row g-4">

                <!-- Profile Photo -->
                <div class="col-md-3 text-center">

                    <img src="https://i.pravatar.cc/150" class="rounded-circle mb-3" width="120" id="profilePhoto">

                    <div class="upload-box mb-3">
                        <input type="file" class="form-control">
                    </div>

                    <button class="btn btn-theme btn-sm">Change Photo</button>

                </div>

                <!-- Profile Form -->

                <div class="col-md-9">

                    <div class="row g-4">

                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Job Title</label>
                            <input type="text" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Website</label>
                            <input type="text" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" placeholder="City, Country">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Current Salary</label>
                            <select class="form-select">
                                <option>40-70K</option>
                                <option>70-100K</option>
                                <option>100-150K</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Experience</label>
                            <select class="form-select">
                                <option>Fresher</option>
                                <option>1-3 Years</option>
                                <option>3-5 Years</option>
                                <option>5-10 Years</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Age</label>
                            <select class="form-select">
                                <option>18-22</option>
                                <option>23-27</option>
                                <option>28-35</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" rows="4"></textarea>
                        </div>

                    </div>

                    <div class="mt-4">
                        <button class="btn btn-theme">Save Profile</button>
                    </div>

                </div>

            </div>

        </div>

        <!-- SOCIAL NETWORK SECTION -->

        <div class="card-box">

            <h5 class="section-title">Social Networks</h5>

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-linkedin"></i> LinkedIn</label>
                    <input type="text" class="form-control" placeholder="LinkedIn profile link">
                </div>

                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-github"></i> GitHub</label>
                    <input type="text" class="form-control" placeholder="GitHub profile link">
                </div>

                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-twitter"></i> Twitter</label>
                    <input type="text" class="form-control" placeholder="Twitter profile link">
                </div>

                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-facebook"></i> Facebook</label>
                    <input type="text" class="form-control" placeholder="Facebook profile link">
                </div>

            </div>

            <div class="mt-4">
                <button class="btn btn-theme">Save Social Links</button>
            </div>

        </div>

    </div>

    <script src="user.js"></script>
</body>
</html>
<<<<<<< Updated upstream
<?php
require_once __DIR__ . '/_user_common.php';

$userId = user_require_login();
user_ensure_profiles_table($conn);

// Fetch profile data for the logged-in user.
$stmt = $conn->prepare('SELECT * FROM profiles WHERE user_id = ? LIMIT 1');
$user_data = null;
if ($stmt) {
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result ? $result->fetch_assoc() : null;
    $stmt->close();
}

// 3. Fallback if database is empty
if (!$user_data) {
    $user_data = [
        'full_name' => '',
        'job_title' => '',
        'phone' => '',
        'email' => '',
        'website' => '',
        'location' => '',
        'skills' => '',
        'salary' => '',
        'experience' => '',
        'age' => '',
        'description' => '',
        'linkedin' => '',
        'github' => '',
        'twitter' => '',
        'facebook' => '',
        'profile_image' => ''
    ];
}

// Determine image path
$image_src = 'https://ui-avatars.com/api/?name=User&background=0d47a1&color=fff';
if (!empty($user_data['profile_image'])) {
    $image_src = (strpos($user_data['profile_image'], 'http') !== false)
        ? $user_data['profile_image']
        : 'uploads/' . $user_data['profile_image'];
}
?>

<!DOCTYPE html>
<html lang="en">

=======
<!DOCTYPE html>
<html lang="en">
>>>>>>> Stashed changes
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="user.css">
<<<<<<< Updated upstream
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <?php include 'sidebar.php'; ?>

    <div class="content">
        <h2 class="page-title">My Profile</h2>

        <form action="save_profile.php" method="POST" enctype="multipart/form-data">
            <div class="card-box">
                <div class="row g-4">
                    <div class="col-md-3 text-center">
                        <img src="<?php echo $image_src; ?>" class="rounded-circle mb-3" width="120" id="profilePhoto"
                            style="object-fit: cover; height: 120px;">
                        <div class="upload-box mb-3">
                            <input type="file" name="profile_image" class="form-control" onchange="previewImage(this)">
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="full_name" class="form-control"
                                    value="<?php echo htmlspecialchars($user_data['full_name']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Job Title</label>
                                <input type="text" name="job_title" class="form-control"
                                    value="<?php echo htmlspecialchars($user_data['job_title']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control"
                                    value="<?php echo htmlspecialchars($user_data['phone']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control"
                                    value="<?php echo htmlspecialchars($user_data['email']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Website</label>
                                <input type="text" name="website" class="form-control"
                                    value="<?php echo htmlspecialchars($user_data['website']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Location</label>
                                <input type="text" name="location" class="form-control"
                                    value="<?php echo htmlspecialchars($user_data['location']); ?>">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Skills (comma separated)</label>
                                <input type="text" name="skills" class="form-control"
                                    value="<?php echo htmlspecialchars($user_data['skills'] ?? ''); ?>"
                                    placeholder="e.g. PHP, Laravel, React, MySQL">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Current Salary</label>
                                <select name="salary" class="form-select">
                                    <option <?php if ($user_data['salary'] == '40-70K') echo 'selected'; ?>>40-70K</option>
                                    <option <?php if ($user_data['salary'] == '70-100K') echo 'selected'; ?>>70-100K</option>
                                    <option <?php if ($user_data['salary'] == '100-150K') echo 'selected'; ?>>100-150K</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Experience</label>
                                <select name="experience" class="form-select">
                                    <option <?php if ($user_data['experience'] == 'Fresher') echo 'selected'; ?>>Fresher</option>
                                    <option <?php if ($user_data['experience'] == '1-3 Years') echo 'selected'; ?>>1-3 Years</option>
                                    <option <?php if ($user_data['experience'] == '3-5 Years') echo 'selected'; ?>>3-5 Years</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Age</label>
                                <input type="number" name="age" class="form-control"
                                    value="<?php echo htmlspecialchars($user_data['age']); ?>"
                                    placeholder="Enter your age">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control"
                                    rows="4"><?php echo htmlspecialchars($user_data['description']); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-box mt-4">
                <h5 class="section-title">Social Networks</h5>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">LinkedIn</label>
                        <input type="text" name="linkedin" class="form-control"
                            value="<?php echo htmlspecialchars($user_data['linkedin']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">GitHub</label>
                        <input type="text" name="github" class="form-control"
                            value="<?php echo htmlspecialchars($user_data['github']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Twitter</label>
                        <input type="text" name="twitter" class="form-control"
                            value="<?php echo htmlspecialchars($user_data['twitter']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Facebook</label>
                        <input type="text" name="facebook" class="form-control"
                            value="<?php echo htmlspecialchars($user_data['facebook']); ?>">
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" name="save_all" class="btn btn-primary">Update Profile</button>
                    <button type="button" id="deleteProfileBtn" class="btn btn-danger ms-2">Delete Profile</button>
                </div>
            </div>
        </form>

    <script src="user.js"></script>
    <script>
    // 2. Function to preview image
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('profilePhoto').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

        // 3. Trigger SweetAlert if status is success in URL
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('status') === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Profile Updated!',
                text: 'Your details have been saved to the database.',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
            // Optional: Clean the URL so the alert doesn't show again on manual refresh
            window.history.replaceState({}, document.title, window.location.pathname);
        }

    // Delete Profile logic
    document.addEventListener('DOMContentLoaded', function() {
        const delBtn = document.getElementById('deleteProfileBtn');
        if (delBtn) {
            delBtn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This will permanently delete your account and all data!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('../api/user_delete_profile.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: res.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                setTimeout(() => {
                                    window.location.href = '../login.php';
                                }, 2000);
                            } else {
                                Swal.fire('Error', res.message, 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Error', 'Failed to delete profile.', 'error');
                        });
                    }
                });
            });
        }
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // 2. Function to preview image
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('profilePhoto').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // 3. Trigger SweetAlert if status is success in URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('status') === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Profile Updated!',
                text: 'Your details have been saved to the database.',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
            
            // Optional: Clean the URL so the alert doesn't show again on manual refresh
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>
</body>

=======
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
>>>>>>> Stashed changes
</html>
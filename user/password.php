<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Change Password - CareerHunt</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link rel="stylesheet" href="user.css">

</head>

<body>

    <div class="dashboard">

        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Content -->
        <div class="content p-4">

            <h2>Change Password</h2>

            <div class="card mt-3 shadow-sm">
                <div class="card-body w-100">

                    <form method="POST">

                        <input type="password" name="current_password" class="form-control mb-3"
                            placeholder="Current Password" required>

                        <input type="password" name="new_password" class="form-control mb-3" placeholder="New Password"
                            required>

                        <input type="password" name="confirm_password" class="form-control mb-3"
                            placeholder="Confirm Password" required>

                        <button class="btn btn-primary btn-theme mt-2" name="update_password">
                            Update Password
                        </button>

                    </form>

                </div>
            </div>

        </div>

    </div>

</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Delete Profile - CareerHunt</title>

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

            <h2>Delete Profile</h2>
            <p class="text-danger">This action cannot be undone.</p>

            <div class="card mt-3 shadow-sm">
                <div class="card-body">

                    <p>Are you sure you want to delete your account?</p>

                    <button class="btn btn-danger">
                        <i class="bi bi-trash"></i> Delete Account
                    </button>

                </div>
            </div>

        </div>

    </div>

</body>

</html>
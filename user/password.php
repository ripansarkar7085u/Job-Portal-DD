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
<<<<<<< Updated upstream
                <div class="card-body w-100">


                    <form id="passwordForm" autocomplete="off">
                        <div id="passwordMsg"></div>
                        <input type="password" name="current_password" class="form-control mb-3"
                            placeholder="Current Password" required>
                        <input type="password" name="new_password" class="form-control mb-3" placeholder="New Password"
                            required>
                        <input type="password" name="confirm_password" class="form-control mb-3"
                            placeholder="Confirm Password" required>
                        <button type="submit" class="btn btn-theme mt-2">
                            Update Password
                        </button>
=======
                <div class="card-body w-50">

                    <form method="POST">

                        <input type="password" name="current_password" class="form-control mb-3"
                            placeholder="Current Password" required>

                        <input type="password" name="new_password" class="form-control mb-3" placeholder="New Password"
                            required>

                        <input type="password" name="confirm_password" class="form-control mb-3"
                            placeholder="Confirm Password" required>

                        <button class="btn btn-theme mt-2" name="update_password">
                            Update Password
                        </button>

>>>>>>> Stashed changes
                    </form>

                </div>
            </div>

        </div>

    </div>

<<<<<<< Updated upstream

<script src="user.js"></script>
<script>
// Password update AJAX
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('passwordForm');
    const msgDiv = document.getElementById('passwordMsg');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            msgDiv.innerHTML = '';
            const data = {
                current_password: form.current_password.value,
                new_password: form.new_password.value,
                confirm_password: form.confirm_password.value
            };
            fetch('../api/user_update_password.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                msgDiv.innerHTML = `<div class='alert alert-${res.success ? 'success' : 'danger'}'>${res.message}</div>`;
                if (res.success) form.reset();
            })
            .catch(() => {
                msgDiv.innerHTML = `<div class='alert alert-danger'>An error occurred. Please try again.</div>`;
            });
        });
    }
});
</script>
=======
>>>>>>> Stashed changes
</body>

</html>
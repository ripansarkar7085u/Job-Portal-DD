<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>Change Password - CareerHunt</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link rel="stylesheet" href="user.css">

</head>

<body>

    <div class="user-container" id="userDashboard">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <header class="main-header">
                <div class="header-left">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1 class="page-title">Change Password</h1>
                </div>
            </header>

            <section class="content-section p-4">

            <div class="card mt-3 shadow-sm">
                <div class="card-body w-100">


                    <form id="passwordForm" autocomplete="off">
                        <div id="passwordMsg"></div>
                        
                        <div class="input-group mb-3">
                            <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Current Password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        
                        <div class="input-group mb-3">
                            <input type="password" name="new_password" id="new_password" class="form-control" placeholder="New Password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        
                        <div class="input-group mb-3">
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="confirm_password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-theme mt-2">
                            Update Password
                        </button>
                    </form>

            </section>
        </main>
    </div>

<!-- Removed user.js -->
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

    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
});
</script>
</body>

</html>

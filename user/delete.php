<?php
require_once __DIR__ . '/_user_common.php';

$userId = user_require_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Delete Profile - CareerHunt</title>
    <link rel="stylesheet" href="user.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
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
                    <h1 class="page-title">Delete Profile</h1>
                </div>
            </header>
            <section class="content-section">
                <div class="page-header mb-4">
                    <p class="text-danger">This action cannot be undone.</p>
                </div>
                <div class="dashboard-card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h4>Are you sure you want to delete your account?</h4>
                        <p class="text-muted mb-4">Deleting your account will permanently remove all your job applications, saved profiles, and messages.</p>
                        
                        <button class="btn btn-danger px-4" id="deleteAccountBtn">
                            <i class="bi bi-trash"></i> Delete Account
                        </button>
                        <div id="deleteErrorMsg" class="text-danger mt-3" style="display:none;"></div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
    document.getElementById('deleteAccountBtn').addEventListener('click', async function() {
        if (!confirm('Are you absolutely sure you want to delete your account? This cannot be undone.')) {
            return;
        }
        
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...';
        
        try {
            const res = await fetch('../api/user_delete_account.php', {
                method: 'POST'
            });
            const data = await res.json();
            
            if (data.success) {
                window.location.href = data.redirect || '../index.php';
            } else {
                document.getElementById('deleteErrorMsg').style.display = 'block';
                document.getElementById('deleteErrorMsg').textContent = data.message || 'Error deleting account.';
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-trash"></i> Delete Account';
            }
        } catch (err) {
            document.getElementById('deleteErrorMsg').style.display = 'block';
            document.getElementById('deleteErrorMsg').textContent = 'Network error occurred.';
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-trash"></i> Delete Account';
        }
    });
    </script>
</body>
</html>

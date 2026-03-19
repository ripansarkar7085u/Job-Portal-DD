<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    if (($_SESSION['account_type'] ?? '') === 'company') {
        header('Location: /Job-Portal-DD/company/index.php');
    } else {
        header('Location: \Job-Portal-DD\user\index.php');
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CareerHunt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .auth-page-wrap { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px 16px; background: linear-gradient(135deg, #f8fbff 0%, #eef5ff 100%); }
        .auth-page-card { width: 100%; max-width: 560px; border-radius: 18px; background: #fff; box-shadow: 0 18px 40px rgba(13, 71, 161, 0.15); overflow: hidden; }
        .auth-page-head { padding: 24px; border-bottom: 1px solid #edf2f7; }
        .auth-page-head h1 { margin: 0; font-size: 1.6rem; }
        .auth-page-body { padding: 24px; }
        .account-switch { display: flex; gap: 8px; margin-bottom: 18px; }
        .account-switch button { flex: 1; border: 0; padding: 10px; border-radius: 10px; background: #eef2f8; font-weight: 600; }
        .account-switch button.active { background: #0d47a1; color: #fff; }
        .alt-links { margin-top: 16px; text-align: center; font-size: 0.95rem; }
    </style>
</head>
<body>
<div class="auth-page-wrap">
    <div class="auth-page-card">
        <div class="auth-page-head">
            <div class="d-flex align-items-center justify-content-between">
                <h1>Login</h1>
                <a href="index.php" class="text-decoration-none"><i class="bi bi-arrow-left"></i> Home</a>
            </div>
        </div>
        <div class="auth-page-body">
            <div id="alertContainer"></div>

            <div class="account-switch">
                <button type="button" id="userLoginTab" class="active">Job Seeker</button>
                <button type="button" id="companyLoginTab">Company</button>
            </div>

            <div id="userForms">
                <form id="userLoginForm">
                    <div class="form-group mb-3">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                        <div class="error-message"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                        <div class="error-message"></div>
                    </div>
                    <div class="form-options mb-3">
                        <label class="checkbox-label"><input type="checkbox" name="remember"> Remember me</label>
                    </div>
                    <button type="submit" class="btn auth-btn"><i class="bi bi-box-arrow-in-right"></i> Log In</button>
                </form>
            </div>

            <div id="companyForms" style="display:none;">
                <form id="companyLoginForm">
                    <div class="form-group mb-3">
                        <label>Company Email</label>
                        <input type="email" name="email" class="form-control" required>
                        <div class="error-message"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                        <div class="error-message"></div>
                    </div>
                    <div class="form-options mb-3">
                        <label class="checkbox-label"><input type="checkbox" name="remember"> Remember me</label>
                    </div>
                    <button type="submit" class="btn auth-btn"><i class="bi bi-box-arrow-in-right"></i> Company Log In</button>
                </form>
            </div>

            <div class="alt-links">
                No account yet? <a href="register.php">Create one</a>
            </div>
        </div>
    </div>
</div>

<script src="js/auth.js"></script>
<script>
    const userLoginTab = document.getElementById('userLoginTab');
    const companyLoginTab = document.getElementById('companyLoginTab');
    const userForms = document.getElementById('userForms');
    const companyForms = document.getElementById('companyForms');

    userLoginTab.addEventListener('click', () => {
        userLoginTab.classList.add('active');
        companyLoginTab.classList.remove('active');
        userForms.style.display = 'block';
        companyForms.style.display = 'none';
    });

    companyLoginTab.addEventListener('click', () => {
        companyLoginTab.classList.add('active');
        userLoginTab.classList.remove('active');
        companyForms.style.display = 'block';
        userForms.style.display = 'none';
    });
</script>
</body>
</html>

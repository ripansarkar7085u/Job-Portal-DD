<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    if (($_SESSION['account_type'] ?? '') === 'company') {
        header('Location: /Job-Portal-DD/company/index.php');
    } else {
        header('Location: /Job-Portal-DD/user/dashboard.php');
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CareerHunt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .auth-page-wrap { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px 16px; background: linear-gradient(135deg, #f8fbff 0%, #eef5ff 100%); }
        .auth-page-card { width: 100%; max-width: 620px; border-radius: 18px; background: #fff; box-shadow: 0 18px 40px rgba(13, 71, 161, 0.15); overflow: hidden; }
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
                <h1>Create Account</h1>
                <a href="index.php" class="text-decoration-none"><i class="bi bi-arrow-left"></i> Home</a>
            </div>
        </div>
        <div class="auth-page-body">
            <div id="alertContainer"></div>

            <div class="account-switch">
                <button type="button" id="userRegTab" class="active">Job Seeker</button>
                <button type="button" id="companyRegTab">Company</button>
            </div>

            <div id="userRegWrap">
                <form id="userRegisterForm">
                    <div class="form-group mb-3">
                        <label>Full Name</label>
                        <input type="text" name="full_name" class="form-control" required>
                        <div class="error-message"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                        <div class="error-message"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>Phone (optional)</label>
                        <input type="tel" name="phone" class="form-control">
                        <div class="error-message"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                        <div class="error-message"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                        <div class="error-message"></div>
                    </div>
                    <button type="submit" class="btn auth-btn"><i class="bi bi-person-plus"></i> Create User Account</button>
                </form>
            </div>

            <div id="companyRegWrap" style="display:none;">
                <form id="companyRegisterForm">
                    <div class="form-group mb-3">
                        <label>Company Name</label>
                        <input type="text" name="company_name" class="form-control" required>
                        <div class="error-message"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>Company Email</label>
                        <input type="email" name="email" class="form-control" required>
                        <div class="error-message"></div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6 form-group">
                            <label>Phone</label>
                            <input type="tel" name="phone" class="form-control">
                            <div class="error-message"></div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Industry</label>
                            <select name="industry" class="form-control" required>
                                <option value="">Select Industry</option>
                                <option value="Technology">Technology</option>
                                <option value="Finance">Finance</option>
                                <option value="Healthcare">Healthcare</option>
                                <option value="Education">Education</option>
                                <option value="Retail">Retail</option>
                                <option value="Manufacturing">Manufacturing</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Consulting">Consulting</option>
                                <option value="Other">Other</option>
                            </select>
                            <div class="error-message"></div>
                        </div>
                    </div>
                    <div class="form-group mt-3 mb-3">
                        <label>Website (optional)</label>
                        <input type="url" name="website" class="form-control">
                        <div class="error-message"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                        <div class="error-message"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                        <div class="error-message"></div>
                    </div>
                    <button type="submit" class="btn auth-btn"><i class="bi bi-building-add"></i> Create Company Account</button>
                </form>
            </div>

            <div class="alt-links">
                Already registered? <a href="login.php">Sign in</a>
            </div>
        </div>
    </div>
</div>

<script src="js/auth.js"></script>
<script>
    const userRegTab = document.getElementById('userRegTab');
    const companyRegTab = document.getElementById('companyRegTab');
    const userRegWrap = document.getElementById('userRegWrap');
    const companyRegWrap = document.getElementById('companyRegWrap');

    userRegTab.addEventListener('click', () => {
        userRegTab.classList.add('active');
        companyRegTab.classList.remove('active');
        userRegWrap.style.display = 'block';
        companyRegWrap.style.display = 'none';
    });

    companyRegTab.addEventListener('click', () => {
        companyRegTab.classList.add('active');
        userRegTab.classList.remove('active');
        companyRegWrap.style.display = 'block';
        userRegWrap.style.display = 'none';
    });
</script>
</body>
</html>

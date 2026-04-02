<?php ini_set('display_errors', 1); error_reporting(E_ALL); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - CareerHunt</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>
    <div class="admin-login-container" id="adminLoginContainer">
        <div class="admin-login-card">
            <div class="login-header">
                <a href="/index.php" class="logo">
                    <img src="../photos/job_logo.png" alt="CareerHunt">
                </a>
                <h1>Admin Login</h1>
                <p>Enter your credentials to access the admin panel</p>
            </div>
            <form id="adminLoginForm" class="login-form">
                <div class="form-group">
                    <label for="adminUsername">Username</label>
                    <div class="input-wrapper">
                        <i class="bi bi-person-fill"></i>
                        <input type="text" id="adminUsername" name="username" placeholder="Enter your username" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="adminPassword">Password</label>
                    <div class="input-wrapper">
                        <i class="bi bi-lock-fill"></i>
                        <input type="password" id="adminPassword" name="password" placeholder="Enter your password" required>
                        <button type="button" class="toggle-password" id="togglePassword">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                    </div>
                </div>
                <div class="form-error" id="loginError"></div>
                <button type="submit" class="btn-login" id="loginBtn">
                    <span>Login</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>
            <div class="login-footer">
                <a href="../index.php"><i class="bi bi-arrow-left"></i> Back to Website</a>
            </div>
        </div>
    </div>
    <script src="js/admin.js"></script>
    <script>
    // Override login handler to redirect to index.php on success
    document.getElementById('adminLoginForm').onsubmit = async function(e) {
        e.preventDefault();
        const loginBtn = document.getElementById('loginBtn');
        const errorDiv = document.getElementById('loginError');
        const username = document.getElementById('adminUsername').value.trim();
        const password = document.getElementById('adminPassword').value;
        errorDiv.textContent = '';
        if (!username || !password) {
            errorDiv.textContent = 'Please fill in all fields';
            return;
        }
        loginBtn.classList.add('loading');
        loginBtn.disabled = true;
        try {
            const response = await fetch('../api/admin_login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username, password })
            });
            const data = await response.json();
            if (data.success) {
                window.location.href = 'index.php';
            } else {
                errorDiv.textContent = data.message || 'Login failed';
            }
        } catch (error) {
            errorDiv.textContent = 'Connection error. Please try again.';
        } finally {
            loginBtn.classList.remove('loading');
            loginBtn.disabled = false;
        }
    };
    </script>
</body>
</html>

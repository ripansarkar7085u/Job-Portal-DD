<?php
// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Get company info from session
$companyName = $_SESSION['company_name'] ?? 'Company';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Settings - CareerHunt</title>
    <link rel="stylesheet" href="css/company.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>
    <div class="company-container" id="companyDashboard">
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="main-header">
                <div class="header-left">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1 class="page-title">Settings</h1>
                </div>
                <div class="header-right">
                    <button class="notification-btn" id="notificationBtn">
                        <i class="bi bi-bell-fill"></i>
                        <span class="notification-badge" id="notificationCount">5</span>
                    </button>
                    <div class="profile-dropdown">
                        <button class="profile-btn" id="profileBtn">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($companyName); ?>&background=0d47a1&color=fff" alt="Company" id="headerAvatar">
                            <span id="headerCompanyName"><?php echo htmlspecialchars($companyName); ?></span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu" id="profileDropdown">
                            <a href="profile.php" class="dropdown-item">
                                <i class="bi bi-building"></i> Company Profile
                            </a>
                            <a href="settings.php" class="dropdown-item">
                                <i class="bi bi-gear"></i> Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <button class="dropdown-item text-danger" id="dropdownLogout">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Settings Content -->
            <section class="content-section">
                <div class="page-header">
                    <div>
                        <h1>Settings</h1>
                        <p>Manage your account settings and preferences</p>
                    </div>
                </div>

                <div class="settings-grid">
                    <!-- Account Settings -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h2><i class="bi bi-person-circle"></i> Account Settings</h2>
                        </div>
                        <div class="card-body">
                            <form id="accountForm">
                                <div class="form-group">
                                    <label for="accountEmail">Account Email</label>
                                    <input type="email" id="accountEmail" class="form-control" value="admin@techcorp.com">
                                    <p class="form-hint">This email is used for account access and notifications</p>
                                </div>
                                
                                <div class="form-group">
                                    <label for="accountName">Account Name</label>
                                    <input type="text" id="accountName" class="form-control" value="John Smith">
                                </div>

                                <div class="form-group">
                                    <label for="accountPhone">Phone Number</label>
                                    <input type="tel" id="accountPhone" class="form-control" value="+1 (555) 123-4567">
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-lg"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Password & Security -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h2><i class="bi bi-shield-lock"></i> Password & Security</h2>
                        </div>
                        <div class="card-body">
                            <form id="passwordForm">
                                <div class="form-group">
                                    <label for="currentPassword">Current Password</label>
                                    <input type="password" id="currentPassword" class="form-control" placeholder="Enter current password">
                                </div>
                                
                                <div class="form-group">
                                    <label for="newPassword">New Password</label>
                                    <input type="password" id="newPassword" class="form-control" placeholder="Enter new password">
                                    <p class="form-hint">Must be at least 8 characters with a mix of letters, numbers, and symbols</p>
                                </div>

                                <div class="form-group">
                                    <label for="confirmPassword">Confirm New Password</label>
                                    <input type="password" id="confirmPassword" class="form-control" placeholder="Confirm new password">
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-key"></i> Update Password
                                    </button>
                                </div>
                            </form>

                            <div class="security-options mt-4">
                                <h4>Two-Factor Authentication</h4>
                                <div class="toggle-option">
                                    <div class="toggle-info">
                                        <span class="toggle-label">Enable 2FA</span>
                                        <span class="toggle-desc">Add an extra layer of security to your account</span>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="enable2FA">
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notification Settings -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h2><i class="bi bi-bell"></i> Notification Settings</h2>
                        </div>
                        <div class="card-body">
                            <div class="notification-options">
                                <div class="toggle-option">
                                    <div class="toggle-info">
                                        <span class="toggle-label">Email Notifications</span>
                                        <span class="toggle-desc">Receive email updates about new applications</span>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="emailNotif" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>

                                <div class="toggle-option">
                                    <div class="toggle-info">
                                        <span class="toggle-label">Application Alerts</span>
                                        <span class="toggle-desc">Get notified when someone applies to your jobs</span>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="appAlerts" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>

                                <div class="toggle-option">
                                    <div class="toggle-info">
                                        <span class="toggle-label">Weekly Summary</span>
                                        <span class="toggle-desc">Receive a weekly summary of your job postings</span>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="weeklySummary" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>

                                <div class="toggle-option">
                                    <div class="toggle-info">
                                        <span class="toggle-label">Marketing Emails</span>
                                        <span class="toggle-desc">Receive tips, updates, and promotions from CareerHunt</span>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="marketingEmails">
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Billing & Subscription -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h2><i class="bi bi-credit-card"></i> Billing & Subscription</h2>
                        </div>
                        <div class="card-body">
                            <div class="current-plan">
                                <div class="plan-info">
                                    <span class="plan-badge">Pro Plan</span>
                                    <h3>$99/month</h3>
                                    <p>Your plan renews on April 15, 2026</p>
                                </div>
                                <div class="plan-features">
                                    <ul>
                                        <li><i class="bi bi-check-circle-fill"></i> Unlimited job postings</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Featured company listing</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Advanced analytics</li>
                                        <li><i class="bi bi-check-circle-fill"></i> Priority support</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="billing-actions">
                                <button class="btn btn-outline">
                                    <i class="bi bi-arrow-up-circle"></i> Upgrade Plan
                                </button>
                                <button class="btn btn-outline">
                                    <i class="bi bi-receipt"></i> View Invoices
                                </button>
                            </div>

                            <div class="payment-method mt-4">
                                <h4>Payment Method</h4>
                                <div class="card-display">
                                    <i class="bi bi-credit-card-2-front"></i>
                                    <span>Visa ending in 4242</span>
                                    <button class="btn btn-sm btn-outline">Change</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="dashboard-card danger-zone">
                        <div class="card-header">
                            <h2><i class="bi bi-exclamation-triangle"></i> Danger Zone</h2>
                        </div>
                        <div class="card-body">
                            <div class="danger-option">
                                <div class="danger-info">
                                    <h4>Deactivate Account</h4>
                                    <p>Temporarily disable your company profile and job listings</p>
                                </div>
                                <button class="btn btn-outline-warning" id="deactivateBtn">Deactivate</button>
                            </div>
                            
                            <div class="danger-option">
                                <div class="danger-info">
                                    <h4>Delete Account</h4>
                                    <p>Permanently delete your account and all associated data. This action cannot be undone.</p>
                                </div>
                                <button class="btn btn-danger" id="deleteAccountBtn">Delete Account</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/company.js?v=<?php echo filemtime(__DIR__ . '/js/company.js'); ?>"></script>
    <script>
        // Account form submission
        // --- Dynamic Company Settings Logic ---
        // Load company info into settings form
        async function loadCompanySettings() {
            try {
                const res = await fetch('../api/company_public_profile.php?id=me', { credentials: 'include' });
                const data = await res.json();
                if (data.success && data.company) {
                    const c = data.company;
                    document.getElementById('accountEmail').value = c.email || '';
                    document.getElementById('accountName').value = c.company_name || '';
                    document.getElementById('accountPhone').value = c.phone || '';
                    // Sidebar/header update
                    if (document.getElementById('companyNameDisplay')) document.getElementById('companyNameDisplay').textContent = c.company_name || '';
                    if (document.getElementById('companyAvatar')) document.getElementById('companyAvatar').src = c.logo || `https://ui-avatars.com/api/?name=${encodeURIComponent(c.company_name || 'Company')}&background=0d47a1&color=fff`;
                    if (document.getElementById('headerCompanyName')) document.getElementById('headerCompanyName').textContent = c.company_name || '';
                    if (document.getElementById('headerAvatar')) document.getElementById('headerAvatar').src = c.logo || `https://ui-avatars.com/api/?name=${encodeURIComponent(c.company_name || 'Company')}&background=0d47a1&color=fff`;
                }
            } catch (e) {
                // fallback: do nothing
            }
        }
        loadCompanySettings();

        // Account form submission (update company info)
        document.getElementById('accountForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const company_name = document.getElementById('accountName').value.trim();
            const email = document.getElementById('accountEmail').value.trim();
            const phone = document.getElementById('accountPhone').value.trim();
            try {
                const res = await fetch('../api/company_update_profile.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'include',
                    body: JSON.stringify({ company_name, email, phone })
                });
                const data = await res.json();
                if (data.success) {
                    if (window.companyDashboard && window.companyDashboard.showToast) {
                        window.companyDashboard.showToast('Account settings updated!', 'success');
                    } else {
                        alert('Account settings updated!');
                    }
                    loadCompanySettings();
                } else {
                    window.companyDashboard?.showToast?.(data.message || 'Failed to update account', 'error');
                }
            } catch (err) {
                window.companyDashboard?.showToast?.('Error updating account', 'error');
            }
        });

        // Password form submission (update password)
        document.getElementById('passwordForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const newPass = document.getElementById('newPassword').value;
            const confirmPass = document.getElementById('confirmPassword').value;
            if (newPass !== confirmPass) {
                window.companyDashboard?.showToast?.('Passwords do not match!', 'error');
                return;
            }
            try {
                const res = await fetch('../api/user_update_password.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'include',
                    body: JSON.stringify({ password: newPass })
                });
                const data = await res.json();
                if (data.success) {
                    window.companyDashboard?.showToast?.('Password updated successfully!', 'success');
                    this.reset();
                } else {
                    window.companyDashboard?.showToast?.(data.message || 'Failed to update password', 'error');
                }
            } catch (err) {
                window.companyDashboard?.showToast?.('Error updating password', 'error');
            }
        });

        // Deactivate account
        document.getElementById('deactivateBtn').addEventListener('click', function() {
            if (confirm('Are you sure you want to deactivate your account? Your job listings will be hidden.')) {
                window.companyDashboard?.showToast?.('Account deactivated', 'warning');
                // TODO: call backend to deactivate
            }
        });

        // Delete account
        document.getElementById('deleteAccountBtn').addEventListener('click', function() {
            if (confirm('Are you sure you want to permanently delete your account? This action cannot be undone.')) {
                if (confirm('This will delete all your jobs, applications, and company data. Type DELETE to confirm.')) {
                    window.companyDashboard?.showToast?.('Account deletion initiated', 'error');
                    // TODO: call backend to delete
                }
            }
        });

        // Sidebar/menu/profile dropdown logic (ensure always initialized)
        if (typeof handleNavigation === 'function') handleNavigation();
        if (menuToggle && sidebar && sidebarOverlay) {
            menuToggle.addEventListener('click', toggleSidebar);
            sidebarOverlay.addEventListener('click', closeSidebar);
        }
        if (profileBtn && profileDropdown) {
            profileBtn.addEventListener('click', toggleProfileDropdown);
            document.addEventListener('click', closeProfileDropdown);
        }
    </script>

    <style>
        /* Settings Page Specific Styles */
        .settings-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        .toggle-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .toggle-option:last-child {
            border-bottom: none;
        }

        .toggle-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .toggle-label {
            font-weight: 600;
            color: var(--text-primary);
        }

        .toggle-desc {
            font-size: 0.875rem;
            color: var(--text-light);
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 28px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.3s;
            border-radius: 28px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }

        .toggle-switch input:checked + .toggle-slider {
            background-color: var(--primary);
        }

        .toggle-switch input:checked + .toggle-slider:before {
            transform: translateX(22px);
        }

        .current-plan {
            background: linear-gradient(135deg, var(--primary) 0%, #1565c0 100%);
            color: white;
            padding: 24px;
            border-radius: var(--radius);
            margin-bottom: 20px;
        }

        .plan-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .plan-info h3 {
            font-size: 2rem;
            margin: 12px 0 8px;
        }

        .plan-info p {
            opacity: 0.9;
            font-size: 0.9rem;
        }

        .plan-features {
            margin-top: 16px;
        }

        .plan-features ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .plan-features li {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .plan-features li i {
            color: #4caf50;
        }

        .billing-actions {
            display: flex;
            gap: 12px;
        }

        .payment-method h4 {
            margin-bottom: 12px;
            font-size: 1rem;
        }

        .card-display {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            background: var(--bg-main);
            border-radius: var(--radius-sm);
        }

        .card-display i {
            font-size: 1.5rem;
            color: var(--primary);
        }

        .card-display span {
            flex: 1;
        }

        .danger-zone .card-header {
            background: #fff5f5;
        }

        .danger-zone .card-header h2 {
            color: #dc3545;
        }

        .danger-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .danger-option:last-child {
            border-bottom: none;
        }

        .danger-info h4 {
            margin-bottom: 4px;
            font-size: 1rem;
        }

        .danger-info p {
            font-size: 0.875rem;
            color: var(--text-light);
            margin: 0;
        }

        .btn-outline-warning {
            color: #f59e0b;
            border-color: #f59e0b;
            background: transparent;
        }

        .btn-outline-warning:hover {
            background: #f59e0b;
            color: white;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
            border: none;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .security-options h4 {
            margin-bottom: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--border-color);
            font-size: 1rem;
        }

        @media (max-width: 992px) {
            .settings-grid {
                grid-template-columns: 1fr;
            }

            .plan-features ul {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .danger-option {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .billing-actions {
                flex-direction: column;
            }
        }
    </style>
</body>
</html>

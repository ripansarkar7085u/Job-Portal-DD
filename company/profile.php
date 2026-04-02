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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Company Profile - CareerHunt</title>
    <link rel="stylesheet" href="css/company.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>
        <?php if (isset($_GET['status'])): ?>
            <div class="container mt-3">
                <?php if ($_GET['status'] === 'success'): ?>
                    <div class="alert alert-success">Profile updated successfully.</div>
                <?php elseif ($_GET['status'] === 'error'): ?>
                    <div class="alert alert-danger">Failed to update profile. Please try again.</div>
                <?php elseif ($_GET['status'] === 'unauthorized'): ?>
                    <div class="alert alert-danger">You are not authorized to update this profile.</div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
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
                    <h1 class="page-title">Company Profile</h1>
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

            <!-- Profile Content -->
            <section class="content-section">
                <div class="page-header">
                    <div>
                        <h1>Company Profile</h1>
                        <p>Manage your company's public profile information</p>
                    </div>
                    <a href="../companies.php" target="_blank" class="btn btn-outline">
                        <i class="bi bi-eye"></i> Preview Public Profile
                    </a>
                </div>

                <div class="profile-grid">
                    <!-- Profile Form -->
                    <div class="dashboard-card profile-form-card">
                        <div class="card-header">
                            <h2><i class="bi bi-pencil-square"></i> Edit Profile</h2>
                        </div>
                        <div class="card-body">
                            <form id="profileForm" action="../api/save_company_profile.php" method="POST" enctype="multipart/form-data">
                                <!-- Logo Upload -->
                                <div class="logo-upload-section">
                                    <div class="current-logo">
                                        <img src="" alt="Company Logo" id="logoPreview">
                                    </div>
                                    <div class="logo-upload-info">
                                        <h4>Company Logo</h4>
                                        <p>Upload a square logo (min. 200x200px)</p>
                                        <label class="btn btn-outline btn-sm">
                                            <i class="bi bi-upload"></i> Upload Logo
                                            <input type="file" id="logoUpload" name="logo" accept="image/*">
                                        </label>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="companyName">Company Name <span class="required">*</span></label>
                                        <input type="text" id="companyName" name="company_name" class="form-control" value="" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="industry">Industry <span class="required">*</span></label>
                                        <select id="industry" name="industry" class="form-control" required>
                                            <option value="">Select Industry</option>
                                            <option value="technology">Technology</option>
                                            <option value="finance">Finance & Banking</option>
                                            <option value="healthcare">Healthcare</option>
                                            <option value="education">Education</option>
                                            <option value="retail">Retail & E-commerce</option>
                                            <option value="manufacturing">Manufacturing</option>
                                            <option value="media">Media & Entertainment</option>
                                            <option value="consulting">Consulting</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="companySize">Company Size <span class="required">*</span></label>
                                        <select id="companySize" name="company_size" class="form-control" required>
                                            <option value="">Select Size</option>
                                            <option value="1-10">1-10 employees</option>
                                            <option value="11-50">11-50 employees</option>
                                            <option value="51-100">51-100 employees</option>
                                            <option value="100-500">100-500 employees</option>
                                            <option value="500-1000">500-1000 employees</option>
                                            <option value="1000+">1000+ employees</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="founded">Founded Year</label>
                                        <input type="number" id="founded" name="founded" class="form-control" value="" min="1800" max="2026">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tagline">Tagline</label>
                                    <input type="text" id="tagline" name="tagline" class="form-control" placeholder="A short description of your company" value="">
                                    <p class="form-hint">A catchy phrase that describes your company (max 100 characters)</p>
                                </div>

                                <div class="form-group">
                                    <label for="description">Company Description <span class="required">*</span></label>
                                    <textarea id="description" name="description" class="form-control" rows="5" required></textarea>
                                    <p class="form-hint">Describe your company, culture, and what makes you unique</p>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="website">Website</label>
                                        <div class="input-with-icon">
                                            <i class="bi bi-globe"></i>
                                            <input type="url" id="website" name="website" class="form-control" value="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Contact Email <span class="required">*</span></label>
                                        <div class="input-with-icon">
                                            <i class="bi bi-envelope"></i>
                                            <input type="email" id="email" name="email" class="form-control" value="" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>
                                        <div class="input-with-icon">
                                            <i class="bi bi-telephone"></i>
                                            <input type="tel" id="phone" name="phone" class="form-control" value="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="location">Headquarters Location <span class="required">*</span></label>
                                        <div class="input-with-icon">
                                            <i class="bi bi-geo-alt"></i>
                                            <input type="text" id="location" name="location" class="form-control" value="" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Social Media Links</label>
                                    <div class="social-links-grid">
                                        <div class="input-with-icon">
                                            <i class="bi bi-linkedin"></i>
                                            <input type="url" id="linkedin" name="linkedin" class="form-control" placeholder="LinkedIn URL" value="">
                                        </div>
                                        <div class="input-with-icon">
                                            <i class="bi bi-twitter-x"></i>
                                            <input type="url" id="twitter" name="twitter" class="form-control" placeholder="Twitter/X URL" value="">
                                        </div>
                                        <div class="input-with-icon">
                                            <i class="bi bi-facebook"></i>
                                            <input type="url" id="facebook" name="facebook" class="form-control" placeholder="Facebook URL" value="">
                                        </div>
                                        <div class="input-with-icon">
                                            <i class="bi bi-instagram"></i>
                                            <input type="url" id="instagram" name="instagram" class="form-control" placeholder="Instagram URL" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="button" class="btn btn-outline">Cancel</button>
                                    <button type="submit" class="btn btn-primary" name="save_all">
                                        <i class="bi bi-check-lg"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Profile Preview Card -->
                    <div class="dashboard-card profile-preview-card">
                        <div class="card-header">
                            <h2><i class="bi bi-eye"></i> Profile Preview</h2>
                        </div>
                        <div class="card-body">
                            <div class="preview-header">
                                <img src="" alt="Company Logo" class="preview-logo" id="previewLogo">
                                <div class="preview-info">
                                    <h3 id="previewCompanyName"></h3>
                                    <p class="preview-tagline" id="previewTagline"></p>
                                    <div class="preview-meta">
                                        <span><i class="bi bi-geo-alt"></i> <span id="previewLocation"></span></span>
                                        <span><i class="bi bi-building"></i> <span id="previewIndustry"></span></span>
                                        <span><i class="bi bi-people"></i> <span id="previewSize"></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-stats">
                                <div class="preview-stat">
                                    <span class="stat-value">18</span>
                                    <span class="stat-label">Active Jobs</span>
                                </div>
                                <div class="preview-stat">
                                    <span class="stat-value">4.5</span>
                                    <span class="stat-label">Rating</span>
                                </div>
                                <div class="preview-stat">
                                    <span class="stat-value">156</span>
                                    <span class="stat-label">Reviews</span>
                                </div>
                            </div>
                            <div class="preview-actions">
                                <a href="jobs.php" class="btn btn-primary btn-sm">
                                    <i class="bi bi-briefcase"></i> View Jobs
                                </a>
                                <button class="btn btn-outline btn-sm">
                                    <i class="bi bi-bookmark"></i> Follow
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Benefits & Perks -->
                    <div class="dashboard-card benefits-card">
                        <div class="card-header">
                            <h2><i class="bi bi-gift"></i> Benefits & Perks</h2>
                            <form id="addBenefitForm" style="display:inline-flex;gap:8px;align-items:center;">
                                <input type="text" id="newBenefitInput" class="form-control form-control-sm" placeholder="Add benefit..." style="width:180px;">
                                <button type="submit" class="btn btn-sm btn-outline"><i class="bi bi-plus"></i> Add</button>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="benefits-grid" id="benefitsGrid"></div>
                        </div>
                    </div>

                    <!-- Company Photos -->
                    <div class="dashboard-card photos-card">
                        <div class="card-header">
                            <h2><i class="bi bi-images"></i> Company Photos</h2>
                            <form id="addPhotoForm" style="display:inline-flex;gap:8px;align-items:center;">
                                <input type="url" id="newPhotoUrl" class="form-control form-control-sm" placeholder="Paste image URL..." style="width:220px;">
                                <button type="submit" class="btn btn-sm btn-outline"><i class="bi bi-plus"></i> Add</button>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="photos-grid" id="photosGrid"></div>
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
        document.addEventListener('DOMContentLoaded', function() {
            const profileForm = document.getElementById('profileForm');
            if (profileForm) {
                profileForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(profileForm);
                    fetch('../api/save_company_profile.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({ icon: 'success', title: 'Success', text: data.message });
                            setTimeout(() => window.location.reload(), 1200);
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                        }
                    })
                    .catch(() => {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Server error. Please try again.' });
                    });
                });
            }
        });
        </script>
    <script>
    // Sidebar/menu/profile dropdown logic (ensure always initialized)
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof handleNavigation === 'function') handleNavigation();
        if (typeof menuToggle !== 'undefined' && typeof sidebar !== 'undefined' && typeof sidebarOverlay !== 'undefined') {
            menuToggle.addEventListener('click', toggleSidebar);
            sidebarOverlay.addEventListener('click', closeSidebar);
        }
        if (typeof profileBtn !== 'undefined' && typeof profileDropdown !== 'undefined') {
            profileBtn.addEventListener('click', toggleProfileDropdown);
            document.addEventListener('click', closeProfileDropdown);
        }
    });
    </script>
    <style>
        /* Profile Page Specific Styles */
        .profile-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }

        .profile-form-card {
            grid-row: span 2;
        }

        .logo-upload-section {
            display: flex;
            align-items: center;
            gap: 24px;
            padding: 24px;
            background: var(--bg-main);
            border-radius: var(--radius);
            margin-bottom: 24px;
        }

        .current-logo img {
            width: 100px;
            height: 100px;
            border-radius: var(--radius);
            object-fit: cover;
            border: 3px solid var(--primary);
        }

        .logo-upload-info h4 {
            margin-bottom: 4px;
        }

        .logo-upload-info p {
            color: var(--text-light);
            font-size: 0.875rem;
            margin-bottom: 12px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .social-links-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
        }

        /* Preview Styles */
        .preview-header {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .preview-logo {
            width: 80px;
            height: 80px;
            border-radius: var(--radius);
            object-fit: cover;
        }

        .preview-info h3 {
            font-size: 1.25rem;
            margin-bottom: 4px;
        }

        .preview-tagline {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .preview-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 0.8rem;
            color: var(--text-light);
        }

        .preview-meta span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .preview-stats {
            display: flex;
            justify-content: space-around;
            padding: 20px 0;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 20px;
        }

        .preview-stat {
            text-align: center;
        }

        .preview-stat .stat-value {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }

        .preview-stat .stat-label {
            font-size: 0.8rem;
            color: var(--text-light);
        }

        .preview-actions {
            display: flex;
            gap: 12px;
        }

        /* Benefits Grid */
        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .benefit-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            background: var(--bg-main);
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
        }

        .benefit-item i {
            color: var(--primary);
            font-size: 1.1rem;
        }

        /* Photos Grid */
        .photos-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .photo-item {
            position: relative;
            border-radius: var(--radius-sm);
            overflow: hidden;
            aspect-ratio: 16/10;
        }

        .photo-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-delete {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: rgba(255, 75, 75, 0.9);
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .photo-item:hover .photo-delete {
            opacity: 1;
        }

        .photo-item.add-photo {
            background: var(--bg-main);
            border: 2px dashed var(--border-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: var(--text-light);
            cursor: pointer;
            transition: all 0.3s;
        }

        .photo-item.add-photo:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .photo-item.add-photo i {
            font-size: 1.5rem;
        }

        @media (max-width: 992px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }

            .profile-form-card {
                grid-row: auto;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .social-links-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>

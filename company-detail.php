<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Get company ID from URL
$company_id = isset($_GET['id']) ? intval($_GET['id']) : 1;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Inc. - CareerHunt</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css\main.css">
    <link rel="stylesheet" href="css/companies.css">
    <link rel="stylesheet" href="css/company-detail.css">
</head>

<body>

    <!-- HEADER -->
    <?php include("header.php") ?>

    <!-- COMPANY HEADER SECTION -->
    <!-- <section class="company-header-section"> -->
      
    

    <!-- COMPANY MAIN CONTENT -->
    <section class="company-main-section">
        <div class="container">
              <div class="company-cover">
            <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=1600&h=400&fit=crop"
                alt="Company Cover">
            <div class="cover-overlay"></div>
        </div>
        <div class="container-fluid px-4">
            <div class="company-header-content">
                <div class="company-info-main">
                    <div class="company-logo-large">
                        <img id="companyLogo" src="" alt="Company Logo">
                    </div>
                    <div class="company-info-text">
                        <div class="company-title-wrapper">
                            <h1 id="companyName"></h1>
                            <span class="verified-badge" id="companyVerified"><i class="bi bi-patch-check-fill"></i> Verified</span>
                        </div>
                        <div class="company-quick-info">
                            <span><i class="bi bi-buildings"></i> <span id="companyIndustry"></span></span>
                            <span><i class="bi bi-geo-alt"></i> <span id="companyLocation"></span></span>
                            <span><i class="bi bi-calendar3"></i> Founded <span id="companyFounded"></span></span>
                            <span><i class="bi bi-people"></i> <span id="companySize"></span></span>
                        </div>
                        <div class="company-tags" id="companyTags"></div>
                    </div>
                </div>
                <div class="company-actions">
                    <a href="#open-positions" class="btn-primary-action">
                        <i class="bi bi-briefcase"></i> View Open Jobs
                        <span class="job-count" id="openJobsCount"></span>
                    </a>
                    <button class="btn-secondary-action">
                        <i class="bi bi-bookmark"></i> Save Company
                    </button>
                    <button class="btn-share">
                        <i class="bi bi-share"></i>
                    </button>
                </div>
            </div>
        </div>
            <div class="row">
                <!-- LEFT CONTENT -->
                <div class="col-lg-8">
                    <!-- About Section -->
                    <div class="content-card">
                        <h3 class="card-title">About Company</h3>
                        <div class="about-content" id="aboutContent"></div>
                    </div>

                    <!-- Gallery Section -->
                    <div class="content-card">
                        <h3 class="card-title">Company Gallery</h3>
                        <div class="gallery-grid" id="companyGallery"></div>
                    </div>

                    <!-- Open Positions Section -->
                    <div class="content-card" id="open-positions">
                        <div class="card-header-flex">
                            <h3 class="card-title">Open Positions <span class="count-badge" id="openJobsCount2"></span></h3>
                            <a href="#" id="viewAllJobsLink" class="view-all-link">View All Jobs <i class="bi bi-arrow-right"></i></a>
                        </div>
                        <div id="jobsList"></div>
                    </div>
                </div>

                <!-- RIGHT SIDEBAR -->
                <div class="col-lg-4">
                    <!-- Company Overview -->
                    <div class="sidebar-card">
                        <h4 class="sidebar-title">Company Overview</h4>
                        <ul class="overview-list">
                            <li>
                                <span class="label"><i class="bi bi-calendar3"></i> Founded</span>
                                <span class="value">September 4, 1998</span>
                            </li>
                            <li>
                                <span class="label"><i class="bi bi-building"></i> Organization Type</span>
                                <span class="value">Public Company</span>
                            </li>
                            <li>
                                <span class="label"><i class="bi bi-people"></i> Team Size</span>
                                <span class="value">150,000+ employees</span>
                            </li>
                            <li>
                                <span class="label"><i class="bi bi-briefcase"></i> Industry</span>
                                <span class="value">Technology</span>
                            </li>
                            <li>
                                <span class="label"><i class="bi bi-cash-stack"></i> Revenue</span>
                                <span class="value">$280+ Billion</span>
                            </li>
                            <li>
                                <span class="label"><i class="bi bi-geo-alt"></i> Headquarters</span>
                                <span class="value">Mountain View, CA</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact Information -->
                    <div class="sidebar-card">
                        <h4 class="sidebar-title">Contact Information</h4>
                        <ul class="contact-list">
                            <li>
                                <a href="https://google.com" target="_blank">
                                    <i class="bi bi-globe"></i> www.google.com
                                </a>
                            </li>
                            <li>
                                <a href="mailto:careers@google.com">
                                    <i class="bi bi-envelope"></i> careers@google.com
                                </a>
                            </li>
                            <li>
                                <a href="tel:+16502530000">
                                    <i class="bi bi-telephone"></i> +1 (650) 253-0000
                                </a>
                            </li>
                        </ul>
                        <div class="social-links-compact">
                            <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="twitter"><i class="bi bi-twitter-x"></i></a>
                            <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                        </div>
                    </div>

                    <!-- Location Map -->
                    <div class="sidebar-card">
                        <h4 class="sidebar-title">Location</h4>
                        <div class="map-placeholder">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3168.639290621062!2d-122.0840897!3d37.4219999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x808fba02425dad8f%3A0x6c296c66619367e0!2sGoogleplex!5e0!3m2!1sen!2sus!4v1635959764512!5m2!1sen!2sus"
                                width="100%" height="200" style="border:0; border-radius: 8px;" allowfullscreen=""
                                loading="lazy">
                            </iframe>
                        </div>
                        <p class="address-text">
                            <i class="bi bi-geo-alt"></i>
                            1600 Amphitheatre Parkway, Mountain View, CA 94043, United States
                        </p>
                    </div>

                    <!-- Similar Companies -->
                    <div class="sidebar-card">
                        <h4 class="sidebar-title">Similar Companies</h4>
                        <div class="similar-companies">
                            <a href="company-detail.php?id=2" class="similar-company-item">
                                <img src="https://ui-avatars.com/api/?name=Microsoft&background=00a4ef&color=fff&size=50&rounded=true"
                                    alt="Microsoft">
                                <div>
                                    <h5>Microsoft</h5>
                                    <span>18 Open Jobs</span>
                                </div>
                            </a>
                            <a href="company-detail.php?id=5" class="similar-company-item">
                                <img src="https://ui-avatars.com/api/?name=Apple&background=333333&color=fff&size=50&rounded=true"
                                    alt="Apple">
                                <div>
                                    <h5>Apple Inc.</h5>
                                    <span>30 Open Jobs</span>
                                </div>
                            </a>
                            <a href="company-detail.php?id=7" class="similar-company-item">
                                <img src="https://ui-avatars.com/api/?name=Meta&background=0668e1&color=fff&size=50&rounded=true"
                                    alt="Meta">
                                <div>
                                    <h5>Meta</h5>
                                    <span>28 Open Jobs</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include("footer.php") ?>

    <!-- Auth Modal -->
    <div class="modal fade" id="authModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <ul class="nav nav-tabs" id="authTabs">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab"
                                data-bs-target="#loginTab">Login</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#registerTab">Register</button>
                        </li>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="loginTab">
                            <form id="loginForm">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                                <div class="form-error" id="loginError"></div>
                                <button type="submit" class="btn btn-primary w-100">Login</button>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="registerTab">
                            <form id="registerForm">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" name="full_name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">I am a</label>
                                    <select class="form-select" name="user_type">
                                        <option value="candidate">Job Seeker</option>
                                        <option value="employer">Employer</option>
                                    </select>
                                </div>
                                <div class="form-error" id="registerError"></div>
                                <button type="submit" class="btn btn-primary w-100">Register</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/auth.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const companyId = urlParams.get('id') || 1;
        fetch(`api/company_public_profile.php?id=${companyId}`)
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    document.body.innerHTML = `<div class='container mt-5'><div class='alert alert-danger'>${data.message || 'Company not found.'}</div></div>`;
                    return;
                }
                const c = data.company;
                // Main header
                document.getElementById('companyLogo').src = c.logo || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(c.company_name || 'Company');
                document.getElementById('companyLogo').alt = c.company_name || 'Company Logo';
                document.getElementById('companyName').textContent = c.company_name || '';
                document.getElementById('companyIndustry').textContent = c.industry || '';
                document.getElementById('companyLocation').textContent = c.location || '';
                document.getElementById('companyFounded').textContent = c.founded || '';
                document.getElementById('companySize').textContent = c.company_size || '';
                // Tags
                const tagsDiv = document.getElementById('companyTags');
                tagsDiv.innerHTML = '';
                if (c.tags && Array.isArray(c.tags)) {
                    c.tags.forEach(tag => {
                        const span = document.createElement('span');
                        span.className = 'tag';
                        span.textContent = tag;
                        tagsDiv.appendChild(span);
                    });
                } else if (c.tags) {
                    c.tags.split(',').forEach(tag => {
                        const span = document.createElement('span');
                        span.className = 'tag';
                        span.textContent = tag.trim();
                        tagsDiv.appendChild(span);
                    });
                }
                // About
                document.getElementById('aboutContent').innerHTML = c.description ? `<p>${c.description.replace(/\n/g, '</p><p>')}</p>` : '<span class="text-muted">No description provided.</span>';
                // Gallery
                const gallery = document.getElementById('companyGallery');
                gallery.innerHTML = '';
                if (c.photos && c.photos.length) {
                    c.photos.forEach(url => {
                        const div = document.createElement('div');
                        div.className = 'gallery-item';
                        div.innerHTML = `<img src="${url}" alt="Photo">`;
                        gallery.appendChild(div);
                    });
                } else {
                    gallery.innerHTML = '<span class="text-muted">No photos uploaded yet.</span>';
                }
                // Open Jobs
                document.getElementById('openJobsCount').textContent = c.jobs ? c.jobs.length : 0;
                document.getElementById('openJobsCount2').textContent = c.jobs ? c.jobs.length : 0;
                const jobsList = document.getElementById('jobsList');
                jobsList.innerHTML = '';
                if (c.jobs && c.jobs.length) {
                    c.jobs.forEach(job => {
                        const div = document.createElement('div');
                        div.className = 'job-listing-card';
                        div.innerHTML = `
                            <div class='job-listing-left'>
                                <h4 class='job-title'>${job.title}</h4>
                                <div class='job-meta'>
                                    <span><i class='bi bi-geo-alt'></i> ${job.location || ''}</span>
                                    <span><i class='bi bi-clock'></i> ${job.type || ''}</span>
                                    <span><i class='bi bi-currency-dollar'></i> ${job.salary || ''}</span>
                                </div>
                                <div class='job-tags'>${(job.tags||[]).map(t => `<span>${t}</span>`).join('')}</div>
                            </div>
                            <div class='job-listing-right'>
                                <span class='posted-time'><i class='bi bi-clock-history'></i> ${job.posted_at ? timeAgo(job.posted_at) : ''}</span>
                                <a href='job-details.php?id=${job.id}' class='apply-btn'>Apply Now</a>
                            </div>
                        `;
                        jobsList.appendChild(div);
                    });
                } else {
                    jobsList.innerHTML = '<span class="text-muted">No open positions.</span>';
                }
                // View all jobs link
                document.getElementById('viewAllJobsLink').href = `jobs.php?company=${encodeURIComponent(c.company_name || '')}`;
            });

        // Helper: time ago
        function timeAgo(dateStr) {
            const date = new Date(dateStr);
            const now = new Date();
            const seconds = Math.floor((now - date) / 1000);
            let interval = Math.floor(seconds / 31536000);
            if (interval >= 1) return interval + ' year' + (interval > 1 ? 's' : '') + ' ago';
            interval = Math.floor(seconds / 2592000);
            if (interval >= 1) return interval + ' month' + (interval > 1 ? 's' : '') + ' ago';
            interval = Math.floor(seconds / 86400);
            if (interval >= 1) return interval + ' day' + (interval > 1 ? 's' : '') + ' ago';
            interval = Math.floor(seconds / 3600);
            if (interval >= 1) return interval + ' hour' + (interval > 1 ? 's' : '') + ' ago';
            interval = Math.floor(seconds / 60);
            if (interval >= 1) return interval + ' minute' + (interval > 1 ? 's' : '') + ' ago';
            return 'just now';
        }
    });
    </script>
</body>

</html>

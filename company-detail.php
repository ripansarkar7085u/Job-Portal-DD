<?php include ("header.php")
?>
    <!-- HEADER -->
    <header class="header">
        <nav class="navbar navbar-expand-lg container">
            <a class="navbar-brand" href="index.php">
                <img src="/photos/job logo.png" alt="CareerHunt">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="jobs.php">Jobs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="companies.php">Companies</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                </ul>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="user/dashboard.php" class="btn login-btn">Dashboard</a>
                <?php else: ?>
                    <a href="login.php" class="btn login-btn me-2">Login</a>
                    <a href="register.php" class="btn login-btn">Register</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <!-- COMPANY HEADER SECTION -->
    <section class="company-header-section">
        <div class="company-cover">
            <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=1600&h=400&fit=crop" alt="Company Cover">
            <div class="cover-overlay"></div>
        </div>
        <div class="container">
            <div class="company-header-content">
                <div class="company-info-main">
                    <div class="company-logo-large">
                        <img src="https://ui-avatars.com/api/?name=Google&background=4285f4&color=fff&size=120&rounded=true" alt="Google">
                    </div>
                    <div class="company-info-text">
                        <div class="company-title-wrapper">
                            <h1>Google Inc.</h1>
                            <span class="verified-badge"><i class="bi bi-patch-check-fill"></i> Verified</span>
                        </div>
                        <div class="company-quick-info">
                            <span><i class="bi bi-buildings"></i> Technology</span>
                            <span><i class="bi bi-geo-alt"></i> Mountain View, CA</span>
                            <span><i class="bi bi-calendar3"></i> Founded 1998</span>
                            <span><i class="bi bi-people"></i> 10,000+ employees</span>
                        </div>
                        <div class="company-tags">
                            <span class="tag">Software Development</span>
                            <span class="tag">AI & Machine Learning</span>
                            <span class="tag">Cloud Computing</span>
                            <span class="tag">Search Engine</span>
                        </div>
                    </div>
                </div>
                <div class="company-actions">
                    <a href="#open-positions" class="btn-primary-action">
                        <i class="bi bi-briefcase"></i> View Open Jobs
                        <span class="job-count">25</span>
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
    </section>

    <!-- COMPANY MAIN CONTENT -->
    <section class="company-main-section">
        <div class="container">
            <div class="row">
                <!-- LEFT CONTENT -->
                <div class="col-lg-8">
                    <!-- About Section -->
                    <div class="content-card">
                        <h3 class="card-title">About Company</h3>
                        <div class="about-content">
                            <p>Google LLC is an American multinational technology company focusing on search engine technology, online advertising, cloud computing, computer software, quantum computing, e-commerce, artificial intelligence, and consumer electronics.</p>
                            <p>It has been referred to as "the most powerful company in the world" and one of the world's most valuable brands due to its market dominance, data collection, and technological advantages in the area of artificial intelligence.</p>
                            <p>Google was founded on September 4, 1998, by Larry Page and Sergey Brin while they were PhD students at Stanford University in California. Together they own about 14% of its publicly listed shares and control 56% of the stockholder voting power through super-voting stock.</p>
                            <h5>Our Mission</h5>
                            <p>To organize the world's information and make it universally accessible and useful.</p>
                            <h5>Our Culture</h5>
                            <p>We strive to create an environment where every employee feels empowered to share their ideas and collaborate across teams. Innovation is at the heart of everything we do, and we believe diverse perspectives lead to better solutions.</p>
                        </div>
                    </div>

                    <!-- Gallery Section -->
                    <div class="content-card">
                        <h3 class="card-title">Company Gallery</h3>
                        <div class="gallery-grid">
                            <div class="gallery-item">
                                <img src="https://images.unsplash.com/photo-1497366811353-6870744d04b2?w=400&h=300&fit=crop" alt="Office">
                            </div>
                            <div class="gallery-item">
                                <img src="https://images.unsplash.com/photo-1497215842964-222b430dc094?w=400&h=300&fit=crop" alt="Workspace">
                            </div>
                            <div class="gallery-item">
                                <img src="https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=400&h=300&fit=crop" alt="Team">
                            </div>
                            <div class="gallery-item">
                                <img src="https://images.unsplash.com/photo-1517502884422-41eaead166d4?w=400&h=300&fit=crop" alt="Meeting">
                            </div>
                        </div>
                    </div>

                    <!-- Open Positions Section -->
                    <div class="content-card" id="open-positions">
                        <div class="card-header-flex">
                            <h3 class="card-title">Open Positions <span class="count-badge">25</span></h3>
                            <a href="jobs.php?company=google" class="view-all-link">View All Jobs <i class="bi bi-arrow-right"></i></a>
                        </div>
                        
                        <!-- Job Listing 1 -->
                        <div class="job-listing-card">
                            <div class="job-listing-left">
                                <h4 class="job-title">Senior Software Engineer</h4>
                                <div class="job-meta">
                                    <span><i class="bi bi-geo-alt"></i> Mountain View, CA</span>
                                    <span><i class="bi bi-clock"></i> Full-time</span>
                                    <span><i class="bi bi-currency-dollar"></i> $180k - $250k</span>
                                </div>
                                <div class="job-tags">
                                    <span>Python</span>
                                    <span>Go</span>
                                    <span>Kubernetes</span>
                                </div>
                            </div>
                            <div class="job-listing-right">
                                <span class="posted-time"><i class="bi bi-clock-history"></i> 2 days ago</span>
                                <a href="job-detail.php" class="apply-btn">Apply Now</a>
                            </div>
                        </div>

                        <!-- Job Listing 2 -->
                        <div class="job-listing-card">
                            <div class="job-listing-left">
                                <h4 class="job-title">Product Manager - Cloud</h4>
                                <div class="job-meta">
                                    <span><i class="bi bi-geo-alt"></i> New York, NY</span>
                                    <span><i class="bi bi-clock"></i> Full-time</span>
                                    <span><i class="bi bi-currency-dollar"></i> $160k - $220k</span>
                                </div>
                                <div class="job-tags">
                                    <span>Product Strategy</span>
                                    <span>Cloud</span>
                                    <span>Agile</span>
                                </div>
                            </div>
                            <div class="job-listing-right">
                                <span class="posted-time"><i class="bi bi-clock-history"></i> 3 days ago</span>
                                <a href="job-detail.php" class="apply-btn">Apply Now</a>
                            </div>
                        </div>

                        <!-- Job Listing 3 -->
                        <div class="job-listing-card">
                            <div class="job-listing-left">
                                <h4 class="job-title">UX Designer</h4>
                                <div class="job-meta">
                                    <span><i class="bi bi-geo-alt"></i> San Francisco, CA</span>
                                    <span><i class="bi bi-clock"></i> Full-time</span>
                                    <span><i class="bi bi-currency-dollar"></i> $130k - $180k</span>
                                </div>
                                <div class="job-tags">
                                    <span>Figma</span>
                                    <span>User Research</span>
                                    <span>Prototyping</span>
                                </div>
                            </div>
                            <div class="job-listing-right">
                                <span class="posted-time"><i class="bi bi-clock-history"></i> 5 days ago</span>
                                <a href="job-detail.php" class="apply-btn">Apply Now</a>
                            </div>
                        </div>

                        <!-- Job Listing 4 -->
                        <div class="job-listing-card">
                            <div class="job-listing-left">
                                <h4 class="job-title">Data Scientist - AI Research</h4>
                                <div class="job-meta">
                                    <span><i class="bi bi-geo-alt"></i> Remote</span>
                                    <span><i class="bi bi-clock"></i> Full-time</span>
                                    <span><i class="bi bi-currency-dollar"></i> $200k - $280k</span>
                                </div>
                                <div class="job-tags">
                                    <span>Machine Learning</span>
                                    <span>TensorFlow</span>
                                    <span>PyTorch</span>
                                </div>
                            </div>
                            <div class="job-listing-right">
                                <span class="posted-time"><i class="bi bi-clock-history"></i> 1 week ago</span>
                                <a href="job-detail.php" class="apply-btn">Apply Now</a>
                            </div>
                        </div>
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
                                width="100%" 
                                height="200" 
                                style="border:0; border-radius: 8px;" 
                                allowfullscreen="" 
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
                                <img src="https://ui-avatars.com/api/?name=Microsoft&background=00a4ef&color=fff&size=50&rounded=true" alt="Microsoft">
                                <div>
                                    <h5>Microsoft</h5>
                                    <span>18 Open Jobs</span>
                                </div>
                            </a>
                            <a href="company-detail.php?id=5" class="similar-company-item">
                                <img src="https://ui-avatars.com/api/?name=Apple&background=333333&color=fff&size=50&rounded=true" alt="Apple">
                                <div>
                                    <h5>Apple Inc.</h5>
                                    <span>30 Open Jobs</span>
                                </div>
                            </a>
                            <a href="company-detail.php?id=7" class="similar-company-item">
                                <img src="https://ui-avatars.com/api/?name=Meta&background=0668e1&color=fff&size=50&rounded=true" alt="Meta">
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

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-widget">
                        <img src="/photos/job logo.png" alt="CareerHunt" class="footer-logo">
                        <p>CareerHunt is a leading job portal connecting talented professionals with top companies worldwide.</p>
                        <div class="social-links">
                            <a href="#"><i class="bi bi-facebook"></i></a>
                            <a href="#"><i class="bi bi-twitter-x"></i></a>
                            <a href="#"><i class="bi bi-linkedin"></i></a>
                            <a href="#"><i class="bi bi-instagram"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="footer-widget">
                        <h5>For Candidates</h5>
                        <ul>
                            <li><a href="#">Browse Jobs</a></li>
                            <li><a href="companies.php">Browse Companies</a></li>
                            <li><a href="#">Candidate Dashboard</a></li>
                            <li><a href="#">Job Alerts</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="footer-widget">
                        <h5>For Employers</h5>
                        <ul>
                            <li><a href="#">Post a Job</a></li>
                            <li><a href="#">Browse Candidates</a></li>
                            <li><a href="#">Employer Dashboard</a></li>
                            <li><a href="#">Pricing</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="footer-widget">
                        <h5>Contact Us</h5>
                        <ul class="contact-info">
                            <li><i class="bi bi-geo-alt"></i> 123 Business Street, NY 10001</li>
                            <li><i class="bi bi-envelope"></i> contact@careerhunt.com</li>
                            <li><i class="bi bi-telephone"></i> +1 (555) 123-4567</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> CareerHunt. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Auth Modal -->
    <div class="modal fade" id="authModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <ul class="nav nav-tabs" id="authTabs">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#loginTab">Login</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#registerTab">Register</button>
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
</body>

</html>

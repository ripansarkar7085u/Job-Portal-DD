    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-widget">
                        <!-- <img src="photos\job_logo.png" alt="CareerHunt" class="footer-logo"> -->
                         <a href="index.php">CareerHunt</a>
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

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>

    <script>
        (async function renderFeaturedJobs() {
            const jobsContainer = document.getElementById('featuredJobsList');
            if (!jobsContainer) {
                return;
            }

            try {
                const response = await fetch('api/featured_jobs.php?limit=6', {
                    method: 'GET',
                    credentials: 'include'
                });

                if (!response.ok) {
                    return;
                }

                const payload = await response.json();
                const featuredJobs = Array.isArray(payload.jobs) ? payload.jobs : [];

                if (featuredJobs.length === 0) {
                    return;
                }

                const escapeHtml = (value) => String(value || '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');

                const employmentTypeLabel = (value) => {
                    const map = {
                        'full-time': 'Full Time',
                        'part-time': 'Part Time',
                        'contract': 'Contract',
                        'freelance': 'Freelance',
                        'internship': 'Internship'
                    };
                    return map[value] || 'Full Time';
                };

                const salaryLabel = (job) => {
                    if (!job.salary_visible || job.salary_min === null || job.salary_max === null) {
                        return 'Salary not disclosed';
                    }

                    const symbolMap = {
                        USD: '$',
                        EUR: 'EUR ',
                        GBP: 'GBP ',
                        CAD: 'CAD ',
                        AUD: 'AUD '
                    };

                    const periodMap = {
                        year: 'Year',
                        month: 'Month',
                        hour: 'Hour'
                    };

                    const symbol = symbolMap[job.currency] || '';
                    const period = periodMap[job.salary_period] || 'Year';
                    return `${symbol}${job.salary_min} - ${symbol}${job.salary_max} / ${period}`;
                };

                const cardsHtml = featuredJobs.map((job) => {
                    const initials = (job.company_name || 'C')
                        .split(' ')
                        .filter(Boolean)
                        .slice(0, 2)
                        .map((part) => part.charAt(0).toUpperCase())
                        .join('') || 'C';

                    return `
                        <div class="col-lg-4 col-md-6">
                            <div class="job-card">
                                <div class="job-top">
                                    <div class="company-logo d-flex align-items-center justify-content-center">${escapeHtml(initials)}</div>
                                    <div>
                                        <h5>${escapeHtml(job.title)}</h5>
                                        <span>${escapeHtml(job.company_name || 'Company')}</span>
                                    </div>
                                </div>
                                <div class="job-info">
                                    <span><i class="bi bi-geo-alt"></i> ${escapeHtml(job.location || 'Not specified')}</span>
                                    <span><i class="bi bi-clock"></i> ${escapeHtml(employmentTypeLabel(job.employment_type))}</span>
                                </div>
                                <div class="job-salary">
                                    ${escapeHtml(salaryLabel(job))}
                                </div>
                                <a href="job.php" class="apply-btn">Apply Now</a>
                            </div>
                        </div>
                    `;
                }).join('');

                jobsContainer.innerHTML = cardsHtml;
            } catch (error) {
                // Keep static cards when API is unavailable.
            }
        })();
    </script>


</body>
</html>
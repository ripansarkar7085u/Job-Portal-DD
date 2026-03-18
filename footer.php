       <footer class="footer">

            <div class="container">

                <div class="row">

                    <div class="col-lg-4">
                        <h4>CareerHunt</h4>
                        <p>
                            Find your dream job with CareerHunt. Search thousands of jobs and connect with top
                            companies.
                        </p>
                    </div>

                    <div class="col-lg-2">
                        <h5>Quick Links</h5>
                        <ul>
                            <li><a href="#home">Home</a></li>
                            <li><a href="#job">Jobs</a></li>
                            <li><a href="#company">Companies</a></li>
                            <li><a href="#news">News</a></li>
                            <li><a href="#about">About</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-3">
                        <h5>Job Categories</h5>
                        <ul>
                            <li><a href="#">Development</a></li>
                            <li><a href="#">Design</a></li>
                            <li><a href="#">Marketing</a></li>
                            <li><a href="#">Finance</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-3">
                        <h5>Contact</h5>
                        <p>Email: support@careerhunt.com</p>
                        <p>Phone: +91 9876543210</p>
                    </div>

                </div>

                <hr>

                <p class="text-center copyright">
                    &copy; <?php echo date('Y'); ?> CareerHunt. All rights reserved.
                </p>

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
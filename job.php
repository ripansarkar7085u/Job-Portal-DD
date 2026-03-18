<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CareerHuntt</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link rel="stylesheet" href="style.css">

    <link rel="stylesheet" href="css\companies.css">



</head>

<body>
<?php include("header.php") ?>
<style>
    .job-card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        border: 1px solid #eee;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .job-info {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .company-logo {
        width: 50px;
        height: 50px;
        background: #0d1b3d;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-weight: bold;
    }

    .job-tags span {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        margin-right: 5px;
    }

    .tag-blue {
        background: #e6f0ff;
        color: #2563eb;
    }

    .tag-green {
        background: #e6f6ec;
        color: #16a34a;
    }

    .tag-yellow {
        background: #fff4e5;
        color: #f59e0b;
    }

    .apply-job-btn {
        min-width: 120px;
    }
</style>

<!-- PAGE HEADER -->

<div class="page-header">
    <h2>Find Jobs</h2>
    <p>Home / Jobs</p>
</div>

        <!-- Search Bar -->
        <div class="job-search-box mb-4 p-3 shadow-sm rounded">
            <div class="row g-3">

        <div class="col-md-4">

            <div class="filter-box">

                <h5>Search by Keywords</h5>

                <input type="text" class="form-control" id="keywordInput" placeholder="Job title, keywords, or company">

                <label>Location</label>

                <input type="text" class="form-control" id="locationInput" placeholder="City or postcode">

                <label>Radius around selected destination</label>

                <input type="range" id="radiusSlider" class="form-range" min="1" max="200" value="100">

                <p><span id="radiusValue">100</span> km</p>

                <label>Category</label>

                <select class="form-control" id="categoryFilter">
                    <option value="">Choose a category</option>
                </select>

                <label>Job Type</label>

                <select class="form-control" id="jobTypeFilter">
                    <option value="">Any Job Type</option>
                    <option value="full-time">Full Time</option>
                    <option value="part-time">Part Time</option>
                    <option value="contract">Contract</option>
                    <option value="freelance">Freelance</option>
                    <option value="internship">Internship</option>
                </select>

            </div>
        </div>


        <!-- JOB LISTINGS -->

        <div class="col-md-8">

            <div class="d-flex justify-content-between mb-3">

                <p>Show <b id="jobsCount">0</b> jobs</p>

                <div>

                    <button class="btn btn-danger" id="clearFiltersBtn">Clear All</button>

                    <select class="btn btn-light" id="sortSelect">
                        <option value="newest">Sort by: Newest</option>
                        <option value="oldest">Sort by: Oldest</option>
                        <option value="title">Sort by: Title</option>
                    </select>

                </div>

            </div>


            <div id="jobsList">
                <div class="job-card">
                    <p class="mb-0 text-muted">Loading jobs...</p>
                </div>
            </div>

        </div>

    </div>

</div>
<?php include("login_register.php") ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>

<script>
    (function initPublicJobsPage() {
        const jobsList = document.getElementById('jobsList');
        const jobsCount = document.getElementById('jobsCount');
        const keywordInput = document.getElementById('keywordInput');
        const locationInput = document.getElementById('locationInput');
        const categoryFilter = document.getElementById('categoryFilter');
        const jobTypeFilter = document.getElementById('jobTypeFilter');
        const sortSelect = document.getElementById('sortSelect');
        const clearFiltersBtn = document.getElementById('clearFiltersBtn');
        const radiusSlider = document.getElementById('radiusSlider');
        const radiusValue = document.getElementById('radiusValue');

        let allJobs = [];

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

        const categoryLabel = (value) => String(value || 'General')
            .split('-')
            .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
            .join(' ');

        const salaryLabel = (job) => {
            if (!job.salary_visible || (job.salary_min === null && job.salary_max === null)) {
                return 'Salary not disclosed';
            }

            const symbolMap = {
                USD: '$',
                EUR: 'EUR ',
                GBP: 'GBP ',
                CAD: 'CAD ',
                AUD: 'AUD '
            };
            const symbol = symbolMap[job.currency] || '';

            if (job.salary_min !== null && job.salary_max !== null) {
                return `${symbol}${job.salary_min} - ${symbol}${job.salary_max}`;
            }
            if (job.salary_min !== null) {
                return `${symbol}${job.salary_min}+`;
            }
            return `Up to ${symbol}${job.salary_max}`;
        };

        const hoursAgoLabel = (createdAt) => {
            const created = new Date(createdAt);
            if (Number.isNaN(created.getTime())) {
                return 'Recently posted';
            }

            const diffMs = Date.now() - created.getTime();
            const diffHours = Math.max(1, Math.floor(diffMs / (1000 * 60 * 60)));
            if (diffHours < 24) {
                return `${diffHours} hours ago`;
            }

            const diffDays = Math.floor(diffHours / 24);
            return `${diffDays} days ago`;
        };

        function buildCategoryOptions(jobs) {
            const categories = [...new Set(jobs.map((job) => job.category).filter(Boolean))].sort();
            const options = ['<option value="">Choose a category</option>'];
            categories.forEach((category) => {
                options.push(`<option value="${escapeHtml(category)}">${escapeHtml(categoryLabel(category))}</option>`);
            });
            categoryFilter.innerHTML = options.join('');
        }

        function filteredJobs() {
            const keyword = keywordInput.value.trim().toLowerCase();
            const location = locationInput.value.trim().toLowerCase();
            const category = categoryFilter.value;
            const type = jobTypeFilter.value;

            let result = allJobs.filter((job) => {
                const haystack = `${job.title} ${job.company_name} ${job.location}`.toLowerCase();
                const keywordMatch = !keyword || haystack.includes(keyword);
                const locationMatch = !location || String(job.location || '').toLowerCase().includes(location);
                const categoryMatch = !category || job.category === category;
                const typeMatch = !type || job.employment_type === type;
                return keywordMatch && locationMatch && categoryMatch && typeMatch;
            });

            if (sortSelect.value === 'oldest') {
                result = result.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
            } else if (sortSelect.value === 'title') {
                result = result.sort((a, b) => String(a.title).localeCompare(String(b.title)));
            } else {
                result = result.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            }

            return result;
        }

        function renderJobs() {
            const list = filteredJobs();
            jobsCount.textContent = String(list.length);

            if (list.length === 0) {
                jobsList.innerHTML = '<div class="job-card"><p class="mb-0 text-muted">No jobs found for selected filters.</p></div>';
                return;
            }

            jobsList.innerHTML = list.map((job) => {
                const initials = (job.company_name || 'C')
                    .split(' ')
                    .filter(Boolean)
                    .slice(0, 2)
                    .map((part) => part.charAt(0).toUpperCase())
                    .join('') || 'C';

                return `
                    <div class="job-card">
                        <div class="job-info">
                            <div class="company-logo">${escapeHtml(initials)}</div>
                            <div>
                                <h5>${escapeHtml(job.title)}</h5>
                                <p class="text-muted mb-1">
                                    <i class="bi bi-building"></i> ${escapeHtml(job.company_name || 'Company')}
                                    &nbsp;&nbsp;
                                    <i class="bi bi-geo-alt"></i> ${escapeHtml(job.location || 'Not specified')}
                                    &nbsp;&nbsp;
                                    <i class="bi bi-clock"></i> ${escapeHtml(hoursAgoLabel(job.created_at))}
                                    &nbsp;&nbsp;
                                    <i class="bi bi-cash"></i> ${escapeHtml(salaryLabel(job))}
                                </p>
                                <div class="job-tags">
                                    <span class="tag-blue">${escapeHtml(employmentTypeLabel(job.employment_type))}</span>
                                    <span class="tag-green">${escapeHtml(categoryLabel(job.category))}</span>
                                </div>
                            </div>
                        </div>
                        <a href="login.php" class="btn btn-primary apply-job-btn">Apply</a>
                    </div>
                `;
            }).join('');
        }

        async function loadJobs() {
            try {
                const response = await fetch('api/featured_jobs.php?limit=200', {
                    method: 'GET'
                });

                const payload = await response.json();
                if (!response.ok || !payload.success) {
                    jobsList.innerHTML = '<div class="job-card"><p class="mb-0 text-danger">Unable to load jobs right now.</p></div>';
                    return;
                }

                allJobs = Array.isArray(payload.jobs) ? payload.jobs : [];
                buildCategoryOptions(allJobs);
                renderJobs();
            } catch (error) {
                jobsList.innerHTML = '<div class="job-card"><p class="mb-0 text-danger">Unable to load jobs right now.</p></div>';
            }
        }

        [keywordInput, locationInput, categoryFilter, jobTypeFilter, sortSelect].forEach((element) => {
            element.addEventListener('input', renderJobs);
            element.addEventListener('change', renderJobs);
        });

        clearFiltersBtn.addEventListener('click', function() {
            keywordInput.value = '';
            locationInput.value = '';
            categoryFilter.value = '';
            jobTypeFilter.value = '';
            sortSelect.value = 'newest';
            radiusSlider.value = 100;
            radiusValue.textContent = '100';
            renderJobs();
        });

        radiusSlider.addEventListener('input', function() {
            radiusValue.textContent = this.value;
        });

        loadJobs();
    })();
</script>

</body>

</html>
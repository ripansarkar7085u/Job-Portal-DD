<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$jobId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
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

    <link rel="stylesheet" href="css\main.css">

    <link rel="stylesheet" href="css\job-details.css">

    <link rel="stylesheet" href="css\companies.css">



</head>

<body>
    <?php include("header.php") ?>
    <button onclick="history.back()" class="back-btn">
        <i class="bi bi-arrow-left"></i>
    </button>

    <div class="container my-5">
        <div id="jobDetailsLoading" class="text-center py-5">Loading job details...</div>
        <div id="jobDetailsCard" style="display:none">
            <div class="job-banner"></div>
            <div class="job-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex gap-3">
                        <img id="companyLogo" src="" class="company-logo">
                        <div>
                            <h3 id="jobTitle"></h3>
                            <p class="text-muted mb-1" id="companyName"></p>
                            <div class="meta">
                                <span><i class="bi bi-geo-alt"></i> <span id="jobLocation"></span></span>
                                <span><i class="bi bi-laptop"></i> <span id="jobType"></span></span>
                                <span><i class="bi bi-briefcase"></i> <span id="jobLevel"></span></span>
                            </div>
                        </div>
                    </div>
                    <span class="badge bg-primary fs-6" id="jobCategory"></span>
                </div>
                <div class="section">
                    <h5><i class="bi bi-cash"></i> Salary</h5>
                    <p class="fw-bold text-success" id="jobSalary"></p>
                </div>
                <div class="section">
                    <h5>Job Description</h5>
                    <p id="jobDescription"></p>
                </div>
                <div class="section">
                    <h5>Requirements</h5>
                    <ul id="jobRequirements"></ul>
                </div>
                <div class="section">
                    <h5>Nice to Have</h5>
                    <ul id="jobNiceToHave"></ul>
                </div>
                <div class="section">
                    <h5>Skills</h5>
                    <span id="jobSkills"></span>
                </div>
                <div class="section">
                    <h5>Benefits</h5>
                    <span id="jobBenefits"></span>
                </div>
            </div>
        </div>
                <span class="tag">Remote Work</span>
                <span class="tag">Paid Time Off</span>
            </div>

            <!-- Extra Info -->
            <div class="section">
                <h5>Additional Info</h5>
                <p><strong>Category:</strong> Engineering</p>
                <p><strong>Positions:</strong> 2</p>
                <p><strong>Timezone:</strong> IST</p>
                <p><strong>Visa:</strong> Not Available</p>
            </div>

            <!-- Apply Box -->
            <div class="apply-box mt-4 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Interested in this job?</h6>
                    <small>Apply now and join our team 🚀</small>
                </div>

                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#applyModal">
                    <i class="bi bi-send"></i> Apply Now
                </button>
            </div>

        </div>
    </div>

    <!-- Apply Modal -->
    <div class="modal fade" id="applyModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Apply for Job</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="applyForm">
                    <div class="modal-body">

                        <input type="hidden" id="applyJobId" value="<?php echo $jobId; ?>">

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" id="applyFullName" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" id="applyEmail" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Upload CV</label>
                            <input type="file" id="applyCv" class="form-control" accept=".pdf,.doc,.docx">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cover Letter</label>
                            <textarea id="applyCoverLetter" class="form-control" rows="3"></textarea>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            Submit Application
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <?php include("footer.php") ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const jobId = parseInt(urlParams.get('id'), 10);
        const loading = document.getElementById('jobDetailsLoading');
        const card = document.getElementById('jobDetailsCard');
        // Validate jobId
        if (!jobId || isNaN(jobId) || jobId <= 0) {
            loading.textContent = 'No job ID provided.';
            card.style.display = 'none';
            return;
        }
        // Set hidden input for apply form
        const applyJobIdInput = document.getElementById('applyJobId');
        if (applyJobIdInput) applyJobIdInput.value = jobId;

        fetch('api/job_details.php?id=' + jobId)
            .then(res => res.json().catch(() => null))
            .then(data => {
                loading.style.display = 'none';
                if (!data || !data.success || !data.job) {
                    card.style.display = 'none';
                    loading.style.display = '';
                    loading.textContent = (data && data.message) || 'Job not found or failed to load.';
                    return;
                }
                card.style.display = '';
                const j = data.job;
                document.getElementById('companyLogo').src = j.company_logo || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(j.company_name || 'Company');
                document.getElementById('jobTitle').textContent = j.title || '';
                document.getElementById('companyName').textContent = j.company_name || '';
                document.getElementById('jobLocation').textContent = j.location || '';
                document.getElementById('jobType').textContent = j.type || '';
                document.getElementById('jobLevel').textContent = j.level || '';
                document.getElementById('jobCategory').textContent = j.category || '';
                document.getElementById('jobSalary').textContent = j.salary || '';
                document.getElementById('jobDescription').textContent = j.description || '';
                // Requirements
                const reqUl = document.getElementById('jobRequirements');
                reqUl.innerHTML = '';
                (j.requirements || []).forEach(r => {
                    if (r.trim()) {
                        const li = document.createElement('li');
                        li.textContent = r;
                        reqUl.appendChild(li);
                    }
                });
                // Nice to have
                const niceUl = document.getElementById('jobNiceToHave');
                niceUl.innerHTML = '';
                (j.nice_to_have || []).forEach(r => {
                    if (r.trim()) {
                        const li = document.createElement('li');
                        li.textContent = r;
                        niceUl.appendChild(li);
                    }
                });
                // Skills
                const skillsSpan = document.getElementById('jobSkills');
                skillsSpan.innerHTML = '';
                (j.tags || []).forEach(tag => {
                    if (tag.trim()) {
                        const span = document.createElement('span');
                        span.className = 'tag';
                        span.textContent = tag;
                        skillsSpan.appendChild(span);
                    }
                });
                // Benefits
                const benefitsSpan = document.getElementById('jobBenefits');
                benefitsSpan.innerHTML = '';
                (j.benefits || []).forEach(b => {
                    if (b.trim()) {
                        const span = document.createElement('span');
                        span.className = 'tag';
                        span.textContent = b;
                        benefitsSpan.appendChild(span);
                    }
                });
            })
            .catch(() => {
                loading.style.display = 'none';
                card.style.display = 'none';
                loading.textContent = 'Failed to load job details. Please try again later.';
            });

        // Apply form submission
        const applyForm = document.getElementById('applyForm');
        if (applyForm) {
            applyForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                // Always use jobId from URL
                if (!jobId || isNaN(jobId) || jobId <= 0) {
                    alert('No job ID provided.');
                    return;
                }
                const fullName = document.getElementById('applyFullName').value.trim();
                const email = document.getElementById('applyEmail').value.trim();
                const coverLetter = document.getElementById('applyCoverLetter').value.trim();
                const formData = new FormData();
                formData.append('job_id', jobId);
                formData.append('full_name', fullName);
                formData.append('email', email);
                formData.append('cover_letter', coverLetter);
                
                const cvInput = document.getElementById('applyCv');
                if (cvInput && cvInput.files[0]) {
                    formData.append('resume', cvInput.files[0]);
                }

                try {
                    const response = await fetch('api/user_apply_job.php', {
                        method: 'POST',
                        credentials: 'include',
                        body: formData
                    });
                    const data = await response.json();
                    if (!response.ok || !data.success) {
                        if (response.status === 401) {
                            window.location.href = 'login.php';
                            return;
                        }
                        alert(data.message || 'Unable to submit application.');
                        return;
                    }
                    alert('Application submitted successfully!');
                    window.location.href = 'user/applied.php';
                } catch (error) {
                    alert('Unable to connect to server. Please try again.');
                }
            });
        }
    });
    </script>
            
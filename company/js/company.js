
document.addEventListener('DOMContentLoaded', function() {
    
    fetchBenefits();
    const addBenefitForm = document.getElementById('addBenefitForm');
    if (addBenefitForm) {
        addBenefitForm.onsubmit = async function(e) {
            e.preventDefault();
            const input = document.getElementById('newBenefitInput');
            const benefit = input.value.trim();
            if (!benefit) return;
            const res = await fetch('../api/company_benefits.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'include',
                body: JSON.stringify({ benefit })
            });
            const data = await res.json();
            if (data.success) {
                input.value = '';
                fetchBenefits();
            } else {
                showToast(data.message || 'Failed to add benefit', 'error');
            }
        };
    }

    
    fetchPhotos();
    const addPhotoForm = document.getElementById('addPhotoForm');
    if (addPhotoForm) {
        addPhotoForm.onsubmit = async function(e) {
            e.preventDefault();
            const input = document.getElementById('newPhotoFile');
            if (input) {
                const file = input.files[0];
                if (!file) return;

                const formData = new FormData();
                formData.append('photo', file);

                const btn = addPhotoForm.querySelector('button[type="submit"]');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-arrow-clockwise fa-spin"></i> Uploading...';
                btn.disabled = true;

                try {
                    const res = await fetch('../api/company_photos.php', {
                        method: 'POST',
                        credentials: 'include',
                        body: formData
                    });
                    const data = await res.json();
                    if (data.success) {
                        input.value = '';
                        fetchPhotos();
                        showToast('Photo uploaded successfully', 'success');
                    } else {
                        showToast(data.message || 'Failed to add photo', 'error');
                    }
                } catch (err) {
                    showToast('Failed to connect to server', 'error');
                } finally {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            } else {
                 // Fallback for old URL input
                 const urlInput = document.getElementById('newPhotoUrl');
                 if(urlInput) {
                    const url = urlInput.value.trim();
                    if (!url) return;
                    const res = await fetch('../api/company_photos.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        credentials: 'include',
                        body: JSON.stringify({ url })
                    });
                    const data = await res.json();
                    if (data.success) {
                        urlInput.value = '';
                        fetchPhotos();
                    } else {
                        showToast(data.message || 'Failed to add photo', 'error');
                    }
                 }
            }
        };
    }
});

// Fetch and render company benefits
async function fetchBenefits() {
    const grid = document.getElementById('benefitsGrid');
    if (!grid) return;
    grid.innerHTML = '<span>Loading...</span>';
    try {
        const res = await fetch('../api/company_benefits.php', { credentials: 'include' });
        const data = await res.json();
        if (data.success && Array.isArray(data.benefits)) {
            if (data.benefits.length === 0) {
                grid.innerHTML = '<span class="text-muted">No benefits added yet.</span>';
            } else {
                grid.innerHTML = '';
                data.benefits.forEach(b => {
                    const div = document.createElement('div');
                    div.className = 'benefit-item';
                    div.innerHTML = `<span>${b.benefit}</span> <button class="btn btn-sm btn-link text-danger" title="Delete" onclick="deleteBenefit(${b.id})"><i class='bi bi-x'></i></button>`;
                    grid.appendChild(div);
                });
            }
        } else {
            grid.innerHTML = '<span class="text-danger">Failed to load benefits.</span>';
        }
    } catch {
        grid.innerHTML = '<span class="text-danger">Failed to load benefits.</span>';
    }
}

async function deleteBenefit(id) {
    if (!confirm('Delete this benefit?')) return;
    const res = await fetch('../api/company_benefits.php', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({ id })
    });
    const data = await res.json();
    if (data.success) {
        fetchBenefits();
    } else {
        showToast(data.message || 'Failed to delete benefit', 'error');
    }
}

// Fetch and render company photos
async function fetchPhotos() {
    const grid = document.getElementById('photosGrid');
    if (!grid) return;
    grid.innerHTML = '<span>Loading...</span>';
    try {
        const res = await fetch('../api/company_photos.php', { credentials: 'include' });
        const data = await res.json();
        if (data.success && Array.isArray(data.photos)) {
            if (data.photos.length === 0) {
                grid.innerHTML = '<span class="text-muted">No photos uploaded yet.</span>';
            } else {
                grid.innerHTML = '';
                data.photos.forEach(p => {
                    const div = document.createElement('div');
                    div.className = 'photo-item';
                    div.innerHTML = `<img src="${p.url}" alt="Photo"><button class="photo-delete" title="Delete" onclick="deletePhoto(${p.id})"><i class='bi bi-x'></i></button>`;
                    grid.appendChild(div);
                });
            }
        } else {
            grid.innerHTML = '<span class="text-danger">Failed to load photos.</span>';
        }
    } catch {
        grid.innerHTML = '<span class="text-danger">Failed to load photos.</span>';
    }
}

async function deletePhoto(id) {
    if (!confirm('Delete this photo?')) return;
    const res = await fetch('../api/company_photos.php', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({ id })
    });
    const data = await res.json();
    if (data.success) {
        fetchPhotos();
    } else {
        showToast(data.message || 'Failed to delete photo', 'error');
    }
}


const sidebar = document.getElementById('sidebar');
const menuToggle = document.getElementById('menuToggle');
const sidebarOverlay = document.getElementById('sidebarOverlay');
const logoutBtn = document.getElementById('logoutBtn');
const dropdownLogout = document.getElementById('dropdownLogout');
const profileBtn = document.getElementById('profileBtn');
const profileDropdown = document.getElementById('profileDropdown');


let companyData = {};
let dashboardStats = {};







async function fetchCompanyProfile() {
    try {
        const cacheBuster = new Date().getTime();
        const res = await fetch(`../api/company_public_profile.php?id=me&_t=${cacheBuster}`, { credentials: 'include' });
        const data = await res.json();
        if (data.success && data.company) {
            companyData = data.company;
            updateCompanyInfo(companyData);
        } else {
            showToast('Failed to load company profile', 'error');
        }
    } catch (error) {
        showToast('Error loading company profile', 'error');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    fetchCompanyProfile();
    
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


 
 
function updateCompanyInfo(company) {

    companyData = { ...companyData, ...company };

    let logoUrl = companyData.logo;
    if (logoUrl && typeof logoUrl === 'string' && !logoUrl.startsWith('http') && !logoUrl.startsWith('../')) {
        logoUrl = '../' + logoUrl;
    }
    const fallbackLogo = `https://ui-avatars.com/api/?name=${encodeURIComponent(companyData.company_name || companyData.name || 'Company')}&background=0d47a1&color=fff`;
    const finalLogo = logoUrl ? logoUrl : fallbackLogo;

    // Sidebar
    const companyNameDisplay = document.getElementById('companyNameDisplay');
    const companyAvatar = document.getElementById('companyAvatar');
    if (companyNameDisplay) companyNameDisplay.textContent = companyData.company_name || companyData.name || '';
    if (companyAvatar) companyAvatar.src = finalLogo;

    // Header
    const headerCompanyName = document.getElementById('headerCompanyName');
    const headerAvatar = document.getElementById('headerAvatar');
    if (headerCompanyName) headerCompanyName.textContent = companyData.company_name || companyData.name || '';
    if (headerAvatar) headerAvatar.src = finalLogo;

    // Profile Form
    if (document.getElementById('companyName')) document.getElementById('companyName').value = companyData.company_name || '';
    if (document.getElementById('industry')) document.getElementById('industry').value = companyData.industry || '';
    if (document.getElementById('companySize')) document.getElementById('companySize').value = companyData.company_size || '';
    if (document.getElementById('founded')) document.getElementById('founded').value = companyData.founded || '';
    if (document.getElementById('tagline')) document.getElementById('tagline').value = companyData.tagline || '';
    if (document.getElementById('description')) document.getElementById('description').value = companyData.description || '';
    if (document.getElementById('website')) document.getElementById('website').value = companyData.website || '';
    if (document.getElementById('email')) document.getElementById('email').value = companyData.email || '';
    if (document.getElementById('phone')) document.getElementById('phone').value = companyData.phone || '';
    if (document.getElementById('location')) document.getElementById('location').value = companyData.location || '';
    if (document.querySelector('input[placeholder="LinkedIn URL"]')) document.querySelector('input[placeholder="LinkedIn URL"]').value = companyData.linkedin || '';
    if (document.querySelector('input[placeholder="Twitter/X URL"]')) document.querySelector('input[placeholder="Twitter/X URL"]').value = companyData.twitter || '';
    if (document.querySelector('input[placeholder="Facebook URL"]')) document.querySelector('input[placeholder="Facebook URL"]').value = companyData.facebook || '';
    if (document.querySelector('input[placeholder="Instagram URL"]')) document.querySelector('input[placeholder="Instagram URL"]').value = companyData.instagram || '';
    if (document.getElementById('logoPreview')) document.getElementById('logoPreview').src = finalLogo;

    // Profile Preview Card
    if (document.getElementById('previewLogo')) document.getElementById('previewLogo').src = finalLogo;
    if (document.getElementById('previewCompanyName')) document.getElementById('previewCompanyName').textContent = companyData.company_name || '';
    if (document.getElementById('previewTagline')) document.getElementById('previewTagline').textContent = companyData.tagline || '';
    if (document.getElementById('previewLocation')) document.getElementById('previewLocation').textContent = companyData.location || '';
    if (document.getElementById('previewIndustry')) document.getElementById('previewIndustry').textContent = companyData.industry || '';
    if (document.getElementById('previewSize')) document.getElementById('previewSize').textContent = companyData.company_size || '';
}


 // Logout function
 
async function logout(event) {
    if (event) {
        event.preventDefault();
    }
    try {
        await fetch('../api/logout.php');
    } catch (e) {
        console.error('Logout error:', e);
    } finally {
        window.location.href = '../index.php';
    }
}



 
function toggleSidebar() {
    sidebar.classList.toggle('show');
    sidebarOverlay.classList.toggle('show');
    document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
}



 
function closeSidebar() {
    sidebar.classList.remove('show');
    sidebarOverlay.classList.remove('show');
    document.body.style.overflow = '';
}

function handleNavigation() {
    const navItems = document.querySelectorAll('.nav-item');
    const currentPage = window.location.pathname.split('/').pop() || 'index.php';
    
    navItems.forEach(item => {
        const page = item.getAttribute('data-page');
        
        if (page === currentPage) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
        
        // Add click handler for navigation
        item.addEventListener('click', function() {
            const targetPage = this.getAttribute('data-page');
            if (targetPage) {
                window.location.href = targetPage;
            }
        });
    });
}



function toggleProfileDropdown() {
    profileDropdown.classList.toggle('show');
}


function closeProfileDropdown(event) {
    if (profileDropdown && !profileBtn.contains(event.target) && !profileDropdown.contains(event.target)) {
        profileDropdown.classList.remove('show');
    }
}

function updateDashboardStats() {
    const totalJobsEl = document.getElementById('totalJobs');
    const activeJobsEl = document.getElementById('activeJobs');
    const totalApplicationsEl = document.getElementById('totalApplications');
    const profileViewsEl = document.getElementById('profileViews');
    const notificationCountEl = document.getElementById('notificationCount');
    
    if (totalJobsEl) totalJobsEl.textContent = dashboardStats.totalJobs;
    if (activeJobsEl) activeJobsEl.textContent = dashboardStats.activeJobs;
    if (totalApplicationsEl) totalApplicationsEl.textContent = dashboardStats.totalApplications;
    if (profileViewsEl) profileViewsEl.textContent = dashboardStats.profileViews.toLocaleString();
    if (notificationCountEl) notificationCountEl.textContent = dashboardStats.newApplicationsToday;
}


async function fetchDashboardData() {
    try {
        const response = await fetch('../api/company_dashboard.php', {
            method: 'GET',
            credentials: 'include'
        });
        
        const data = await response.json();
        
        if (data.success) {
            dashboardStats = { ...dashboardStats, ...data.stats };
            updateDashboardStats();
        }
    } catch (error) {
        console.error('Failed to fetch dashboard data:', error);
        dashboardStats = { ...dashboardStats, ...sampleDashboardStats };
        updateDashboardStats();
    }
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
}


function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}


function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <i class="bi bi-${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info-circle'}"></i>
        <span>${message}</span>
    `;
    
    
    document.body.appendChild(toast);
    
    
    setTimeout(() => toast.classList.add('show'), 10);
    
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}


function confirmAction(message) {
    return new Promise((resolve) => {
        const confirmed = confirm(message);
        resolve(confirmed);
    });
}



  //Handle global search
 
function handleSearch() {
    const searchInput = document.getElementById('globalSearch');
    
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query) {
                    
                    console.log('Searching for:', query);
                    
                }
            }
        });
    }
}



 //View application details
 
function viewApplication(applicationId) {
    window.location.href = `applications.html?view=${applicationId}`;
}

async function acceptApplication(applicationId) {
    const confirmed = await confirmAction('Are you sure you want to accept this application?');
    
    if (confirmed) {
        try {
            const response = await fetch('../api/company_application_action.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'include',
                body: JSON.stringify({ applicationId, action: 'accept' })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showToast('Application accepted successfully!', 'success');
                location.reload();
            } else {
                showToast(data.message || 'Failed to accept application', 'error');
            }
        } catch (error) {
            console.error('Accept application failed:', error);
            showToast('An error occurred', 'error');
        }
    }
}

/**
 * Reject application
 */
async function rejectApplication(applicationId) {
    const confirmed = await confirmAction('Are you sure you want to reject this application?');
    
    if (confirmed) {
        try {
            const response = await fetch('../api/company_application_action.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'include',
                body: JSON.stringify({ applicationId, action: 'reject' })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showToast('Application rejected', 'success');
                location.reload();
            } else {
                showToast(data.message || 'Failed to reject application', 'error');
            }
        } catch (error) {
            console.error('Reject application failed:', error);
            showToast('An error occurred', 'error');
        }
    }
}

// ===================================
// Job Actions
// ===================================

/**
 * Edit job
 */
function editJob(jobId) {
    window.location.href = `job-create.html?edit=${jobId}`;
}

// Pagination logic is handled independently by the pages.

/**
 * Delete job
 */
async function deleteJob(jobId) {
    const confirmed = await confirmAction('Are you sure you want to delete this job posting? This action cannot be undone.');
    
    if (confirmed) {
        try {
            const response = await fetch('../api/company_delete_job.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'include',
                body: JSON.stringify({ jobId })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showToast('Job deleted successfully!', 'success');
                location.reload();
            } else {
                showToast(data.message || 'Failed to delete job', 'error');
            }
        } catch (error) {
            console.error('Delete job failed:', error);
            showToast('An error occurred', 'error');
        }
    }
}

/**
 * Toggle job status (active/closed)
 */
async function toggleJobStatus(jobId, currentStatus) {
    const newStatus = currentStatus === 'active' ? 'closed' : 'active';
    const message = newStatus === 'active' ? 'reactivate' : 'close';
    
    const confirmed = await confirmAction(`Are you sure you want to ${message} this job posting?`);
    
    if (confirmed) {
        try {
            const response = await fetch('../api/company_toggle_job.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'include',
                body: JSON.stringify({ jobId, status: newStatus })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showToast(`Job ${newStatus === 'active' ? 'reactivated' : 'closed'} successfully!`, 'success');
                location.reload();
            } else {
                showToast(data.message || 'Failed to update job status', 'error');
            }
        } catch (error) {
            console.error('Toggle job status failed:', error);
            showToast('An error occurred', 'error');
        }
    }
}

// ===================================
// Event Listeners
// ===================================

document.addEventListener('DOMContentLoaded', function() {
    // Check session
    // checkSession();
    
    // Handle navigation
    handleNavigation();
    
    // Setup search
    handleSearch();
    
    // Update dashboard stats if on dashboard page
    if (window.location.pathname.includes('index.php') || window.location.pathname.endsWith('/company/')) {
        fetchDashboardData();
    }
    
    // Sidebar toggle
    if (menuToggle) {
        menuToggle.addEventListener('click', toggleSidebar);
    }
    
    // Sidebar overlay click
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar);
    }
    
    // Logout buttons
    if (logoutBtn) {
        logoutBtn.addEventListener('click', logout);
    }
    
    if (dropdownLogout) {
        dropdownLogout.addEventListener('click', logout);
    }
    
    // Profile dropdown toggle
    if (profileBtn) {
        profileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleProfileDropdown();
        });
    }
    
    // Close dropdown on outside click
    document.addEventListener('click', closeProfileDropdown);
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 992) {
            closeSidebar();
        }
    });
    
    // Setup action button handlers for applications table
    document.querySelectorAll('.action-btn.view').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            const applicationId = row.dataset.applicationId || '1';
            viewApplication(applicationId);
        });
    });
    
    document.querySelectorAll('.action-btn.accept').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            const applicationId = row.dataset.applicationId || '1';
            acceptApplication(applicationId);
        });
    });
    
    document.querySelectorAll('.action-btn.reject').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            const applicationId = row.dataset.applicationId || '1';
            rejectApplication(applicationId);
        });
    });
});

// Add toast styles dynamically
const toastStyles = document.createElement('style');
toastStyles.textContent = `
    .toast {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 16px 24px;
        border-radius: 8px;
        background: #333;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 9999;
    }
    
    .toast.show {
        transform: translateY(0);
        opacity: 1;
    }
    
    .toast-success {
        background: #22c55e;
    }
    
    .toast-error {
        background: #ff4b4b;
    }
    
    .toast-info {
        background: #0d47a1;
    }
    
    .toast-warning {
        background: #f59e0b;
    }
`;
document.head.appendChild(toastStyles);

// Export functions for use in other pages
window.companyDashboard = {
    showToast,
    confirmAction,
    formatDate,
    formatNumber,
    logout,
    viewApplication,
    acceptApplication,
    rejectApplication,
    editJob,
    deleteJob,
    toggleJobStatus
};

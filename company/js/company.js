

// DOM Elements
const sidebar = document.getElementById('sidebar');
const menuToggle = document.getElementById('menuToggle');
const sidebarOverlay = document.getElementById('sidebarOverlay');
const logoutBtn = document.getElementById('logoutBtn');
const dropdownLogout = document.getElementById('dropdownLogout');
const profileBtn = document.getElementById('profileBtn');
const profileDropdown = document.getElementById('profileDropdown');

// Sample Company Data (will be replaced with API data)
let companyData = {
    id: 1,
    name: 'TechCorp Inc.',
    email: 'hr@techcorp.com',
    logo: 'https://ui-avatars.com/api/?name=TechCorp&background=0d47a1&color=fff',
    industry: 'Technology',
    size: '100-500 employees',
    location: 'San Francisco, CA'
};

// Dashboard Stats (sample data)
let dashboardStats = {
    totalJobs: 24,
    activeJobs: 18,
    totalApplications: 156,
    profileViews: 1248,
    newApplicationsToday: 5
};





 // Check company session on page load
 
async function checkSession() {
    try {
        const response = await fetch('../api/company_check_session.php', {
            method: 'GET',
            credentials: 'include'
        });
        
        const data = await response.json();
        
        if (data.success && data.loggedIn) {
            
            updateCompanyInfo(data.company);
        } else {
           
            // For demo purposes, we'll just log this
            console.log('Session not found, using demo data');
            updateCompanyInfo(companyData);
        }
    } catch (error) {
        console.error('Session check failed:', error);
        // Use demo data for development
        updateCompanyInfo(companyData);
    }
}


 // Update company information in UI
 
function updateCompanyInfo(company) {
    companyData = { ...companyData, ...company };
    
    // Update sidebar
    const companyNameDisplay = document.getElementById('companyNameDisplay');
    const companyAvatar = document.getElementById('companyAvatar');
    
    if (companyNameDisplay) {
        companyNameDisplay.textContent = companyData.name;
    }
    
    if (companyAvatar) {
        companyAvatar.src = companyData.logo || `https://ui-avatars.com/api/?name=${encodeURIComponent(companyData.name)}&background=0d47a1&color=fff`;
    }
    
    // Update header
    const headerCompanyName = document.getElementById('headerCompanyName');
    const headerAvatar = document.getElementById('headerAvatar');
    
    if (headerCompanyName) {
        headerCompanyName.textContent = companyData.name;
    }
    
    if (headerAvatar) {
        headerAvatar.src = companyData.logo || `https://ui-avatars.com/api/?name=${encodeURIComponent(companyData.name)}&background=0d47a1&color=fff`;
    }
}


 // Logout function
 
async function logout(event) {
    if (event) {
        event.preventDefault();
    }
    window.location.replace('../api/logout.php');
}


// Toggle sidebar on mobile
 
function toggleSidebar() {
    sidebar.classList.toggle('show');
    sidebarOverlay.classList.toggle('show');
    document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
}


 // Close sidebar
 
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

// ===================================
// Profile Dropdown
// ===================================

/**
 * Toggle profile dropdown
 */
function toggleProfileDropdown() {
    profileDropdown.classList.toggle('show');
}

/**
 * Close profile dropdown when clicking outside
 */
function closeProfileDropdown(event) {
    if (profileDropdown && !profileBtn.contains(event.target) && !profileDropdown.contains(event.target)) {
        profileDropdown.classList.remove('show');
    }
}

// ===================================
// Dashboard Functions
// ===================================

/**
 * Update dashboard statistics
 */
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

/**
 * Fetch dashboard data from API
 */
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

// ===================================
// Utility Functions
// ===================================

/**
 * Format date to readable string
 */
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
}

/**
 * Format number with commas
 */
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

/**
 * Show notification toast
 */
function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <i class="bi bi-${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info-circle'}"></i>
        <span>${message}</span>
    `;
    
    // Add to document
    document.body.appendChild(toast);
    
    // Trigger animation
    setTimeout(() => toast.classList.add('show'), 10);
    
    // Remove after delay
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

/**
 * Confirm action dialog
 */
function confirmAction(message) {
    return new Promise((resolve) => {
        const confirmed = confirm(message);
        resolve(confirmed);
    });
}

// ===================================
// Search Functionality
// ===================================

/**
 * Handle global search
 */
function handleSearch() {
    const searchInput = document.getElementById('globalSearch');
    
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query) {
                    // Redirect to search results or filter current page
                    console.log('Searching for:', query);
                    // Implement search logic based on current page
                }
            }
        });
    }
}

// ===================================
// Application Actions
// ===================================

/**
 * View application details
 */
function viewApplication(applicationId) {
    window.location.href = `applications.html?view=${applicationId}`;
}

/**
 * Accept application
 */
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
    checkSession();
    
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
    checkSession,
    viewApplication,
    acceptApplication,
    rejectApplication,
    editJob,
    deleteJob,
    toggleJobStatus
};

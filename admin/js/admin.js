// Create Company Logic

document.addEventListener('DOMContentLoaded', function() {
    const createCompanyBtn = document.getElementById('createCompanyBtn');
    const createCompanyModal = document.getElementById('createCompanyModal');
    const closeCreateCompanyModal = document.getElementById('closeCreateCompanyModal');
    const cancelCreateCompany = document.getElementById('cancelCreateCompany');
    const createCompanyForm = document.getElementById('createCompanyForm');
    const createCompanyError = document.getElementById('createCompanyError');

    if (createCompanyBtn && createCompanyModal) {
        createCompanyBtn.onclick = () => { createCompanyModal.style.display = 'flex'; };
    }
    }
    if (cancelCreateCompany) {
        cancelCreateCompany.onclick = () => { createCompanyModal.style.display = 'none'; };
    }
    if (createCompanyForm) {
        createCompanyForm.onsubmit = async function(e) {
            e.preventDefault();
            createCompanyError.textContent = '';
            const company_name = document.getElementById('companyName').value.trim();
            const company_username = document.getElementById('companyUsername').value.trim();
            const company_name = document.getElementById('companyName').value.trim();
            const company_email = document.getElementById('companyEmail').value.trim();
            const company_password = document.getElementById('companyPassword').value;
            if (!company_username || !company_name || !company_email || !company_password) {
                createCompanyError.textContent = 'All fields are required.';
                return;
            }
            try {
                const res = await fetch('../api/admin_create_company.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ company_username, company_name, company_email, company_password })
                });
                const data = await res.json();
                if (data.success) {
                    createCompanyModal.style.display = 'none';
                    showToast('Company created successfully', 'success');
                    fetchCompanies().then(renderCompaniesTable).then(renderRecentCompanies).then(updateDashboardStats);
                } else {
                    createCompanyError.textContent = data.message || 'Failed to create company.';
                }
            } catch (err) {
                createCompanyError.textContent = 'Error: ' + err.message;
            }
    }
});



// Admin Login Handler
async function handleAdminLogin(e) {
    e.preventDefault();

    const loginBtn = document.getElementById('loginBtn');
    const errorDiv = document.getElementById('loginError');
    const username = document.getElementById('adminUsername').value.trim();
    const password = document.getElementById('adminPassword').value;


        // Navigation and section logic
        window.navItems = Array.from(document.querySelectorAll('.sidebar-nav .nav-item'));
        window.contentSections = Array.from(document.querySelectorAll('.content-section'));
        window.sidebar = document.querySelector('.sidebar');
        window.menuToggle = document.getElementById('menuToggle');
        window.pageTitle = document.querySelector('.page-title');
        window.itemsPerPage = 10;
        window.currentUserPage = 1;
        window.currentCompanyPage = 1;
        window.currentJobPage = 1;

        initializeEventListeners();
        fetchUsers().then(renderUsersTable).then(renderRecentUsers).then(updateDashboardStats);
        fetchCompanies().then(renderCompaniesTable).then(renderRecentCompanies).then(updateDashboardStats);
        fetchJobs().then(renderJobsTable).then(updateDashboardStats);
        switchSection('dashboard');
    // Clear previous errors
    errorDiv.textContent = '';

    // Validation
    if (!username || !password) {
        errorDiv.textContent = 'Please fill in all fields';
        return;
    }

    // Set loading state
    loginBtn.classList.add('loading');
    loginBtn.disabled = true;

    try {
        const response = await fetch('../api/admin_login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
                credentials: 'include',
            body: JSON.stringify({ username, password })
        });

        const data = await response.json();

        if (data.success) {
            // Redirect to admin folder index after successful login
            window.location.href = "index.php";
        } else {
            errorDiv.textContent = data.message || 'Login failed';
        }
    } catch (error) {
        console.error('Login error:', error);
        errorDiv.textContent = 'Connection error. Please try again.';
    } finally {
        loginBtn.classList.remove('loading');
        loginBtn.disabled = false;
    }
}

// Admin Logout Handler
            credentials: 'include' 
async function handleAdminLogout() {
    try {
        await fetch('../api/admin_logout.php');
        currentAdmin = null;
        showLogin();
        showToast('Logged out successfully', 'success');
    } catch (error) {
        console.error('Logout error:', error);
    }
}

// Toggle password visibility
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('adminPassword');
    const toggleBtn = document.getElementById('togglePassword');
    const icon = toggleBtn.querySelector('i');    
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('bi-eye-fill');
        icon.classList.add('bi-eye-slash-fill');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('bi-eye-slash-fill');
        icon.classList.add('bi-eye-fill');
    }
}


// State Management
let users = [];
let companies = [];
let jobs = [];

// Fetch users from backend
async function fetchUsers() {
    try {
        const res = await fetch('../api/admin_get_users.php');
        const data = await res.json();
        if (data.success && Array.isArray(data.users)) {
            users = data.users;
        } else {
            users = [];
        }
    } catch (err) {
        users = [];
    }
}

// Fetch companies from backend
async function fetchCompanies() {
    try {
        const res = await fetch('../api/admin_get_companies.php');
        const data = await res.json();
        if (data.success && Array.isArray(data.companies)) {
            companies = data.companies;
        } else {
            companies = [];
        }
    } catch (err) {
        companies = [];
    }
}

// Fetch jobs from backend
async function fetchJobs() {
    try {
        const res = await fetch('../api/admin_get_jobs.php');
        const data = await res.json();
        if (data.success && Array.isArray(data.jobs)) {
            jobs = data.jobs;
        } else {
            jobs = [];
        }
    } catch (err) {
        jobs = [];
    }
}

// Login Event Listeners
function initializeLoginListeners() {
    const loginForm = document.getElementById('adminLoginForm');
    const togglePassword = document.getElementById('togglePassword');
    const logoutBtn = document.getElementById('adminLogoutBtn');
    
    if (loginForm) {
        loginForm.addEventListener('submit', handleAdminLogin);
    }
    
    if (togglePassword) {
        togglePassword.addEventListener('click', togglePasswordVisibility);
    }
    
    if (logoutBtn) {
        logoutBtn.addEventListener('click', handleAdminLogout);
    }
}

// Login Event Listeners
function initializeLoginListeners() {
    const loginForm = document.getElementById('adminLoginForm');
    const togglePassword = document.getElementById('togglePassword');
    const logoutBtn = document.getElementById('adminLogoutBtn');
    
    if (loginForm) {
        loginForm.addEventListener('submit', handleAdminLogin);
    }
    
    if (togglePassword) {
        togglePassword.addEventListener('click', togglePasswordVisibility);
    }
    
    if (logoutBtn) {
        logoutBtn.addEventListener('click', handleAdminLogout);
    }
}

// Event Listeners

function initializeEventListeners() {
    // Menu toggle 
    menuToggle?.addEventListener('click', () => {
        sidebar.classList.toggle('open');
    });

   
    navItems.forEach(item => {
        item.addEventListener('click', () => {
            const section = item.dataset.section;
            switchSection(section);
        });
    });

    // View All links
    document.querySelectorAll('.view-all').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const target = link.dataset.target;
            switchSection(target);
        });
    });

    // Modal events
    modalCancel?.addEventListener('click', closeModal);
    modalClose?.addEventListener('click', closeModal);
    modalOverlay?.addEventListener('click', closeModal);
    modalConfirm?.addEventListener('click', executePendingAction);

    // User filters and search
    document.getElementById('userFilter')?.addEventListener('change', renderUsersTable);
    document.getElementById('userSearch')?.addEventListener('input', renderUsersTable);
    document.getElementById('userPrevPage')?.addEventListener('click', () => changePage('users', -1));
    document.getElementById('userNextPage')?.addEventListener('click', () => changePage('users', 1));

    // Company filters and search
    document.getElementById('companyFilter')?.addEventListener('change', renderCompaniesTable);
    document.getElementById('companySearch')?.addEventListener('input', renderCompaniesTable);
    document.getElementById('companyPrevPage')?.addEventListener('click', () => changePage('companies', -1));
    document.getElementById('companyNextPage')?.addEventListener('click', () => changePage('companies', 1));

    // Job filters and search
    document.getElementById('jobFilter')?.addEventListener('change', renderJobsTable);
    document.getElementById('jobSearch')?.addEventListener('input', renderJobsTable);
    document.getElementById('jobPrevPage')?.addEventListener('click', () => changePage('jobs', -1));
    document.getElementById('jobNextPage')?.addEventListener('click', () => changePage('jobs', 1));

    // Global search
    document.getElementById('globalSearch')?.addEventListener('input', handleGlobalSearch);
}


// Navigation

function switchSection(sectionName) {
    // Update nav items
    navItems.forEach(item => {
        item.classList.toggle('active', item.dataset.section === sectionName);
    });

    // Update sections
    contentSections.forEach(section => {
        section.classList.toggle('active', section.id === sectionName);
    });

    // Update page title
    const titles = {
        dashboard: 'Dashboard',
        users: 'User Management',
        companies: 'Company Management',
        jobs: 'Job Management'
    };
    pageTitle.textContent = titles[sectionName] || 'Dashboard';

    
    sidebar.classList.remove('open');
}


// Dashboard Stats

function updateDashboardStats() {
    const totalUsers = users.length;
    const totalCompanies = companies.length;
    const activeJobs = jobs.filter(j => j.status === 'active').length;
    const blockedCount = users.filter(u => u.status === 'blocked').length + 
                         companies.filter(c => c.status === 'blocked').length;

    animateNumber('totalUsers', totalUsers);
    animateNumber('totalCompanies', totalCompanies);
    animateNumber('totalJobs', activeJobs);
    animateNumber('blockedCount', blockedCount);
}

function animateNumber(elementId, target) {
    const element = document.getElementById(elementId);
    if (!element) return;

    let current = 0;
    const increment = target / 30;
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            element.textContent = target;
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(current);
        }
    }, 30);
}

// render recent users 
function renderRecentUsers() {
    const tbody = document.getElementById('recentUsersTable');
    if (!tbody) return;

    const recentUsers = users.slice(0, 5);
    tbody.innerHTML = recentUsers.map(user => `
        <tr>
            <td>
                <div class="user-cell">
                    <img src="${user.avatar}" alt="${user.name}" class="user-avatar">
                    <span class="user-name">${user.name}</span>
                </div>
            </td>
            <td>${user.email}</td>
            <td><span class="status-badge ${user.status}">${user.status}</span></td>
            <td>${formatDate(user.joined)}</td>
        </tr>
    `).join('');
}

function renderRecentCompanies() {
    const tbody = document.getElementById('recentCompaniesTable');
    if (!tbody) return;

    const recentCompanies = companies.slice(0, 5);
    tbody.innerHTML = recentCompanies.map(company => `
        <tr>
            <td>
                <div class="company-cell">
                    <img src="${company.logo}" alt="${company.name}" class="company-logo">
                    <span class="company-name">${company.name}</span>
                </div>
            </td>
            <td>${company.industry}</td>
            <td><span class="status-badge ${company.status}">${company.status}</span></td>
            <td>${company.jobsPosted}</td>
        </tr>
    `).join('');
}


// Users Table

function renderUsersTable() {
    const tbody = document.getElementById('usersTableBody');
    if (!tbody) return;

    const filter = document.getElementById('userFilter')?.value || 'all';
    const search = document.getElementById('userSearch')?.value.toLowerCase() || '';

    let filteredUsers = users.filter(user => {
        const matchesFilter = filter === 'all' || user.status === filter;
        const matchesSearch = user.name.toLowerCase().includes(search) ||
                              user.email.toLowerCase().includes(search);
        return matchesFilter && matchesSearch;
    });

    const totalPages = Math.ceil(filteredUsers.length / itemsPerPage);
    currentUserPage = Math.min(currentUserPage, Math.max(1, totalPages));

    const start = (currentUserPage - 1) * itemsPerPage;
    const paginatedUsers = filteredUsers.slice(start, start + itemsPerPage);

    tbody.innerHTML = paginatedUsers.map(user => `
        <tr data-id="${user.id}">
            <td><input type="checkbox" class="row-checkbox"></td>
            <td>
                <div class="user-cell">
                    <img src="${user.avatar}" alt="${user.name}" class="user-avatar">
                    <span class="user-name">${user.name}</span>
                </div>
            </td>
            <td>${user.email}</td>
            <td>${user.phone}</td>
            <td><span class="status-badge ${user.status}">${user.status}</span></td>
            <td>${formatDate(user.joined)}</td>
            <td>
                <div class="action-btns">
                    <button class="action-btn view" title="View Details" onclick="viewUser(${user.id})">
                        <i class="bi bi-eye-fill"></i>
                    </button>
                    ${user.status === 'active' 
                        ? `<button class="action-btn block" title="Block User" onclick="confirmBlockUser(${user.id})">
                               <i class="bi bi-slash-circle"></i>
                           </button>`
                        : `<button class="action-btn unblock" title="Unblock User" onclick="confirmUnblockUser(${user.id})">
                               <i class="bi bi-check-circle-fill"></i>
                           </button>`
                    }
                </div>
            </td>
        </tr>
    `).join('');

    updatePagination('user', currentUserPage, totalPages);
}

// Companies Table

function renderCompaniesTable() {
    const tbody = document.getElementById('companiesTableBody');
    if (!tbody) return;

    const filter = document.getElementById('companyFilter')?.value || 'all';
    const search = document.getElementById('companySearch')?.value.toLowerCase() || '';

    let filteredCompanies = companies.filter(company => {
        const matchesFilter = filter === 'all' || company.status === filter;
        const matchesSearch = company.name.toLowerCase().includes(search) ||
                              company.industry.toLowerCase().includes(search);
        return matchesFilter && matchesSearch;
    });

    const totalPages = Math.ceil(filteredCompanies.length / itemsPerPage);
    currentCompanyPage = Math.min(currentCompanyPage, Math.max(1, totalPages));

    const start = (currentCompanyPage - 1) * itemsPerPage;
    const paginatedCompanies = filteredCompanies.slice(start, start + itemsPerPage);

    tbody.innerHTML = paginatedCompanies.map(company => `
        <tr data-id="${company.id}">
            <td><input type="checkbox" class="row-checkbox"></td>
            <td>
                <div class="company-cell">
                    <img src="${company.logo}" alt="${company.name}" class="company-logo">
                    <span class="company-name">${company.name}</span>
                </div>
            </td>
            <td>${company.industry}</td>
            <td>${company.email}</td>
            <td>${company.jobsPosted}</td>
            <td><span class="status-badge ${company.status}">${company.status}</span></td>
            <td>
                <div class="action-btns">
                    <button class="action-btn view" title="View Details" onclick="viewCompany(${company.id})">
                        <i class="bi bi-eye-fill"></i>
                    </button>
                    ${company.status === 'active' 
                        ? `<button class="action-btn block" title="Block Company" onclick="confirmBlockCompany(${company.id})">
                               <i class="bi bi-slash-circle"></i>
                           </button>`
                        : `<button class="action-btn unblock" title="Unblock Company" onclick="confirmUnblockCompany(${company.id})">
                               <i class="bi bi-check-circle-fill"></i>
                           </button>`
                    }
                </div>
            </td>
        </tr>
    `).join('');

    updatePagination('company', currentCompanyPage, totalPages);
}


// Jobs Table

function renderJobsTable() {
    const tbody = document.getElementById('jobsTableBody');
    if (!tbody) return;

    const filter = document.getElementById('jobFilter')?.value || 'all';
    const search = document.getElementById('jobSearch')?.value.toLowerCase() || '';

    let filteredJobs = jobs.filter(job => {
        const matchesFilter = filter === 'all' || job.status === filter;
        const matchesSearch = job.title.toLowerCase().includes(search) ||
                              job.company.toLowerCase().includes(search) ||
                              job.location.toLowerCase().includes(search);
        return matchesFilter && matchesSearch;
    });

    const totalPages = Math.ceil(filteredJobs.length / itemsPerPage);
    currentJobPage = Math.min(currentJobPage, Math.max(1, totalPages));

    const start = (currentJobPage - 1) * itemsPerPage;
    const paginatedJobs = filteredJobs.slice(start, start + itemsPerPage);

    tbody.innerHTML = paginatedJobs.map(job => `
        <tr data-id="${job.id}">
            <td><input type="checkbox" class="row-checkbox"></td>
            <td><strong>${job.title}</strong></td>
            <td>${job.company}</td>
            <td>${job.location}</td>
            <td><span class="job-type">${job.type}</span></td>
            <td>${formatDate(job.posted)}</td>
            <td><span class="status-badge ${job.status}">${job.status}</span></td>
            <td>
                <div class="action-btns">
                    <button class="action-btn view" title="View Details" onclick="viewJob(${job.id})">
                        <i class="bi bi-eye-fill"></i>
                    </button>
                    <button class="action-btn delete" title="Delete Job" onclick="confirmDeleteJob(${job.id})">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');

    updatePagination('job', currentJobPage, totalPages);
}


// Pagination

function updatePagination(type, currentPage, totalPages) {
    const pageInfo = document.getElementById(`${type}PageInfo`);
    const prevBtn = document.getElementById(`${type}PrevPage`);
    const nextBtn = document.getElementById(`${type}NextPage`);

    if (pageInfo) pageInfo.textContent = `Page ${currentPage} of ${totalPages || 1}`;
    if (prevBtn) prevBtn.disabled = currentPage <= 1;
    if (nextBtn) nextBtn.disabled = currentPage >= totalPages;
}

function changePage(type, direction) {
    if (type === 'users') {
        currentUserPage += direction;
        renderUsersTable();
    } else if (type === 'companies') {
        currentCompanyPage += direction;
        renderCompaniesTable();
    } else if (type === 'jobs') {
        currentJobPage += direction;
        renderJobsTable();
    }
}

// Action Handlers


function viewUser(id) {
    const user = users.find(u => u.id === id);
    if (user) {
        showToast(`Viewing ${user.name}'s profile`, 'info');
        // TODO: Open user detail view/modal
    }
}

function viewCompany(id) {
    const company = companies.find(c => c.id === id);
    if (company) {
        showToast(`Viewing ${company.name}'s profile`, 'info');
        // TODO: Open company detail view/modal
    }
}

function viewJob(id) {
    const job = jobs.find(j => j.id === id);
    if (job) {
        showToast(`Viewing job: ${job.title}`, 'info');
        // TODO: Open job detail view/modal
    }
}

// Block/Unblock Users
function confirmBlockUser(id) {
    const user = users.find(u => u.id === id);
    if (!user) return;

    showModal(
        'Block User',
        `Are you sure you want to block <strong>${user.name}</strong>? They will not be able to access the platform.`,
        () => blockUser(id)
    );
}

function confirmUnblockUser(id) {
    const user = users.find(u => u.id === id);
    if (!user) return;

    showModal(
        'Unblock User',
        `Are you sure you want to unblock <strong>${user.name}</strong>? They will regain access to the platform.`,
        () => unblockUser(id)
    );
}

function blockUser(id) {
    const user = users.find(u => u.id === id);
    if (user) {
        user.status = 'blocked';
        renderUsersTable();
        renderRecentUsers();
        updateDashboardStats();
        showToast(`${user.name} has been blocked`, 'warning');
    }
    closeModal();
}

function unblockUser(id) {
    const user = users.find(u => u.id === id);
    if (user) {
        user.status = 'active';
        renderUsersTable();
        renderRecentUsers();
        updateDashboardStats();
        showToast(`${user.name} has been unblocked`, 'success');
    }
    closeModal();
}

// Block/Unblock Companies
function confirmBlockCompany(id) {
    const company = companies.find(c => c.id === id);
    if (!company) return;

    showModal(
        'Block Company',
        `Are you sure you want to block <strong>${company.name}</strong>? Their job listings will be hidden.`,
        () => blockCompany(id)
    );
}

function confirmUnblockCompany(id) {
    const company = companies.find(c => c.id === id);
    if (!company) return;

    showModal(
        'Unblock Company',
        `Are you sure you want to unblock <strong>${company.name}</strong>? Their account and listings will be restored.`,
        () => unblockCompany(id)
    );
}

function blockCompany(id) {
    const company = companies.find(c => c.id === id);
    if (company) {
        company.status = 'blocked';
        renderCompaniesTable();
        renderRecentCompanies();
        updateDashboardStats();
        showToast(`${company.name} has been blocked`, 'warning');
    }
    closeModal();
}

function unblockCompany(id) {
    const company = companies.find(c => c.id === id);
    if (company) {
        company.status = 'active';
        renderCompaniesTable();
        renderRecentCompanies();
        updateDashboardStats();
        showToast(`${company.name} has been unblocked`, 'success');
    }
    closeModal();
}

// Delete Jobs
function confirmDeleteJob(id) {
    const job = jobs.find(j => j.id === id);
    if (!job) return;

    showModal(
        'Delete Job',
        `Are you sure you want to delete the job listing <strong>${job.title}</strong> from <strong>${job.company}</strong>? This action cannot be undone.`,
        () => deleteJob(id)
    );
}

function deleteJob(id) {
    const jobIndex = jobs.findIndex(j => j.id === id);
    if (jobIndex > -1) {
        const job = jobs[jobIndex];
        jobs.splice(jobIndex, 1);
        renderJobsTable();
        updateDashboardStats();
        showToast(`Job "${job.title}" has been deleted`, 'error');
    }
    closeModal();
}

// Modal Functions

function showModal(title, message, onConfirm) {
    modalTitle.textContent = title;
    modalMessage.innerHTML = message;
    pendingAction = onConfirm;
    modal.classList.add('active');
}

function closeModal() {
    modal.classList.remove('active');
    pendingAction = null;
}

function executePendingAction() {
    if (pendingAction && typeof pendingAction === 'function') {
        pendingAction();
    }
}


// Toast Notifications

function showToast(message, type = 'success') {
    const toastIcon = toast.querySelector('.toast-icon');
    const toastMessage = toast.querySelector('.toast-message');

    // Set icon based on type
    const icons = {
        success: 'bi-check-circle-fill',
        error: 'bi-x-circle-fill',
        warning: 'bi-exclamation-circle-fill',
        info: 'bi-info-circle-fill'
    };

    toastIcon.className = `toast-icon bi ${icons[type] || icons.success}`;
    toastMessage.textContent = message;
    toast.className = `toast ${type}`;

    
    setTimeout(() => toast.classList.add('show'), 10);

    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}


// Global Search

function handleGlobalSearch(e) {
    const query = e.target.value.toLowerCase().trim();
    
    if (!query) return;

    
    const userMatches = users.filter(u => 
        u.name.toLowerCase().includes(query) || 
        u.email.toLowerCase().includes(query)
    );
    
    const companyMatches = companies.filter(c => 
        c.name.toLowerCase().includes(query) || 
        c.industry.toLowerCase().includes(query)
    );
    
    const jobMatches = jobs.filter(j => 
        j.title.toLowerCase().includes(query) || 
        j.company.toLowerCase().includes(query)
    );

    
    if (userMatches.length >= companyMatches.length && userMatches.length >= jobMatches.length) {
        document.getElementById('userSearch').value = query;
        switchSection('users');
        renderUsersTable();
    } else if (companyMatches.length >= jobMatches.length) {
        document.getElementById('companySearch').value = query;
        switchSection('companies');
        renderCompaniesTable();
    } else {
        document.getElementById('jobSearch').value = query;
        switchSection('jobs');
        renderJobsTable();
    } 
}



// Utility Functions

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
}


window.viewUser = viewUser;
window.viewCompany = viewCompany;
window.viewJob = viewJob;
window.confirmBlockUser = confirmBlockUser;
window.confirmUnblockUser = confirmUnblockUser;
window.confirmBlockCompany = confirmBlockCompany;
window.confirmUnblockCompany = confirmUnblockCompany;
window.confirmDeleteJob = confirmDeleteJob;

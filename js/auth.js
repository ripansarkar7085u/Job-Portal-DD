const patterns = {
    fullName: /^[a-zA-Z\s.'-]{2,100}$/,
    companyName: /^.{2,255}$/,
    email: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
    password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{12,128}$/,
    phone: /^[0-9+\-\s()]{10,20}$/
};

let currentAccountType = 'user';

function getAppBasePath() {
    const scriptTag = Array.from(document.scripts).find((script) => script.src && /\/js\/auth\.js(\?|$)/.test(script.src));
    if (scriptTag) {
        const scriptUrl = new URL(scriptTag.src, window.location.origin);
        return scriptUrl.pathname.replace(/\/js\/auth\.js$/, '') || '';
    }

    const parts = window.location.pathname.split('/').filter(Boolean);
    if (parts.length === 0) return '';
    if (parts[0].includes('.')) return '';
    return `/${parts[0]}`;
}

const APP_BASE_PATH = getAppBasePath();

function appUrl(path) {
    const cleanPath = path.startsWith('/') ? path : `/${path}`;
    return `${APP_BASE_PATH}${cleanPath}`.replace(/\/\/{2,}/g, '/');
}

function apiUrl(endpointFile) {
    return appUrl(`/api/${endpointFile}`);
}

function closeAuthModalIfPresent() {
    const modalElement = document.getElementById('authModal');
    if (!modalElement) return;
    if (typeof bootstrap === 'undefined' || !bootstrap.Modal) return;

    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) {
        modal.hide();
    }
}

function showError(inputElement, message) {
    const formGroup = inputElement.closest('.form-group') || inputElement.closest('.mb-3') || inputElement.parentElement;
    if (!formGroup) return;

    let errorDiv = formGroup.querySelector('.error-message');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        formGroup.appendChild(errorDiv);
    }

    errorDiv.textContent = message;
    inputElement.classList.add('is-invalid');
    inputElement.classList.remove('is-valid');
}

function clearError(inputElement) {
    const formGroup = inputElement.closest('.form-group') || inputElement.closest('.mb-3') || inputElement.parentElement;
    if (!formGroup) return;

    const errorDiv = formGroup.querySelector('.error-message');
    if (errorDiv) {
        errorDiv.textContent = '';
    }

    inputElement.classList.remove('is-invalid');
    inputElement.classList.add('is-valid');
}

function clearFormErrors() {
    clearAlerts();
    document.querySelectorAll('.error-message').forEach((el) => {
        el.textContent = '';
    });
    document.querySelectorAll('.is-invalid, .is-valid').forEach((el) => {
        el.classList.remove('is-invalid', 'is-valid');
    });
}

function showAlert(message, type = 'danger') {
    const alertContainer = document.getElementById('alertContainer');
    if (!alertContainer) return;

    const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
    const alertDiv = document.createElement('div');
    alertDiv.className = `auth-alert auth-alert-${type}`;
    alertDiv.innerHTML = `
        <i class="bi ${icon}"></i>
        <span>${message}</span>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
            <i class="bi bi-x"></i>
        </button>
    `;

    alertContainer.innerHTML = '';
    alertContainer.appendChild(alertDiv);

    setTimeout(() => {
        if (alertDiv.parentElement) {
            alertDiv.remove();
        }
    }, 5000);
}

function clearAlerts() {
    const alertContainer = document.getElementById('alertContainer');
    if (alertContainer) {
        alertContainer.innerHTML = '';
    }
}

function validateField(input, pattern, errorMessage) {
    if (!input) return false;
    const value = input.value.trim();

    if (value === '' && input.hasAttribute('required')) {
        showError(input, 'This field is required');
        return false;
    }

    if (value !== '' && pattern && !pattern.test(value)) {
        showError(input, errorMessage);
        return false;
    }

    clearError(input);
    return true;
}

function switchAccountType(type, evt) {
    currentAccountType = type;
    clearFormErrors();

    document.querySelectorAll('.account-tab').forEach((tab) => tab.classList.remove('active'));

    if (evt && evt.target) {
        const btn = evt.target.closest('.account-tab');
        if (btn) btn.classList.add('active');
    } else {
        const tabs = document.querySelectorAll('.account-tab');
        if (type === 'user' && tabs[0]) tabs[0].classList.add('active');
        if (type === 'company' && tabs[1]) tabs[1].classList.add('active');
    }

    const userForms = document.getElementById('userForms');
    const companyForms = document.getElementById('companyForms');
    const title = document.getElementById('authTitle');

    if (type === 'user') {
        if (userForms) userForms.style.display = 'block';
        if (companyForms) companyForms.style.display = 'none';
        if (title) title.textContent = 'Welcome Back!';
        showUserLogin();
    } else {
        if (userForms) userForms.style.display = 'none';
        if (companyForms) companyForms.style.display = 'block';
        if (title) title.textContent = 'Company Login';
        showCompanyLogin();
    }
}

function showUserLogin() {
    const loginForm = document.getElementById('userLoginForm');
    const registerForm = document.getElementById('userRegisterForm');
    if (loginForm) loginForm.style.display = 'block';
    if (registerForm) registerForm.style.display = 'none';
    const title = document.getElementById('authTitle');
    if (title) title.textContent = 'Welcome Back!';
    clearFormErrors();
}

function showUserRegister() {
    const loginForm = document.getElementById('userLoginForm');
    const registerForm = document.getElementById('userRegisterForm');
    if (loginForm) loginForm.style.display = 'none';
    if (registerForm) registerForm.style.display = 'block';
    const title = document.getElementById('authTitle');
    if (title) title.textContent = 'Create Your Account';
    clearFormErrors();
}

function showCompanyLogin() {
    const loginForm = document.getElementById('companyLoginForm');
    const registerForm = document.getElementById('companyRegisterForm');
    if (loginForm) loginForm.style.display = 'block';
    if (registerForm) registerForm.style.display = 'none';
    const title = document.getElementById('authTitle');
    if (title) title.textContent = 'Company Login';
    clearFormErrors();
}

function showCompanyRegister() {
    const loginForm = document.getElementById('companyLoginForm');
    const registerForm = document.getElementById('companyRegisterForm');
    if (loginForm) loginForm.style.display = 'none';
    if (registerForm) registerForm.style.display = 'block';
    const title = document.getElementById('authTitle');
    if (title) title.textContent = 'Register Your Company';
    clearFormErrors();
}

async function postAuth(endpoint, formData) {
    const response = await fetch(endpoint, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    });

    let data = null;
    try {
        data = await response.json();
    } catch {
        const raw = await response.text();
        const sample = raw ? raw.slice(0, 200) : 'empty response';
        throw new Error(`Unexpected response from server (HTTP ${response.status}). ${sample}`);
    }

    if (!response.ok || !data.success) {
        throw new Error(data?.message || 'Authentication failed');
    }

    return data;
}

async function handleUserLogin(event) {
    event.preventDefault();

    const form = event.currentTarget;
    const emailInput = form.querySelector('input[name="email"]');
    const passwordInput = form.querySelector('input[name="password"]');
    const rememberInput = form.querySelector('input[name="remember"]');
    const submitBtn = form.querySelector('button[type="submit"], .auth-btn');

    let isValid = true;

    if (!validateField(emailInput, patterns.email, 'Please enter a valid email address')) isValid = false;
    if (!passwordInput || !passwordInput.value.trim()) {
        if (passwordInput) showError(passwordInput, 'Password is required');
        isValid = false;
    } else {
        clearError(passwordInput);
    }

    if (!isValid) return;

    const originalHtml = submitBtn ? submitBtn.innerHTML : '';
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> Logging in...';
    }

    try {
        const formData = new FormData();
        formData.append('email', emailInput.value.trim());
        formData.append('password', passwordInput.value);
        formData.append('remember', rememberInput?.checked ? '1' : '0');

        const data = await postAuth(apiUrl('login.php'), formData);
        showAlert(data.message || 'Login successful.', 'success');

        closeAuthModalIfPresent();

        setTimeout(() => {
            window.location.href = data.redirect || appUrl('/user/dashboard.php');
        }, 600);
    } catch (error) {
        showAlert(error.message, 'danger');
    } finally {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHtml;
        }
    }
}

async function handleUserRegister(event) {
    event.preventDefault();

    const form = event.currentTarget;
    const fullNameInput = form.querySelector('input[name="full_name"]');
    const emailInput = form.querySelector('input[name="email"]');
    const phoneInput = form.querySelector('input[name="phone"]');
    const passwordInput = form.querySelector('input[name="password"]');
    const confirmPasswordInput = form.querySelector('input[name="confirm_password"]');
    const submitBtn = form.querySelector('button[type="submit"], .auth-btn');

    let isValid = true;

    if (!validateField(fullNameInput, patterns.fullName, 'Please enter a valid name (2-100 characters)')) isValid = false;
    if (!validateField(emailInput, patterns.email, 'Please enter a valid email address')) isValid = false;

    if (phoneInput && phoneInput.value.trim() !== '' && !patterns.phone.test(phoneInput.value.trim())) {
        showError(phoneInput, 'Please enter a valid phone number');
        isValid = false;
    } else if (phoneInput && phoneInput.value.trim() !== '') {
        clearError(phoneInput);
    }

    if (!validateField(passwordInput, patterns.password, 'Min 12 chars with uppercase, lowercase, number and special character')) isValid = false;

    if (!confirmPasswordInput || confirmPasswordInput.value !== passwordInput.value) {
        if (confirmPasswordInput) showError(confirmPasswordInput, 'Passwords do not match');
        isValid = false;
    } else {
        clearError(confirmPasswordInput);
    }

    if (!isValid) return;

    const originalHtml = submitBtn ? submitBtn.innerHTML : '';
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> Creating account...';
    }

    try {
        const formData = new FormData();
        formData.append('full_name', fullNameInput.value.trim());
        formData.append('email', emailInput.value.trim());
        formData.append('phone', phoneInput ? phoneInput.value.trim() : '');
        formData.append('password', passwordInput.value);
        formData.append('confirm_password', confirmPasswordInput.value);
        formData.append('user_type', 'candidate');

        const data = await postAuth(apiUrl('register.php'), formData);
        showAlert(data.message || 'Registration successful.', 'success');

        closeAuthModalIfPresent();

        setTimeout(() => {
            window.location.href = data.redirect || appUrl('/user/dashboard.php');
        }, 600);
    } catch (error) {
        showAlert(error.message, 'danger');
    } finally {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHtml;
        }
    }
}

async function handleCompanyLogin(event) {
    event.preventDefault();

    const form = event.currentTarget;
    const emailInput = form.querySelector('input[name="email"]');
    const passwordInput = form.querySelector('input[name="password"]');
    const rememberInput = form.querySelector('input[name="remember"]');
    const submitBtn = form.querySelector('button[type="submit"], .auth-btn');

    let isValid = true;

    if (!validateField(emailInput, patterns.email, 'Please enter a valid email address')) isValid = false;
    if (!passwordInput || !passwordInput.value.trim()) {
        if (passwordInput) showError(passwordInput, 'Password is required');
        isValid = false;
    } else {
        clearError(passwordInput);
    }

    if (!isValid) return;

    const originalHtml = submitBtn ? submitBtn.innerHTML : '';
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> Logging in...';
    }

    try {
        const formData = new FormData();
        formData.append('email', emailInput.value.trim());
        formData.append('password', passwordInput.value);
        formData.append('remember', rememberInput?.checked ? '1' : '0');

        const data = await postAuth(apiUrl('company_login.php'), formData);
        showAlert(data.message || 'Login successful.', 'success');

        closeAuthModalIfPresent();

        setTimeout(() => {
            window.location.href = data.redirect || appUrl('/company/index.php');
        }, 600);
    } catch (error) {
        showAlert(error.message, 'danger');
    } finally {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHtml;
        }
    }
}

async function handleCompanyRegister(event) {
    event.preventDefault();

    const form = event.currentTarget;
    const companyNameInput = form.querySelector('input[name="company_name"]');
    const emailInput = form.querySelector('input[name="email"]');
    const phoneInput = form.querySelector('input[name="phone"]');
    const industryInput = form.querySelector('select[name="industry"]');
    const websiteInput = form.querySelector('input[name="website"]');
    const passwordInput = form.querySelector('input[name="password"]');
    const confirmPasswordInput = form.querySelector('input[name="confirm_password"]');
    const submitBtn = form.querySelector('button[type="submit"], .auth-btn');

    let isValid = true;

    if (!validateField(companyNameInput, patterns.companyName, 'Company name must be between 2 and 255 characters')) isValid = false;
    if (!validateField(emailInput, patterns.email, 'Please enter a valid email address')) isValid = false;

    if (phoneInput && phoneInput.value.trim() !== '' && !patterns.phone.test(phoneInput.value.trim())) {
        showError(phoneInput, 'Please enter a valid phone number');
        isValid = false;
    } else if (phoneInput && phoneInput.value.trim() !== '') {
        clearError(phoneInput);
    }

    if (industryInput && !industryInput.value) {
        showError(industryInput, 'Please select an industry');
        isValid = false;
    }

    if (websiteInput && websiteInput.value.trim() !== '') {
        try {
            const normalized = websiteInput.value.trim().match(/^https?:\/\//i)
                ? websiteInput.value.trim()
                : `https://${websiteInput.value.trim()}`;
            new URL(normalized);
            clearError(websiteInput);
        } catch {
            showError(websiteInput, 'Please enter a valid website URL');
            isValid = false;
        }
    }

    if (!validateField(passwordInput, patterns.password, 'Min 12 chars with uppercase, lowercase, number and special character')) isValid = false;

    if (!confirmPasswordInput || confirmPasswordInput.value !== passwordInput.value) {
        if (confirmPasswordInput) showError(confirmPasswordInput, 'Passwords do not match');
        isValid = false;
    } else {
        clearError(confirmPasswordInput);
    }

    if (!isValid) return;

    const originalHtml = submitBtn ? submitBtn.innerHTML : '';
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> Registering...';
    }

    try {
        const formData = new FormData();
        formData.append('company_name', companyNameInput.value.trim());
        formData.append('email', emailInput.value.trim());
        formData.append('phone', phoneInput ? phoneInput.value.trim() : '');
        formData.append('industry', industryInput ? industryInput.value : '');
        formData.append('website', websiteInput ? websiteInput.value.trim() : '');
        formData.append('password', passwordInput.value);
        formData.append('confirm_password', confirmPasswordInput.value);

        const data = await postAuth(apiUrl('company_register.php'), formData);
        showAlert(data.message || 'Registration successful.', 'success');

        closeAuthModalIfPresent();

        setTimeout(() => {
            window.location.href = data.redirect || appUrl('/company/index.php');
        }, 600);
    } catch (error) {
        showAlert(error.message, 'danger');
    } finally {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHtml;
        }
    }
}

async function checkLoginStatus() {
    try {
        const response = await fetch(apiUrl('check_session.php'), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const data = await response.json();

        if (!data.logged_in) return;

        const loginBtn = document.querySelector('.login-btn');
        if (!loginBtn) return;

        if (data.account_type === 'company' && data.company) {
            loginBtn.textContent = data.company.name || 'Dashboard';
            loginBtn.removeAttribute('data-bs-toggle');
            loginBtn.removeAttribute('data-bs-target');
            loginBtn.href = appUrl('/company/index.php');
        } else if (data.user) {
            loginBtn.textContent = data.user.name || 'Dashboard';
            loginBtn.removeAttribute('data-bs-toggle');
            loginBtn.removeAttribute('data-bs-target');
            loginBtn.href = appUrl('/user/dashboard.php');
        }
    } catch (error) {
        console.error('Session check error:', error);
    }
}

function bindRealtimeValidation() {
    const selectors = [
        '#userLoginForm input',
        '#userRegisterForm input',
        '#companyLoginForm input',
        '#companyRegisterForm input',
        '#registerForm input',
        '#loginForm input'
    ];

    document.querySelectorAll(selectors.join(',')).forEach((input) => {
        input.addEventListener('blur', function onBlur() {
            if (this.value.trim() === '') return;

            if (this.type === 'email') {
                validateField(this, patterns.email, 'Please enter a valid email address');
                return;
            }
            if (this.name === 'full_name') {
                validateField(this, patterns.fullName, 'Please enter a valid name');
                return;
            }
            if (this.name === 'company_name') {
                validateField(this, patterns.companyName, 'Company name must be between 2 and 255 characters');
                return;
            }
            if (this.name === 'password') {
                validateField(this, patterns.password, 'Min 12 chars with uppercase, lowercase, number and special character');
                return;
            }
            if (this.name === 'confirm_password') {
                const form = this.closest('form');
                const passwordInput = form?.querySelector('input[name="password"]');
                if (!passwordInput || this.value !== passwordInput.value) {
                    showError(this, 'Passwords do not match');
                } else {
                    clearError(this);
                }
                return;
            }
            if (this.name === 'phone') {
                validateField(this, patterns.phone, 'Please enter a valid phone number');
            }
        });
    });
}

function bindLegacyForms() {
    const legacyLogin = document.getElementById('loginForm');
    const legacyRegister = document.getElementById('registerForm');

    if (legacyLogin) {
        legacyLogin.addEventListener('submit', handleUserLogin);
    }

    if (legacyRegister) {
        legacyRegister.addEventListener('submit', async (event) => {
            const form = event.currentTarget;
            if (!form.querySelector('input[name="confirm_password"]')) {
                const pwd = form.querySelector('input[name="password"]')?.value || '';
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'confirm_password';
                hidden.value = pwd;
                form.appendChild(hidden);
            }
            await handleUserRegister(event);
        });
    }
}

function resetModalOnClose() {
    const authModal = document.getElementById('authModal');
    if (!authModal) return;

    authModal.addEventListener('hidden.bs.modal', () => {
        currentAccountType = 'user';

        document.querySelectorAll('.account-tab').forEach((tab, index) => {
            if (index === 0) tab.classList.add('active');
            else tab.classList.remove('active');
        });

        const userForms = document.getElementById('userForms');
        const companyForms = document.getElementById('companyForms');
        if (userForms) userForms.style.display = 'block';
        if (companyForms) companyForms.style.display = 'none';

        const userLoginForm = document.getElementById('userLoginForm');
        const userRegisterForm = document.getElementById('userRegisterForm');
        if (userLoginForm) userLoginForm.style.display = 'block';
        if (userRegisterForm) userRegisterForm.style.display = 'none';

        const title = document.getElementById('authTitle');
        if (title) title.textContent = 'Welcome Back!';

        authModal.querySelectorAll('input').forEach((input) => {
            if (input.type === 'checkbox') {
                input.checked = false;
            } else {
                input.value = '';
            }
        });

        authModal.querySelectorAll('select').forEach((select) => {
            select.selectedIndex = 0;
        });

        clearFormErrors();
    });
}

document.addEventListener('DOMContentLoaded', () => {
    checkLoginStatus();

    const userLoginForm = document.getElementById('userLoginForm');
    if (userLoginForm) userLoginForm.addEventListener('submit', handleUserLogin);

    const userRegisterForm = document.getElementById('userRegisterForm');
    if (userRegisterForm) userRegisterForm.addEventListener('submit', handleUserRegister);

    const companyLoginForm = document.getElementById('companyLoginForm');
    if (companyLoginForm) companyLoginForm.addEventListener('submit', handleCompanyLogin);

    const companyRegisterForm = document.getElementById('companyRegisterForm');
    if (companyRegisterForm) companyRegisterForm.addEventListener('submit', handleCompanyRegister);

    bindLegacyForms();
    bindRealtimeValidation();
    resetModalOnClose();
});

function logout() {
    window.location.href = apiUrl('logout.php');
}

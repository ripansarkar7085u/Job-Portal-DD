/**
 * Authentication Functions
 * CareerHunt Job Portal
 */

// Form validation patterns
const patterns = {
    fullName: /^[a-zA-Z\s]{2,100}$/,
    email: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
    password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>])[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/,
    phone: /^[0-9+\-\s()]{10,20}$/
};

// Show error message
function showError(inputElement, message) {
    const formGroup = inputElement.closest('.mb-3') || inputElement.parentElement;
    let errorDiv = formGroup.querySelector('.error-message');
    
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'error-message text-danger small mt-1';
        formGroup.appendChild(errorDiv);
    }
    
    errorDiv.textContent = message;
    inputElement.classList.add('is-invalid');
    inputElement.classList.remove('is-valid');
}

// Clear error message
function clearError(inputElement) {
    const formGroup = inputElement.closest('.mb-3') || inputElement.parentElement;
    const errorDiv = formGroup.querySelector('.error-message');
    
    if (errorDiv) {
        errorDiv.textContent = '';
    }
    
    inputElement.classList.remove('is-invalid');
    inputElement.classList.add('is-valid');
}

// Show alert message
function showAlert(message, type = 'danger') {
    const alertContainer = document.getElementById('alertContainer');
    if (!alertContainer) return;
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    alertContainer.innerHTML = '';
    alertContainer.appendChild(alertDiv);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Validate single field
function validateField(input, pattern, errorMessage) {
    const value = input.value.trim();
    
    if (value === '') {
        showError(input, 'This field is required');
        return false;
    }
    
    if (pattern && !pattern.test(value)) {
        showError(input, errorMessage);
        return false;
    }
    
    clearError(input);
    return true;
}

// Login user
async function loginUser() {
    const form = document.getElementById('loginForm');
    const emailInput = form.querySelector('input[type="email"]');
    const passwordInput = form.querySelector('input[type="password"]');
    const rememberInput = form.querySelector('input[type="checkbox"]');
    const submitBtn = form.querySelector('.auth-btn');
    
    // Validate fields
    let isValid = true;
    
    if (!validateField(emailInput, patterns.email, 'Please enter a valid email address')) {
        isValid = false;
    }
    
    if (!validateField(passwordInput, null, '')) {
        isValid = false;
    }
    
    if (!isValid) return;
    
    // Show loading state
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Logging in...';
    
    try {
        const formData = new FormData();
        formData.append('email', emailInput.value.trim());
        formData.append('password', passwordInput.value);
        formData.append('remember', rememberInput?.checked ? '1' : '0');
        
        const response = await fetch('/api/login.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            showAlert(data.message, 'success');
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('authModal'));
            if (modal) modal.hide();
            
            // Redirect after short delay
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);
        } else {
            showAlert(data.message, 'danger');
        }
    } catch (error) {
        console.error('Login error:', error);
        showAlert('An error occurred. Please try again.', 'danger');
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    }
}

// Register user
async function registerUser() {
    const form = document.getElementById('registerForm');
    const fullNameInput = form.querySelector('input[name="full_name"]');
    const emailInput = form.querySelector('input[type="email"]');
    const phoneInput = form.querySelector('input[name="phone"]');
    const passwordInput = form.querySelector('input[name="password"]');
    const confirmPasswordInput = form.querySelector('input[name="confirm_password"]');
    const userTypeBtn = form.querySelector('.type-btn.active');
    const submitBtn = form.querySelector('.auth-btn');
    
    // Validate fields
    let isValid = true;
    
    if (!validateField(fullNameInput, patterns.fullName, 'Please enter a valid name (letters only, 2-100 characters)')) {
        isValid = false;
    }
    
    if (!validateField(emailInput, patterns.email, 'Please enter a valid email address')) {
        isValid = false;
    }
    
    // Phone is optional but if provided, must be valid
    if (phoneInput.value.trim() !== '' && !patterns.phone.test(phoneInput.value.trim())) {
        showError(phoneInput, 'Please enter a valid phone number');
        isValid = false;
    } else if (phoneInput.value.trim() !== '') {
        clearError(phoneInput);
    }
    
    if (!validateField(passwordInput, patterns.password, 'Password must be at least 8 characters with uppercase, lowercase, number, and special character')) {
        isValid = false;
    }
    
    if (confirmPasswordInput.value !== passwordInput.value) {
        showError(confirmPasswordInput, 'Passwords do not match');
        isValid = false;
    } else if (confirmPasswordInput.value !== '') {
        clearError(confirmPasswordInput);
    }
    
    if (!isValid) return;
    
    // Show loading state
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating account...';
    
    try {
        const formData = new FormData();
        formData.append('full_name', fullNameInput.value.trim());
        formData.append('email', emailInput.value.trim());
        formData.append('phone', phoneInput.value.trim());
        formData.append('password', passwordInput.value);
        formData.append('confirm_password', confirmPasswordInput.value);
        formData.append('user_type', userTypeBtn ? userTypeBtn.textContent.trim().toLowerCase() : 'candidate');
        
        const response = await fetch('/api/register.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            showAlert(data.message, 'success');
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('authModal'));
            if (modal) modal.hide();
            
            // Redirect after short delay
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);
        } else {
            showAlert(data.message, 'danger');
        }
    } catch (error) {
        console.error('Registration error:', error);
        showAlert('An error occurred. Please try again.', 'danger');
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    }
}

// Switch to register form
function showRegister() {
    document.getElementById('loginForm').style.display = 'none';
    document.getElementById('registerForm').style.display = 'block';
    document.getElementById('authTitle').innerText = 'Create a CareerHunt Account';
    
    // Clear any previous errors
    document.querySelectorAll('.error-message').forEach(el => el.remove());
    document.querySelectorAll('.is-invalid, .is-valid').forEach(el => {
        el.classList.remove('is-invalid', 'is-valid');
    });
}

// Switch to login form
function showLogin() {
    document.getElementById('registerForm').style.display = 'none';
    document.getElementById('loginForm').style.display = 'block';
    document.getElementById('authTitle').innerText = 'Login to CareerHunt';
    
    // Clear any previous errors
    document.querySelectorAll('.error-message').forEach(el => el.remove());
    document.querySelectorAll('.is-invalid, .is-valid').forEach(el => {
        el.classList.remove('is-invalid', 'is-valid');
    });
}

// User type button selection
function selectType(btn) {
    document.querySelectorAll('.type-btn').forEach(b => {
        b.classList.remove('active');
    });
    btn.classList.add('active');
}

// Check if user is logged in
async function checkLoginStatus() {
    try {
        const response = await fetch('/api/check_session.php');
        const data = await response.json();
        
        if (data.logged_in) {
            // Update UI for logged-in user
            const loginBtn = document.querySelector('.login-btn');
            if (loginBtn) {
                loginBtn.textContent = data.user.name;
                loginBtn.removeAttribute('data-bs-toggle');
                loginBtn.removeAttribute('data-bs-target');
                loginBtn.onclick = () => {
                    window.location.href = data.user.type === 'employer' ? '/admin/index.html' : '/user/candidate-dashboard.html';
                };
            }
        }
    } catch (error) {
        console.error('Session check error:', error);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    checkLoginStatus();
    
    // Add real-time validation on blur
    document.querySelectorAll('#loginForm input, #registerForm input').forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() !== '') {
                if (this.type === 'email') {
                    validateField(this, patterns.email, 'Please enter a valid email address');
                } else if (this.name === 'full_name') {
                    validateField(this, patterns.fullName, 'Please enter a valid name');
                } else if (this.name === 'password') {
                    validateField(this, patterns.password, 'Password must be at least 8 characters with uppercase, lowercase, number, and special character');
                }
            }
        });
    });
});

// Logout function
function logout() {
    window.location.href = '/api/logout.php';
}

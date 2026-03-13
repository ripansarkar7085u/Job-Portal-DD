<?php

require_once __DIR__ . '/_auth_common.php';

auth_set_security_headers();
auth_require_post();
auth_ensure_core_tables($conn);

if (!auth_validate_same_origin()) {
    auth_json_response(403, ['success' => false, 'message' => 'Request origin is not allowed.']);
}

$payload = auth_get_request_data();

$companyName = trim((string) ($payload['company_name'] ?? ''));
$email = auth_normalize_email((string) ($payload['email'] ?? ''));
$password = (string) ($payload['password'] ?? '');
$confirmPassword = (string) ($payload['confirm_password'] ?? '');
$phone = trim((string) ($payload['phone'] ?? ''));
$industry = trim((string) ($payload['industry'] ?? ''));
$website = trim((string) ($payload['website'] ?? ''));

$validIndustries = ['Technology', 'Finance', 'Healthcare', 'Education', 'Retail', 'Manufacturing', 'Marketing', 'Consulting', 'Other'];

$errors = [];

if ($companyName === '') {
    $errors[] = 'Company name is required.';
} elseif (!preg_match('/^[a-zA-Z0-9\s\.\-\&\,\']{2,255}$/', $companyName)) {
    $errors[] = 'Company name contains invalid characters or length.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
    $errors[] = 'A valid email is required.';
}

if (!in_array($industry, $validIndustries, true)) {
    $errors[] = 'Please select a valid industry.';
}

if ($password !== $confirmPassword) {
    $errors[] = 'Passwords do not match.';
}

$errors = array_merge($errors, auth_password_errors($password));

if (!auth_validate_phone($phone)) {
    $errors[] = 'Phone number must be 10-15 digits when provided.';
}

if ($website !== '') {
    if (!preg_match('/^https?:\/\//i', $website)) {
        $website = 'https://' . $website;
    }

    if (!filter_var($website, FILTER_VALIDATE_URL) || strlen($website) > 255) {
        $errors[] = 'A valid website URL is required when provided.';
    }
}

if (!empty($errors)) {
    auth_json_response(422, ['success' => false, 'message' => $errors[0], 'errors' => $errors]);
}

$checkCompany = $conn->prepare('SELECT id FROM companies WHERE email = ? LIMIT 1');
$checkCompany->bind_param('s', $email);
$checkCompany->execute();
$existsCompany = $checkCompany->get_result()->fetch_assoc();
$checkCompany->close();

if ($existsCompany) {
    auth_json_response(409, ['success' => false, 'message' => 'Email is already registered.']);
}

$checkUser = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$checkUser->bind_param('s', $email);
$checkUser->execute();
$existsUser = $checkUser->get_result()->fetch_assoc();
$checkUser->close();

if ($existsUser) {
    auth_json_response(409, ['success' => false, 'message' => 'Email is already registered as a user account.']);
}

$passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

$insert = $conn->prepare('INSERT INTO companies (company_name, email, password, phone, industry, website) VALUES (?, ?, ?, ?, ?, ?)');
$insert->bind_param('ssssss', $companyName, $email, $passwordHash, $phone, $industry, $website);

if (!$insert->execute()) {
    error_log('company_register.php failed: ' . $conn->error);
    $insert->close();
    auth_json_response(500, ['success' => false, 'message' => 'Registration failed. Please try again.']);
}

$companyId = (int) $conn->insert_id;
$insert->close();

auth_start_company_session([
    'id' => $companyId,
    'company_name' => $companyName,
    'email' => $email,
]);

auth_json_response(201, [
    'success' => true,
    'message' => 'Company registration successful.',
    'redirect' => auth_path('/company/index.php'),
]);

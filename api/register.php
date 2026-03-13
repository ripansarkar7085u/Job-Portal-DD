<?php

require_once __DIR__ . '/_auth_common.php';

auth_set_security_headers();
auth_require_post();
auth_ensure_core_tables($conn);

if (!auth_validate_same_origin()) {
    auth_json_response(403, ['success' => false, 'message' => 'Request origin is not allowed.']);
}

$payload = auth_get_request_data();

$fullName = trim((string) ($payload['full_name'] ?? ''));
$email = auth_normalize_email((string) ($payload['email'] ?? ''));
$password = (string) ($payload['password'] ?? '');
$confirmPassword = (string) ($payload['confirm_password'] ?? '');
$userType = trim((string) ($payload['user_type'] ?? 'candidate'));
$phone = trim((string) ($payload['phone'] ?? ''));

$errors = [];

if ($fullName === '') {
    $errors[] = 'Full name is required.';
} elseif (!preg_match('/^[a-zA-Z\s\.\-\']{2,100}$/', $fullName)) {
    $errors[] = 'Full name must be 2-100 chars and contain only letters and basic punctuation.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
    $errors[] = 'A valid email is required.';
}

if (!in_array($userType, ['candidate', 'employer'], true)) {
    $errors[] = 'Invalid user type.';
}

if ($password !== $confirmPassword) {
    $errors[] = 'Passwords do not match.';
}

$errors = array_merge($errors, auth_password_errors($password));

if (!auth_validate_phone($phone)) {
    $errors[] = 'Phone number must be 10-15 digits when provided.';
}

if (!empty($errors)) {
    auth_json_response(422, ['success' => false, 'message' => $errors[0], 'errors' => $errors]);
}

$checkUser = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$checkUser->bind_param('s', $email);
$checkUser->execute();
$existsUser = $checkUser->get_result()->fetch_assoc();
$checkUser->close();

if ($existsUser) {
    auth_json_response(409, ['success' => false, 'message' => 'Email is already registered.']);
}

if (auth_table_exists($conn, 'companies')) {
    $checkCompany = $conn->prepare('SELECT id FROM companies WHERE email = ? LIMIT 1');
    if ($checkCompany) {
        $checkCompany->bind_param('s', $email);
        $checkCompany->execute();
        $existsCompany = $checkCompany->get_result()->fetch_assoc();
        $checkCompany->close();

        if ($existsCompany) {
            auth_json_response(409, ['success' => false, 'message' => 'Email is already registered as a company account.']);
        }
    }
}

$passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

$insert = $conn->prepare('INSERT INTO users (full_name, email, password, user_type, phone) VALUES (?, ?, ?, ?, ?)');
$insert->bind_param('sssss', $fullName, $email, $passwordHash, $userType, $phone);

if (!$insert->execute()) {
    error_log('register.php failed: ' . $conn->error);
    $insert->close();
    auth_json_response(500, ['success' => false, 'message' => 'Registration failed. Please try again.']);
}

$userId = (int) $conn->insert_id;
$insert->close();

auth_start_user_session([
    'id' => $userId,
    'full_name' => $fullName,
    'email' => $email,
    'user_type' => $userType,
]);

auth_json_response(201, [
    'success' => true,
    'message' => 'Registration successful.',
    'redirect' => auth_path('/user/dashboard.php'),
]);

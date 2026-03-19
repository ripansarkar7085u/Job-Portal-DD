<?php

require_once __DIR__ . '/_auth_common.php';

auth_set_security_headers();
auth_require_post();
auth_ensure_core_tables($conn);

if (!auth_validate_same_origin()) {
    auth_json_response(403, ['success' => false, 'message' => 'Request origin is not allowed.']);
}

$payload = auth_get_request_data();

$email = auth_normalize_email((string) ($payload['email'] ?? ''));
$password = (string) ($payload['password'] ?? '');
$remember = !empty($payload['remember']) && (string) $payload['remember'] !== '0';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    auth_json_response(422, ['success' => false, 'message' => 'Valid email and password are required.']);
}

$rateKey = auth_get_client_ip() . '|' . $email;
if (!auth_rate_limit_check($conn, 'user_login', $rateKey)) {
    auth_json_response(429, ['success' => false, 'message' => 'Too many attempts. Try again later.']);
}

$stmt = $conn->prepare('SELECT id, full_name, email, password, user_type, is_active FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user || !(bool) $user['is_active'] || !password_verify($password, $user['password'])) {
    auth_rate_limit_record_failure($conn, 'user_login', $rateKey);
    auth_json_response(401, ['success' => false, 'message' => 'Invalid email or password.']);
}

if (password_needs_rehash($user['password'], PASSWORD_BCRYPT, ['cost' => 6])) {
    $newHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 6]);
    $rehash = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
    if ($rehash) {
        $rehash->bind_param('si', $newHash, $user['id']);
        $rehash->execute();
        $rehash->close();
    }
}

auth_rate_limit_clear($conn, 'user_login', $rateKey);

auth_start_user_session($user);

if ($remember) {
    auth_issue_remember_token($conn, 'user', (int) $user['id']);
}

auth_json_response(200, [
    'success' => true,
    'message' => 'Login successful.',
    'redirect' => auth_path('/user/index.php'),
    'user' => [
        'id' => (int) $user['id'],
        'name' => $user['full_name'],
        'email' => $user['email'],
        'type' => $user['user_type'],
    ],
]);

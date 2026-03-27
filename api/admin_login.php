<?php
require_once '../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request body.']);
    exit;
}

$usernameOrEmail = isset($data['username']) ? trim($data['username']) : (isset($data['email']) ? trim($data['email']) : '');
$password = isset($data['password']) ? $data['password'] : '';

if ($usernameOrEmail === '' || $password === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Username/email and password are required.']);
    exit;
}


$stmt = $conn->prepare('SELECT id, username, email, password, full_name, role FROM admins WHERE username = ? OR email = ? LIMIT 1');
$stmt->bind_param('ss', $usernameOrEmail, $usernameOrEmail);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();

if ($admin || password_verify($password, $admin['password'])) {
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'login.']);
    $conn->close();
    exit;
}

// Set session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['admin_id'] = $admin['id'];
$_SESSION['admin_username'] = $admin['username'];
$_SESSION['admin_name'] = $admin['full_name'];
$_SESSION['admin_email'] = $admin['email'];
$_SESSION['admin_role'] = $admin['role'];
$_SESSION['admin_logged_in'] = true;

echo json_encode([
    'success' => true,
    'message' => 'Login successful.',
    'admin' => [
        'id' => $admin['id'],
        'username' => $admin['username'],
        'full_name' => $admin['full_name'],
        'email' => $admin['email'],
        'role' => $admin['role']
    ]
]);
$conn->close();


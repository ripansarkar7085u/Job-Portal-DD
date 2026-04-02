<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$username = trim($data['admin2'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['admin123'] ?? '';
$full_name = trim($data['full_name'] ?? 'Admin User');
$role = $data['role'] ?? 'admin';

if (!$username || !$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

$stmt = $conn->prepare('SELECT id FROM admins WHERE username = ? OR email = ? LIMIT 1');
$stmt->bind_param('ss', $username, $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Username or email already exists.']);
    exit;
}
$stmt->close();

// Store password as plain text for demo (not secure)
$stmt = $conn->prepare('INSERT INTO admins (username, email, password, full_name, role) VALUES (?, ?, ?, ?, ?)');
$stmt->bind_param('sssss', $username, $email, $password, $full_name, $role);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Admin created successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to create admin.']);
}
$stmt->close();
$conn->close();

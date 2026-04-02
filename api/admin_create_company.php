<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$company_username = trim($data['company_username'] ?? '');
$company_name = trim($data['company_name'] ?? '');
$company_email = trim($data['company_email'] ?? '');
$company_password = $data['company_password'] ?? '';

if (!$company_username || !$company_name || !$company_email || !$company_password) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

$stmt = $conn->prepare('SELECT id FROM companies WHERE username = ? OR email = ? LIMIT 1');
$stmt->bind_param('ss', $company_username, $company_email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Username or email already exists.']);
    exit;
}
$stmt->close();

$hash = password_hash($company_password, PASSWORD_DEFAULT);

$stmt = $conn->prepare('INSERT INTO companies (username, company_name, email, password, is_active) VALUES (?, ?, ?, ?, 1)');
$stmt->bind_param('ssss', $company_username, $company_name, $company_email, $hash);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Company created successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to create company.']);
}
$stmt->close();
$conn->close();

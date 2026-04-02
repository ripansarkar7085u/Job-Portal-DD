<?php
require_once __DIR__ . '/../config/database.php';
if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION['account_type'] ?? '') !== 'company' || !isset($_SESSION['company_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$companyId = (int) $_SESSION['company_id'];

// Capture POST data
$fields = [
    'company_name', 'industry', 'company_size', 'founded', 'tagline', 'description', 'website', 'email', 'phone', 'location', 'linkedin', 'twitter', 'facebook', 'instagram'
];
$data = [];
foreach ($fields as $field) {
    $data[$field] = trim((string) ($_POST[$field] ?? ''));
}

// Check for duplicate email (other companies)
$check = $conn->prepare('SELECT id FROM companies WHERE email = ? AND id != ? LIMIT 1');
$check->bind_param('si', $data['email'], $companyId);
$check->execute();
$exists = $check->get_result()->fetch_assoc();
$check->close();
if ($exists) {
    echo json_encode(['success' => false, 'message' => 'Email is already registered with another company.']);
    exit();
}

// Handle Logo Upload
$logo_name = '';
if (!empty($_FILES['logo']['name'])) {
    $target_dir = '../photos/';
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $logo_name = time() . '_' . basename($_FILES['logo']['name']);
    move_uploaded_file($_FILES['logo']['tmp_name'], $target_dir . $logo_name);
    $logo_path = 'photos/' . $logo_name;
} else {
    // Keep current logo if no new logo uploaded
    $stmt = $conn->prepare('SELECT logo FROM companies WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $companyId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result ? $result->fetch_assoc() : null;
    $logo_path = $row && !empty($row['logo']) ? $row['logo'] : '';
    $stmt->close();
}

// Update company profile
$sql = "UPDATE companies SET company_name=?, industry=?, company_size=?, founded=?, tagline=?, description=?, website=?, email=?, phone=?, location=?, linkedin=?, twitter=?, facebook=?, instagram=?, logo=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    'sssssssssssssssi',
    $data['company_name'],
    $data['industry'],
    $data['company_size'],
    $data['founded'],
    $data['tagline'],
    $data['description'],
    $data['website'],
    $data['email'],
    $data['phone'],
    $data['location'],
    $data['linkedin'],
    $data['twitter'],
    $data['facebook'],
    $data['instagram'],
    $logo_path,
    $companyId
);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
    exit();
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update profile.']);
    exit();
}

<?php
require_once __DIR__ . '/../config/database.php';
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION['account_type'] ?? '') !== 'company' || !isset($_SESSION['company_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authorized.']);
    exit;
}

$companyId = (int) $_SESSION['company_id'];
$data = json_decode(file_get_contents('php://input'), true);

$fields = [
    'company_name', 'industry', 'company_size', 'founded', 'tagline', 'description', 'website', 'email', 'phone', 'location', 'linkedin', 'twitter', 'facebook', 'instagram', 'logo'
];
$updates = [];
$params = [];
$types = '';
foreach ($fields as $field) {
    if (isset($data[$field])) {
        $updates[] = "$field = ?";
        $params[] = $data[$field];
        $types .= 's';
    }
}
if (empty($updates)) {
    echo json_encode(['success' => false, 'message' => 'No data to update.']);
    exit;
}
$params[] = $companyId;
$types .= 'i';
$sql = "UPDATE companies SET " . implode(', ', $updates) . " WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update profile.']);
}
$stmt->close();
$conn->close();

<?php
require_once __DIR__ . '/../config/database.php';
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION['account_type'] ?? '') !== 'user' || !isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authorized.']);
    exit;
}

$userId = (int) $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);
$current_password = $data['current_password'] ?? '';
$new_password = $data['new_password'] ?? '';
$confirm_password = $data['confirm_password'] ?? '';

if (!$current_password || !$new_password || !$confirm_password) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}
if ($new_password !== $confirm_password) {
    echo json_encode(['success' => false, 'message' => 'New passwords do not match.']);
    exit;
}

$stmt = $conn->prepare('SELECT password FROM users WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $userId);
$stmt->execute();
$stmt->bind_result($hash);
if ($stmt->fetch()) {
    if (!password_verify($current_password, $hash)) {
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'User not found.']);
    exit;
}
$stmt->close();

$new_hash = password_hash($new_password, PASSWORD_DEFAULT);
$stmt = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
$stmt->bind_param('si', $new_hash, $userId);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Password updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update password.']);
}
$stmt->close();
$conn->close();

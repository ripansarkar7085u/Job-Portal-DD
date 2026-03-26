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

// Delete user-related data (applications, resumes, etc.)
try {
    $conn->begin_transaction();
    $conn->query("DELETE FROM applications WHERE user_id = $userId");
    $conn->query("DELETE FROM user_resumes WHERE user_id = $userId");
    $conn->query("DELETE FROM users WHERE id = $userId");
    $conn->commit();
    session_destroy();
    echo json_encode(['success' => true, 'message' => 'Profile deleted successfully.']);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Failed to delete profile.']);
}
$conn->close();

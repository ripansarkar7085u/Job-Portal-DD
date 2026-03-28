<?php
session_start();
require_once __DIR__ . '/_auth_common.php';
require_once __DIR__ . '/../user/_user_common.php';

auth_require_post();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION['account_type'] ?? '') !== 'user' || !isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$userId = (int)$_SESSION['user_id'];

// Begin Transaction
$conn->begin_transaction();

try {
    // Delete profile
    $conn->query("DELETE FROM profiles WHERE user_id = $userId");
    
    // Delete job applications
    $conn->query("DELETE FROM user_job_applications WHERE user_id = $userId");
    
    // Delete messages
    $conn->query("DELETE FROM user_messages WHERE user_id = $userId");
    
    // Delete alerts
    $conn->query("DELETE FROM user_alerts WHERE user_id = $userId");
    
    // Delete remember tokens
    $conn->query("DELETE FROM remember_tokens WHERE user_id = $userId");
    
    // Delete user
    $conn->query("DELETE FROM users WHERE id = $userId");
    
    $conn->commit();
    
    // Clear Session
    auth_clear_remember_token($conn);
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 42000,
            'path' => $params['path'],
            'domain' => $params['domain'],
            'secure' => (bool) $params['secure'],
            'httponly' => (bool) $params['httponly'],
            'samesite' => $params['samesite'] ?? 'Lax',
        ]);
    }
    session_destroy();

    echo json_encode(['success' => true, 'message' => 'Account deleted successfully', 'redirect' => '../index.php']);
} catch (Exception $e) {
    $conn->rollback();
    error_log("Failed to delete user $userId: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred while deleting your account.']);
}

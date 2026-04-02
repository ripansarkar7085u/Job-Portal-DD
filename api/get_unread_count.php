<?php
require_once 'messages_alerts.php';

header('Content-Type: application/json');

$user_id = $_GET['user_id'] ?? null;
$user_type = $_GET['user_type'] ?? null;

if ($user_id && $user_type) {
    $count = get_unread_count($user_id, $user_type);
    echo json_encode(['success' => true, 'unread' => $count]);
} else {
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
}
?>
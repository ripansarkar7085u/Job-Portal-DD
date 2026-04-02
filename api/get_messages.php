<?php
/**
 * Get Messages API
 * 
 * Retrieves all messages between two users in a conversation
 */

require_once 'messages_common.php';

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => '',
    'messages' => []
];

try {
    $user1_id = $_GET['user1_id'] ?? null;
    $user1_type = $_GET['user1_type'] ?? null;
    $user2_id = $_GET['user2_id'] ?? null;
    $user2_type = $_GET['user2_type'] ?? null;
    
    // Validate required parameters
    if (!$user1_id || !$user1_type || !$user2_id || !$user2_type) {
        throw new Exception('Missing required parameters: user1_id, user1_type, user2_id, user2_type');
    }
    
    // Validate types
    if (!in_array($user1_type, ['user', 'company']) || !in_array($user2_type, ['user', 'company'])) {
        throw new Exception('Invalid user types. Must be "user" or "company"');
    }
    
    // Validate IDs are positive integers
    if (!is_numeric($user1_id) || $user1_id <= 0 || !is_numeric($user2_id) || $user2_id <= 0) {
        throw new Exception('Invalid user IDs');
    }
    
    // Get messages
    $messages = get_messages($user1_id, $user1_type, $user2_id, $user2_type);
    
    $response['success'] = true;
    $response['messages'] = $messages;
    $response['message'] = 'Messages retrieved successfully';
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>

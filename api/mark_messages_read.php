<?php
/**
 * Mark Messages as Read API
 * 
 * Marks messages as read. Supports:
 * - Single message marking
 * - Bulk marking (all messages from a conversation)
 */

require_once 'messages_common.php';

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => '',
    'marked_count' => 0
];

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $sender_id = $input['sender_id'] ?? null;
    $sender_type = $input['sender_type'] ?? null;
    $receiver_id = $input['receiver_id'] ?? null;
    $receiver_type = $input['receiver_type'] ?? null;
    $mark_all = $input['mark_all'] ?? false;
    
    // Validate required parameters
    if (!$sender_id || !$sender_type || !$receiver_id || !$receiver_type) {
        throw new Exception('Missing required parameters: sender_id, sender_type, receiver_id, receiver_type');
    }
    
    if (!in_array($sender_type, ['user', 'company']) || !in_array($receiver_type, ['user', 'company'])) {
        throw new Exception('Invalid sender_type or receiver_type. Must be "user" or "company"');
    }
    
    if ($mark_all) {
        // Mark all messages in the conversation as read
        $success = mark_conversation_read($sender_id, $sender_type, $receiver_id, $receiver_type);
    } else {
        // Mark messages from sender to receiver as read (original behavior)
        $success = mark_as_read($sender_id, $sender_type, $receiver_id, $receiver_type);
    }
    
    if ($success) {
        global $conn;
        $response['marked_count'] = $conn->affected_rows;
        $response['success'] = true;
        $response['message'] = 'Messages marked as read successfully';
    } else {
        throw new Exception('Failed to mark messages as read');
    }
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

echo json_encode($response);

// Mark all messages in a conversation as read
function mark_conversation_read($user1_id, $user1_type, $user2_id, $user2_type) {
    global $conn;
    
    // Mark all unread messages where user1 is the receiver
    $stmt = $conn->prepare("UPDATE messages 
        SET is_read=1 
        WHERE receiver_id=? 
        AND receiver_type=? 
        AND sender_id=? 
        AND sender_type=? 
        AND is_read=0");
        
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("isis", $user1_id, $user1_type, $user2_id, $user2_type);
    $success = $stmt->execute();
    $stmt->close();
    
    return $success;
}
?>

<?php
/**
 * Send Message API
 * 
 * Sends a message from one user to another
 */

require_once 'messages_common.php';

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => '',
    'message_id' => null
];

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        throw new Exception('Invalid JSON input');
    }
    
    $sender_id = $input['sender_id'] ?? null;
    $sender_type = $input['sender_type'] ?? null;
    $receiver_id = $input['receiver_id'] ?? null;
    $receiver_type = $input['receiver_type'] ?? null;
    $message = $input['message'] ?? null;
    
    // Validate required parameters
    if (!$sender_id || !$sender_type || !$receiver_id || !$receiver_type || !$message) {
        throw new Exception('Missing required parameters');
    }
    
    // Validate types
    if (!in_array($sender_type, ['user', 'company']) || !in_array($receiver_type, ['user', 'company'])) {
        throw new Exception('Invalid sender_type or receiver_type. Must be "user" or "company"');
    }
    
    // Validate IDs are positive integers
    if (!is_numeric($sender_id) || $sender_id <= 0 || !is_numeric($receiver_id) || $receiver_id <= 0) {
        throw new Exception('Invalid sender_id or receiver_id');
    }
    
    // Validate message content
    $message = trim($message);
    if (empty($message)) {
        throw new Exception('Message cannot be empty');
    }
    
    // Check message length (prevent abuse)
    if (strlen($message) > 5000) {
        throw new Exception('Message is too long (maximum 5000 characters)');
    }
    
    // Verify sender exists
    if (!verify_user_exists($sender_id, $sender_type)) {
        throw new Exception('Sender does not exist');
    }
    
    // Verify receiver exists
    if (!verify_user_exists($receiver_id, $receiver_type)) {
        throw new Exception('Receiver does not exist');
    }
    
    // Send the message
    $message_id = send_message($sender_id, $sender_type, $receiver_id, $receiver_type, $message);
    
    if ($message_id) {
        $response['success'] = true;
        $response['message'] = 'Message sent successfully';
        $response['message_id'] = $message_id;
    } else {
        throw new Exception('Failed to send message');
    }
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>

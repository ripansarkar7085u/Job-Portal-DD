<?php
require_once '../config/database.php';

function send_message($sender_id, $sender_type, $receiver_id, $receiver_type, $message) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, sender_type, receiver_id, receiver_type, message) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param("issis", $sender_id, $sender_type, $receiver_id, $receiver_type, $message);
    $success = $stmt->execute();
    $insert_id = $success ? $conn->insert_id : false;
    $stmt->close();
    return $insert_id;
}

function get_messages($user1_id, $user1_type, $user2_id, $user2_type) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM messages WHERE (sender_id=? AND sender_type=? AND receiver_id=? AND receiver_type=?) OR (sender_id=? AND sender_type=? AND receiver_id=? AND receiver_type=?) ORDER BY created_at ASC");
    if (!$stmt) {
        return [];
    }
    $stmt->bind_param("isisiisi", $user1_id, $user1_type, $user2_id, $user2_type, $user2_id, $user2_type, $user1_id, $user1_type);
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    $stmt->close();
    return $messages;
}

function mark_as_read($sender_id, $sender_type, $receiver_id, $receiver_type) {
    global $conn;
    $stmt = $conn->prepare("UPDATE messages SET is_read=1 WHERE sender_id=? AND sender_type=? AND receiver_id=? AND receiver_type=?");
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param("isis", $sender_id, $sender_type, $receiver_id, $receiver_type);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

function verify_user_exists($user_id, $user_type) {
    global $conn;
    
    $table = ($user_type === 'user') ? 'users' : 'companies';
    $stmt = $conn->prepare("SELECT 1 FROM {$table} WHERE id = ? LIMIT 1");
    
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    
    return $exists;
}
?>

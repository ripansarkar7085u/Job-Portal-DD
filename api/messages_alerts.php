<?php


require_once '../config/database.php';

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => '',
    'unread_count' => 0
];

try {
    // Get parameters
    $user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
    $user_type = isset($_GET['user_type']) ? $_GET['user_type'] : '';
    
    // Validate parameters
    if ($user_id <= 0) {
        throw new Exception('Invalid user_id parameter');
    }
    
    if (!in_array($user_type, ['user', 'company'])) {
        throw new Exception('Invalid user_type parameter. Must be "user" or "company"');
    }
    
    // Get unread count using the helper function
    $unread = get_unread_count($user_id, $user_type);
    
    $response['success'] = true;
    $response['unread_count'] = $unread;
    $response['message'] = 'Unread count retrieved successfully';
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

echo json_encode($response);

function get_unread_count($user_id, $user_type) {
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) as unread FROM messages WHERE receiver_id=? AND receiver_type=? AND is_read=0");
    if (!$stmt) {
        return 0;
    }
    $stmt->bind_param("is", $user_id, $user_type);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['unread'] ?? 0;
}
?>

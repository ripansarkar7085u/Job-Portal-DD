<?php
/**
 * Get Conversations API
 * 
 * Returns all conversations for a user or company with:
 * - Last message preview
 * - Unread message count
 * - Last message timestamp
 * - Ordered by most recent activity
 */

require_once '../config/database.php';

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => '',
    'conversations' => []
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
    
    // Build query based on user type
    if ($user_type === 'user') {
        // User viewing conversations with companies
        $query = "SELECT 
            CASE 
                WHEN m.sender_type = 'company' THEN m.sender_id
                ELSE m.receiver_id
            END as other_id,
            'company' as other_type,
            c.company_name as other_name,
            MAX(m.created_at) AS last_message_at,
            SUBSTRING_INDEX(GROUP_CONCAT(m.message ORDER BY m.created_at DESC SEPARATOR '\n'), '\n', 1) AS last_message,
            SUM(CASE WHEN m.receiver_id = ? AND m.receiver_type = 'user' AND m.is_read = 0 THEN 1 ELSE 0 END) as unread_count
            FROM messages m
            INNER JOIN companies c ON c.id = CASE 
                WHEN m.sender_type = 'company' THEN m.sender_id
                ELSE m.receiver_id
            END
            WHERE (m.sender_id = ? AND m.sender_type = 'user') 
               OR (m.receiver_id = ? AND m.receiver_type = 'user')
            GROUP BY other_id, c.company_name
            ORDER BY last_message_at DESC";
        
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception('Database error: ' . $conn->error);
        }
        
        $stmt->bind_param('iii', $user_id, $user_id, $user_id);
        
    } else {
        // Company viewing conversations with users
        $query = "SELECT 
            CASE 
                WHEN m.sender_type = 'user' THEN m.sender_id
                ELSE m.receiver_id
            END as other_id,
            'user' as other_type,
            u.full_name as other_name,
            MAX(m.created_at) AS last_message_at,
            SUBSTRING_INDEX(GROUP_CONCAT(m.message ORDER BY m.created_at DESC SEPARATOR '\n'), '\n', 1) AS last_message,
            SUM(CASE WHEN m.receiver_id = ? AND m.receiver_type = 'company' AND m.is_read = 0 THEN 1 ELSE 0 END) as unread_count
            FROM messages m
            INNER JOIN users u ON u.id = CASE 
                WHEN m.sender_type = 'user' THEN m.sender_id
                ELSE m.receiver_id
            END
            WHERE (m.sender_id = ? AND m.sender_type = 'company') 
               OR (m.receiver_id = ? AND m.receiver_type = 'company')
            GROUP BY other_id, u.full_name
            ORDER BY last_message_at DESC";
        
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception('Database error: ' . $conn->error);
        }
        
        $stmt->bind_param('iii', $user_id, $user_id, $user_id);
    }
    
    // Execute query
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $response['conversations'][] = [
            'other_id' => (int)$row['other_id'],
            'other_type' => $row['other_type'],
            'other_name' => $row['other_name'],
            'last_message' => $row['last_message'],
            'last_message_at' => $row['last_message_at'],
            'unread_count' => (int)$row['unread_count']
        ];
    }
    
    $stmt->close();
    
    $response['success'] = true;
    $response['message'] = 'Conversations retrieved successfully';
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>

<?php
/**
 * Debug Script - Check Messages Table and Data
 */

require_once '../config/database.php';

header('Content-Type: application/json');

$debug = [
    'success' => true,
    'checks' => []
];

try {
    // Check if messages table exists
    $tableCheck = $conn->query("SHOW TABLES LIKE 'messages'");
    $debug['checks']['messages_table_exists'] = $tableCheck && $tableCheck->num_rows > 0;
    
    if ($debug['checks']['messages_table_exists']) {
        // Get table structure
        $structure = $conn->query("DESCRIBE messages");
        $columns = [];
        while ($row = $structure->fetch_assoc()) {
            $columns[] = $row['Field'];
        }
        $debug['checks']['table_columns'] = $columns;
        
        // Count total messages
        $countResult = $conn->query("SELECT COUNT(*) as total FROM messages");
        $count = $countResult->fetch_assoc();
        $debug['checks']['total_messages'] = (int)$count['total'];
        
        // Get recent messages
        $recent = $conn->query("SELECT id, sender_id, sender_type, receiver_id, receiver_type, LEFT(message, 50) as message_preview, is_read, created_at FROM messages ORDER BY created_at DESC LIMIT 10");
        $debug['checks']['recent_messages'] = [];
        while ($row = $recent->fetch_assoc()) {
            $debug['checks']['recent_messages'][] = $row;
        }
        
        // Check indexes
        $indexes = $conn->query("SHOW INDEX FROM messages");
        $debug['checks']['indexes'] = [];
        while ($row = $indexes->fetch_assoc()) {
            $debug['checks']['indexes'][] = $row['Key_name'];
        }
        $debug['checks']['indexes'] = array_unique($debug['checks']['indexes']);
        
    } else {
        $debug['error'] = 'Messages table does not exist!';
        $debug['success'] = false;
    }
    
    // Check if old user_messages table still exists
    $oldTableCheck = $conn->query("SHOW TABLES LIKE 'user_messages'");
    $debug['checks']['old_user_messages_exists'] = $oldTableCheck && $oldTableCheck->num_rows > 0;
    
    if ($debug['checks']['old_user_messages_exists']) {
        $oldCount = $conn->query("SELECT COUNT(*) as total FROM user_messages");
        $oldCountData = $oldCount->fetch_assoc();
        $debug['checks']['old_user_messages_count'] = (int)$oldCountData['total'];
    }
    
} catch (Exception $e) {
    $debug['success'] = false;
    $debug['error'] = $e->getMessage();
}

echo json_encode($debug, JSON_PRETTY_PRINT);
?>

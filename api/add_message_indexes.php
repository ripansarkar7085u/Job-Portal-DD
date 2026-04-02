<?php
/**
 * Database Index Management Script
 * 
 * This script ensures all necessary indexes exist on the messages table
 * for optimal query performance.
 */

require_once '../config/database.php';

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => '',
    'indexes_added' => []
];

try {
    // Check if messages table exists
    $tableCheck = $conn->query("SHOW TABLES LIKE 'messages'");
    if (!$tableCheck || $tableCheck->num_rows === 0) {
        throw new Exception('Messages table does not exist. Run migration script first.');
    }
    
    // Add indexes (CREATE INDEX IF NOT EXISTS is not supported in all MySQL versions, so we check first)
    $indexesToAdd = [
        [
            'name' => 'idx_receiver_unread',
            'query' => 'CREATE INDEX idx_receiver_unread ON messages (receiver_id, receiver_type, is_read)',
            'description' => 'Index for fetching unread message counts'
        ],
        [
            'name' => 'idx_conversation',
            'query' => 'CREATE INDEX idx_conversation ON messages (sender_id, sender_type, receiver_id, receiver_type, created_at)',
            'description' => 'Index for fetching conversation messages'
        ],
        [
            'name' => 'idx_created_at',
            'query' => 'CREATE INDEX idx_created_at ON messages (created_at)',
            'description' => 'Index for sorting by time'
        ]
    ];
    
    foreach ($indexesToAdd as $index) {
        // Check if index exists
        $checkIndex = $conn->query("SHOW INDEX FROM messages WHERE Key_name = '{$index['name']}'");
        
        if ($checkIndex && $checkIndex->num_rows === 0) {
            // Index doesn't exist, create it
            if ($conn->query($index['query'])) {
                $response['indexes_added'][] = $index['name'] . ': ' . $index['description'];
            } else {
                // If error is "Duplicate key name", ignore it
                if ($conn->errno !== 1061) {
                    throw new Exception("Failed to create index {$index['name']}: " . $conn->error);
                }
            }
        } else {
            $response['indexes_added'][] = $index['name'] . ': Already exists';
        }
    }
    
    $response['success'] = true;
    $response['message'] = 'All indexes verified/created successfully';
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Failed to add indexes: ' . $e->getMessage();
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>

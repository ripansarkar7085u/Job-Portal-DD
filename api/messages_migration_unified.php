<?php
/**
 * Unified Messages Migration Script
 * 
 * This script migrates data from the old user_messages table to the unified messages table.
 * It ensures data integrity and adds necessary indexes for optimal performance.
 * 
 * Run this script once to migrate from the old schema to the new unified schema.
 */

require_once '../config/database.php';

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => '',
    'steps' => []
];

try {
    // Step 1: Create messages table if it doesn't exist
    $createMessagesTable = "CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        sender_id INT NOT NULL,
        sender_type ENUM('user','company') NOT NULL,
        receiver_id INT NOT NULL,
        receiver_type ENUM('user','company') NOT NULL,
        message TEXT NOT NULL,
        is_read TINYINT(1) DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_receiver_unread (receiver_id, receiver_type, is_read),
        INDEX idx_conversation (sender_id, sender_type, receiver_id, receiver_type, created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if ($conn->query($createMessagesTable)) {
        $response['steps'][] = 'Messages table created/verified successfully';
    } else {
        throw new Exception('Failed to create messages table: ' . $conn->error);
    }
    
    // Step 2: Check if user_messages table exists
    $checkUserMessages = $conn->query("SHOW TABLES LIKE 'user_messages'");
    $userMessagesExists = $checkUserMessages && $checkUserMessages->num_rows > 0;
    
    if ($userMessagesExists) {
        $response['steps'][] = 'Found user_messages table';
        
        // Step 3: Count existing records
        $countOld = $conn->query("SELECT COUNT(*) as count FROM user_messages");
        $oldCount = $countOld ? $countOld->fetch_assoc()['count'] : 0;
        $response['steps'][] = "Found {$oldCount} records in user_messages";
        
        if ($oldCount > 0) {
            // Step 4: Migrate data from user_messages to messages
            $migrateQuery = "INSERT INTO messages (sender_id, sender_type, receiver_id, receiver_type, message, is_read, created_at)
                SELECT 
                    CASE 
                        WHEN um.sender_type = 'user' THEN um.user_id
                        ELSE um.company_id
                    END as sender_id,
                    um.sender_type,
                    CASE 
                        WHEN um.sender_type = 'user' THEN um.company_id
                        ELSE um.user_id
                    END as receiver_id,
                    CASE 
                        WHEN um.sender_type = 'user' THEN 'company'
                        ELSE 'user'
                    END as receiver_type,
                    um.message_text as message,
                    um.is_read,
                    um.created_at
                FROM user_messages um
                WHERE NOT EXISTS (
                    SELECT 1 FROM messages m 
                    WHERE m.sender_id = CASE WHEN um.sender_type = 'user' THEN um.user_id ELSE um.company_id END
                    AND m.sender_type = um.sender_type
                    AND m.receiver_id = CASE WHEN um.sender_type = 'user' THEN um.company_id ELSE um.user_id END
                    AND m.receiver_type = CASE WHEN um.sender_type = 'user' THEN 'company' ELSE 'user' END
                    AND m.message = um.message_text
                    AND m.created_at = um.created_at
                )";
            
            if ($conn->query($migrateQuery)) {
                $migratedCount = $conn->affected_rows;
                $response['steps'][] = "Migrated {$migratedCount} records to messages table";
            } else {
                throw new Exception('Migration failed: ' . $conn->error);
            }
            
            // Step 5: Verify migration
            $countNew = $conn->query("SELECT COUNT(*) as count FROM messages");
            $newCount = $countNew ? $countNew->fetch_assoc()['count'] : 0;
            $response['steps'][] = "Messages table now has {$newCount} total records";
        }
        
        // Step 6: Rename old table as backup
        $backupTableName = 'user_messages_backup_' . date('Ymd_His');
        if ($conn->query("RENAME TABLE user_messages TO {$backupTableName}")) {
            $response['steps'][] = "Old table backed up as {$backupTableName}";
        }
        
    } else {
        $response['steps'][] = 'No user_messages table found - nothing to migrate';
    }
    
    // Step 7: Ensure indexes exist (in case table was created without them)
    $indexQueries = [
        "CREATE INDEX IF NOT EXISTS idx_receiver_unread ON messages (receiver_id, receiver_type, is_read)",
        "CREATE INDEX IF NOT EXISTS idx_conversation ON messages (sender_id, sender_type, receiver_id, receiver_type, created_at)"
    ];
    
    foreach ($indexQueries as $indexQuery) {
        $conn->query($indexQuery);
    }
    $response['steps'][] = 'Database indexes verified/created';
    
    // Step 8: Final count
    $finalCount = $conn->query("SELECT COUNT(*) as count FROM messages");
    $finalTotal = $finalCount ? $finalCount->fetch_assoc()['count'] : 0;
    
    $response['success'] = true;
    $response['message'] = "Migration completed successfully! Total messages: {$finalTotal}";
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Migration failed: ' . $e->getMessage();
    $response['steps'][] = 'ERROR: ' . $e->getMessage();
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>

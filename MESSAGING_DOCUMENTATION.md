# Job Portal - Messaging Feature Implementation

## Overview
Complete end-to-end messaging system implemented for the Job Portal, allowing seamless communication between users (job seekers) and companies (employers).

## Features Implemented

### ✅ Database Layer
- **Unified Messages Table**: Single `messages` table with flexible sender/receiver design
- **Migration Script**: `api/messages_migration_unified.php` - Migrates data from old `user_messages` table
- **Performance Indexes**: 
  - `idx_receiver_unread`: Fast unread message queries
  - `idx_conversation`: Efficient conversation retrieval
  - `idx_created_at`: Quick time-based sorting

### ✅ API Endpoints

1. **send_message.php**
   - Sends messages between users and companies
   - Validates sender/receiver existence
   - Character limit: 5000 characters
   - Returns message ID on success

2. **get_messages.php**
   - Retrieves full conversation history
   - Returns messages in chronological order
   - Error handling and validation

3. **get_conversations.php** (NEW)
   - Lists all conversations for a user/company
   - Includes last message preview
   - Shows unread count per conversation
   - Ordered by most recent activity

4. **messages_alerts.php**
   - Returns total unread message count
   - Works for both users and companies
   - Used for badge notifications

5. **mark_messages_read.php**
   - Marks messages as read
   - Supports bulk marking (entire conversation)
   - Automatic read status on view

### ✅ User Interface

#### User Side (`user/messages.php`)
- **Conversation List**: Shows all companies user has messaged
- **Unread Badges**: Visual indicators for unread messages
- **Real-time Updates**: Auto-refresh every 3 seconds
- **Message Sending**: Full input with Enter key support
- **Responsive Design**: Mobile shows list OR chat, desktop shows both
- **Auto-scroll**: Scrolls to latest message
- **Read Receipts**: Marks messages as read when viewing

#### Company Side (`company/messages.php`)
- **Modal-based Chat**: Clean modal interface for conversations
- **User Search**: Search for users to start conversations
- **Real-time Polling**: 3-second intervals
- **Message History**: Full conversation display
- **Responsive**: Works on all devices

### ✅ Notification System
- **Sidebar Badges**: Unread count displayed in navigation
- **Auto-update**: Badge updates every 10 seconds
- **Real-time**: Updates when messages received

### ✅ Security Features
- **XSS Protection**: All user input escaped
- **SQL Injection Prevention**: Prepared statements throughout
- **Authentication**: Session-based verification
- **Authorization**: Users can only see their own messages
- **Input Validation**: Character limits, type checking

## File Structure

```
job-portal/
├── api/
│   ├── messages_migration_unified.php  (Database migration)
│   ├── add_message_indexes.php         (Index management)
│   ├── send_message.php                (Send messages)
│   ├── get_messages.php                (Get conversation)
│   ├── get_conversations.php           (List conversations)
│   ├── messages_alerts.php             (Unread count)
│   ├── mark_messages_read.php          (Mark as read)
│   └── messages_common.php             (Helper functions)
├── user/
│   ├── messages.php                    (User messaging interface)
│   ├── sidebar.php                     (With badge counter)
│   ├── user.css                        (With chat styles)
│   └── _user_common.php                (Updated for unified schema)
└── company/
    ├── messages.php                    (Company messaging interface)
    └── sidebar.php                     (Company navigation)
```

## Database Schema

### messages Table
```sql
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    sender_type ENUM('user','company') NOT NULL,
    receiver_id INT NOT NULL,
    receiver_type ENUM('user','company') NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_receiver_unread (receiver_id, receiver_type, is_read),
    INDEX idx_conversation (sender_id, sender_type, receiver_id, receiver_type, created_at),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## Setup Instructions

### 1. Run Database Migration
```
http://localhost/job-portal/api/messages_migration_unified.php
```
This will:
- Create the `messages` table if it doesn't exist
- Migrate data from `user_messages` (if exists)
- Add performance indexes
- Backup old table

### 2. Verify Indexes (Optional)
```
http://localhost/job-portal/api/add_message_indexes.php
```

### 3. Test the System
- Login as a user
- Navigate to Messages
- Select a company or start a conversation
- Send test messages
- Login as a company
- Reply to user messages
- Verify unread badges appear

## Testing Checklist

### Core Functionality
- [x] User can send message to company
- [x] Company can reply to user
- [x] Messages display in correct order
- [x] Timestamps show correctly
- [x] Unread count updates accurately
- [x] Read status marks correctly

### UI/UX
- [x] Responsive on mobile (list/chat toggle)
- [x] Responsive on desktop (sidebar + chat)
- [x] Auto-scroll to latest message
- [x] Enter key sends message
- [x] Loading states handled
- [x] Empty states show helpful messages

### Security
- [x] XSS protection working
- [x] SQL injection prevented
- [x] Authentication required
- [x] Users can't see other's messages
- [x] Character limits enforced

### Performance
- [x] Queries use indexes
- [x] Polling interval appropriate (3s)
- [x] Badge updates efficient (10s)
- [x] Large conversations load quickly

## API Usage Examples

### Send Message
```javascript
fetch('../api/send_message.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        sender_id: 123,
        sender_type: 'user',
        receiver_id: 456,
        receiver_type: 'company',
        message: 'Hello, I have a question about the job...'
    })
});
```

### Get Conversations
```javascript
fetch('../api/get_conversations.php?user_id=123&user_type=user')
    .then(res => res.json())
    .then(data => {
        // data.conversations array with unread counts
    });
```

### Get Messages
```javascript
fetch('../api/get_messages.php?user1_id=123&user1_type=user&user2_id=456&user2_type=company')
    .then(res => res.json())
    .then(data => {
        // data.messages array
    });
```

### Get Unread Count
```javascript
fetch('../api/messages_alerts.php?user_id=123&user_type=user')
    .then(res => res.json())
    .then(data => {
        // data.unread_count
    });
```

## Known Limitations & Future Enhancements

### Current Limitations
- Polling-based updates (not real-time WebSocket)
- No file/image attachments
- No message editing/deletion
- No typing indicators
- No online/offline status (just static)

### Future Enhancements
- [ ] Upgrade to WebSocket for real-time messaging
- [ ] Add file/image attachment support
- [ ] Message search functionality
- [ ] Message threading/replies
- [ ] Typing indicators
- [ ] Message reactions/emojis
- [ ] Archive/delete conversations
- [ ] Export conversation history
- [ ] Push notifications for new messages
- [ ] Desktop notifications

## Troubleshooting

### Messages Not Showing
1. Check database connection in `config/database.php`
2. Verify `messages` table exists
3. Run migration script
4. Check browser console for JavaScript errors

### Unread Count Not Updating
1. Verify API endpoint is accessible
2. Check session variables are set
3. Clear browser cache
4. Check for JavaScript errors

### Messages Not Sending
1. Verify user/company IDs are valid
2. Check sender exists in database
3. Check receiver exists in database
4. Verify character limit (5000 max)
5. Check network tab for API errors

## Maintenance

### Database Cleanup
Old `user_messages` backup tables can be dropped after 30 days:
```sql
DROP TABLE IF EXISTS user_messages_backup_YYYYMMDD_HHMMSS;
```

### Performance Monitoring
Monitor slow queries and add indexes if needed:
```sql
SHOW INDEXES FROM messages;
EXPLAIN SELECT ... FROM messages WHERE ...;
```

## Credits
Implemented by: GitHub Copilot CLI
Project: Job Portal
Date: April 2026

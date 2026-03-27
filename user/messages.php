<?php
require_once __DIR__ . '/_user_common.php';

$userId = user_require_login();
user_ensure_messages_table($conn);

$conversations = [];
$stmtConversations = $conn->prepare("SELECT m.company_id, c.company_name, MAX(m.created_at) AS last_message_at,
    SUBSTRING_INDEX(GROUP_CONCAT(m.message_text ORDER BY m.created_at DESC SEPARATOR '\\n'), '\\n', 1) AS last_message
    FROM user_messages m
    INNER JOIN companies c ON c.id = m.company_id
    WHERE m.user_id = ?
    GROUP BY m.company_id, c.company_name
    ORDER BY last_message_at DESC");

if ($stmtConversations) {
    $stmtConversations->bind_param('i', $userId);
    $stmtConversations->execute();
    $result = $stmtConversations->get_result();
    while ($result && ($row = $result->fetch_assoc())) {
        $conversations[] = $row;
    }
    $stmtConversations->close();
}

$activeCompanyId = isset($_GET['company_id']) ? (int) $_GET['company_id'] : 0;
// We no longer auto-select so mobile can show the chat list first
// if ($activeCompanyId <= 0 && !empty($conversations)) {
//     $activeCompanyId = (int) $conversations[0]['company_id'];
// }

$messages = [];
$activeCompanyName = '';

if ($activeCompanyId > 0) {
    $stmtMessages = $conn->prepare("SELECT m.message_text, m.sender_type, m.created_at, c.company_name
        FROM user_messages m
        INNER JOIN companies c ON c.id = m.company_id
        WHERE m.user_id = ? AND m.company_id = ?
        ORDER BY m.created_at ASC");

    if ($stmtMessages) {
        $stmtMessages->bind_param('ii', $userId, $activeCompanyId);
        $stmtMessages->execute();
        $result = $stmtMessages->get_result();
        while ($result && ($row = $result->fetch_assoc())) {
            $messages[] = $row;
            if ($activeCompanyName === '') {
                $activeCompanyName = (string) $row['company_name'];
            }
        }
        $stmtMessages->close();
    }
}

if ($activeCompanyName === '' && !empty($conversations)) {
    $activeCompanyName = (string) $conversations[0]['company_name'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>Messages</title>
    <link rel="stylesheet" href="user.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

</head>

<body>

    <div class="user-container" id="userDashboard">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <header class="main-header">
                <div class="header-left">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1 class="page-title">Messages</h1>
                </div>
            </header>

            <section class="content-section">

            <div class="chat-wrapper <?php echo $activeCompanyId > 0 ? 'has-active-chat' : ''; ?>">

                <!-- USER LIST -->

                <div class="chat-sidebar">

                    <div class="chat-sidebar-header">
                        <h3>Messages</h3>
                        <div class="chat-search">
                            <i class="bi bi-search"></i>
                            <input type="text" placeholder="Search conversations...">
                        </div>
                    </div>
                    
                    <div class="chat-list">
                    <?php if (empty($conversations)): ?>
                        <div class="p-3 text-muted">No conversations found.</div>
                    <?php else: ?>
                        <?php foreach ($conversations as $conversation): ?>
                            <?php $companyId = (int) $conversation['company_id']; ?>
                            <a href="messages.php?company_id=<?php echo $companyId; ?>" class="chat-user-item <?php echo $companyId === $activeCompanyId ? 'active' : ''; ?>">
                                <div class="chat-user-avatar">
                                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode((string) $conversation['company_name']); ?>&background=0d47a1&color=fff" alt="Avatar">
                                </div>
                                <div class="chat-user-details">
                                    <div class="chat-user-name">
                                        <h6><?php echo user_esc((string) $conversation['company_name']); ?></h6>
                                        <span class="chat-time"><?php echo user_esc(date('M j', strtotime((string) $conversation['last_message_at']))); ?></span>
                                    </div>
                                    <p class="chat-snippet"><?php echo user_esc((string) $conversation['last_message']); ?></p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    </div>
                </div>

                <!-- CHAT AREA -->
                <div class="chat-main">

                    <?php if ($activeCompanyId === 0): ?>
                        <div class="empty-state h-100 d-none d-lg-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-chat-dots" style="font-size: 5rem; color: #e2e8f0; margin-bottom: 20px;"></i>
                            <h4 style="color: #64748b; font-weight: 600;">Your Messages</h4>
                            <p style="color: #94a3b8;">Select a conversation from the sidebar to start chatting</p>
                        </div>
                    <?php else: ?>

                    <!-- HEADER -->
                    <div class="chat-main-header">
                        <div class="chat-active-user">
                            <a href="messages.php" class="d-lg-none me-3 text-dark"><i class="bi bi-arrow-left fs-4"></i></a>
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($activeCompanyName !== '' ? $activeCompanyName : 'Company'); ?>&background=0d47a1&color=fff" alt="Avatar">
                            <div class="chat-active-user-info">
                                <h5><?php echo user_esc($activeCompanyName !== '' ? $activeCompanyName : 'No conversation selected'); ?></h5>
                                <span>Online</span>
                            </div>
                        </div>

                        <div class="chat-actions">
                            <button class="chat-btn" title="Voice Call"><i class="bi bi-telephone"></i></button>
                            <button class="chat-btn" title="Video Call"><i class="bi bi-camera-video"></i></button>
                            <button class="chat-btn" title="More Options"><i class="bi bi-info-circle"></i></button>
                        </div>
                    </div>


                    <!-- MESSAGES -->
                    <div class="chat-messages-area">
                        <?php if (empty($messages)): ?>
                            <div class="msg-bubble msg-received">
                                No messages found for this conversation.
                            </div>
                        <?php else: ?>
                            <?php foreach ($messages as $message): ?>
                                <div class="msg-bubble <?php echo $message['sender_type'] === 'user' ? 'msg-sent' : 'msg-received'; ?>">
                                    <?php echo nl2br(user_esc((string) $message['message_text'])); ?>
                                    <span class="msg-time"><?php echo user_esc(date('H:i', strtotime((string) $message['created_at']))); ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- MESSAGE INPUT -->
                    <div class="chat-input-area">
                        <button class="chat-attach-btn" title="Attach file"><i class="bi bi-paperclip"></i></button>
                        <div class="chat-input-wrapper">
                            <input type="text" placeholder="Type your message here...">
                        </div>
                        <button class="chat-send-btn" title="Send message">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </div>

                    <?php endif; ?>

                </div>

            </div>

            </section>
        </main>
    </div>

</body>

</html>

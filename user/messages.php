<?php
require_once __DIR__ . '/_user_common.php';

$userId = user_require_login();
user_ensure_messages_table($conn);

// Fetch conversations with companies (using unified messages table)
$conversations = [];
$stmtConversations = $conn->prepare("SELECT 
    CASE 
        WHEN m.sender_type = 'company' THEN m.sender_id
        ELSE m.receiver_id
    END as company_id,
    c.company_name,
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
    GROUP BY company_id, c.company_name
    ORDER BY last_message_at DESC");

if ($stmtConversations) {
    $stmtConversations->bind_param('iii', $userId, $userId, $userId);
    $stmtConversations->execute();
    $result = $stmtConversations->get_result();
    while ($result && ($row = $result->fetch_assoc())) {
        $conversations[] = $row;
    }
    $stmtConversations->close();
}

$activeCompanyId = isset($_GET['company_id']) ? (int) $_GET['company_id'] : 0;

$messages = [];
$activeCompanyName = '';

if ($activeCompanyId > 0) {
    // Fetch all messages between user and company
    $stmtMessages = $conn->prepare("SELECT 
        m.message,
        m.sender_type,
        m.created_at,
        c.company_name
        FROM messages m
        INNER JOIN companies c ON c.id = ?
        WHERE ((m.sender_id = ? AND m.sender_type = 'user' AND m.receiver_id = ? AND m.receiver_type = 'company')
           OR (m.sender_id = ? AND m.sender_type = 'company' AND m.receiver_id = ? AND m.receiver_type = 'user'))
        ORDER BY m.created_at ASC");

    if ($stmtMessages) {
        $stmtMessages->bind_param('iiiii', $activeCompanyId, $userId, $activeCompanyId, $activeCompanyId, $userId);
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
    
    // Mark messages from this company as read
    $stmtMarkRead = $conn->prepare("UPDATE messages 
        SET is_read = 1 
        WHERE receiver_id = ? 
        AND receiver_type = 'user' 
        AND sender_id = ? 
        AND sender_type = 'company' 
        AND is_read = 0");
    if ($stmtMarkRead) {
        $stmtMarkRead->bind_param('ii', $userId, $activeCompanyId);
        $stmtMarkRead->execute();
        $stmtMarkRead->close();
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
                            <?php $unreadCount = (int) ($conversation['unread_count'] ?? 0); ?>
                            <div class="chat-user-item <?php echo $companyId === $activeCompanyId ? 'active' : ''; ?>" data-company-id="<?php echo $companyId; ?>" data-company-name="<?php echo user_esc((string) $conversation['company_name']); ?>" data-company-avatar="https://ui-avatars.com/api/?name=<?php echo urlencode((string) $conversation['company_name']); ?>&background=0d47a1&color=fff">
                                <div class="chat-user-avatar">
                                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode((string) $conversation['company_name']); ?>&background=0d47a1&color=fff" alt="Avatar">
                                    <?php if ($unreadCount > 0): ?>
                                        <span class="unread-badge"><?php echo $unreadCount; ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="chat-user-details">
                                    <div class="chat-user-name">
                                        <h6><?php echo user_esc((string) $conversation['company_name']); ?></h6>
                                        <span class="chat-time"><?php echo user_esc(date('M j', strtotime((string) $conversation['last_message_at']))); ?></span>
                                    </div>
                                    <p class="chat-snippet"><?php echo user_esc((string) $conversation['last_message']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    </div>
                </div>

                <div class="chat-main">
                    <div class="empty-state h-100 d-none d-lg-flex flex-column align-items-center justify-content-center">
                        <i class="bi bi-chat-dots" style="font-size: 5rem; color: #e2e8f0; margin-bottom: 20px;"></i>
                        <h4 style="color: #64748b; font-weight: 600;">Your Messages</h4>
                        <p style="color: #94a3b8;">Select a conversation from the sidebar to open chat</p>
                    </div>
                </div>

            </div>

            </section>
        </main>
    </div>

    <div class="chat-modal" id="chatModal" aria-hidden="true" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:1300; align-items:center; justify-content:center; padding:16px;">
        <div class="chat-modal-content" style="width:min(740px,100%);height:min(84vh,720px);background:#fff;border-radius:14px;box-shadow:0 24px 48px rgba(2,10,34,.28);display:flex;flex-direction:column;overflow:hidden;">
            <div class="chat-main-header">
                <div class="chat-active-user">
                    <img id="modalCompanyAvatar" src="" alt="Avatar">
                    <div class="chat-active-user-info">
                        <h5 id="modalCompanyName">Conversation</h5>
                        <span>Live chat</span>
                    </div>
                </div>
                <button class="chat-btn" id="closeChatModal" title="Close"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="chat-messages-area" id="chatMessagesArea"></div>
            <div class="chat-input-area">
                <button class="chat-attach-btn" title="Attach file"><i class="bi bi-paperclip"></i></button>
                <div class="chat-input-wrapper">
                    <input type="text" id="chatInput" placeholder="Type your message here...">
                </div>
                <button class="chat-send-btn" id="chatSendBtn" title="Send message">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
        </div>
    </div>

<script>
const userId = <?php echo json_encode($userId); ?>;
const initialCompanyId = <?php echo json_encode($activeCompanyId); ?>;
const initialCompanyName = <?php echo json_encode($activeCompanyName); ?>;
let chatInterval = null;
let selectedCompanyId = 0;

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text || '';
    return div.innerHTML;
}

function formatTime(dateTimeValue) {
    if (!dateTimeValue || dateTimeValue.length < 16) {
        return '';
    }
    return dateTimeValue.substring(11, 16);
}

async function fetchMessages() {
    if (selectedCompanyId <= 0) {
        return;
    }

    try {
        const endpoint = '../api/get_messages.php?user1_id=' + encodeURIComponent(userId)
            + '&user1_type=user&user2_id=' + encodeURIComponent(selectedCompanyId)
            + '&user2_type=company';

        const res = await fetch(endpoint);
        const data = await res.json();

        if (data.success && data.messages) {
            renderMessages(data.messages);
        }
    } catch (error) {
        console.error('Failed to fetch messages:', error);
    }
}

function renderMessages(messages) {
    const area = document.getElementById('chatMessagesArea');
    if (!area) return;
    
    area.innerHTML = '';

    if (!messages || !messages.length) {
        area.innerHTML = '<div class="msg-bubble msg-received">No messages found for this conversation.</div>';
        return;
    }

    messages.forEach((msg) => {
        const bubble = document.createElement('div');
        const isSent = msg.sender_type === 'user';
        bubble.className = 'msg-bubble ' + (isSent ? 'msg-sent' : 'msg-received');
        bubble.innerHTML = escapeHtml(msg.message).replace(/\n/g, '<br>') 
            + '<span class="msg-time">' + escapeHtml(formatTime(msg.created_at)) + '</span>';
        area.appendChild(bubble);
    });

    area.scrollTop = area.scrollHeight;
}

async function sendMessage() {
    if (selectedCompanyId <= 0) {
        return;
    }

    const input = document.getElementById('chatInput');
    if (!input) return;
    
    const msg = input.value.trim();
    if (!msg) {
        return;
    }

    const sendBtn = document.getElementById('chatSendBtn');
    if (sendBtn) {
        sendBtn.disabled = true;
    }

    try {
        const res = await fetch('../api/send_message.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                sender_id: userId,
                sender_type: 'user',
                receiver_id: selectedCompanyId,
                receiver_type: 'company',
                message: msg
            })
        });

        const data = await res.json();
        if (data.success) {
            input.value = '';
            await fetchMessages();
        } else {
            alert('Failed to send message. Please try again.');
        }
    } catch (error) {
        console.error('Failed to send message:', error);
        alert('Failed to send message. Please try again.');
    } finally {
        if (sendBtn) {
            sendBtn.disabled = false;
        }
    }
}

function startPolling() {
    if (chatInterval) {
        clearInterval(chatInterval);
    }
    if (selectedCompanyId > 0) {
        chatInterval = setInterval(fetchMessages, 3000);
    }
}

function stopPolling() {
    if (chatInterval) {
        clearInterval(chatInterval);
        chatInterval = null;
    }
}

function setActiveCompanyVisual(companyId) {
    document.querySelectorAll('.chat-user-item').forEach((item) => {
        item.classList.toggle('active', Number(item.dataset.companyId) === Number(companyId));
    });
}

async function openChatModal(companyId, companyName, companyAvatar) {
    selectedCompanyId = Number(companyId);
    setActiveCompanyVisual(selectedCompanyId);

    document.getElementById('modalCompanyName').textContent = companyName || 'Conversation';
    document.getElementById('modalCompanyAvatar').src = companyAvatar || '';

    const modal = document.getElementById('chatModal');
    modal.style.display = 'flex';
    modal.setAttribute('aria-hidden', 'false');

    await fetchMessages();
    startPolling();
}

function closeChatModal() {
    const modal = document.getElementById('chatModal');
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
    stopPolling();
}

document.addEventListener('DOMContentLoaded', () => {
    const sendBtn = document.getElementById('chatSendBtn');
    const input = document.getElementById('chatInput');

    if (sendBtn) {
        sendBtn.addEventListener('click', sendMessage);
    }

    if (input) {
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    }

    document.querySelectorAll('.chat-user-item').forEach((item) => {
        item.addEventListener('click', () => {
            openChatModal(item.dataset.companyId, item.dataset.companyName, item.dataset.companyAvatar);
        });
    });

    const closeBtn = document.getElementById('closeChatModal');
    if (closeBtn) {
        closeBtn.addEventListener('click', closeChatModal);
    }

    const modal = document.getElementById('chatModal');
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target.id === 'chatModal') {
                closeChatModal();
            }
        });
    }

    if (initialCompanyId > 0) {
        const initialAvatar = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(initialCompanyName || 'Company') + '&background=0d47a1&color=fff';
        openChatModal(initialCompanyId, initialCompanyName, initialAvatar);
    }
});

// Clean up on page unload
window.addEventListener('beforeunload', stopPolling);
</script>

</body>

</html>

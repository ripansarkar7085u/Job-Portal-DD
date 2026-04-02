<?php
require_once '../config/database.php';
require_once '../api/messages_common.php';

$companyId = $_SESSION['company_id'] ?? 0;
if (!$companyId) {
    header('Location: login.php');
    exit;
}

$applicants = [];
$stmt = $conn->prepare("SELECT
    CASE
        WHEN m.sender_type = 'user' THEN m.sender_id
        ELSE m.receiver_id
    END AS user_id,
    u.full_name,
    MAX(m.created_at) AS last_message_at,
    SUBSTRING_INDEX(GROUP_CONCAT(m.message ORDER BY m.created_at DESC SEPARATOR '\n'), '\n', 1) AS last_message
    FROM messages m
    INNER JOIN users u ON u.id = CASE
        WHEN m.sender_type = 'user' THEN m.sender_id
        ELSE m.receiver_id
    END
    WHERE (m.sender_id = ? AND m.sender_type = 'company')
       OR (m.receiver_id = ? AND m.receiver_type = 'company')
    GROUP BY user_id, u.full_name
    ORDER BY last_message_at DESC");
if ($stmt) {
    $stmt->bind_param('ii', $companyId, $companyId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($result && ($row = $result->fetch_assoc())) {
        $applicants[] = $row;
    }
    $stmt->close();
}

$activeUserId = isset($_GET['user_id']) ? (int) $_GET['user_id'] : 0;
$activeUserName = '';
$activeUserAvatar = '';

if ($activeUserId > 0) {
    $stmtActive = $conn->prepare('SELECT full_name FROM users WHERE id = ? LIMIT 1');
    if ($stmtActive) {
        $stmtActive->bind_param('i', $activeUserId);
        $stmtActive->execute();
        $resActive = $stmtActive->get_result();
        if ($resActive && ($rowActive = $resActive->fetch_assoc())) {
            $activeUserName = (string) $rowActive['full_name'];
            $activeUserAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($activeUserName) . '&background=0d47a1&color=fff';
        }
        $stmtActive->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Messages</title>
    <link rel="stylesheet" href="css/company.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        .messages-shell {
            display: grid;
            grid-template-columns: minmax(300px, 360px) 1fr;
            gap: 16px;
            height: calc(100vh - 130px);
        }

        .whatsapp-list {
            background: #fff;
            border: 1px solid #e8ecf3;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 6px 16px rgba(16, 24, 40, 0.06);
        }

        .whatsapp-list-header {
            padding: 14px;
            border-bottom: 1px solid #edf1f7;
            background: #f8fbff;
        }

        .whatsapp-list-header h3 {
            margin: 0 0 10px;
            font-size: 1.05rem;
            font-weight: 700;
            color: #12213a;
        }

        .chat-search {
            position: relative;
        }

        .chat-search i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #7b8798;
        }

        .chat-search input {
            width: 100%;
            border: 1px solid #d9e2ef;
            border-radius: 10px;
            padding: 9px 12px 9px 33px;
            outline: none;
            background: #fff;
        }

        .chat-search input:focus {
            border-color: #0d47a1;
            box-shadow: 0 0 0 3px rgba(13, 71, 161, 0.12);
        }

        .search-results {
            margin-top: 8px;
            border: 1px solid #e0e6ef;
            border-radius: 10px;
            background: #fff;
            max-height: 220px;
            overflow-y: auto;
            box-shadow: 0 8px 20px rgba(16, 24, 40, 0.12);
        }

        .search-user-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px;
            cursor: pointer;
        }

        .search-user-item:hover {
            background: #f4f8ff;
        }

        .search-user-item img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .chat-list {
            overflow-y: auto;
            flex: 1;
        }

        .chat-user-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 12px;
            border-bottom: 1px solid #f1f4f8;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .chat-user-item:hover,
        .chat-user-item.active {
            background: #eaf2ff;
        }

        .chat-user-avatar img {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
        }

        .chat-user-details {
            flex: 1;
            min-width: 0;
        }

        .chat-user-name {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
        }

        .chat-user-name h6 {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 700;
            color: #1d2939;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chat-time {
            font-size: 0.78rem;
            color: #667085;
            flex-shrink: 0;
        }

        .chat-snippet {
            margin: 2px 0 0;
            color: #667085;
            font-size: 0.85rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chat-placeholder {
            border: 1px dashed #d7e2f2;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: #667085;
            background: linear-gradient(145deg, #ffffff, #f6f9ff);
        }

        .chat-placeholder i {
            font-size: 3rem;
            margin-bottom: 10px;
            color: #b3c3de;
        }

        .chat-modal {
            position: fixed;
            inset: 0;
            background: rgba(3, 10, 24, 0.46);
            z-index: 1100;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .chat-modal.show {
            display: flex;
        }

        .chat-modal-content {
            width: min(740px, 100%);
            height: min(84vh, 720px);
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 24px 48px rgba(2, 10, 34, 0.28);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .chat-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            border-bottom: 1px solid #e9eef7;
            background: #f7faff;
        }

        .chat-active-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chat-active-user img {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
        }

        .chat-active-user-info h5 {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            color: #101828;
        }

        .chat-active-user-info span {
            color: #667085;
            font-size: 0.8rem;
        }

        .chat-modal-close {
            border: none;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #eef3fb;
            color: #344054;
            cursor: pointer;
        }

        .chat-messages-area {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
            background:
                radial-gradient(circle at 25% 20%, #f4f8ff 0%, transparent 35%),
                radial-gradient(circle at 85% 90%, #ecf3ff 0%, transparent 30%),
                #f7f9fc;
        }

        .message-row {
            display: flex;
            margin-bottom: 10px;
        }

        .message-row.sent {
            justify-content: flex-end;
        }

        .message-row.received {
            justify-content: flex-start;
        }

        .msg-bubble {
            max-width: 72%;
            padding: 9px 12px 7px;
            border-radius: 14px;
            line-height: 1.35;
            font-size: 0.93rem;
            box-shadow: 0 2px 8px rgba(16, 24, 40, 0.08);
            word-break: break-word;
            white-space: pre-wrap;
        }

        .msg-sent {
            background: #0d47a1;
            color: #fff;
            border-bottom-right-radius: 4px;
        }

        .msg-received {
            background: #fff;
            color: #1d2939;
            border: 1px solid #dce5f2;
            border-bottom-left-radius: 4px;
        }

        .msg-time {
            display: block;
            margin-top: 3px;
            text-align: right;
            font-size: 0.72rem;
            opacity: 0.85;
        }

        .chat-input-area {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 14px;
            border-top: 1px solid #e9eef7;
            background: #fff;
        }

        .chat-input-wrapper {
            flex: 1;
        }

        .chat-input-wrapper input {
            width: 100%;
            border: 1px solid #d4ddec;
            border-radius: 10px;
            padding: 10px 11px;
            outline: none;
        }

        .chat-input-wrapper input:focus {
            border-color: #0d47a1;
            box-shadow: 0 0 0 3px rgba(13, 71, 161, 0.12);
        }

        .chat-attach-btn,
        .chat-send-btn {
            border: none;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            cursor: pointer;
        }

        .chat-attach-btn {
            background: #eef3fb;
            color: #344054;
        }

        .chat-send-btn {
            background: #0d47a1;
            color: #fff;
        }

        @media (max-width: 992px) {
            .messages-shell {
                grid-template-columns: 1fr;
                height: auto;
                min-height: calc(100vh - 130px);
            }

            .chat-placeholder {
                min-height: 180px;
            }
        }
    </style>
</head>
<body>
<div class="company-container" id="companyDashboard">
      <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="../index.php" class="logo">
                    <img src="..\photos\job_logo.png" alt="CareerHunt">
                </a>
                <span class="company-badge">Company</span>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li class="nav-item" data-page="index.php">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </li>
                    <li class="nav-item" data-page="job-create.php">
                        <i class="bi bi-plus-circle-fill"></i>
                        <span>Post Job</span>
                    </li>
                    <li class="nav-item" data-page="jobs.php">
                        <i class="bi bi-file-earmark-text-fill"></i>
                        <span>Manage Jobs</span>
                    </li>
                    <li class="nav-item" data-page="applications.php">
                        <i class="bi bi-people-fill"></i>
                        <span>Applications</span>
                    </li>
                     <li class="nav-item" data-page="messages.php">
                        <a href="messages.php"><i class="bi bi-chat-dots-fill"></i> <span>Messages</span></a>
                    </li>
                    <li class="nav-item active" data-page="profile.php">
                        <i class="bi bi-building"></i>
                        <span>Company Profile</span>
                    </li>
                    <li class="nav-item" data-page="settings.php">
                        <i class="bi bi-gear-fill"></i>
                        <span>Settings</span>
                    </li>
                </ul>
            </nav>
      </aside>
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
            <div class="messages-shell">
                <div class="whatsapp-list">
                    <div class="whatsapp-list-header">
                        <h3>Applicants</h3>
                        <div class="chat-search">
                            <i class="bi bi-search"></i>
                            <input type="text" id="userSearchInput" placeholder="Search users...">
                        </div>
                        <div id="userSearchResults" class="search-results" style="display:none;"></div>
                    </div>

                    <div class="chat-list" id="chatList">
                        <?php if (empty($applicants)): ?>
                            <div class="p-3 text-muted">No conversations found.</div>
                        <?php else: ?>
                            <?php foreach ($applicants as $applicant): ?>
                                <?php $userId = (int) $applicant['user_id']; ?>
                                <?php $fullName = (string) $applicant['full_name']; ?>
                                <?php $avatar = 'https://ui-avatars.com/api/?name=' . urlencode($fullName) . '&background=0d47a1&color=fff'; ?>
                                <div
                                    class="chat-user-item <?php echo $userId === $activeUserId ? 'active' : ''; ?>"
                                    data-user-id="<?php echo $userId; ?>"
                                    data-user-name="<?php echo htmlspecialchars($fullName); ?>"
                                    data-user-avatar="<?php echo htmlspecialchars($avatar); ?>"
                                >
                                    <div class="chat-user-avatar">
                                        <img src="<?php echo htmlspecialchars($avatar); ?>" alt="Avatar">
                                    </div>
                                    <div class="chat-user-details">
                                        <div class="chat-user-name">
                                            <h6><?php echo htmlspecialchars($fullName); ?></h6>
                                            <span class="chat-time"><?php echo htmlspecialchars(date('M j', strtotime((string) $applicant['last_message_at']))); ?></span>
                                        </div>
                                        <p class="chat-snippet"><?php echo htmlspecialchars((string) $applicant['last_message']); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="chat-placeholder" id="chatPlaceholder">
                    <i class="bi bi-chat-dots"></i>
                    <h5 class="mb-1">Open a conversation</h5>
                    <p class="mb-0">Tap any user to load chat in a modal.</p>
                </div>
            </div>
        </section>
    </main>
</div>

<div class="chat-modal" id="chatModal" aria-hidden="true">
    <div class="chat-modal-content">
        <div class="chat-modal-header">
            <div class="chat-active-user">
                <img id="modalUserAvatar" src="" alt="Avatar">
                <div class="chat-active-user-info">
                    <h5 id="modalUserName">Conversation</h5>
                    <span>Live chat</span>
                </div>
            </div>
            <button class="chat-modal-close" id="closeChatModal" title="Close chat">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="chat-messages-area" id="chatMessagesArea"></div>

        <div class="chat-input-area">
            <button class="chat-attach-btn" title="Attach file" type="button">
                <i class="bi bi-paperclip"></i>
            </button>
            <div class="chat-input-wrapper">
                <input type="text" id="chatInput" placeholder="Type your message here...">
            </div>
            <button class="chat-send-btn" id="chatSendBtn" title="Send message" type="button">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
    </div>
</div>

<script src="js/company.js"></script>
<script>
const companyId = <?php echo json_encode($companyId); ?>;
const initialUserId = <?php echo json_encode($activeUserId); ?>;
const initialUserName = <?php echo json_encode($activeUserName); ?>;
const initialUserAvatar = <?php echo json_encode($activeUserAvatar); ?>;

let chatInterval = null;
let selectedUserId = null;

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

function setActiveUserVisual(userId) {
    document.querySelectorAll('.chat-user-item').forEach((item) => {
        item.classList.toggle('active', Number(item.dataset.userId) === Number(userId));
    });
}

function renderMessages(messages) {
    const area = document.getElementById('chatMessagesArea');
    area.innerHTML = '';

    if (!messages || !messages.length) {
        area.innerHTML = '<div class="message-row received"><div class="msg-bubble msg-received">No messages found for this conversation.</div></div>';
        return;
    }

    messages.forEach((msg) => {
        const row = document.createElement('div');
        const isSent = msg.sender_type === 'company';
        row.className = 'message-row ' + (isSent ? 'sent' : 'received');

        const bubble = document.createElement('div');
        bubble.className = 'msg-bubble ' + (isSent ? 'msg-sent' : 'msg-received');
        bubble.innerHTML = escapeHtml(msg.message).replace(/\n/g, '<br>') + '<span class="msg-time">' + escapeHtml(formatTime(msg.created_at)) + '</span>';

        row.appendChild(bubble);
        area.appendChild(row);
    });

    area.scrollTop = area.scrollHeight;
}

async function fetchMessages() {
    if (!selectedUserId) {
        return;
    }

    const endpoint = '../api/get_messages.php?user1_id=' + encodeURIComponent(companyId)
        + '&user1_type=company&user2_id=' + encodeURIComponent(selectedUserId)
        + '&user2_type=user';

    try {
        const res = await fetch(endpoint, { cache: 'no-store' });
        const data = await res.json();

        if (Array.isArray(data.messages)) {
            renderMessages(data.messages);
            return;
        }

        renderMessages([]);
    } catch (e) {
        renderMessages([]);
    }
}

async function sendMessage() {
    if (!selectedUserId) {
        return;
    }

    const input = document.getElementById('chatInput');
    const msg = input.value.trim();
    if (!msg) {
        return;
    }

    const res = await fetch('../api/send_message.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            sender_id: companyId,
            sender_type: 'company',
            receiver_id: Number(selectedUserId),
            receiver_type: 'user',
            message: msg
        })
    });

    const data = await res.json();
    if (data.success) {
        input.value = '';
        await fetchMessages();
    }
}

function startPolling() {
    if (chatInterval) {
        clearInterval(chatInterval);
    }
    chatInterval = setInterval(fetchMessages, 3000);
}

function stopPolling() {
    if (chatInterval) {
        clearInterval(chatInterval);
        chatInterval = null;
    }
}

async function openChatModal(userId, userName, userAvatar) {
    selectedUserId = Number(userId);
    setActiveUserVisual(selectedUserId);

    document.getElementById('modalUserName').textContent = userName || 'Conversation';
    document.getElementById('modalUserAvatar').src = userAvatar || '';

    const modal = document.getElementById('chatModal');
    modal.classList.add('show');
    modal.setAttribute('aria-hidden', 'false');

    await fetchMessages();
    startPolling();

    document.getElementById('chatInput').focus();
}

function closeChatModal() {
    const modal = document.getElementById('chatModal');
    modal.classList.remove('show');
    modal.setAttribute('aria-hidden', 'true');
    stopPolling();
}

async function bindUserSearch() {
    const searchInput = document.getElementById('userSearchInput');
    const searchResults = document.getElementById('userSearchResults');
    let searchTimeout = null;

    searchInput.addEventListener('input', function () {
        const q = this.value.trim();

        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }

        if (!q) {
            searchResults.style.display = 'none';
            searchResults.innerHTML = '';
            return;
        }

        searchTimeout = setTimeout(async () => {
            const res = await fetch('../api/search_users.php?q=' + encodeURIComponent(q));
            const data = await res.json();

            if (!data.success || !data.users || !data.users.length) {
                searchResults.innerHTML = '<div class="p-2 text-muted">No users found.</div>';
                searchResults.style.display = 'block';
                return;
            }

            searchResults.innerHTML = data.users.map((u) => {
                return '<div class="search-user-item" data-id="' + escapeHtml(String(u.id)) + '" data-name="' + escapeHtml(u.name) + '" data-avatar="' + escapeHtml(u.avatar) + '">'
                    + '<img src="' + escapeHtml(u.avatar) + '" alt="Avatar">'
                    + '<div><div class="fw-semibold small">' + escapeHtml(u.name) + '</div><div class="text-muted" style="font-size:0.78rem;">' + escapeHtml(u.email) + '</div></div>'
                    + '</div>';
            }).join('');
            searchResults.style.display = 'block';
        }, 250);
    });

    searchResults.addEventListener('click', function (e) {
        const item = e.target.closest('.search-user-item');
        if (!item) {
            return;
        }

        const userId = Number(item.dataset.id);
        const userName = item.dataset.name || 'User';
        const userAvatar = item.dataset.avatar || '';

        searchResults.style.display = 'none';
        searchInput.value = '';

        openChatModal(userId, userName, userAvatar);
    });

    document.addEventListener('click', function (e) {
        if (!searchResults.contains(e.target) && e.target !== searchInput) {
            searchResults.style.display = 'none';
        }
    });
}

document.addEventListener('DOMContentLoaded', async () => {
    document.querySelectorAll('.chat-user-item').forEach((item) => {
        item.addEventListener('click', () => {
            openChatModal(item.dataset.userId, item.dataset.userName, item.dataset.userAvatar);
        });
    });

    document.getElementById('chatSendBtn').addEventListener('click', sendMessage);
    document.getElementById('chatInput').addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    document.getElementById('closeChatModal').addEventListener('click', closeChatModal);

    document.getElementById('chatModal').addEventListener('click', (e) => {
        if (e.target.id === 'chatModal') {
            closeChatModal();
        }
    });

    await bindUserSearch();

    if (initialUserId > 0) {
        await openChatModal(initialUserId, initialUserName, initialUserAvatar);
    }
});
</script>
</body>
</html>

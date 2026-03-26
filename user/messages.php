<<<<<<< Updated upstream
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
if ($activeCompanyId <= 0 && !empty($conversations)) {
    $activeCompanyId = (int) $conversations[0]['company_id'];
}

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
=======
>>>>>>> Stashed changes
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Messages</title>
<<<<<<< Updated upstream
    <link rel="stylesheet" href="user.css">
    <link rel="stylesheet" href="user\css\message.css">
=======
>>>>>>> Stashed changes

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<<<<<<< Updated upstream
=======
    <link rel="stylesheet" href="user.css">

>>>>>>> Stashed changes
</head>

<body>

    <div class="dashboard">

         <?php include 'sidebar.php'; ?>

        <div class="content">

            <h2 class="mb-4">Messages</h2>

            <div class="chat-container">

                <!-- USER LIST -->

                <div class="chat-sidebar">

                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Search conversation">
                    </div>
<<<<<<< Updated upstream
                    <?php if (empty($conversations)): ?>
                        <div class="p-3 text-muted">No conversations found.</div>
                    <?php else: ?>
                        <?php foreach ($conversations as $conversation): ?>
                            <?php $companyId = (int) $conversation['company_id']; ?>
                            <a href="messages.php?company_id=<?php echo $companyId; ?>" class="chat-user <?php echo $companyId === $activeCompanyId ? 'active' : ''; ?>" style="text-decoration:none;color:inherit;display:flex;">
                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode((string) $conversation['company_name']); ?>&background=0d47a1&color=fff">

                                <div class="user-info">
                                    <h6><?php echo user_esc((string) $conversation['company_name']); ?></h6>
                                    <small><?php echo user_esc((string) $conversation['last_message']); ?></small>
                                </div>

                                <div class="meta">
                                    <span class="time"><?php echo user_esc(date('M j', strtotime((string) $conversation['last_message_at']))); ?></span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
=======


                    <div class="chat-user active">

                        <img src="https://i.pravatar.cc/40?img=1">

                        <div class="user-info">
                            <h6>Darlene Robertson</h6>
                            <small>See you tomorrow!</small>
                        </div>

                        <div class="meta">
                            <span class="time">10:30</span>
                            <span class="status online"></span>
                        </div>

                    </div>


                    <div class="chat-user">

                        <img src="https://i.pravatar.cc/40?img=2">

                        <div class="user-info">
                            <h6>Jane Cooper</h6>
                            <small>Sent the documents.</small>
                        </div>

                        <div class="meta">
                            <span class="badge bg-danger">2</span>
                        </div>

                    </div>


                    <div class="chat-user">

                        <img src="https://i.pravatar.cc/40?img=3">

                        <div class="user-info">
                            <h6>Guy Hawkins</h6>
                            <small>Thanks!</small>
                        </div>

                        <div class="meta">
                            <span class="time">Yesterday</span>
                        </div>

                    </div>
>>>>>>> Stashed changes

                </div>


                <!-- CHAT AREA -->

                <div class="chat-main">

                    <!-- HEADER -->

                    <div class="chat-header">

<<<<<<< Updated upstream
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($activeCompanyName !== '' ? $activeCompanyName : 'Company'); ?>&background=0d47a1&color=fff">

                        <div>
                            <h6 class="mb-0"><?php echo user_esc($activeCompanyName !== '' ? $activeCompanyName : 'No conversation selected'); ?></h6>
                            <small class="text-success">Messages</small>
=======
                        <img src="https://i.pravatar.cc/40?img=1">

                        <div>
                            <h6 class="mb-0">Darlene Robertson</h6>
                            <small class="text-success">Online</small>
>>>>>>> Stashed changes
                        </div>

                        <div class="header-actions ms-auto">
                            <i class="bi bi-telephone"></i>
                            <i class="bi bi-camera-video"></i>
                            <i class="bi bi-three-dots"></i>
                        </div>

                    </div>


                    <!-- MESSAGES -->

                    <div class="chat-messages">
<<<<<<< Updated upstream
                        <?php if (empty($messages)): ?>
                            <div class="message received">
                                <p>No messages found for this conversation.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($messages as $message): ?>
                                <div class="message <?php echo $message['sender_type'] === 'user' ? 'sent' : 'received'; ?>">
                                    <p><?php echo nl2br(user_esc((string) $message['message_text'])); ?></p>
                                    <span class="msg-time"><?php echo user_esc(date('H:i', strtotime((string) $message['created_at']))); ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
=======

                        <div class="message received">
                            <p>Hello 👋</p>
                            <span class="msg-time">10:20</span>
                        </div>

                        <div class="message received">
                            <p>How likely are you to recommend our company?</p>
                            <span class="msg-time">10:21</span>
                        </div>

                        <div class="message sent">
                            <p>I would definitely recommend it 👍</p>
                            <span class="msg-time">10:25</span>
                        </div>

                        <div class="message received">
                            <p>Great! Thank you.</p>
                            <span class="msg-time">10:27</span>
                        </div>
>>>>>>> Stashed changes

                    </div>


                    <!-- MESSAGE INPUT -->

                    <div class="chat-input">
<<<<<<< Updated upstream
                        <i class="bi bi-chat-dots"></i>
                        <input type="text" placeholder="Read-only message history">
                        <button disabled>
                            <i class="bi bi-send"></i>
                        </button>
=======

                        <i class="bi bi-emoji-smile"></i>

                        <input type="text" placeholder="Type a message...">

                        <button>
                            <i class="bi bi-send"></i>
                        </button>

>>>>>>> Stashed changes
                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>
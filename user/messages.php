<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Messages</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link rel="stylesheet" href="user.css">

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

                </div>


                <!-- CHAT AREA -->

                <div class="chat-main">

                    <!-- HEADER -->

                    <div class="chat-header">

                        <img src="https://i.pravatar.cc/40?img=1">

                        <div>
                            <h6 class="mb-0">Darlene Robertson</h6>
                            <small class="text-success">Online</small>
                        </div>

                        <div class="header-actions ms-auto">
                            <i class="bi bi-telephone"></i>
                            <i class="bi bi-camera-video"></i>
                            <i class="bi bi-three-dots"></i>
                        </div>

                    </div>


                    <!-- MESSAGES -->

                    <div class="chat-messages">

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

                    </div>


                    <!-- MESSAGE INPUT -->

                    <div class="chat-input">

                        <i class="bi bi-emoji-smile"></i>

                        <input type="text" placeholder="Type a message...">

                        <button>
                            <i class="bi bi-send"></i>
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>
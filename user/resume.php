<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/_user_common.php';

user_ensure_resumes_table($conn);

if (isset($_GET['id']) && isset($_GET['update_status'])) {
    $stmt = $conn->prepare("UPDATE user_resumes SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $_GET['update_status'], $_GET['id']);
    $stmt->execute();
    header("Location: resume.php?action=updated");
    exit();
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $conn->prepare("SELECT file_name FROM user_resumes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $file_data = $stmt->get_result()->fetch_assoc();

    if ($file_data) {
        $path = dirname(__DIR__) . "/user_uploads/" . $file_data['file_name'];
        if (file_exists($path)) unlink($path);
        $del = $conn->prepare("DELETE FROM user_resumes WHERE id = ?");
        $del->bind_param("i", $id);
        $del->execute();
    }
    header("Location: resume.php?action=deleted");
    exit();
}

$upload_success = false;

if (isset($_POST['upload'])) {
    $file = $_FILES['resume']['name'];
    $tmp = $_FILES['resume']['tmp_name'];
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $size = $_FILES['resume']['size'];
    $maxSize = 2 * 1024 * 1024; // 2MB
    $allowedExt = 'pdf';
    $uploadDir = dirname(__DIR__) . '/user_uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0700, true);
    }

    if ($ext !== $allowedExt) {
        $upload_error = "Only PDF files are allowed!";
    } elseif ($size > $maxSize) {
        $upload_error = "File size exceeds 2MB limit.";
    } else {
        $random = bin2hex(random_bytes(8));
        $safeName = 'resume_' . time() . '_' . $random . '.pdf';
        $dest = $uploadDir . $safeName;
        if (move_uploaded_file($tmp, $dest)) {
            $user_id = $_SESSION['user_id'] ?? null;
            if ($user_id) {
                $stmt = $conn->prepare("INSERT INTO user_resumes (user_id, file_name, display_name, status) VALUES (?, ?, ?, 'Active')");
                $stmt->bind_param("iss", $user_id, $safeName, $file);
            } else {
                $stmt = $conn->prepare("INSERT INTO user_resumes (file_name, display_name, status) VALUES (?, ?, 'Active')");
                $stmt->bind_param("ss", $safeName, $file);
            }
            $stmt->execute();
            $upload_success = true;
        } else {
            $upload_error = "Failed to upload file. Check permissions for user_uploads directory.";
        }
    }
}

// Fetch resumes for the table
$user_id = $_SESSION['user_id'] ?? 0;
$stmt_fetch = $conn->prepare("SELECT * FROM user_resumes WHERE user_id = ? OR user_id IS NULL ORDER BY upload_date DESC");
$stmt_fetch->bind_param("i", $user_id);
$stmt_fetch->execute();
$result = $stmt_fetch->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta charset="UTF-8">
    <title>Upload CV</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="user.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    <h1 class="page-title">Upload CV</h1>
                </div>
            </header>

            <section class="content-section p-4">
                <div class="container-fluid">

        <div class="row">
            <div class="col-12 col-md-8 col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-primary">Select your CV</span>
                    </div>
                    <div class="card-body p-2">
                        <form method="post" enctype="multipart/form-data" id="uploadForm">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Only PDF files (Max 2MB)</label>
                                <input type="file" class="form-control custom-input" name="resume" accept="application/pdf" required>
                            </div>
                            <button class="btn btn-primary w-100 py-2 mt-2" name="upload">
                                <i class="bi bi-cloud-arrow-up me-2"></i> Upload to Manager
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php include 'cv_list.php'; ?>
    </div>
                </div>
            </section>
        </main>
    </div>

    <?php if ($upload_success): ?>
        <script>
            Swal.fire({
                title: 'Uploaded!',
                text: 'Your CV has been saved.',
                icon: 'success'
            });
        </script>
    <?php endif; ?>

    <?php if (!empty($upload_error)): ?>
        <script>
            Swal.fire({
                title: 'Error!',
                text: '<?php echo addslashes($upload_error); ?>',
                icon: 'error'
            });
        </script>
    <?php endif; ?>

    <script>
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has('action')) {
        let actionMsg = urlParams.get('action') === 'deleted' ? 'CV deleted permanently.' : 'Status updated successfully.';
        Swal.fire({
            icon: 'success',
            title: 'Done!',
            text: actionMsg,
            timer: 1500,
            showConfirmButton: false
        });
    }

    function confirmStatus(id, newStatus) {
        Swal.fire({
            title: 'Change status to ' + newStatus + '?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, change it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `resume.php?id=${id}&update_status=${newStatus}`;
            }
        });
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the file forever!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `resume.php?delete_id=${id}`;
            }
        });
    }
    </script>

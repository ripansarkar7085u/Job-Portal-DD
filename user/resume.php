<?php

require_once __DIR__ . '/_user_common.php';

$upload_success = false;
$upload_error = "";

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
            $stmt = $conn->prepare("INSERT INTO user_resumes (file_name, display_name, status) VALUES (?, ?, 'Active')");
            $stmt->bind_param("ss", $safeName, $file);
            $stmt->execute();
            $upload_success = true;
        } else {
            $upload_error = "Failed to upload file. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Upload CV</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="user.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php include 'sidebar.php'; ?>
   <div class="content p-4">
    <div class="container-fluid">
        <h2 class="mb-4">Upload CV</h2>

        <div class="row">
            <div class="col-12 col-md-8 col-lg-6">
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
    </div>
</div>

    <?php if ($upload_success): ?>
        <script>
            Swal.fire({
                title: 'Uploaded!',
                text: 'Your resume has been saved.',
                icon: 'success'
            });
        </script>
    <?php endif; ?>

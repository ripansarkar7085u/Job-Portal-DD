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
        <h2>Upload CV</h2>

        <div class="card mt-3 shadow-sm w-50">
            <div class="card-body">
                <form method="post" enctype="multipart/form-data" id="uploadForm">
                    <label class="form-label">Select  your CV </label>
                    <input type="file" class="form-control" name="resume" accept="application/pdf" required>
                    <button class="btn btn-primary mt-3" name="upload">Upload to Manager</button>
                </form>
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
<?php
$conn = new mysqli("localhost", "root", "", "job-portal");

$upload_success = false;
$upload_error = "";

if (isset($_POST['upload'])) {
    $file = $_FILES['resume']['name'];
    $tmp = $_FILES['resume']['tmp_name'];
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    if ($ext == "pdf") {
        $target_dir = "uploads/";
        if (!is_dir($target_dir))
            mkdir($target_dir, 0777, true);

        $new_filename = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $file);

        if (move_uploaded_file($tmp, $target_dir . $new_filename)) {
            $stmt = $conn->prepare("INSERT INTO user_resumes (file_name, display_name, status) VALUES (?, ?, 'Active')");
            $stmt->bind_param("ss", $new_filename, $file);
            $stmt->execute();
            $upload_success = true;
        }
    } else {
        $upload_error = "Only PDF files are allowed!";
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
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => { window.location.href = 'cv.php'; });
        </script>
    <?php endif; ?>

    <?php if ($upload_error != ""): ?>
        <script>
            Swal.fire('Error', '<?php echo $upload_error; ?>', 'error');
        </script>
    <?php endif; ?>
</body>

</html>
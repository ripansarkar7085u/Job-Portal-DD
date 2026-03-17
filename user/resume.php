<?php

if (isset($_POST['upload'])) {

    $file = $_FILES['resume']['name'];
    $tmp = $_FILES['resume']['tmp_name'];
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    if ($ext == "pdf") {

        move_uploaded_file($tmp, "uploads/" . $file);
        echo "<div class='alert alert-success'>Resume Uploaded Successfully</div>";

    } else {
        echo "<div class='alert alert-danger'>Only PDF files are allowed!</div>";
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Upload Resume</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="user.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="content p-4">
    <h2>Upload Resume</h2>

    <div class="card mt-3 shadow-sm w-50">
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <input type="file" class="form-control" name="resume" accept="application/pdf" required>
                <button class="btn btn-theme mt-3" name="upload">Upload</button>
            </form>
        </div>
    </div>

</div>

<script src="user.js"></script>
</body>
</html>
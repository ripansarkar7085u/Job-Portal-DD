<?php
$conn = new mysqli("localhost", "root", "", "job-portal");

// --- BACKEND LOGIC ---
if (isset($_GET['id']) && isset($_GET['update_status'])) {
    $stmt = $conn->prepare("UPDATE user_resumes SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $_GET['update_status'], $_GET['id']);
    $stmt->execute();
    header("Location: cv.php?action=updated");
    exit();
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $conn->prepare("SELECT file_name FROM user_resumes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $file_data = $stmt->get_result()->fetch_assoc();

    if ($file_data) {
        $path = "uploads/" . $file_data['file_name'];
        if (file_exists($path)) unlink($path);
        $del = $conn->prepare("DELETE FROM user_resumes WHERE id = ?");
        $del->bind_param("i", $id);
        $del->execute();
    }
    header("Location: cv.php?action=deleted");
    exit();
}

$result = $conn->query("SELECT * FROM user_resumes ORDER BY upload_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CV Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="user.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content p-4">
        <h2 class="section-title">CV Manager</h2>
        
        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>CV Name</th>
                            <th>Date Uploaded</th>
                            <th>Statu</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <i class="bi bi-file-earmark-pdf text-danger fs-3"></i>
                                    <strong><?php echo htmlspecialchars($row['display_name']); ?></strong>
                                </div>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($row['upload_date'])); ?></td>
                            <td>
                                <span class="badge <?php echo ($row['status'] == 'Active') ? 'bg-success' : 'bg-secondary'; ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="download_resume.php?id=<?php echo $row['id']; ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                    
                                    <button onclick="confirmStatus(<?php echo $row['id']; ?>, '<?php echo ($row['status'] == 'Active') ? 'Deactive' : 'Active'; ?>')" 
                                            class="btn btn-sm <?php echo ($row['status'] == 'Active') ? 'btn-warning' : 'btn-success'; ?>">
                                        <?php echo ($row['status'] == 'Active') ? 'Deactivate' : 'Activate'; ?>
                                    </button>
                                    
                                    <button onclick="confirmDelete(<?php echo $row['id']; ?>)" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <div class="mt-3">
                    <a href="resume.php" class="btn btn-primary"><i class="bi bi-upload"></i> Upload New CV</a>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Show success toast after redirect
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has('action')) {
        Swal.fire({
            icon: 'success',
            title: 'Done!',
            text: 'Database updated successfully.',
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
                window.location.href = `cv.php?id=${id}&update_status=${newStatus}`;
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
                window.location.href = `cv.php?delete_id=${id}`;
            }
        });
    }
    </script>
</body>
</html>
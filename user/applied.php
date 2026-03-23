<?php
require_once __DIR__ . '/_user_common.php';

$userId = user_require_login();
user_ensure_applications_table($conn);

$appliedRows = [];
$stmt = $conn->prepare("SELECT a.id, a.status, a.applied_at, j.id AS job_id, j.title, j.location, c.company_name
    FROM user_job_applications a
    INNER JOIN jobs j ON j.id = a.job_id
    INNER JOIN companies c ON c.id = j.company_id
    WHERE a.user_id = ?
    ORDER BY a.applied_at DESC");

if ($stmt) {
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($result && ($row = $result->fetch_assoc())) {
        $appliedRows[] = $row;
    }
    $stmt->close();
}

function applied_status_class(string $status): string
{
    $normalized = strtolower(trim($status));
    if (in_array($normalized, ['interview', 'shortlisted', 'active'], true)) {
        return 'text-success';
    }
    if (in_array($normalized, ['rejected', 'closed'], true)) {
        return 'text-danger';
    }
    return 'text-primary';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applied Jobs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="user.css">
</head>

<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <h2>Applied Jobs</h2>
        <p class="text-muted">Ready to jump back in?</p>

        <div class="card p-3 shadow-sm border-0">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Job Title</th>
                        <th>Date Applied</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($appliedRows)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No applied jobs found for your account.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($appliedRows as $row): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="job-logo bg-dark"><?php echo user_esc(strtoupper(substr($row['company_name'], 0, 1))); ?></div>
                                        <div>
                                            <strong><?php echo user_esc($row['title']); ?></strong><br>
                                            <small class="text-muted">
                                                <i class="bi bi-briefcase"></i> <?php echo user_esc($row['company_name']); ?>
                                                <i class="bi bi-geo-alt"></i> <?php echo user_esc($row['location'] ?: 'Not specified'); ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo user_esc(date('M j, Y', strtotime((string) $row['applied_at']))); ?></td>
                                <td class="fw-semibold <?php echo applied_status_class((string) $row['status']); ?>"><?php echo user_esc(ucfirst((string) $row['status'])); ?></td>
                                <td>
                                    <a class="btn btn-light btn-sm" href="../job-details.php?id=<?php echo (int) $row['job_id']; ?>"><i class="bi bi-eye"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="user.js"></script>
</body>

</html>
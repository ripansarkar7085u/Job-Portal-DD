<?php
require_once __DIR__ . '/_user_common.php';

$userId = user_require_login();
user_ensure_alerts_table($conn);

$alerts = [];
$stmt = $conn->prepare("SELECT a.id, a.title, a.message, a.alert_type, a.is_read, a.created_at, j.id AS job_id, j.title AS job_title, c.company_name, j.location
    FROM user_alerts a
    LEFT JOIN jobs j ON j.id = a.related_job_id
    LEFT JOIN companies c ON c.id = j.company_id
    WHERE a.user_id = ?
    ORDER BY a.created_at DESC");

if ($stmt) {
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($result && ($row = $result->fetch_assoc())) {
        $alerts[] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Alerts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="user.css">
</head>

<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <h2 class="mb-3">Job Alerts</h2>
        <p><?php echo empty($alerts) ? 'No alerts available.' : 'Latest alerts for your account.'; ?></p>

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
                    <?php if (empty($alerts)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No alerts found for your account.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($alerts as $alert): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="job-logo <?php echo ((int) $alert['is_read'] === 1) ? 'bg-secondary' : 'bg-danger'; ?> text-white"><?php echo user_esc(strtoupper(substr((string) ($alert['alert_type'] ?: 'AL'), 0, 2))); ?></div>
                                        <div>
                                            <strong><?php echo user_esc((string) ($alert['title'] ?: $alert['job_title'] ?: 'Alert')); ?></strong><br>
                                            <small class="text-muted">
                                                <i class="bi bi-briefcase"></i> <?php echo user_esc((string) ($alert['company_name'] ?: 'CareerHunt')); ?>
                                                <i class="bi bi-geo-alt"></i> <?php echo user_esc((string) ($alert['location'] ?: 'Not specified')); ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo user_esc(date('M j, Y', strtotime((string) $alert['created_at']))); ?></td>
                                <td class="fw-semibold <?php echo ((int) $alert['is_read'] === 1) ? 'text-secondary' : 'text-success'; ?>"><?php echo ((int) $alert['is_read'] === 1) ? 'Read' : 'New'; ?></td>
                                <td>
                                    <?php if (!empty($alert['job_id'])): ?>
                                        <a class="btn btn-light btn-sm" href="../job-details.php?id=<?php echo (int) $alert['job_id']; ?>"><i class="bi bi-eye"></i></a>
                                    <?php else: ?>
                                        <button class="btn btn-light btn-sm" type="button" title="No linked job"><i class="bi bi-info-circle"></i></button>
                                    <?php endif; ?>
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
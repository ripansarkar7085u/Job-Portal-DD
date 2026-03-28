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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Job Alerts</title>
    <link rel="stylesheet" href="user.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
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
                    <h1 class="page-title">Job Alerts</h1>
                </div>
            </header>

            <section class="content-section">
                <div class="page-header mb-4">
                    <p class="text-muted"><?php echo empty($alerts) ? 'No alerts available.' : 'Latest alerts for your account.'; ?></p>
                </div>

                <div class="dashboard-card shadow-sm border-0">
                    <div class="table-responsive card-body p-0">
                        <table class="data-table">
                            <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Date Applied</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="alertsTableBody">
                    <?php if (empty($alerts)): ?>
                        <tr class="no-data-row">
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
                                                <i class="bi bi-briefcase"></i> <?php echo user_esc((string) ($alert['company_name'] ?: 'CareerHunt')); ?> | 
                                                <i class="bi bi-geo-alt"></i> <?php echo user_esc((string) ($alert['location'] ?: 'Not specified')); ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo user_esc(date('M j, Y', strtotime((string) $alert['created_at']))); ?></td>
                                <td class="fw-semibold <?php echo ((int) $alert['is_read'] === 1) ? 'text-secondary' : 'text-success'; ?>"><?php echo ((int) $alert['is_read'] === 1) ? 'Read' : 'New'; ?></td>
                                <td>
                                    <?php if (!empty($alert['job_id'])): ?>
                                        <a class="action-btn view" href="../job-details.php?id=<?php echo (int) $alert['job_id']; ?>"><i class="bi bi-eye"></i></a>
                                    <?php else: ?>
                                        <button class="action-btn" type="button" title="No linked job"><i class="bi bi-info-circle"></i></button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
            <!-- Pagination -->
            <div class="pagination mt-4 d-flex justify-content-center gap-2" id="paginationContainer"></div>
        </div>
        </section>
    </main>
    </div>

    <!-- No user.js here -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const tbody = document.getElementById('alertsTableBody');
        if (!tbody) return;
        
        const rows = Array.from(tbody.querySelectorAll('tr:not(.no-data-row)'));
        if (rows.length === 0 || (rows.length === 1 && rows[0].querySelector('td[colspan]'))) return;

        const perPage = 5;
        let currentPage = 1;
        const totalMatches = rows.length;
        let totalPages = Math.ceil(totalMatches / perPage);
        const paginationContainer = document.getElementById('paginationContainer');

        function renderPagination() {
            if (!paginationContainer) return;
            paginationContainer.innerHTML = '';

            if (totalPages < 1) totalPages = 1;

            const prevBtn = document.createElement('button');
            prevBtn.className = 'pagination-btn';
            prevBtn.innerHTML = '<i class="bi bi-chevron-left"></i>';
            prevBtn.disabled = currentPage === 1;
            prevBtn.onclick = () => { if (currentPage > 1) { currentPage--; renderTable(); } };
            paginationContainer.appendChild(prevBtn);

            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            if (endPage - startPage < 4) startPage = Math.max(1, endPage - 4);

            for (let i = startPage; i <= endPage; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = `pagination-btn ${i === currentPage ? 'active' : ''}`;
                pageBtn.textContent = i;
                pageBtn.onclick = () => { currentPage = i; renderTable(); };
                paginationContainer.appendChild(pageBtn);
            }

            const nextBtn = document.createElement('button');
            nextBtn.className = 'pagination-btn';
            nextBtn.innerHTML = '<i class="bi bi-chevron-right"></i>';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.onclick = () => { if (currentPage < totalPages) { currentPage++; renderTable(); } };
            paginationContainer.appendChild(nextBtn);
        }

        function renderTable() {
            const startIndex = (currentPage - 1) * perPage;
            const endIndex = startIndex + perPage;
            
            rows.forEach((row, index) => {
                if (index >= startIndex && index < endIndex) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            renderPagination();
        }

        renderTable();
    });
    </script>
</body>

</html>

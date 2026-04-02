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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Applied Jobs</title>
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
                    <h1 class="page-title">Applied Jobs</h1>
                </div>
            </header>

            <section class="content-section">
                <div class="page-header mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <p class="text-muted mb-0">Ready to jump back in?</p>
                    <div style="max-width: 300px; width: 100%;">
                        <input type="text" id="appliedJobSearch" class="form-control" placeholder="Search jobs or companies...">
                    </div>
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
                <tbody id="appliedTableBody">
                    <?php if (empty($appliedRows)): ?>
                        <tr class="no-data-row">
                            <td colspan="4" class="text-center text-muted py-4">No applied jobs found for your account.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($appliedRows as $row): ?>
                            <tr>
                                <td data-label="Job Details">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="job-logo bg-dark"><?php echo user_esc(strtoupper(substr($row['company_name'], 0, 1))); ?></div>
                                        <div>
                                            <strong><?php echo user_esc($row['title']); ?></strong><br>
                                            <small class="text-muted">
                                                <i class="bi bi-briefcase"></i> <?php echo user_esc($row['company_name']); ?> | 
                                                <i class="bi bi-geo-alt"></i> <?php echo user_esc($row['location'] ?: 'Not specified'); ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Date Applied"><?php echo user_esc(date('M j, Y', strtotime((string) $row['applied_at']))); ?></td>
                                <td data-label="Status" class="fw-semibold <?php echo applied_status_class((string) $row['status']); ?>"><?php echo user_esc(ucfirst((string) $row['status'])); ?></td>
                                <td data-label="Action">
                                    <a class="action-btn view" href="../job-details.php?id=<?php echo (int) $row['job_id']; ?>"><i class="bi bi-eye"></i></a>
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

    <!-- No user.js here to avoid conflicts -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const tbody = document.getElementById('appliedTableBody');
        if (!tbody) return;
        
        const rows = Array.from(tbody.querySelectorAll('tr:not(.no-data-row)'));
        if (rows.length === 0 || (rows.length === 1 && rows[0].querySelector('td[colspan]'))) return;

        const perPage = 5;
        let currentPage = 1;
        let totalMatches = rows.length;
        let totalPages = Math.ceil(totalMatches / perPage);
        const paginationContainer = document.getElementById('paginationContainer');
        const searchInput = document.getElementById('appliedJobSearch');
        let searchQuery = '';
        let filteredRows = [...rows];

        function renderPagination() {
            if (!paginationContainer) return;
            paginationContainer.innerHTML = '';
            
            if (totalPages < 1) totalPages = 1;
            if (currentPage > totalPages) currentPage = totalPages;

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
            
            rows.forEach(row => {
                row.style.display = 'none';
            });

            filteredRows.forEach((row, index) => {
                if (index >= startIndex && index < endIndex) {
                    row.style.display = '';
                }
            });
            renderPagination();
        }

        function applySearch() {
            filteredRows = rows.filter(row => {
                const text = row.innerText.toLowerCase();
                return text.includes(searchQuery);
            });
            totalMatches = filteredRows.length;
            totalPages = Math.ceil(totalMatches / perPage);
            currentPage = 1;
            renderTable();
        }

        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                searchQuery = e.target.value.toLowerCase();
                applySearch();
            });
        }

        renderTable();
    });
    </script>
</body>

</html>

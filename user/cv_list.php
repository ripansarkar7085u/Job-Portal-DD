<div class="dashboard-card shadow-sm border-0 mt-3">
    <div class="table-responsive card-body p-0">
        <table class="data-table align-middle">
            <thead>
                <tr>
                    <th>CV Name</th>
                    <th>Date Uploaded</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($result) && $result instanceof mysqli_result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td data-label="CV Name">
                        <div class="applicant-info flex-row gap-3">
                            <i class="bi bi-file-earmark-pdf fs-3 text-danger"></i>
                            <span class="name fw-bold" style="font-size: 1rem;"><?php echo htmlspecialchars($row['display_name']); ?></span>
                        </div>
                    </td>
                    <td data-label="Date Uploaded"><?php echo date('M d, Y', strtotime($row['upload_date'])); ?></td>
                    <td data-label="Status">
                        <span class="status-badge <?php echo ($row['status'] == 'Active') ? 'active' : 'closed'; ?>">
                            <?php echo $row['status']; ?>
                        </span>
                    </td>
                    <td data-label="Action">
                        <div class="d-flex align-items-center gap-2">
                            <a href="download_resume.php?id=<?php echo $row['id']; ?>" target="_blank" class="action-btn view" title="View"><i class="bi bi-eye"></i></a>
                            
                            <button onclick="confirmStatus(<?php echo $row['id']; ?>, '<?php echo ($row['status'] == 'Active') ? 'Deactive' : 'Active'; ?>')" 
                                    class="btn <?php echo ($row['status'] == 'Active') ? 'btn-outline' : 'btn-primary'; ?>" style="padding: 6px 12px; font-size: 0.8rem; height: 36px; display: inline-flex; align-items: center; justify-content: center; gap: 4px; min-width: 100px;">
                                <i class="bi <?php echo ($row['status'] == 'Active') ? 'bi-x-circle' : 'bi-check-circle'; ?>"></i> <?php echo ($row['status'] == 'Active') ? 'Deactivate' : 'Activate'; ?>
                            </button>
                            
                            <button onclick="confirmDelete(<?php echo $row['id']; ?>)" class="action-btn delete" style="color: #ef4444; background: #fee2e2;" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">No CVs found. Upload one to get started.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

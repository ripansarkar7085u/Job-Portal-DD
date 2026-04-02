<div class="card shadow-sm mt-3">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                <tr>
                    <th>CV Name</th>
                    <th>Date Uploaded</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($result) && $result instanceof mysqli_result): ?>
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
                <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">No CVs found. Upload one to get started.</td>
                </tr>
                <?php endif; ?>
            </tbody>
            </table>
        </div>
    </div>
</div>

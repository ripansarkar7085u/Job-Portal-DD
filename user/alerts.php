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
        <p>No alerts available.</p>

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
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="job-logo bg-danger text-white">IN</div>
                                <div>
                                    <strong>Product Manager, Studio</strong><br>
                                    <small class="text-muted">
                                        <i class="bi bi-briefcase"></i> Segment
                                        <i class="bi bi-geo-alt"></i> London, UK
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>Dec 5, 2020</td>
                        <td class="text-success fw-semibold">Active</td>
                        <td>
                            <button class="btn btn-light btn-sm"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-light btn-sm"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="job-logo bg-success text-white">UP</div>
                                <div>
                                    <strong>Senior Product Designer</strong><br>
                                    <small class="text-muted">
                                        <i class="bi bi-briefcase"></i> Segment
                                        <i class="bi bi-geo-alt"></i> London, UK
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>Dec 5, 2020</td>
                        <td class="text-success fw-semibold">Active</td>
                        <td>
                            <button class="btn btn-light btn-sm"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-light btn-sm"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script src="user.js"></script>
</body>

</html>
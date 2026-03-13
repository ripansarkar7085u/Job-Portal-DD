<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shortlisted Jobs</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="user.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="content">

    <div class="container">

        <h2 class="section-title">CV Manager</h2>
        <p class="text-muted">Manage your CV files here.</p>

        <div class="card shadow-sm mt-3">

            <div class="card-body">

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

                        <tr>

                            <td>
                                <div class="d-flex align-items-center gap-3">

                                    <div class="cv-logo bg-primary">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </div>

                                    <div>
                                        <strong>John_Doe_CV.pdf</strong>
                                        <br>
                                        <small class="text-muted">Main CV</small>
                                    </div>

                                </div>
                            </td>

                            <td>Jan 15, 2024</td>

                            <td>
                                <span class="badge bg-success">Active</span>
                            </td>

                            <td>

                                <button class="action-btn">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <button class="action-btn">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <button class="action-btn">
                                    <i class="bi bi-download"></i>
                                </button>

                                <button class="action-btn">
                                    <i class="bi bi-trash"></i>
                                </button>

                            </td>

                        </tr>

                    </tbody>

                </table>

                <div class="mt-3">

                    <label class="btn btn-primary">

                        <i class="bi bi-upload"></i> Upload New CV

                        <input type="file" id="uploadCV" hidden accept=".pdf,.doc,.docx">

                    </label>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="user.js"></script>
</body>
</html>
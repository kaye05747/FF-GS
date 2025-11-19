<?php
session_start();
require_once "../config/db.php";
$pdo = db();

// Fetch announcements
$rows = $pdo->query("SELECT * FROM announcements ORDER BY id DESC")->fetchAll();
include "sidebar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Announcements</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        .content {
            margin-left: 300px;
            padding: 25px;
            font-size: 18px;
        }

        h3 {
            font-size: 32px;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }

        table th, table td {
            font-size: 18px !important;
        }

        th {
            background: #198754 !important;
            color: white !important;
        }

        .btn {
            font-size: 16px !important;
        }

        .add-btn-container {
            margin: 20px 0;
            display: flex;
            justify-content: flex-start;
        }
    </style>
</head>

<body>
<div class="content">

    <h3 class="fw-bold mb-3">📢 Announcements</h3>

    <!-- Success Message -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size:18px;">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Table -->
    <div class="card p-3 mb-4">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th width="100px">ID</th>
                        <th>Message</th>
                        <th width="200px">Date Posted</th>
                        <th width="200px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($rows): ?>
                        <?php foreach ($rows as $r): ?>
                            <tr>
                                <td><?= $r['id'] ?></td>
                                <td><?= nl2br(htmlspecialchars($r['message'])) ?></td>
                                <td><?= date("F d, Y • h:i A", strtotime($r['created_at'])) ?></td>

                                <td>
                                    <a href="announcement_edit.php?id=<?= $r['id'] ?>" 
                                       class="btn btn-warning btn-sm">
                                       <i class="fa fa-edit"></i> Edit
                                    </a>

                                    <a href="announcement_delete.php?id=<?= $r['id'] ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Delete this announcement?');">
                                       <i class="fa fa-trash"></i> Delete
                                    </a>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center">No announcements found.</td></tr>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div>

    <!-- Add Button -->
    <div class="add-btn-container">
        <a class="btn btn-success px-4" data-bs-toggle="collapse" href="#addForm">
            <i class="fa fa-plus"></i> Add Announcement
        </a>
    </div>

    <!-- Add Announcement Form (Collapsed like Borrow Equipment) -->
    <div class="collapse" id="addForm">
        <div class="card p-4">

            <h4 class="fw-bold mb-3">
                <i class="fa fa-bullhorn"></i> New Announcement
            </h4>

            <form action="announcement_add_process.php" method="POST">

                <div class="mb-3">
                    <label class="form-label fw-bold">Message</label>
                    <textarea class="form-control" name="message" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fa fa-check-circle"></i> Save Announcement
                </button>
            </form>

        </div>
    </div>

</div>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>

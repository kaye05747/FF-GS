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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        .content {
            margin-left: 300px;
            padding: 25px;
            font-size: 18px;
            position: relative;
            z-index: 10;
        }

        .add-btn-container {
            margin: 20px 0;
        }

        .sidebar {
            z-index: 1 !important;
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

    <!-- Add Announcement Button (opens modal) -->
    <div class="add-btn-container">
        <button class="btn btn-success px-4" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
            <i class="fa fa-plus"></i> Add Announcement
        </button>
    </div>

    <!-- Announcements Table -->
    <div class="card p-3 mb-4">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th width="80px">ID</th>
                        <th>Message</th>
                        <th width="250px">Date Posted</th>
                        <th width="220px">Actions</th>
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
                                    <a href="announcement_edit.php?id=<?= $r['id'] ?>" class="btn btn-warning btn-sm">
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

</div>


<!-- ADD ANNOUNCEMENT MODAL -->
<div class="modal fade" id="addAnnouncementModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fa fa-bullhorn"></i> New Announcement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="announcement_add_process.php" method="POST">
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Message</label>
                        <textarea class="form-control" name="message" rows="5" required></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check-circle"></i> Save Announcement
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>


<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>

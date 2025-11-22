<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
checkAdmin();

$pdo = db();
$stmt = $pdo->query("
    SELECT f.*, u.username
    FROM feedback f
    LEFT JOIN users u ON f.user_id = u.id
    ORDER BY f.created_at DESC
");
$feedbacks = $stmt->fetchAll();

include "sidebar.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Feedback</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.content-wrapper { margin-left: 250px; padding: 20px; }
</style>
</head>
<body>

<div class="content-wrapper">
    <h3 class="fw-bold mb-4">View Feedback</h3>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-success">
                <tr>
                    <th>User</th>
                    <th>Farmer Name</th>
                    <th>Concern Type</th>
                    <th>Date Submitted</th>
                    <th>Phone Number</th>
                    <th>Status</th>
                    <th>Print</th>
                </tr>
            </thead>
            <tbody>
                <?php if($feedbacks): ?>
                   <?php foreach($feedbacks as $fb): ?>
<tr>
    <td><?= htmlspecialchars($fb['username'] ?? 'Guest') ?></td>
    <td><?= htmlspecialchars($fb['farmer_name']) ?></td>
    <td><?= htmlspecialchars($fb['concern_type']) ?></td>
    <td><?= $fb['created_at'] ?></td>
    <td><?= htmlspecialchars($fb['phone'] ?? '-') ?></td>
    <td>
        <form action="update_status.php" method="POST" style="display:inline;">
            <input type="hidden" name="feedback_id" value="<?= $fb['id'] ?>">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="Pending" <?= $fb['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Resolved" <?= $fb['status'] === 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                <option value="In Progress" <?= $fb['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
            </select>
        </form>
    </td>
    <td style="vertical-align: middle; min-width: 120px;">
        <a href="feedback_detail.php?id=<?= $fb['id'] ?>" class="btn btn-success btn-sm" target="_blank">View / Print</a>
    </td>
</tr>
                   <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">No feedback found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

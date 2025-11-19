<?php
session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
checkAdmin(); // make sure this checks if admin is logged in

$pdo = db();

// Handle status update
if (isset($_POST['update_status'])) {
    $feedback_id = $_POST['feedback_id'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE feedback SET status = ? WHERE id = ?");
    $stmt->execute([$status, $feedback_id]);

    $_SESSION['success'] = "Feedback status updated!";
    header('Location: admin_feedback.php');
    exit;
}

// Fetch all feedbacks
$stmt = $pdo->query("SELECT f.*, u.username AS user_name 
                     FROM feedback f 
                     LEFT JOIN users u ON f.user_id = u.id
                     ORDER BY f.created_at DESC");
$feedbacks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - Manage Feedback</title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
<h3>Admin Feedback Management</h3>

<?php if(isset($_SESSION['success'])): ?>
<div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<table class="table table-bordered table-hover">
<thead class="table-light">
<tr>
<th>User</th>
<th>Farmer Name</th>
<th>Concern</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php foreach($feedbacks as $fb): ?>
<tr>
<td><?= htmlspecialchars($fb['user_name'] ?? 'Guest') ?></td>
<td><?= htmlspecialchars($fb['farmer_name']) ?></td>
<td><?= htmlspecialchars($fb['concern_type']) ?></td>
<td><?= htmlspecialchars($fb['status']) ?></td>
<td>
    <form action="" method="POST" class="d-flex">
        <input type="hidden" name="feedback_id" value="<?= $fb['id'] ?>">
        <select name="status" class="form-select form-select-sm me-2" required>
            <option value="Pending" <?= $fb['status']=='Pending'?'selected':'' ?>>Pending</option>
            <option value="Reviewed" <?= $fb['status']=='Reviewed'?'selected':'' ?>>Reviewed</option>
            <option value="Resolved" <?= $fb['status']=='Resolved'?'selected':'' ?>>Resolved</option>
        </select>
        <button type="submit" name="update_status" class="btn btn-primary btn-sm me-2">Update</button>
        <a href="feedback_detail.php?id=<?= $fb['id'] ?>" class="btn btn-success btn-sm" target="_blank">Print</a>
    </form>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

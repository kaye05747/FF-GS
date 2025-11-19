<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
checkAdmin();

$pdo = db();
$feedback_id = $_GET['id'] ?? 0;

// Handle status update
if (isset($_POST['update_status'])) {
    $new_status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE feedback SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $feedback_id]);

    $_SESSION['success'] = "Feedback status updated!";
    header("Location: feedback_detail.php?id=$feedback_id");
    exit();
}

// Fetch feedback
$stmt = $pdo->prepare("
    SELECT f.*, u.username AS user_name 
    FROM feedback f
    LEFT JOIN users u ON f.user_id = u.id
    WHERE f.id = ?
");
$stmt->execute([$feedback_id]);
$fb = $stmt->fetch();

if (!$fb) {
    echo "Feedback not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Feedback Detail</title>
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<style>
.form-container { max-width: 800px; margin: 20px auto; background: #fff; padding: 20px; border-radius: 8px; }
.line { border-bottom: 1px solid #000; padding: 5px 0; margin-bottom: 10px; }
.signature-line { border-bottom: 1px solid #000; width: 250px; margin-top: 30px; }
</style>
</head>
<body>
<div class="form-container">

    <h2 class="text-center mb-4">FARMER FEEDBACK AND GOVERNANCE FORM</h2>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <div class="mb-3">
        <strong>Submitted by:</strong> <?= htmlspecialchars($fb['user_name'] ?? 'Guest') ?><br>
        <strong>Date Submitted:</strong> <?= $fb['created_at'] ?>
    </div>

    <div class="mb-3">
        <label>Farmer’s Name:</label>
        <div class="line"><?= htmlspecialchars($fb['farmer_name']) ?></div>
    </div>

    <div class="mb-3">
        <label>Type of Concern:</label>
        <div class="line"><?= htmlspecialchars($fb['concern_type']) ?></div>
    </div>

    <div class="mb-3">
        <label>Details of Complaint / Feedback:</label>
        <div class="line" style="min-height:120px;"><?= nl2br(htmlspecialchars($fb['details'] ?? '')) ?></div>
    </div>

    <!-- <div class="mb-3">
        <form method="POST" class="d-flex align-items-center">
            <label class="me-2">Status:</label>
            <select name="status" class="form-select w-auto me-2" required>
                <option value="Pending"  <?= $fb['status']=='Pending'?'selected':'' ?>>Pending</option>
                <option value="Reviewed" <?= $fb['status']=='Reviewed'?'selected':'' ?>>Reviewed</option>
                <option value="Resolved" <?= $fb['status']=='Resolved'?'selected':'' ?>>Resolved</option>
            </select>
            <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
        </form>
    </div> -->

    <div class="mb-3">
        <label>Farmer’s Signature:</label>
        <div class="signature-line"></div>
    </div>

    <button class="btn btn-success mt-3" onclick="window.print()">Print Feedback</button>

</div>
<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

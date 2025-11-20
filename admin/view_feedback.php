<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
checkAdmin();

$pdo = db();

// Get feedbacks
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
.content-wrapper {
    margin-left: 250px; /* MATCH SIDEBAR WIDTH */
    padding: 20px;
}
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
            <table class="table table-bordered table-hover">
                <thead class="table-success">
                    <tr>
                        <th>User</th>
                        <th>Farmer Name</th>
                        <th>Concern Type</th>
                        <th>Status</th>
                        <th>Date Submitted</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($feedbacks): ?>
                       <?php foreach($feedbacks as $fb): ?>
<tr>
    <td><?= htmlspecialchars($fb['username'] ?? 'Guest') ?></td>
    <td><?= htmlspecialchars($fb['farmer_name']) ?></td>
    <td><?= htmlspecialchars($fb['concern_type']) ?></td>
    <td><?= htmlspecialchars($fb['status']) ?></td>
    <td><?= $fb['created_at'] ?></td>
    <td>
        <a href="feedback_detail.php?id=<?= $fb['id'] ?>" 
           class="btn btn-success btn-sm" target="_blank">View / Print</a>

        <!-- Reply Button -->
        <button class="btn btn-primary btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#replyModal<?= $fb['id'] ?>">
            Reply
        </button>
    </td>
</tr>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal<?= $fb['id'] ?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form action="send_reply.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Reply to <?= htmlspecialchars($fb['username'] ?? 'User') ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
            <input type="hidden" name="feedback_id" value="<?= $fb['id'] ?>">

            <label class="form-label">Message</label>
            <textarea name="reply_message" class="form-control" rows="4" required></textarea>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Send Reply</button>
        </div>

      </form>

    </div>
  </div>
</div>
<?php endforeach; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No feedback found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

</div> <!-- content-wrapper end -->

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>

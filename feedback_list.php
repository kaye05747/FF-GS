<?php
session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';

$pdo = db();

// current user id
$currentUserId = $_SESSION['user_id'] ?? null;

// fetch feedback for this user (with phone)
if ($currentUserId) {
    $stmt = $pdo->prepare("
        SELECT id, date, time, farmer_name, organization, phone, concern_type, details, admin_reply, admin_reply_by, admin_reply_at, status, reply_seen
        FROM feedback
        WHERE user_id = :uid
        ORDER BY id DESC
    ");
    $stmt->execute([':uid' => $currentUserId]);
} else {
    $stmt = $pdo->query("
        SELECT id, date, time, farmer_name, organization, phone, concern_type, details, admin_reply, admin_reply_by, admin_reply_at, status, reply_seen
        FROM feedback
        ORDER BY id DESC
    ");
}

$feedback = $stmt->fetchAll(PDO::FETCH_ASSOC);

// unread count
if ($currentUserId) {
    $cstmt = $pdo->prepare("SELECT COUNT(*) FROM feedback WHERE user_id = :uid AND admin_reply IS NOT NULL AND reply_seen = 0");
    $cstmt->execute([':uid' => $currentUserId]);
    $unreadCount = (int)$cstmt->fetchColumn();
} else {
    $unreadCount = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Your Feedback List</title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="css/feedback_list.css">
</head>
<body>
<div class="wrapper bg-white">
<div class="container main-container mt-5">
    <h3 class="page-title text-center">📋 Your Feedback List</h3>

    <!-- <div class="notification-wrap">
        <div class="me-auto"></div>
        <div>
            <span class="muted">Unread replies</span>
            <span id="notifBadge" class="notification-badge"><?= $unreadCount ?></span>
        </div>
    </div> -->

    <div class="table-card">
        <table class="table table-bordered table-hover text-center align-middle">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Farmer Name</th>
                    <th>Organization</th>
                    <th>Phone Number</th>
                    <th>Concern Type</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($feedback): ?>
                    <?php foreach ($feedback as $fb): ?>
                    <tr>
                        <td><?= htmlspecialchars($fb['date']) ?></td>
                        <td><?= htmlspecialchars($fb['time']) ?></td>
                        <td><?= htmlspecialchars($fb['farmer_name']) ?></td>
                        <td><?= htmlspecialchars($fb['organization']) ?></td>
                        <td><?= htmlspecialchars($fb['phone']) ?></td>
                        <td><?= htmlspecialchars($fb['concern_type']) ?></td>
                        <td>
                            <?php
                                $status = htmlspecialchars($fb['status']);
                                if (strtolower($status) === 'pending') {
                                    // echo '<span class="badge bg-warning text-dark p-2">'.$status.'</span>';
                                } elseif (strtolower($status) === 'replied') {
                                    echo '<span class="badge bg-success p-2">'.$status.'</span>';
                                } else {
                                    echo '<span class="badge bg-secondary p-2">'.$status.'</span>';
                                }
                            ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">No feedback found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="text-center mt-3">
            <a href="dashboard.php" class="btn-back">⬅ Back to Dashboard</a>
        </div>
    </div>
</div>

<footer>
    © 2025 Farmer Feedback System — All Rights Reserved
</footer>
</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

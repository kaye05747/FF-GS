<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
checkAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedbackId = (int)$_POST['feedback_id'];
    $replyMessage = trim($_POST['reply_message']);
    $adminName = $_SESSION['username'] ?? 'Admin';

    $pdo = db();
    $stmt = $pdo->prepare("
        UPDATE feedback
        SET admin_reply = :reply,
            admin_reply_by = :admin,
            admin_reply_at = NOW(),
            status = 'Replied',
            reply_seen = 0
        WHERE id = :fid
    ");
    $stmt->execute([
        ':reply' => $replyMessage,
        ':admin' => $adminName,
        ':fid' => $feedbackId
    ]);

    $_SESSION['success'] = "Reply sent successfully.";
    header("Location: view_feedback.php");
    exit;
}

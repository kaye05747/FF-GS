<?php
session_start();
require_once "../config/db.php";
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $feedback_id = $_POST['feedback_id'];
    $reply_message = trim($_POST['reply_message']);

    // Insert reply to table
    $stmt = $pdo->prepare("
        INSERT INTO feedback_replies (feedback_id, reply_message)
        VALUES (?, ?)
    ");
    $stmt->execute([$feedback_id, $reply_message]);

    $_SESSION['success'] = "Reply sent successfully!";
}

header("Location: view_feedback.php");
exit;

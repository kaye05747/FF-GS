<?php
session_start();
require_once "../config/db.php";
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message']);

    if ($message == "") {
        $_SESSION['success'] = "Message cannot be empty.";
        header("Location: announcements.php");
        exit;
    }

    // INSERT that matches your table fields:
    // message, created_at, is_read, category
    $stmt = $pdo->prepare("
        INSERT INTO announcements (message, created_at, is_read, category)
        VALUES (?, NOW(), 0, '')
    ");
    $stmt->execute([$message]);

    $_SESSION['success'] = "Announcement posted successfully!";
    header("Location: announcements.php");
    exit;
}

header("Location: announcements.php");
exit;

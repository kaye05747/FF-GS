<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../config/db.php";
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message'] ?? '');
    $category = trim($_POST['category'] ?? 'General'); // default if empty

    if ($message === '') {
        $_SESSION['error'] = "Announcement message cannot be empty.";
        header("Location: announcement.php");
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO announcements (message, created_at, is_read, category) VALUES (:message, NOW(), 0, :category)");
        $stmt->execute([
            'message' => $message,
            'category' => $category
        ]);

        $_SESSION['success'] = "Announcement added successfully!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }

    header("Location: announcement.php");
    exit;
}

<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
checkAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback_id'], $_POST['status'])) {
    $pdo = db();

    $feedback_id = intval($_POST['feedback_id']);
    $status = $_POST['status'];

    // Optional: Validate status value against allowed statuses
    $allowed_statuses = ['Pending', 'Resolved', 'In Progress'];
    if (!in_array($status, $allowed_statuses)) {
        $_SESSION['success'] = "Invalid status value.";
        header("Location: view_feedback.php");
        exit;
    }

    $stmt = $pdo->prepare("UPDATE feedback SET status = :status WHERE id = :id");
    $stmt->execute(['status' => $status, 'id' => $feedback_id]);

    $_SESSION['success'] = "Status updated successfully.";
}

header("Location: view_feedback.php");
exit;
?>

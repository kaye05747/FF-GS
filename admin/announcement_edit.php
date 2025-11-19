<?php
session_start();
require_once "../config/db.php";
$pdo = db();

if (!isset($_GET['id'])) die("Invalid ID");

$stmt = $pdo->prepare("SELECT * FROM announcements WHERE id=?");
$stmt->execute([$_GET['id']]);
$row = $stmt->fetch();

if (!$row) die("Announcement not found");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE announcements SET message=? WHERE id=?");
    $stmt->execute([$_POST['message'], $_GET['id']]);

    $_SESSION['success'] = "Announcement updated!";
    header("Location: announcement.php");
    exit;
}
include "sidebar.php"
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Announcement</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

    <style>
        .content { margin-left: 300px; padding: 25px; }
        .card { border-radius: 12px; padding: 20px; box-shadow: 0 3px 8px rgba(0,0,0,0.1); }
    </style>
</head>

<body>
<div class="content">

    <h3 class="fw-bold mb-3">✏️ Edit Announcement</h3>

    <div class="card">
        <form method="POST">
            <label class="fw-bold mb-1">Message:</label>
            <textarea name="message" class="form-control mb-3" rows="6" required><?= htmlspecialchars($row['message']) ?></textarea>

            <button class="btn btn-warning px-4">Update</button>
            <a href="announcements.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

</div>
</body>
</html>

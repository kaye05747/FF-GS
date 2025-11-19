<?php
session_start();
require_once "../config/db.php";
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['message'])) {

        $stmt = $pdo->prepare("INSERT INTO announcements (message, created_at) VALUES (?, NOW())");
        $stmt->execute([$_POST['message']]);

        $_SESSION['success'] = "Announcement added!";
        header("Location: announcements.php");
        exit;
    }
}
include "sidebar.php"
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Announcement</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

    <!-- <style>
        .content { margin-left: 300px; padding: 25px; }
        .card { border-radius: 12px; padding: 20px; box-shadow: 0 3px 8px rgba(0,0,0,0.1); }
    </style> -->
</head>

<body>
<div class="content">

    <h3 class="fw-bold mb-3">📝 Add Announcement</h3>

    <div class="card">
        <form method="POST">
            <label class="fw-bold mb-3">Message:</label>
            <textarea name="message" class="form-control mb-5 text-align- center" rows="6" required></textarea>

            <button class="btn btn-success px-4">Post</button>
            <a href="announcements.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

</div>
</body>
</html>

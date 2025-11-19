<?php
session_start();
require_once __DIR__ . '/includes/db.php';
$pdo = db();

// Check login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];
$feedback_id = $_GET['id'] ?? 0;

// Fetch feedback
$stmt = $pdo->prepare("SELECT * FROM feedback WHERE id = ? AND user_id = ?");
$stmt->execute([$feedback_id, $user_id]);
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
    <title>Feedback Details</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/submit_feedback.css">
</head>
<body>
<div class="form-container">

    <!-- Header -->
    <h2 class="form-header text-center mb-4">FARMER FEEDBACK AND GOVERNANCE FORM</h2>

    <!-- Date & Time box -->
    <div class="date-time-box mb-4">
        <div class="d-flex justify-content-end">
            <div class="date-time-inner p-2 border rounded">
                <div class="mb-2"><strong>Date:</strong> <?= htmlspecialchars($fb['date']) ?></div>
                <div><strong>Time:</strong> <?= htmlspecialchars($fb['time']) ?></div>
            </div>
        </div>
    </div>

    <!-- Feedback details -->
    <div class="form-group">
        <label>Farmer’s Name:</label>
        <div class="line"><?= htmlspecialchars($fb['farmer_name']) ?></div>
    </div>

    <div class="form-group">
        <label>Organization / Farmers’ Group:</label>
        <div class="line"><?= htmlspecialchars($fb['organization']) ?></div>
    </div>

    <div class="form-group">
        <label>Type of Concern:</label>
        <div class="line"><?= htmlspecialchars($fb['concern_type']) ?></div>
    </div>

    <div class="form-group">
        <label>Details of Complaint / Feedback:</label>
        <div class="line" style="min-height:120px;"><?= nl2br(htmlspecialchars($fb['details'])) ?></div>
    </div>

    <div class="form-group">
        <label>Status:</label>
        <div class="line"><?= htmlspecialchars($fb['status']) ?></div>
    </div>

    <div class="form-group">
        <label>Farmer’s Signature:</label>
        <div class="signature-line"></div>
    </div>

    <button class="btn btn-primary mt-3" onclick="window.print()">Print Feedback</button>
</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

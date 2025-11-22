<?php
session_start();
require_once __DIR__ . '/../CONFIG/db.php';
$pdo = db();

// Get feedback id
$feedback_id = $_GET['id'] ?? 0;

// Fetch feedback
$stmt = $pdo->prepare("SELECT * FROM feedback WHERE id = ?");
$stmt->execute([$feedback_id]);
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
<title>Barangay & Governance Feedback Form</title>
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f8f9fa;
    }
    .form-container {
        max-width: 900px;
        margin: 30px auto;
        padding: 30px;
        border: 2px solid #000;
        background: #fff;
    }
    .form-header {
        text-align: center;
        margin-bottom: 20px;
    }
    .form-header h2 {
        margin: 0;
        font-weight: bold;
        text-decoration: underline;
    }
    .line {
        border-bottom: 1px solid #000;
        padding: 5px;
        min-height: 25px;
        margin-bottom: 15px;
    }
    .section-label {
        font-weight: bold;
    }
    .row-cols {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }
    .col-left, .col-right {
        width: 48%;
    }
    .details-line {
        min-height: 80px;
        border: 1px solid #000;
        padding: 5px;
        margin-top: 5px;
    }
    .signature-row {
        display: flex;
        justify-content: space-between;
        margin-top: 50px;
    }
    .signature-block {
        width: 30%;
        text-align: center;
    }
    .signature-line {
        border-bottom: 1px solid #000;
        height: 50px;
        margin-top: 5px;
    }
    @media print {
        body { background: #fff; }
        .form-container { border: none; padding: 0; }
        button { display: none; }
    }
</style>
</head>
<body>

<div class="form-container">

    <!-- Header -->
    <div class="form-header">
        <h2>BARANGAY FEEDBACK & GOVERNANCE FORM</h2>
        <p class="mb-3">Official Record of Complaints / Feedbacks</p>
    </div>

    <!-- Date & Time -->
    <div class="row-cols">
        <div class="col-left"><strong>Date Submitted:</strong> <?= htmlspecialchars($fb['date']) ?></div>
        <div class="col-right"><strong>Time:</strong> <?= htmlspecialchars($fb['time']) ?></div>
    </div>

    <!-- Two-column Farmer Info -->
    <div class="row-cols">
        <div class="col-left">
            <label class="section-label">Farmer’s Name:</label>
            <div class="line"><?= htmlspecialchars($fb['farmer_name']) ?></div>

            <label class="section-label">Organization / Farmers’ Group:</label>
            <div class="line"><?= htmlspecialchars($fb['organization']) ?></div>
        </div>

        <div class="col-right">
            <label class="section-label">Phone Number:</label>
            <div class="line"><?= htmlspecialchars($fb['phone'] ?? '-') ?></div>

            <label class="section-label">Type of Concern:</label>
            <div class="line"><?= htmlspecialchars($fb['concern_type']) ?></div>
        </div>
    </div>

    <!-- Details -->
    <div>
        <label class="section-label">Details of Complaint / Feedback:</label>
        <div class="details-line"><?= nl2br(htmlspecialchars($fb['details'])) ?></div>
    </div>

    <!-- Status -->
    <div class="mt-5">
        <label class="section-label">Status:</label>
        <div class="line"><?= htmlspecialchars($fb['status']) ?></div>
    </div>

    <!-- Signature Row -->
    <div class="signature-row">
        <div class="signature-block">
            <label>Farmer’s Signature:</label>
            <div class="signature-line"></div>
        </div>
        <div class="signature-block">
            <label>Barangay Captain / Governance Officer:</label>
            <div class="signature-line"></div>
        </div>
        <div class="signature-block">
            <label>Barangay / Governance Official Signature:</label>
            <div class="signature-line"></div>
        </div>
    </div>

    <button class="btn btn-primary mt-4" onclick="window.print()">Print Feedback</button>
</div>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

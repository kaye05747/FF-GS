<?php
session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '../includes/header.php';

$pdo = db();
$stmt = $pdo->query("SELECT * FROM feedback ORDER BY id DESC");
$feedback = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Feedback List</title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h3>Feedback List</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Farmer Name</th>
                <th>Organization</th>
                <th>Concern Type</th>
                <th>Details</th>
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
                    <td><?= htmlspecialchars($fb['concern_type']) ?></td>
                    <td><?= htmlspecialchars($fb['details']) ?></td>
                    <td><?= htmlspecialchars($fb['status']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">No feedback found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
            <p class="text-center mt-2">
                <a href="dashboard.php" class="btn-back">⬅ Back to Dashboard</a>
            </p>
</div>

<?php include 'includes/footer.php'; ?>
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

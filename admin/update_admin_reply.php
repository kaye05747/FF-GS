<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/db.php';
checkAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback_id'], $_POST['admin_reply'])) {
    $feedback_id = sanitize($_POST['feedback_id']);
    $admin_reply = sanitize($_POST['admin_reply']);

    $pdo = db();

    // Check if the is_read column exists, and add it if it doesn't
    try {
        $pdo->query("SELECT is_read FROM feedbacks LIMIT 1");
    } catch (PDOException $e) {
        if ($e->getCode() === '42S22') { // Column not found
            $pdo->exec("ALTER TABLE feedbacks ADD COLUMN is_read BOOLEAN NOT NULL DEFAULT 0");
        }
    }

    $stmt = $pdo->prepare("UPDATE feedbacks SET admin_reply = ?, is_read = 0 WHERE id = ?");
    $stmt->execute([$admin_reply, $feedback_id]);
}

header("Location: admin_ui.php#view-feedback");
exit;
?>
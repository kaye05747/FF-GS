<?php
require_once __DIR__ . '/../includes/functions.php';
checkAdmin();
$pdo = db();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Optional: Delete associated photo file
    $stmt = $pdo->prepare("SELECT photo FROM feedbacks WHERE id = ?");
    $stmt->execute([$id]);
    $feedback = $stmt->fetch();
    if ($feedback && $feedback['photo']) {
        $photo_path = __DIR__ . '/../uploads/' . $feedback['photo'];
        if (file_exists($photo_path)) {
            unlink($photo_path);
        }
    }

    $stmt = $pdo->prepare("DELETE FROM feedbacks WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: admin_feedback.php?deleted=1');
exit;
?>
<?php
require_once __DIR__ . '/../includes/functions.php';
checkAdmin();

$pdo = db();
$user_id = $_GET['user_id'] ?? null;

if (!$user_id) {
    header("Location: admin_feedback.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message_content = sanitize($_POST['message_content']);
    $sender_id = $_SESSION['user']['id']; // Assuming admin is the sender

    if (!empty($message_content)) {
        $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message_content) VALUES (?, ?, ?)");
        $stmt->execute([$sender_id, $user_id, $message_content]);
    }

    header("Location: admin_feedback.php?message_sent=1");
    exit;
}

// Fetch receiver's username for display
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$receiver = $stmt->fetch();

if (!$receiver) {
    header("Location: admin_feedback.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send Message to <?= htmlspecialchars($receiver['username']) ?></title>
    <link rel="stylesheet" href="../css/admin_dashboard.css">
</head>
<body>
    <div class="main-content">
        <div class="dashboard-section">
            <h2>Send Message to <?= htmlspecialchars($receiver['username']) ?></h2>
            <form method="post">
                <input type="hidden" name="user_id" value="<?= $user_id ?>">
                <textarea name="message_content" placeholder="Type your message here..." required></textarea>
                <button type="submit">Send Message</button>
            </form>
        </div>
    </div>
</body>
</html>
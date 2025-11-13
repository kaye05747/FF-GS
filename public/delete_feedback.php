<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();
$pdo = db();
$id = intval($_GET['id'] ?? 0);
// only allow delete if pending
$del = $pdo->prepare("DELETE FROM feedbacks WHERE id = ? AND user_id = ? AND status = 'Pending'");
$del->execute([$id, $_SESSION['user']['id']]);
header('Location: feedback_list.php');
exit;

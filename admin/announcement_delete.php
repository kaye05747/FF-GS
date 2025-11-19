<?php
session_start();
require_once "../config/db.php";
$pdo = db();

if (!isset($_GET['id'])) die("Invalid ID");

$stmt = $pdo->prepare("DELETE FROM announcements WHERE id=?");
$stmt->execute([$_GET['id']]);

$_SESSION['success'] = "Announcement deleted!";
header("Location: announcement.php");
exit;

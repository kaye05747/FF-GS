<?php
session_start();
require_once __DIR__ . '/config/db.php';

$pdo = db();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get POST data
$date         = $_POST['date'];
$time         = $_POST['time'];
$farmer_name  = $_POST['farmer_name'];
$organization = $_POST['organization'];
$concern_type = $_POST['concern_type'];
$details      = $_POST['details'];
$status       = "Pending";

// Insert into DB
$stmt = $pdo->prepare("INSERT INTO feedback 
    (date, time, farmer_name, organization, concern_type, details, status)
    VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([
    $date, $time, $farmer_name, $organization,
    $concern_type, $details, $status
]);

// Redirect to feedback list
header("Location: feedback_list.php");
exit;
?>

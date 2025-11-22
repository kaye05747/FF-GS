<?php
session_start();
require_once __DIR__ . '/config/db.php';

$pdo = db();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get POST data
$farmer_name = $_POST['farmer_name'];
$phone = $_POST['phone']; // ✅ new
$organization = $_POST['organization'];
$concern_type = $_POST['concern_type'];
$details = $_POST['details'];
$date = $_POST['date'];
$time = $_POST['time'];

// Insert into database
$stmt = $pdo->prepare("
    INSERT INTO feedback (date, time, farmer_name, phone, organization, concern_type, details, status)
    VALUES (:date, :time, :farmer_name, :phone, :organization, :concern_type, :details, 'Pending')
");

$stmt->execute([
    ':date' => $date,
    ':time' => $time,
    ':farmer_name' => $farmer_name,
    ':phone' => $phone,
    ':organization' => $organization,
    ':concern_type' => $concern_type,
    ':details' => $details
]);


// Redirect to feedback list
header("Location: feedback_list.php");
exit;
?>

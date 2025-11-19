<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
checkAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Panel</title>
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<style>
    body {
        background: #f5f5f5;
    }
    .sidebar {
        height: 100vh;
        width: 250px;
        position: fixed;
        left: 0;
        top: 0;
        background: #212529;
        color: white;
        padding-top: 20px;
    }
    .sidebar a {
        color: #ddd;
        padding: 12px 20px;
        display: block;
        text-decoration: none;
    }
    .sidebar a:hover {
        background: #495057;
        color: white;
    }
    .content {
        margin-left: 260px;
        padding: 20px;
    }
</style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h4 class="text-center mb-4">Admin Panel</h4>

    <a href="admin_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="announcement.php"><i class="bi bi-megaphone"></i> Announcement</a>
    <a href="monthly_tally.php"><i class="bi bi-calendar3"></i> Monthly Tally</a>
    <a href="borrow_equipment.php"><i class="bi bi-tools"></i> Borrow Equipment</a>
    <a href="view_feedback.php"><i class="bi bi-chat-dots"></i> View Feedback</a>
    <hr class="bg-light">
    <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- MAIN CONTENT -->
<div class="content">

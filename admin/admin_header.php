<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Optional: Only allow admins to access pages using this header
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: admin_login.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel | Farmer Feedback & Governance System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .admin-header {
      background-color: #198754; /* Bootstrap success color */
      color: white;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    .admin-header h4 {
      margin: 0;
      font-weight: 600;
    }
    .admin-header a {
      color: white;
      text-decoration: none;
      font-weight: 500;
      margin-left: 15px;
    }
    .admin-header a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="admin-header">
    <h4>🌾 Admin Dashboard</h4>
    <div>
      <span>Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
      <a href="admin_ui.php">Dashboard</a>
      <a href="admin_feedbacks.php">Feedbacks</a>
      <a href="admin_announcements.php">Announcements</a>
      <a href="../logout.php" class="text-warning">Logout</a>
    </div>
  </div>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Farmer Feedback & Governance</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 60px;
      background-color: #2e7d32; /* Original color */
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 40px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      z-index: 1000;
    }

    .logo {
      font-weight: bold;
      font-size: 18px;
    }

    nav {
      display: flex;
      align-items: center;
    }

    nav a {
      color: white;
      text-decoration: none;
      margin-left: 15px;
      padding: 6px 12px;
      border-radius: 5px;
      transition: background 0.3s;
    }

    nav a:hover {
      background-color: #388e3c;
    }

    .auth-links {
      display: flex;
      align-items: center;
      margin-left: auto;
    }

    body {
      margin: 0;
      padding-top: 70px; /* Prevent content from hiding behind header */
    }

    .notification-count {
      position: absolute;
      top: -8px;
      right: -8px;
      background-color: red;
      color: white;
      border-radius: 50%;
      padding: 2px 6px;
      font-size: 12px;
    }
  </style>
</head>

<header>
  <div class="logo">
    🌾 FARMER'S FEEDBACK AND GRIEVANCE SYSTEM
  </div>

    <nav>
      <?php 
      if (isset($_SESSION['user'])):
      ?>
      <div class="dropdown" style="margin-right: 15px;">
        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="position: relative; color: white;">
          <i class="fas fa-bell"></i>
          <span id="notificationCount" class="notification-count"></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end notification-dropdown"></ul>
      </div>
      <?php endif; ?>
      <?php
      if (isset($_SESSION['user'])) {
          echo '<a href="dashboard.php" class="btn">Home</a>';
          echo '<a href="logout.php" class="btn register">Logout</a>';
      } else {
          echo '<a href="index.php" class="btn">Home</a>';
          echo '<div class="auth-links">';
          echo '<a href="login.php" class="btn">Login</a>';
          echo '<a href="register.php" class="btn">Register</a>';
          echo '</div>';
      }
      ?>
    </nav>
  </div>
</header>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="js/main.js"></script>

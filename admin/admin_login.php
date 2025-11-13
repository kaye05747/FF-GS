<?php
session_start();
require_once __DIR__ . '/../config/db.php';
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['user'] = ['username' => $username, 'role' => 'admin'];
        header('Location: admin_ui.php');
        exit;
    } else {
        $error = "Invalid admin credentials.";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login | Farmer Feedback System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* 🔰 Bigger, more modern header */
    .admin-header {
      background: linear-gradient(90deg, #198754, #157347);
      color: white;
      padding: 15px 20px;
      text-align: center;
      font-weight: 500;
      font-size: 1.8rem;
      letter-spacing: 1px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    .login-container {
      max-width: 400px;
      margin: 100px auto 60px;
      background: white;
      padding: 35px;
      border-radius: 15px;
      box-shadow: 0 3px 15px rgba(0,0,0,0.1);
    }

    .login-container h2 {
      font-weight: 600;
    }

    .login-container button {
      width: 100%;
      padding: 10px;
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    /* 🌿 Footer design */
    footer {
      background-color: #198754;
      color: white;
      text-align: center;
      padding: 10px 0;
      position: fixed;
      bottom: 0;
      width: 100%;
      font-size: 0.9rem;
      letter-spacing: 0.5px;
    }
  </style>
</head>

<body>
  <div class="admin-header">🌾 Farmer Feedback System — Admin Access Only</div>

  <div class="login-container">
    <h2 class="text-center text-success">Admin Login</h2>
    <?php if (isset($error)): ?>
      <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="mb-3">
        <label for="username" class="form-label">Admin Username:</label>
        <input type="text" id="username" name="username" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password:</label>
        <input type="password" id="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-success">Login</button>
    </form>
  </div>

  <footer>
    &copy; <?= date('Y') ?> Farmer Feedback & Governance System | Admin Portal
  </footer>
</body>
</html>

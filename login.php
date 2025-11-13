<?php
session_start();
require_once __DIR__ . '/includes/functions.php'; 
require_once __DIR__ . '/includes/header.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $pdo = db();

    // ✅ Use email instead of username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // ✅ Store user session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role'] ?? 'user'
        ];

        header("Location: dashboard.php");
        exit;
    } else {
        $errors[] = "Invalid email or password.";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | Farmer Feedback & Governance System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/login.css">
</head>

<body>
  <div class="login-container p-4">
    <h2>Login</h2>

    <!-- <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <?= implode('<br>', array_map('htmlspecialchars', $errors)) ?>
      </div>
    <?php endif; ?> -->

    <form action="login.php" method="POST">
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required>
      </div>

      <div class="d-flex justify-content-center mt-2">
        <button type="submit" class="btn btn-success px-5">Login</button>
      </div>

      <p class="mt-3">Don't have an account? <a href="register.php">Register here</a></p>
    </form>
  </div>

  <?php include 'includes/footer.php'; ?>
</body>
</html>

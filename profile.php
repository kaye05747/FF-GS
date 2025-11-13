<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
require_login();
$pdo = db();
$uid = $_SESSION['user']['id'];
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? '')) $errors[] = 'Invalid CSRF.';

    // --- Update profile info ---
    if (isset($_POST['update_info'])) {
        $name = sanitize($_POST['name'] ?? '');
        $barangay = sanitize($_POST['barangay'] ?? '');
        $contact = sanitize($_POST['contact'] ?? '');
        $farm_type = sanitize($_POST['farm_type'] ?? '');
        $photo_name = $user['photo'] ?? null;

        // Handle photo upload
        if (!empty($_FILES['photo']['name'])) {
            $f = $_FILES['photo'];
            $allowed = ['image/jpeg','image/png','image/jpg'];
            if ($f['error'] === 0 && in_array($f['type'],$allowed) && $f['size'] <= 5*1024*1024) {
                $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
                $photo_name = uniqid('u_') . '.' . $ext;
                move_uploaded_file($f['tmp_name'], __DIR__ . '/uploads/' . $photo_name);
            } else {
                $errors[] = 'Invalid photo (jpg/png, ≤5MB).';
            }
        }

        if (!$name) $errors[] = 'Name required.';
        if (empty($errors)) {
            $upd = $pdo->prepare("UPDATE users SET name=?, barangay=?, contact=?, farm_type=?, photo=? WHERE id=?");
            $upd->execute([$name, $barangay, $contact, $farm_type, $photo_name, $uid]);
            $_SESSION['user']['name'] = $name;
            $success = 'Profile updated.';
        }
    }

    // --- Change password ---
    elseif (isset($_POST['change_pw'])) {
        $old = $_POST['old_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        if (!$old || !$new) $errors[] = 'Fill both password fields.';
        if (empty($errors)) {
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id=?");
            $stmt->execute([$uid]);
            $row = $stmt->fetch();
            if (!$row || !password_verify($old, $row['password'])) $errors[] = 'Old password incorrect.';
            else {
                $hash = password_hash($new, PASSWORD_DEFAULT);
                $upd = $pdo->prepare("UPDATE users SET password=? WHERE id=?");
                $upd->execute([$hash, $uid]);
                $success = 'Password changed.';
            }
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$uid]);
$user = $stmt->fetch();

if (!$user) {
    die("User not found.");
}

$token = csrf_token();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile</title>
  <link rel="stylesheet" href="css/profile.css">
</head>
<body>
  <main>
    <div class="profile-container">
      <h2>My Profile</h2>

      <?php foreach($errors as $e) echo "<p style='color:red'>$e</p>"; ?>
      <?php if($success) echo "<p style='color:green'>$success</p>"; ?>

      <!-- Profile Picture -->
      <div class="profile-pic">
        <?php if (!empty($user['photo']) && file_exists(__DIR__ . '/uploads/' . $user['photo'])): ?>
          <img src="uploads/<?= htmlspecialchars($user['photo']) ?>" alt="Profile Picture">
        <?php else: ?>
          <img src="images/default-user.png" alt="Default Profile">
        <?php endif; ?>
      </div>

      <!-- Update Info Form -->
      <form method="post" enctype="multipart/form-data" class="info-form">
        <input type="hidden" name="csrf" value="<?= $token ?>">
        <input type="hidden" name="update_info" value="1">

        <div class="row">
          <div class="col">
            <label>Name</label>
            <input name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

            <label>Barangay</label>
            <input name="barangay" value="<?= htmlspecialchars($user['barangay']) ?>">
          </div>

          <div class="col">
            <label>Contact</label>
            <input name="contact" value="<?= htmlspecialchars($user['contact']) ?>">

            <label>Farm Type</label>
            <input name="farm_type" value="<?= htmlspecialchars($user['farm_type']) ?>">
          </div>
        </div>

        <label>Profile Photo</label>
        <input type="file" name="photo" accept="image/*">

        <button type="submit">Update Info</button>
      </form>

      <hr>
      <h3>Change Password</h3>
      <form method="post" class="password-form">
        <input type="hidden" name="csrf" value="<?= $token ?>">
        <input type="hidden" name="change_pw" value="1">

        <div class="row">
          <div class="col">
            <label>Old Password</label>
            <input type="password" name="old_password" required>
          </div>
          <div class="col">
            <label>New Password</label>
            <input type="password" name="new_password" required>
          </div>
        </div>

        <button type="submit">Change Password</button>
      </form>

      <p><a href="submit_feedback.php">← Back to Dashboard</a></p>
    </div>
  </main>
  <?php include 'includes/footer.php'; ?>
</body>
</html>

<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
require_login();
$pdo = db();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? '')) $errors[] = 'Invalid CSRF token.';

    $title = sanitize($_POST['title'] ?? '');
    $type = sanitize($_POST['complaint_type'] ?? '');
    $desc = sanitize($_POST['description'] ?? '');
    $photo_name = null;

    // Basic validation
    if (!$title || !$type || !$desc) $errors[] = 'All fields are required.';

    // Handle image upload
    if (empty($errors) && !empty($_FILES['photo']['name'])) {
        $f = $_FILES['photo'];
        $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
        if ($f['error'] === 0 && in_array($f['type'], $allowed) && $f['size'] <= 5 * 1024 * 1024) {
            $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
            $photo_name = uniqid('p_') . '.' . $ext;
            move_uploaded_file($f['tmp_name'], __DIR__ . '/uploads/' . $photo_name);
        } else {
            $errors[] = 'Invalid photo (jpg/png, ≤5MB).';
        }
    }

    // Save feedback to database
    if (empty($errors)) {
        $ins = $pdo->prepare("
            INSERT INTO feedbacks (user_id, title, type, description, photo, status)
            VALUES (?, ?, ?, ?, ?, 'Pending')
        ");
        $ins->execute([$_SESSION['user']['id'], $title, $type, $desc, $photo_name]);
        header('Location: feedback_list.php?created=1');
        exit;
    }
}

$token = csrf_token();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Submit Feedback</title>
  <link rel="stylesheet" href="css/submit_feedback.css">
</head>
<body>
<main class="main-content">
  <div class="feedback-container">
    <h2>Submit Farmer Feedback</h2>

    <?php foreach ($errors as $e): ?>
      <p class="error"><?= htmlspecialchars($e) ?></p>
    <?php endforeach; ?>

    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrf" value="<?= $token ?>">

      <div class="row">
        <label>Title*</label>
        <input type="text" name="title" placeholder="Enter feedback title" required>
      </div>

      <div class="row">
        <label>Type of Complaint / Concern*</label>
        <select name="complaint_type" required>
          <option value="" disabled selected>-- Select complaint type --</option>
          <option value="Subsidy Delay">Subsidy Delay</option>
          <option value="Crop Insurance Issue">Crop Insurance Issue</option>
          <option value="Equipment Request">Equipment Request</option>
          <option value="Training or Seminar Request">Training or Seminar Request</option>
          <option value="Infrastructure Problem">Infrastructure Problem</option>
          <option value="Other">Other</option>
        </select>
      </div>

      <div class="row">
        <label>Description*</label>
        <textarea name="description" placeholder="Describe your feedback or concern..." required></textarea>
      </div>

      <div class="row">
        <label>Photo (optional)</label>
        <input type="file" name="photo" accept="image/*">
      </div>

      <button type="submit">Send Feedback</button>
    </form>

    <div class="nav-links">
      <a href="dashboard.php">Back to Dashboard</a> | 
      <a href="profile.php">Profile</a>  
    </div>
  </div>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>

<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
require_login();
$pdo = db();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM feedbacks WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user']['id']]);
$feedback = $stmt->fetch();

if (!$feedback) {
    die("Feedback not found or unauthorized.");
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? '')) $errors[] = 'Invalid CSRF.';
    $title = sanitize($_POST['title'] ?? '');
    $type = sanitize($_POST['complaint_type'] ?? '');
    $desc = sanitize($_POST['description'] ?? '');
    $photo_name = $feedback['photo'];

    if (!$title || !$type || !$desc) $errors[] = 'All fields are required.';

    if (empty($errors) && !empty($_FILES['photo']['name'])) {
        $f = $_FILES['photo'];
        $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
        if ($f['error'] === 0 && in_array($f['type'], $allowed)) {
            $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
            $photo_name = uniqid('p_') . '.' . $ext;
            move_uploaded_file($f['tmp_name'], __DIR__ . '/uploads/' . $photo_name);
        }
    }

    if (empty($errors)) {
        $upd = $pdo->prepare("UPDATE feedbacks SET title=?, complaint_type=?, description=?, photo=? WHERE id=? AND user_id=?");
        $upd->execute([$title, $type, $desc, $photo_name, $id, $_SESSION['user']['id']]);
        header('Location: feedback_list.php?updated=1');
        exit;
    }
}

$token = csrf_token();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Edit Feedback</title>
  <link rel="stylesheet" href="css/submit_feedback.css">
</head>
<body>
  <main>
    <div class="feedback-container">
      <h2>Edit Feedback</h2>

      <?php foreach ($errors as $e): ?>
        <p class="error"><?= htmlspecialchars($e) ?></p>
      <?php endforeach; ?>

      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf" value="<?= $token ?>">

        <label>Title*</label>
        <input type="text" name="title" value="<?= htmlspecialchars($feedback['title']) ?>" required>

        <label>Type of Complaint / Concern*</label>
        <select name="complaint_type" required>
          <option value="Subsidy Delay" <?= $feedback['complaint_type'] == 'Subsidy Delay' ? 'selected' : '' ?>>Subsidy Delay</option>
          <option value="Crop Insurance Issue" <?= $feedback['complaint_type'] == 'Crop Insurance Issue' ? 'selected' : '' ?>>Crop Insurance Issue</option>
          <option value="Equipment Request" <?= $feedback['complaint_type'] == 'Equipment Request' ? 'selected' : '' ?>>Equipment Request</option>
          <option value="Training or Seminar Request" <?= $feedback['complaint_type'] == 'Training or Seminar Request' ? 'selected' : '' ?>>Training or Seminar Request</option>
          <option value="Infrastructure Problem" <?= $feedback['complaint_type'] == 'Infrastructure Problem' ? 'selected' : '' ?>>Infrastructure Problem</option>
          <option value="Other" <?= $feedback['complaint_type'] == 'Other' ? 'selected' : '' ?>>Other</option>
        </select>

        <label>Description*</label>
        <textarea name="description" required><?= htmlspecialchars($feedback['description']) ?></textarea>

        <label>Photo (optional)</label>
        <input type="file" name="photo" accept="image/*">
        <?php if ($feedback['photo']): ?>
          <p>Current: <img src="uploads/<?= htmlspecialchars($feedback['photo']) ?>" class="thumb"></p>
        <?php endif; ?>

        <button type="submit">Save Changes</button>
      </form>

      <p><a href="feedback_list.php">Back to Feedback List</a></p>
    </div>
  </main>

  <?php include 'includes/footer.php'; ?>
</body>
</html>

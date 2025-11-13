<?php
require_once __DIR__ . '/../includes/functions.php';
checkAdmin();
$pdo = db();
$id = intval($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM feedbacks WHERE id = ?");
$stmt->execute([$id]);
$fb = $stmt->fetch();
if (!$fb) { echo "Feedback not found."; exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? '')) $errors[]='Invalid CSRF.';
    $description = sanitize($_POST['description'] ?? '');
    $status = sanitize($_POST['status'] ?? '');

    $type = sanitize($_POST['type'] ?? '');

    if (!$description || !$status || !$type) $errors[]='Please fill all fields.';

    $photo_name = $fb['photo'];
    if (empty($errors) && !empty($_FILES['photo']['name'])) {
        $f = $_FILES['photo'];
        $allowed = ['image/jpeg','image/png','image/jpg'];
        if ($f['error']===0 && in_array($f['type'],$allowed) && $f['size'] <= 5*1024*1024) {
            $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
            $photo_name = uniqid('p_') . '.' . $ext;
            move_uploaded_file($f['tmp_name'], __DIR__ . '/../uploads/' . $photo_name);
        } else $errors[]='Invalid photo.';
    }

    if (empty($errors)) {
        $upd = $pdo->prepare("UPDATE feedbacks SET description=?, status=?, photo=?, type=?, updated_at=NOW() WHERE id=?");
        $upd->execute([$description, $status, $photo_name, $type, $id]);
        header('Location: admin_feedback.php?edited=1');
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
  <link rel="stylesheet" href="../css/admin_feedback.css">
</head>
<body>
  <h1>Edit Feedback</h1>
  <?php foreach($errors as $e) echo "<p style='color:red'>$e</p>"; ?>
  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= $token ?>">
    <label>Description <textarea name="description" required><?= htmlspecialchars($fb['description']) ?></textarea></label><br>
    <label>Type
      <select name="type">
        <option value="Subsidy Delay" <?= $fb['type']=='Subsidy Delay'?'selected':'' ?>>Subsidy Delay</option>
        <option value="Crop Insurance Issue" <?= $fb['type']=='Crop Insurance Issue'?'selected':'' ?>>Crop Insurance Issue</option>
        <option value="Equipment Request" <?= $fb['type']=='Equipment Request'?'selected':'' ?>>Equipment Request</option>
        <option value="Training or Seminar Request" <?= $fb['type']=='Training or Seminar Request'?'selected':'' ?>>Training or Seminar Request</option>
        <option value="Infrastructure Problem" <?= $fb['type']=='Infrastructure Problem'?'selected':'' ?>>Infrastructure Problem</option>
        <option value="Other" <?= $fb['type']=='Other'?'selected':'' ?>>Other</option>
      </select>
    </label><br>
    <label>Status
      <select name="status">
        <option value="Pending" <?= $fb['status']=='Pending'?'selected':'' ?>>Pending</option>
        <option value="In Progress" <?= $fb['status']=='In Progress'?'selected':'' ?>>In Progress</option>
        <option value="Completed" <?= $fb['status']=='Completed'?'selected':'' ?>>Completed</option>
      </select>
    </label><br>
    <p>Current photo: <?php if($fb['photo']) echo "<a href='/FF&GS/uploads/{$fb['photo']}' target='_blank'>View</a>"; else echo "None"; ?></p>
    <label>Replace photo <input type="file" name="photo" accept="image/*"></label><br>
    <button type="submit">Save</button>
  </form>
  <p><a href="admin_feedback.php">⬅ Back to Feedback Management</a></p>
</body>
</html>

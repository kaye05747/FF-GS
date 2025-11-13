<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();
$pdo = db();
$id = intval($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM feedbacks WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user']['id']]);
$fb = $stmt->fetch();
if (!$fb) { echo "Not found."; exit; }
if ($fb['status'] !== 'Pending') { echo "Cannot edit; feedback already processed."; exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? '')) $errors[]='Invalid CSRF.';
    $title = sanitize($_POST['title'] ?? '');
    $desc = sanitize($_POST['description'] ?? '');
    if (!$title || !$desc) $errors[]='Please fill fields.';

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
        $upd = $pdo->prepare("UPDATE feedbacks SET title=?, description=?, photo=?, updated_at=NOW() WHERE id=? AND user_id=?");
        $upd->execute([$title, $desc, $photo_name, $id, $_SESSION['user']['id']]);
        header('Location: feedback_list.php?edited=1');
        exit;
    }
}

$token = csrf_token();
?>
<!doctype html>
<html><head><link rel="stylesheet" href="/css/main.css"></head><body>
<h2>Edit Feedback</h2>
<?php foreach($errors as $e) echo "<p style='color:red'>$e</p>"; ?>
<form method="post" enctype="multipart/form-data">
  <input type="hidden" name="csrf" value="<?= $token ?>">
  <label>Title <input name="title" value="<?= htmlspecialchars($fb['title']) ?>" required></label><br>
  <label>Description <textarea name="description" required><?= htmlspecialchars($fb['description']) ?></textarea></label><br>
  <p>Current photo: <?php if($fb['photo']) echo "<a href='/uploads/{$fb['photo']}' target='_blank'>View</a>"; else echo "None"; ?></p>
  <label>Replace photo <input type="file" name="photo" accept="image/*"></label><br>
  <button type="submit">Save</button>
</form>
<p><a href="feedback_list.php">Back</a></p>
</body></html>

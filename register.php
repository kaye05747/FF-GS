<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
$pdo = db();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? '')) $errors[] = 'Invalid CSRF token.';

    // Sanitize inputs
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $name = sanitize($_POST['name'] ?? '');
    $lastname = sanitize($_POST['lastname'] ?? '');
    $age = sanitize($_POST['age'] ?? '');
    $contact = sanitize($_POST['contact'] ?? '');
    $barangay = sanitize($_POST['barangay'] ?? '');
    $purok = sanitize($_POST['purok'] ?? '');
    $municipality = sanitize($_POST['municipality'] ?? '');
    $province = sanitize($_POST['province'] ?? '');
    $farm_type = sanitize($_POST['farm_type'] ?? null);
    $farm_size = sanitize($_POST['farm_size'] ?? null);
    $email = sanitize($_POST['email'] ?? '');

    // Validation
    if (empty($username)) $errors[] = 'Username is required.';
    if (empty($email)) $errors[] = 'Email is required.';
    if (empty($name)) $errors[] = 'First name is required.';
    if (empty($lastname)) $errors[] = 'Last name is required.';
    if (empty($password)) $errors[] = 'Password is required.';
    if ($password !== $confirm_password) $errors[] = 'Passwords do not match.';

    // Username uniqueness
    if (!empty($username)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) $errors[] = 'Username already taken.';
    }

    // Email uniqueness
    if (!empty($email)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) $errors[] = 'Email already registered.';
    }

    // Insert if no errors
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users 
            (username, password, name, email, lastname, barangay, municipality, province, contact, farm_type, farm_size, role, is_active, created_at, is_admin, age, purok)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 0, ?, ?)");

        $stmt->execute([
            $username, $hash, $name, $email, $lastname, $barangay, $municipality, $province, $contact,
            $farm_type, $farm_size, 'farmer', 1, $age, $purok
        ]);

        header('Location: login.php?registered=1');
        exit;
    }
}

$token = csrf_token();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | Farmer Feedback & Governance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
<div class="container d-flex justify-content-center align-items-center min-vh-100 mt-5">    
    <div class="register-container p-4 bg-gray rounded shadow w-100" style="max-width: 800px;">
        <h2 class="text-center text-success mb-4">Create Farmer Account</h2>

        <!-- <?php if(!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach($errors as $e): ?>
                    <div><?= htmlspecialchars($e) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?> -->

        <form method="post" action="register.php" autocomplete="off">
            <input type="hidden" name="csrf" value="<?= $token ?>">

            <!-- Personal Information -->
            <h5 class="text-success mb-2 border-bottom pb-1">Personal Information</h5>
            <div class="row g-2 mb-3">
                <div class="col-md-3">
                    <label class="form-label small">First Name*</label>
                    <input type="text" class="form-control form-control-sm" name="name" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Last Name*</label>
                    <input type="text" class="form-control form-control-sm" name="lastname" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Age*</label>
                    <input type="number" class="form-control form-control-sm" name="age" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Contact*</label>
                    <input type="text" class="form-control form-control-sm" name="contact" required>
                </div>
            </div>

            <!-- Address Information -->
            <h5 class="text-success mb-2 border-bottom pb-1">Address Information</h5>
            <div class="row g-2 mb-3">
                <div class="col-md-3">
                    <label class="form-label small">Purok*</label>
                    <input type="text" class="form-control form-control-sm" name="purok" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Barangay*</label>
                    <input type="text" class="form-control form-control-sm" name="barangay" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Municipality*</label>
                    <input type="text" class="form-control form-control-sm" name="municipality" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Province*</label>
                    <input type="text" class="form-control form-control-sm" name="province" required>
                </div>
            </div>

            <!-- Account Information -->
            <h5 class="text-success mb-2 border-bottom pb-1">Account Information</h5>
            <div class="row g-2 mb-3">
                <div class="col-md-3">
                    <label class="form-label small">Email*</label>
                    <input type="email" class="form-control form-control-sm" name="email" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Username*</label>
                    <input type="text" class="form-control form-control-sm" name="username" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Password*</label>
                    <input type="password" class="form-control form-control-sm" name="password" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Confirm Password*</label>
                    <input type="password" class="form-control form-control-sm" name="confirm_password" required>
                </div>
            </div>

            <!-- Farm Information -->
            <h5 class="text-success mb-2 border-bottom pb-1">Farm Information</h5>
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label small">Farm Type</label>
                    <input type="text" class="form-control form-control-sm" name="farm_type">
                </div>
                <div class="col-md-6">
                    <label class="form-label small">Farm Size (hectares)</label>
                    <input type="text" class="form-control form-control-sm" name="farm_size">
                </div>
            </div>

            <div class="d-flex justify-content-center mt-3">
                            <button type="submit" class="btn btn-success px-5 btn-sm">Register</button>
                        </div>
        </form>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>

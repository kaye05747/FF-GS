<?php
// Start session early
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/includes/functions.php';
$pdo = db();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? '')) $errors[] = 'Invalid CSRF token.';

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

    if (empty($username)) $errors[] = 'Username is required.';
    if (empty($email)) $errors[] = 'Email is required.';
    if (empty($name)) $errors[] = 'First name is required.';
    if (empty($lastname)) $errors[] = 'Last name is required.';
    if (empty($password)) $errors[] = 'Password is required.';
    if ($password !== $confirm_password) $errors[] = 'Passwords do not match.';

    if (!empty($username)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) $errors[] = 'Username already taken.';
    }

    if (!empty($email)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) $errors[] = 'Email already registered.';
    }

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users 
            (username, password, name, email, lastname, barangay, municipality, province, contact, farm_type, farm_size, role, is_active, created_at, is_admin, age, purok)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 0, ?, ?)");

        $stmt->execute([
            $username, $hash, $name, $email, $lastname, $barangay, $municipality, $province, $contact,
            $farm_type, $farm_size, 'farmer', 1, $age, $purok
        ]);

        // Redirect immediately after successful registration
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
     <style>
    html, body {
        height: 100%;
        margin: 0;
    }

    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .content-wrapper {
        flex: 1; /* pushes the footer to bottom */
    }

    footer {
        background-color: #2e7d32;
        color: white;
        text-align: center;
        font-size: 15px;
        box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        width: 100%;
        padding: 8px 0;
    }
</style>

    
</head>
<body>
<?php require_once __DIR__ . '/includes/header.php'; ?>

<div class="content-wrapper">

    <div class="container d-flex justify-content-center align-items-center min-vh-100 mt-2">
        <div class="register-container p-3 rounded shadow w-100"
     style="max-width:700px; background:rgba(255, 255, 255, 0.33); backdrop-filter:blur(3px);">


        <h2 class="text-center text-success mb-3 text-white">Register</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $e): ?>
                    <div><?= htmlspecialchars($e) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="register.php" autocomplete="off">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($token) ?>">

            <!-- Personal Information -->
            <h5 class="text-success mb-1 text-white">Personal Information</h5>
            <div class="row g-2 mb-3">
                <div class="col-md-3">
                    <label class="form-label small text-white">First Name*</label>
                    <input type="text" class="form-control form-control-sm border border-dark" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-white">Last Name*</label>
                    <input type="text" class="form-control form-control-sm border border-dark" name="lastname" required value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-white">Age*</label>
                    <input type="number" class="form-control form-control-sm border border-dark" name="age" required value="<?= htmlspecialchars($_POST['age'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-white">Contact*</label>
                    <input type="text" class="form-control form-control-sm border border-dark" name="contact" required value="<?= htmlspecialchars($_POST['contact'] ?? '') ?>">
                </div>
            </div>

            <!-- Address Information -->
            <h5 class="text-success mb-2 text-white ">Address Information</h5>
            <div class="row g-2 mb-3">
                <div class="col-md-3">
                    <label class="form-label small text-white">Purok*</label>
                    <input type="text" class="form-control form-control-sm border border-dark" name="purok" required value="<?= htmlspecialchars($_POST['purok'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-white">Barangay*</label>
                    <input type="text" class="form-control form-control-sm border border-dark" name="barangay" required value="<?= htmlspecialchars($_POST['barangay'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-white">Municipality*</label>
                    <input type="text" class="form-control form-control-sm border border-dark" name="municipality" required value="<?= htmlspecialchars($_POST['municipality'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-white">Province*</label>
                    <input type="text" class="form-control form-control-sm border border-dark" name="province" required value="<?= htmlspecialchars($_POST['province'] ?? '') ?>">
                </div>
            </div>

            <!-- Account Information -->
            <h5 class="text-success mb-2 text-white">Account Information</h5>
            <div class="row g-2 mb-3">
                <div class="col-md-3">
                    <label class="form-label small text-white">Email*</label>
                    <input type="email" class="form-control form-control-sm border border-dark" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-white">Username*</label>
                    <input type="text" class="form-control form-control-sm border border-dark" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-white">Password*</label>
                    <input type="password" class="form-control form-control-sm border border-dark" name="password" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-white">Confirm Password*</label>
                    <input type="password" class="form-control form-control-sm border border-dark" name="confirm_password" required>
                </div>
            </div>

            <!-- Farm Information -->
            <h5 class="text-success mb-2 text-white">Farm Information</h5>
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label small text-white">Farm Type</label>
                    <input type="text" class="form-control form-control-sm border border-dark" name="farm_type" value="<?= htmlspecialchars($_POST['farm_type'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label small text-white">Farm Size (hectares)</label>
                    <input type="text" class="form-control form-control-sm border border-dark" name="farm_size" value="<?= htmlspecialchars($_POST['farm_size'] ?? '') ?>">
                </div>
            </div>

            <div class="d-flex justify-content-center mt-3">
                <button type="submit" class="btn btn-success px-5 btn-sm border border-dark">Register</button>
            </div>
        </form>
    </div>
</div>

<!-- Footer without gray background -->
<?php include 'includes/footer.php'; ?>

</body>
</html>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
$pdo = db();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? '')) {
        $errors[] = 'Invalid CSRF token.';
    }

    // ✅ Collect and sanitize all inputs
    $name = sanitize($_POST['name'] ?? '');
    $lastname = sanitize($_POST['lastname'] ?? '');
    $age = sanitize($_POST['age'] ?? '');
    $contact = sanitize($_POST['contact'] ?? '');
    $barangay = sanitize($_POST['barangay'] ?? '');
    $purok = sanitize($_POST['purok'] ?? '');
    $municipality = sanitize($_POST['municipality'] ?? '');
    $province = sanitize($_POST['province'] ?? '');
    $farm_type = sanitize($_POST['farm_type'] ?? '');
    $farm_size = sanitize($_POST['farm_size'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    // ✅ Validation checks
    if (empty($username)) {
        $errors[] = 'Username is required.';
    } else {
        $checkUser = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $checkUser->execute([$username]);
        if ($checkUser->fetch()) {
            $errors[] = 'Username already taken.';
        }
    }

    if (empty($email)) {
        $errors[] = 'Email is required.';
    }

    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    // ✅ Continue if no errors
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Email already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $ins = $pdo->prepare("INSERT INTO users 
                (email, password, username, lastname, age, contact, barangay, purok, municipality, province, farm_type, farm_size)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $ins->execute([
                $email,
                $hash,
                $username,
                $lastname,
                $age,
                $contact,
                $barangay,
                $purok,
                $municipality,
                $province,
                $farm_type,
                $farm_size
            ]);

            header('Location: login.php?registered=1');
            exit;
        }
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
    <div class="container d-flex justify-content-center align-items-center min-vh-0">
        <div class="register-container p-4 bg-gray rounded shadow w-100" style="max-width: 1000px;">
            <h2 class="text-center text-success mb-3">Create Farmer Account</h2>

            <!-- <?php if(!empty($errors)): ?>
      <div class="alert alert-danger">
        <?php foreach($errors as $e): ?>
          <div><?= htmlspecialchars($e) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?> -->

            <form method="post" action="register.php" autocomplete="off">
                <input type="hidden" name="csrf" value="<?= $token ?>">

                <h5 class="text-success mb-1">Personal Information</h5>
                <div class="row g-1 mb-2">
                    <div class="col-md-3">
                        <label class="form-label small">First Name*</label>
                        <input type="text" class="form-control form-control-sm" name="name" placeholder="Juan" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Last Name*</label>
                        <input type="text" class="form-control form-control-sm" name="lastname" placeholder="Dela Cruz"
                            required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Age*</label>
                        <input type="number" class="form-control form-control-sm" name="age" placeholder="Age" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Contact*</label>
                        <input type="text" class="form-control form-control-sm" name="contact" placeholder="09XXXXXXXXX"
                            required>
                    </div>
                </div>

                <h5 class="text-success mb-1">Address Information</h5>
                <div class="row g-2 mb-2">
                    <div class="col-md-3">
                        <label class="form-label small">Purok*</label>
                        <input type="text" class="form-control form-control-sm" name="purok" placeholder="Purok"
                            required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Barangay*</label>
                        <input type="text" class="form-control form-control-sm" name="barangay" placeholder="Barangay"
                            required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Municipality*</label>
                        <input type="text" class="form-control form-control-sm" name="municipality"
                            placeholder="Municipality" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Province*</label>
                        <input type="text" class="form-control form-control-sm" name="province" placeholder="Province"
                            required>
                    </div>
                </div>

                <h5 class="text-success mb-1">Account Information</h5>
                <div class="row g-2 mb-2">
                    <div class="col-md-3">
                        <label class="form-label small">Email *</label>
                        <input type="email" class="form-control form-control-sm" name="email"
                            placeholder="example@email.com" required>
                    </div>
                    <div class="col-md-3">
    <label class="form-label small">Username *</label>
    <input type="text" class="form-control form-control-sm" name="username"
        placeholder="Username" required>
</div>

                    <div class="col-md-3">
                        <label class="form-label small">Password *</label>
                        <input type="password" class="form-control form-control-sm" name="password"
                            placeholder="Enter password" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Confirm Password*</label>
                        <input type="password" class="form-control form-control-sm" name="confirm_password"
                            placeholder="Re-enter password" required>
                    </div>
                </div>

                <h5 class="text-success mb-1">Farm Information</h5>
                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <label class="form-label small">Farm Type</label>
                        <input type="text" class="form-control form-control-sm" name="farm_type"
                            placeholder="e.g., Rice, Corn, Vegetable">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Farm Size (in hectares)</label>
                        <input type="text" class="form-control form-control-sm" name="farm_size"
                            placeholder="e.g., 5.0">
                    </div>
                </div>

                <div class="d-grid mt-4 ">
                    <div class="text-center">
                        <button type="submit" class="btn btn-success btn-sm px-5">Register</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>

</html>
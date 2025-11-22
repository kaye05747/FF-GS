<?php 
session_start();
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
require_login();

$pdo = db();
$user = $_SESSION['user'] ?? null;

$farmer_equipment = [
    "Planting" => ["Hand Tractor","Rice Transplanter","Seedling Tray"],
    "Harvesting" => ["Combine Harvester","Threshing Machine","Sickle","Wheelbarrow"],
    "Irrigation" => ["Water Pump","Hose Pipe","Sprinkler"]
];

// Handle borrow request submission
if(isset($_POST['borrow'])) {
    $farmer_name = $_POST['farmer_name'];
    $equipment = $_POST['equipment'];
    $borrow_date = $_POST['borrow_date'];
    $return_date = $_POST['return_date'];
    $user_id = $user['id'];

    // Check if there's already a pending request for this equipment
    $check = $pdo->prepare("SELECT * FROM borrowed_items WHERE borrower_name = ? AND item_name = ? AND status = 'Pending'");
    $check->execute([$farmer_name, $equipment]);
    $already = $check->fetch(PDO::FETCH_ASSOC);

    if($already) {
        $_SESSION['error'] = "You have already submitted a borrow request for $equipment!";
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO borrowed_items
            (borrower_name, item_name, borrow_date, expected_return_date, status, remarks, created_at)
            VALUES (?, ?, ?, ?, 'Pending', '', NOW())
        ");
        $stmt->execute([$farmer_name, $equipment, $borrow_date, $return_date]);
        $_SESSION['success'] = "Borrow request submitted for $equipment!";
    }

    header("Location: borrow_harvester.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Borrow Equipment</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .form-icon { font-size: 22px; color: #0d6efd; margin-right: 8px; }
        .card-custom { border-radius: 10px; border: 1px solid #ddd; width: 100%; max-width: 700px; margin: auto; }
        .highlight-update { background-color: #d1e7dd !important; transition: background-color 2s ease; }
    </style>
</head>
<body>
<div class="container mt-5 mb-5 d-flex justify-content-center">
    <div class="card card-custom shadow p-4">

        <h4 class="mb-3 text-center"><i class="bi bi-gear-fill"></i> Borrow Equipment</h4>
        <p class="text-muted text-center mb-4">Fill out the form below to submit your borrowing request.</p>

        <?php if(isset($_SESSION['success'])): ?>
            <div id="alert-success" class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
            <div id="alert-error" class="alert alert-warning alert-dismissible fade show" role="alert">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-person form-icon"></i> Farmer Name</label>
                    <input type="text" name="farmer_name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-tools form-icon"></i> Select Equipment</label>
                    <select name="equipment" class="form-select" required>
                        <option value="">-- Select Equipment --</option>
                        <?php foreach($farmer_equipment as $category => $items): ?>
                            <optgroup label="<?= htmlspecialchars($category) ?>">
                                <?php foreach($items as $item): ?>
                                    <option value="<?= htmlspecialchars($item) ?>"><?= htmlspecialchars($item) ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-calendar-check form-icon"></i> Borrow Date</label>
                    <input type="date" name="borrow_date" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-calendar-event form-icon"></i> Expected Return Date</label>
                    <input type="date" name="return_date" class="form-control" required>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" name="borrow" class="btn btn-primary px-4">Submit Request</button>
            </div>
        </form>

        <div class="text-center mt-3">
            <a href="dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
        </div>

        <!-- Borrow Requests Table -->
        <div class="mt-5">
            <h5 class="text-center mb-3">My Borrow Requests</h5>
            <div id="borrow-requests-table" class="table-responsive">
                <!-- Table will be loaded here by AJAX -->
            </div>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Auto-hide alerts -->
<script>
setTimeout(() => {
    const alertSuccess = document.getElementById('alert-success');
    if(alertSuccess) alertSuccess.style.display = 'none';

    const alertError = document.getElementById('alert-error');
    if(alertError) alertError.style.display = 'none';
}, 4000);
</script>

<!-- Auto-refresh table with highlight -->
<script>
let previousStatuses = {};

function loadBorrowRequests() {
    $.ajax({
        url: 'fetch_borrow_requests.php',
        method: 'GET',
        success: function(data) {
            $('#borrow-requests-table').html(data);

            $('#borrow-requests-table tbody tr').each(function() {
                const row = $(this);
                const item = row.find('td:nth-child(2)').text();
                const status = row.find('td:nth-child(5) span').text();

                if(previousStatuses[item] && previousStatuses[item] !== status) {
                    row.addClass('highlight-update');
                    setTimeout(() => row.removeClass('highlight-update'), 3000);
                }

                previousStatuses[item] = status;
            });
        }
    });
}

// Initial load
loadBorrowRequests();

// Refresh every 10 seconds
setInterval(loadBorrowRequests, 10000);
</script>

</body>
</html>

<?php 
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
checkAdmin();
$pdo = db();

// Add borrow entry
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_borrow'])) {
    $borrower_name = trim($_POST['borrower_name']);
    $item_name = trim($_POST['item_name']);
    $borrow_date = $_POST['borrow_date'];
    $expected_return_date = $_POST['expected_return_date'] ?: null;
    $remarks = trim($_POST['remarks']);

    $stmt = $pdo->prepare("INSERT INTO borrowed_items (borrower_name, item_name, borrow_date, expected_return_date, remarks) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$borrower_name, $item_name, $borrow_date, $expected_return_date, $remarks]);

    $_SESSION['success'] = "Borrow record created.";
    $_SESSION['last_insert_id'] = $pdo->lastInsertId();

    header('Location: borrow_equipment.php');
    exit;
}

// Update status
if (isset($_POST['update_status'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE borrowed_items SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);

    $_SESSION['success'] = "Status updated.";
    header('Location: borrow_equipment.php');
    exit;
}

// Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    $stmt = $pdo->prepare("DELETE FROM borrowed_items WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['success'] = "Record deleted.";
    header('Location: borrow_equipment.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM borrowed_items ORDER BY created_at DESC");
$items = $stmt->fetchAll();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Borrow Equipment - Admin</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Your custom CSS -->
    <link rel="stylesheet" href="../css/borrow_equipment.css">
</head>
<body>

<?php include "sidebar.php"; ?>

<div class="content">
    <div class="container">

        <h3 class="mb-4 fw-bold">
            <i class="bi bi-tools me-2"></i>Borrowed Equipment Records
        </h3>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success shadow-sm">
                <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- ======================= -->
        <!--   TABLE 1 – BORROW LIST -->
        <!-- ======================= -->
        <div class="card shadow-sm mb-5">
            <div class="card-header fw-bold">
                <i class="bi bi-list-ul me-2"></i>Borrowed Items List
            </div>

            <div class="card-body">
                <table class="table table-bordered table-striped align-middle shadow-sm">
                    <thead class="table-dark color-green">
                        <tr>
                            <th><i class="bi bi-person-badge me-1"></i>Borrower</th>
                            <th><i class="bi bi-box-seam me-1"></i>Item</th>
                            <th><i class="bi bi-calendar-check me-1"></i>Borrow Date</th>
                            <th><i class="bi bi-calendar-event me-1"></i>Expected Return</th>
                            <th><i class="bi bi-arrow-repeat me-1"></i>Status</th>
                            <th><i class="bi bi-gear-wide-connected me-1"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($items): foreach ($items as $it): ?>
                        <tr>
                            <td><?= htmlspecialchars($it['borrower_name']) ?></td>
                            <td><?= htmlspecialchars($it['item_name']) ?></td>
                            <td><?= htmlspecialchars($it['borrow_date']) ?></td>
                            <td><?= htmlspecialchars($it['expected_return_date']) ?></td>

                            <td>
                                <?php 
                                    $status = $it['status'];
                                    $class = 'status-pending';
                                    if ($status == 'Returned') $class = 'status-returned';
                                    if ($status == 'Overdue') $class = 'status-overdue';
                                ?>
                                <span class="status-badge <?= $class ?>">
                                    <?= htmlspecialchars($status) ?>
                                </span>
                            </td>

                            <td>
                                <form method="POST" class="d-flex align-items-center gap-1">
                                    <input type="hidden" name="id" value="<?= $it['id'] ?>">

                                    <select name="status" class="form-select form-select-sm" style="width:140px">
                                        <option value="Pending"  <?= $status=='Pending'?'selected':'' ?>>Pending</option>
                                        <option value="Returned" <?= $status=='Returned'?'selected':'' ?>>Returned</option>
                                        <option value="Overdue"  <?= $status=='Overdue'?'selected':'' ?>>Overdue</option>
                                    </select>

                                    <button class="btn btn-sm btn-primary" name="update_status">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <a href="print_borrow.php?id=<?= $it['id'] ?>" 
                                       class="btn btn-secondary btn-sm" title="Print">
                                        <i class="bi bi-printer"></i>
                                    </a>

                                    <a href="print_borrow_pdf.php?id=<?= $it['id'] ?>" 
                                       class="btn btn-danger btn-sm" title="Export PDF">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </a>

                                    <a href="?delete=<?= $it['id'] ?>" 
                                       class="btn btn-sm btn-danger" title="Delete"
                                       onclick="return confirm('Delete record?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </form>
                            </td>
                        </tr>

                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No records found.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ======================= -->
        <!-- COLLAPSIBLE ADD BORROW FORM -->
        <!-- ======================= -->

        <button class="btn btn-success mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#addBorrowForm" aria-expanded="false" aria-controls="addBorrowForm">
            <i class="fa fa-folder-plus me-2"></i> Add Borrow Record
        </button>

        <div class="collapse" id="addBorrowForm">
          <form method="POST" class="p-3 border rounded bg-light">
            <!-- Hidden input to detect form submission -->
            <input type="hidden" name="save_borrow" value="1">
            <table class="table table-borderless align-middle mb-0">
              <tbody>
                <tr>
                  <th scope="row" style="width: 200px;">Borrower Name</th>
                  <td><input type="text" class="form-control" name="borrower_name" required></td>
                </tr>
                <tr>
                  <th scope="row">Item Name</th>
                  <td><input type="text" class="form-control" name="item_name" required></td>
                </tr>
                <tr>
                  <th scope="row">Borrow Date</th>
                  <td><input type="date" class="form-control" name="borrow_date" value="<?= date('Y-m-d') ?>" required></td>
                </tr>
                <tr>
                  <th scope="row">Expected Return Date</th>
                  <td><input type="date" class="form-control" name="expected_return_date"></td>
                </tr>
                <tr>
                  <th scope="row">Remarks</th>
                  <td><textarea class="form-control" name="remarks" rows="3"></textarea></td>
                </tr>
                <tr>
                  <td colspan="2" class="text-start">
                    <button type="submit" class="btn btn-success">
                      <i class="fa fa-check-circle me-1"></i> Save Record
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </form>
        </div>

    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

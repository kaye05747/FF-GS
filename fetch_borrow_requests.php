<?php
session_start();
require_once __DIR__ . '/includes/functions.php';
$pdo = db();

$user = $_SESSION['user'] ?? null;
if(!$user) exit('No user logged in');

// Use borrower_name from session if exists, else username
$borrower_name = $user['username'] ?? $user['name'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM borrowed_items WHERE borrower_name = ? ORDER BY created_at DESC");
$stmt->execute([$borrower_name]);
$borrow_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<?php if(!empty($borrow_requests)): ?>
    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Equipment</th>
                <th>Borrow Date</th>
                <th>Expected Return</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Date Submitted</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($borrow_requests as $index => $req): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($req['item_name']) ?></td>
                    <td><?= htmlspecialchars($req['borrow_date']) ?></td>
                    <td><?= htmlspecialchars($req['expected_return_date']) ?></td>
                    <td>
                        <?php 
                            $status = $req['status'];
                            if($status == 'Pending') $badge = 'warning';
                            elseif($status == 'Approved') $badge = 'success';
                            elseif($status == 'Rejected') $badge = 'danger';
                            elseif($status == 'Overdue') $badge = 'danger';
                            else $badge = 'secondary';
                        ?>
                        <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($status) ?></span>
                    </td>
                    <td><?= htmlspecialchars($req['remarks']) ?></td>
                    <td><?= htmlspecialchars($req['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p class="text-center text-muted">No borrow requests submitted yet.</p>
<?php endif; ?>

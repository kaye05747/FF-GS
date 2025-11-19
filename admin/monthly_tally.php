<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
checkAdmin();
$pdo = db();

// Calculate counts per month for the past 6 months
$months = [];
$labels = [];
$dataPending = [];
$dataReviewed = [];
$dataResolved = [];

for ($i = 5; $i >= 0; $i--) {
    $dt = new DateTime("first day of -{$i} month");
    $key = $dt->format('Y-m');
    $label = $dt->format('M Y');
    $months[] = $key;
    $labels[] = $label;

    $start = $dt->format('Y-m-01') . ' 00:00:00';
    $end = $dt->format('Y-m-t') . ' 23:59:59';

    $stmt = $pdo->prepare("SELECT status, COUNT(*) as cnt 
                           FROM feedback 
                           WHERE created_at BETWEEN ? AND ?
                           GROUP BY status");
    $stmt->execute([$start, $end]);
    $rows = $stmt->fetchAll();

    $counts = ['Pending'=>0,'Reviewed'=>0,'Resolved'=>0];
    foreach ($rows as $r) {
        $counts[$r['status']] = intval($r['cnt']);
    }

    $dataPending[] = $counts['Pending'];
    $dataReviewed[] = $counts['Reviewed'];
    $dataResolved[] = $counts['Resolved'];
}

// Total summary
$totalStmt = $pdo->query("SELECT status, COUNT(*) as cnt FROM feedback GROUP BY status");
$totalAll = $totalStmt->fetchAll();
$totals = ['Pending'=>0,'Reviewed'=>0,'Resolved'=>0];
foreach ($totalAll as $t) { 
    $totals[$t['status']] = intval($t['cnt']); 
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Monthly Tally - Admin</title>
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>

<body>

<?php include "sidebar.php"; ?>

<div class="content margin-left:30px;">
  <h3><b>Monthly Tally (Feedback)</b></h3>

  <div class="row mb-4 mt-4">
    <div class="col-md-4">
      <div class="card p-3 text-center">
        <h6>Total Pending</h6>
        <h3><?= $totals['Pending'] ?></h3>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-3 text-center">
        <h6>Total Reviewed</h6>
        <h3><?= $totals['Reviewed'] ?></h3>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-3 text-center">
        <h6>Total Resolved</h6>
        <h3><?= $totals['Resolved'] ?></h3>
      </div>
    </div>
  </div>

  <div class="card mb-4 p-3">
    <canvas id="tallyChart" height="120"></canvas>
  </div>

  <div class="card">
    <div class="card-body">
      <h5><b>Monthly Breakdown (Last 6 Months)</b></h5>
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>Month</th>
            <th>Pending</th>
            <th>Reviewed</th>
            <th>Resolved</th>
          </tr>
        </thead>
        <tbody>
          <?php for ($i = 0; $i < count($labels); $i++): ?>
            <tr>
              <td><?= htmlspecialchars($labels[$i]) ?></td>
              <td><?= $dataPending[$i] ?></td>
              <td><?= $dataReviewed[$i] ?></td>
              <td><?= $dataResolved[$i] ?></td>
            </tr>
          <?php endfor; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const labels = <?= json_encode($labels) ?>;
const pending = <?= json_encode($dataPending) ?>;
const reviewed = <?= json_encode($dataReviewed) ?>;
const resolved = <?= json_encode($dataResolved) ?>;

new Chart(document.getElementById('tallyChart'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            { label: 'Pending', data: pending, backgroundColor: 'rgba(255, 206, 86, 0.6)' },
            { label: 'Reviewed', data: reviewed, backgroundColor: 'rgba(54, 162, 235, 0.6)' },
            { label: 'Resolved', data: resolved, backgroundColor: 'rgba(75, 192, 192, 0.6)' }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' }},
        scales: { y: { beginAtZero: true } }
    }
});
</script>

</body>
</html>

<?php 
require_once __DIR__ . '/includes/functions.php';
require_login();

$user = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Farmer Dashboard</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/dashboard.css">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

</head>

<body>
  <?php include 'includes/header.php'; ?>

  <main class="dashboard">

    <div class="container text-center text-dark py-1 ">

      <h1 class="fw-bold display-5 mb-5 fade-in text-white">
        <?php
          if ($user && isset($user['email'])) {
              $displayName = explode('@', $user['email'])[0];
              echo "Welcome, " . htmlspecialchars($displayName) . "!";
          } else {
              echo "Welcome!";
          }
        ?>
      </h1>

      <!-- <h4 class="mb-5 opacity-75 fade-in-delay">SERVICES WE PROVIDE</h4> -->

      <div class="row justify-content-center g-3">

        <?php 
$services = [
  ["📩 Submit Feedback", "Share your feedback, complaints, or requests.", "submit_feedback.php", "Submit Now"],
  ["📋 My Feedback Status", "Track updates on your submitted feedback.", "feedback_list.php", "Check Status"],
  ["📢 Announcements", "Get the latest news and agricultural updates.", "announcement.php", "View Updates"],
  ["🚜 Borrow Harvester", "Harvesting Equipment Borrowing Form.", "borrow_harvester.php", "Borrow Now"],
  ["🧑‍🌾 Support Services", "Access farm tools, support, and subsidy programs.", "services.php", "View Services"],
  ["🤝 Help & Assistance", "Get support from your local agricultural office.", "help.php", "Get Help"],
];

foreach($services as $srv): ?>
  <div class="col-12 col-md-4 mb-4">
    <div class="glass-card shadow-lg p-4 h-100  bg:white;">
      <h3 class="fw-200 text-white"><?= $srv[0] ?></h3>
      <p class="small text-white"><?= $srv[1] ?></p>
      <a href="<?= $srv[2] ?>" class="btn modern-btn w-100"><?= $srv[3] ?></a>
    </div>
  </div>
<?php endforeach; ?>

      </div>

    </div>
  </main>

  <?php include 'includes/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

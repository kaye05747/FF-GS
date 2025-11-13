<?php
require_once __DIR__ . '/includes/functions.php';
require_login(); // Make sure this checks session_start() and redirects if not logged in

$user = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Farmer Dashboard</title>
  <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <main class="dashboard">
    <section class="hero">
      <div class="overlay">
        <h2>
          <?php
            if ($user) {
                // Get only the part before "@"
                $displayName = explode('@', $user['email'])[0];
                echo "Welcome, " . htmlspecialchars($displayName) . "!<br><br>";
            } else {
                echo "Welcome!<br><br>";
            }
          ?>
          SERVICES WE PROVIDE
        </h2>


        <div class="cards">
          <div class="card">
            <h3>📩 Submit Feedback</h3>
            <p>Share your feedback, complaints, or requests to help improve agricultural programs and services.</p>
            <a href="submit_feedback.php" class="btn">Submit Now</a>
          </div>

          <div class="card">
            <h3>📋 View My Feedback Status</h3>
            <p>Check progress and updates on your submitted feedback or complaints.</p>
            <a href="feedback_list.php" class="btn">Check Status</a>
          </div>

          <div class="card">
            <h3>📢 Announcements / Updates</h3>
            <p>Stay updated on the latest agricultural news, programs, and activities in your area.</p>
            <a href="announcement.php" class="btn">View Updates</a>
          </div>

          <div class="card">
            <h3>🧑‍🌾 Farmer Support Services</h3>
            <p>Access government agricultural support, farm tools, and subsidy programs.</p>
            <a href="services.php" class="btn">View Services</a>
          </div>

          <div class="card">
            <h3>🤝 Help & Assistance</h3>
            <p>Need help? Contact your local agricultural officer or get online support here.</p>
            <a href="help.php" class="btn">Get Help</a>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include 'includes/footer.php'; ?>
</body>
</html>

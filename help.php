<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
require_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Help & Assistance</title>
  <link rel="stylesheet" href="css/help.css">
</head>
<body>

  <div class="help-container">
    <h2>Help & Assistance</h2>

    <div class="help-card">
      <h3>📞 Contact Your Local Agricultural Office</h3>
      <p>If you need assistance regarding subsidies, training, or crop insurance, you can reach out to your local agricultural office during office hours.</p>
      <p><strong>Hotline:</strong> (032) 123-4567<br>
         <strong>Email:</strong> agri_support@ffgs.gov</p>
    </div>

    <div class="help-card">
      <h3>💬 Online Support</h3>
      <p>Need quick assistance? Our support team is available online.</p>
      <p><strong>Facebook:</strong> <a href="#">facebook.com/FFGSOfficial</a><br>
         <strong>Messenger:</strong> <a href="#">m.me/FFGSHelpdesk</a></p>
    </div>


    <p><a href="dashboard.php">⬅ Back to Dashboard</a></p>
  </div>
  <?php include 'includes/footer.php'; ?>

</body>
</html>

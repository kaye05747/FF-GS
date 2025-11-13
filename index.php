<?php session_start(); 
require_once __DIR__ . '/includes/header.php'; ?> 
<!doctype html> 
<html> 
  <head> 
    <title>Farmer Feedback System</title> 
    <link rel="stylesheet" href="css/index.css"> 
  </head> 
  <body> 
    <main> <section class="hero"> 
      <div class="hero-content"> 
        <h1>Welcome to the Farmer Feedback & Governance System</h1> 
        <p>Empowering farmers to share their voice and help improve agricultural governance and support programs.</p> 
        <?php if (!isset($_SESSION['user'])): ?> 
          <!-- If not logged in --> <!-- <div class="actions"> 
            <a href="login.php" class="btn">Login</a> 
            <a href="register.php" class="btn">Register</a> </div> --> 
            <?php else: ?>
               <!-- If logged in --> 
                <p style="margin-top:20px;">Hi 
                  <strong>
                    <?= htmlspecialchars($_SESSION['user']['username']); ?>
                </strong>! 👋<br> You’re currently logged in.</p> 
                <a href="dashboard.php" class="btn">Go to Dashboard</a> 
                <?php endif; ?> 
              </div> 
            </section> 
          </main> 
          <?php include 'includes/footer.php'; ?> 
        </body> 
                </html>
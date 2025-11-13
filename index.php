<?php session_start();
require_once __DIR__ . '/includes/header.php'; ?>
<!doctype html>
<html>

<head>
    <title>Farmer Feedback System</title>
    <link rel="stylesheet" href="/css/index.css">
</head>

<body>
    <main>
        <!-- HERO / WELCOME SECTION -->
        <section class="hero py-5">
            <div class=" container">
                <div class="row align-items-center">
                    <!-- LEFT SIDE: About Text -->
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <h1 class="display-5 fw-bold text-success mb-3">
                            Welcome to the Farmer Feedback & Governance System
                        </h1>
                        <p class="fs-5 text-secondary mb-4">
                            Empowering farmers to share their voice and help improve agricultural governance and support
                            programs.
                        </p>

                        <ul class="list-unstyled fs-6 mb-4">
                            <li class="mb-2">✅ Submit feedback about agricultural programs in your area</li>
                            <li class="mb-2">✅ Track program updates and responses from local authorities</li>
                            <li class="mb-2">✅ Connect with other farmers and share valuable insights</li>
                        </ul>

                        <!-- Conditional Buttons -->
                        <?php if (!isset($_SESSION['user'])): ?>
                        <div class="d-flex gap-3 flex-wrap">
                            <a href="login.php" class="btn btn-success btn-lg px-4">Login</a>
                            <a href="register.php" class="btn btn-outline-success btn-lg px-4">Register</a>
                        </div>
                        <?php else: ?>
                        <p class="mb-3">
                            Hi <strong><?= htmlspecialchars($_SESSION['user']['username']); ?></strong>! 👋<br> You’re
                            currently logged in.
                        </p>
                        <a href="dashboard.php" class="btn btn-success btn-lg px-4">Go to Dashboard</a>
                        <?php endif; ?>
                    </div>

                    <!-- RIGHT SIDE: Image -->
                    <div class="col-lg-6 text-center  ">
                        <img src="images/farmers-collaboration.png" alt="Farmers Collaboration"
                            class="img-fluid rounded shadow">
                    </div>
                </div>
            </div>
        </section>

        <!-- FEATURES SECTION -->
        <section class="features py-5">
            <div class="container text-center">
                <h2 class="fw-bold mb-4 text-success">Key Features</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="p-4 bg-white rounded shadow h-100">
                            <h5 class="fw-bold mb-3">Submit Feedback</h5>
                            <p>Quickly submit feedback about agricultural programs in your area.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-4 bg-white rounded shadow h-100">
                            <h5 class="fw-bold mb-3">Track Progress</h5>
                            <p>Monitor responses and updates from local governance offices.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-4 bg-white rounded shadow h-100">
                            <h5 class="fw-bold mb-3">Community Support</h5>
                            <p>Connect with other farmers and share valuable insights and tips.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ABOUT / MISSION SECTION -->
        <section class="mission py-5 bg-light">
            <div class="container text-center">
                <h2 class="fw-bold mb-4 text-success">Our Mission</h2>
                <p class="fs-5 text-secondary mx-auto" style="max-width: 800px;">
                    To empower farmers by giving them a platform to share their feedback, track agricultural programs,
                    and collaborate with local governance for improved farming practices and community growth.
                </p>
            </div>
        </section>

        <!-- CALL TO ACTION SECTION -->
        <section class="cta py-5">
            <div class="container text-center">
                <h2 class="fw-bold mb-3 text-success">Get Started Today</h2>
                <p class="fs-5 text-secondary mb-4">Join the system and make your voice heard.</p>
                <?php if (!isset($_SESSION['user'])): ?>
                <a href="register.php" class="btn btn-success btn-lg px-5">Register Now</a>
                <?php else: ?>
                <a href="dashboard.php" class="btn btn-success btn-lg px-5">Go to Dashboard</a>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>



</html>
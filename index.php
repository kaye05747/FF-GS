<?php 
session_start();
require_once __DIR__ . '/includes/header.php'; 
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Farmer Feedback System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/index.css">

    <style>
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden; /* prevent scroll */
        }

        .home-section {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .home-text {
            text-align: left;
        }

        .carousel img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 8px;
        }

        .carousel-caption {
            background: rgba(0, 0, 0, 0.5);
            border-radius: 6px;
            padding: 10px 15px;
        }

        @media (max-width: 768px) {
            .home-section {
                flex-direction: column;
            }
            .carousel img {
                height: 250px;
            }
        }

        .hero-btn {
            margin-top: 20px;
        }
    </style>
</head>

<body>

<div class="container-fluid home-section">
    <div class="row w-100">
        <!-- Left: Text -->
        <div class="col-md-6 d-flex flex-column justify-content-center home-text px-4">
            <h1 class="display-2 fw-bold text-white mt-1 fade-in-up">
                Farmer's Feedback and Governance System
            </h1>

            <p class="lead mt-3 text-white fade-in-up delay-1">
                Empowering farmers with a voice — improving transparency, fairness, and agricultural governance.
            </p>


            <?php if (!isset($_SESSION['user'])): ?>
                <!-- <div class="hero-btn">
                    <a href="login.php" class="btn btn-success btn-lg px-4 me-2">Login</a>
                    <a href="register.php" class="btn btn-success btn-lg px-4 me-2">Register</a>
                </div> -->
            <?php else: ?>
                <p class="mt-3 text-dark">
                    Welcome back, <strong><?= htmlspecialchars($_SESSION['user']['username']); ?></strong>! 👋
                </p>
                <a href="dashboard.php" class="btn btn-success btn-lg hero-btn">Go to Dashboard</a>
            <?php endif; ?>
        </div>

        <!-- Right: Carousel -->
        <div class="col-md-6 px-4">
            <div id="homeCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3000">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="images/pic11.jpg" class="d-block w-100" alt="Farming Equipment">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Efficient Equipment Management</h5>
                            <p>Track and borrow agricultural tools with ease.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="images/pic2.jpg" class="d-block w-100" alt="Farmer Collaboration">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Farmer Collaboration</h5>
                            <p>Join a community of farmers and share insights.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="images/pic3.jpg" class="d-block w-100" alt="Reports and Governance">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Transparent Governance</h5>
                            <p>Submit feedback and track government responses effectively.</p>
                        </div>
                    </div>
                </div>

                

                <!-- Carousel Indicators -->
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

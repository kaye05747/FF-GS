<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
/* Sidebar adjustments */
.admin-sidebar {
    width: 250px;       /* standard width */
    height: 100vh;
    background: #078A35;
    position: fixed;
    top: 0;
    left: 0;
    padding: 25px 20px;
    color: white;
    font-family: Arial, sans-serif;
    border-right: 2px solid #0a5d25;
}

.admin-sidebar h2 {
    text-align: center;
    margin-bottom: 40px;
}

.admin-sidebar a {
    display: flex;
    align-items: center;
    height: 50px;
    padding: 0 15px;
    color: white;
    text-decoration: none;
    font-size: 16px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    transition: background-color 0.2s;
}

.admin-sidebar a:last-child {
    border-bottom: none;
}

.admin-sidebar a:hover {
    background-color: #0a5d25;
}

/* .content {
    margin-left: 250px;  
    padding: 20px;
} */


/* Dashboard cards */
.dashboard-card {
    border-radius: 10px;
    transition: 0.2s;
    cursor: pointer;
}
.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0px 8px 18px rgba(0,0,0,0.1);
}
.icon-box {
    font-size: 40px;
    margin-bottom: 10px;
}
</style>
</head>
<body>

<?php include "sidebar.php"; ?>

<div class="content">

    <h4 class="fw-bold mb-4"><i class="fa-solid fa-gauge"></i> Admin Dashboard</h4>

    <div class="row g-4">

        <div class="col-md-3">
            <a href="admin_dashboard.php" class="text-decoration-none">
                <div class="card p-3 text-center dashboard-card">
                    <i class="fa-solid fa-house text-success icon-box"></i>
                    <h6 class="fw-bold">Dashboard</h6>
                    <p class="text-muted small">Admin overview</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="announcements.php" class="text-decoration-none">
                <div class="card p-3 text-center dashboard-card">
                    <i class="fa-solid fa-bullhorn text-warning icon-box"></i>
                    <h6 class="fw-bold">Announcement</h6>
                    <p class="text-muted small">Manage announcements</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="monthly_tally.php" class="text-decoration-none">
                <div class="card p-3 text-center dashboard-card">
                    <i class="fa-solid fa-chart-line text-info icon-box"></i>
                    <h6 class="fw-bold">Monthly Tally</h6>
                    <p class="text-muted small">Monthly stats</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="borrow_equipment.php" class="text-decoration-none">
                <div class="card p-3 text-center dashboard-card">
                    <i class="fa-solid fa-toolbox text-primary icon-box"></i>
                    <h6 class="fw-bold">Borrow Equipment</h6>
                    <p class="text-muted small">Equipment request</p>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="view_feedback.php" class="text-decoration-none">
                <div class="card p-3 text-center dashboard-card">
                    <i class="fa-solid fa-comments text-danger icon-box"></i>
                    <h6 class="fw-bold">View Feedback</h6>
                    <p class="text-muted small">Check farmer feedback</p>
                </div>
            </a>
        </div>

    </div><!-- row end -->

</div><!-- content-wrapper end -->

</body>
</html>

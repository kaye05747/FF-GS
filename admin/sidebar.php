<div class="admin-sidebar">
    <h2>Admin Panel</h2>

    <a href="admin_dashboard.php">Dashboard</a>
    <a href="announcement.php">Announcement</a>
    <a href="monthly_tally.php">Monthly Tally</a>
    <a href="borrow_equipment.php">Borrow Equipment</a>
    <a href="view_feedback.php">View Feedback</a>
    <a href="../logout.php">Logout</a>
</div>

<style>
    /* FINAL PERFECT MATCH SIDEBAR */
    /* sidebar.php CSS */
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

.content {
    margin-left: 300px;  /* matches sidebar width */
    padding: 20px;
}


    .admin-sidebar h2 {
        text-align: center;
        margin-bottom: 40px;
        font-weight: bold;
        color: white;
    }

    .admin-sidebar a {
        display: block;
        padding: 15px 0;
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
        text-decoration: none;
        background-color: #0a5d25;
    }

    /* CONTENT ADJUSTMENT - THIS CLASS IS NOW USED IN monthly_tally.php */
    
    
    /* Custom style for the green navbar */
    .bg-main { background-color: #198754 !important; color: #fff; }
</style>

<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/db.php';
checkAdmin();

$pdo = db();

// ✅ Handle admin reply submission
if (isset($_POST['submit_admin_reply'])) {
    $feedback_id = $_POST['feedback_id'];
    $admin_reply = sanitize($_POST['admin_reply']);
    
    // Update feedback
    $stmt = $pdo->prepare("UPDATE feedbacks SET admin_reply = ?, status = 'Completed' WHERE id = ?");
    $stmt->execute([$admin_reply, $feedback_id]);

    // Create notification for the user
    $stmt = $pdo->prepare("SELECT user_id FROM feedbacks WHERE id = ?");
    $stmt->execute([$feedback_id]);
    $user_id = $stmt->fetchColumn();

    if ($user_id) {
        $message = "Your feedback has been replied to by an admin.";
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $stmt->execute([$user_id, $message]);
    }

    header("Location: admin_dashboard.php");
    exit;
}

// ✅ Delete all pending feedbacks
if (isset($_POST['delete_pending'])) {
    $stmt = $pdo->prepare("DELETE FROM feedbacks WHERE status != 'Completed'");
    $stmt->execute();
    header("Location: admin_dashboard.php");
    exit;
}

// ✅ Delete all done feedbacks
if (isset($_POST['delete_done'])) {
    $stmt = $pdo->prepare("DELETE FROM feedbacks WHERE status = 'Completed'");
    $stmt->execute();
    header("Location: admin_dashboard.php");
    exit;
}

// ✅ Fetch all feedbacks
$stmt = $pdo->query("
    SELECT f.id, f.type, f.description, f.status, f.created_at, f.photo, f.user_id, f.admin_reply,
           u.username
    FROM feedbacks f
    JOIN users u ON f.user_id = u.id
    ORDER BY f.created_at DESC
");
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pending = array_filter($feedbacks, fn($f) => empty($f['admin_reply']) || strtolower($f['status']) != 'completed');
$done = array_filter($feedbacks, fn($f) => !empty($f['admin_reply']) && strtolower($f['status']) == 'completed');

// ✅ Fetch login logs
$logs = $pdo->query("SELECT username, login_time FROM login_logs ORDER BY login_time DESC")->fetchAll(PDO::FETCH_ASSOC);

// ✅ Fetch users
$users = $pdo->query("SELECT id, username, is_active, role, created_at FROM users ORDER BY id DESC")->fetchAll();

// ✅ Fetch users with feedback count
$users_with_feedback = $pdo->query("
    SELECT u.id, u.username, u.role, COUNT(f.id) as feedback_count
    FROM users u
    LEFT JOIN feedbacks f ON u.id = f.user_id
    GROUP BY u.id, u.username, u.role
    ORDER BY feedback_count DESC
")->fetchAll();

// ✅ Monthly feedback report
$monthly_feedback_report = $pdo->query("
    SELECT 
        MONTHNAME(created_at) as month,
        COUNT(*) as total_feedbacks,
        SUM(CASE WHEN status = 'Resolved' THEN 1 ELSE 0 END) as resolved_issues,
        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_issues,
        SUM(CASE WHEN status = 'In Process' THEN 1 ELSE 0 END) as in_process_issues
    FROM feedbacks
    GROUP BY MONTH(created_at), MONTHNAME(created_at)
    ORDER BY MONTH(created_at)
")->fetchAll();

// ✅ Fetch announcements
$announcements = $pdo->query("SELECT id, message, created_at FROM announcements ORDER BY id DESC")->fetchAll();

// ✅ Handle new announcement
if (isset($_POST['post_announcement'])) {
    $announcement_message = sanitize($_POST['announcement']);
    if ($announcement_message) {
        // Insert announcement
        $stmt = $pdo->prepare("INSERT INTO announcements (message) VALUES (?)");
        $stmt->execute([$announcement_message]);
        $announcement_id = $pdo->lastInsertId();

        // Create notifications for all users
        $users_stmt = $pdo->query("SELECT id FROM users");
        $users = $users_stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $notification_stmt = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        foreach ($users as $user_id) {
            $notification_stmt->execute([$user_id, "New announcement: " . substr($announcement_message, 0, 50) . "..."]);
        }

        header("Location: admin_dashboard.php?section=announcements");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      background-color: #f8f9fa;
    }
    .sidebar {
      background-color: #145A32;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 8px 12px;
      border-radius: 5px;
    }
    .sidebar a:hover {
      background-color: #117A45;
      padding-left: 16px;
      transition: all 0.3s;
    }
    .sidebar .nav-link.active {
      background-color: #0B3D1E;
    }
    .content-section {
      display: none;
    }
    .content-section.active {
      display: block;
    }
    .tab-header { display: flex; justify-content: space-between; align-items: center; }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    <!-- ✅ SIDEBAR -->
    <nav class="col-md-2 d-none d-md-block sidebar vh-100 position-fixed">
      <div class="sidebar-sticky pt-3 text-white">
        <h4 class="text-center mb-4">Admin Dashboard</h4>
        <ul class="nav flex-column">
          <li class="nav-item"><a class="nav-link text-white" href="#" onclick="showSection('monthly-tally', this)">📊 Monthly Tally</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="#" onclick="showSection('announcements', this)">📢 Announcements</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="#" onclick="showSection('manage-users', this)">👥 Manage Users</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="#" onclick="showSection('user-feedback-overview', this)">📈 Feedback Overview</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="#" onclick="showSection('view-feedback', this)">💬 View Feedback</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="#" onclick="showSection('active-users', this)">🟢 Active Users</a></li>
          <li class="nav-item"><a class="nav-link text-danger fw-bold" href="../logout.php">🚪 Logout</a></li>
        </ul>
      </div>
    </nav>

    <!-- ✅ MAIN CONTENT -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-4 offset-md-2 pt-4">

      <!-- ✅ MONTHLY TALLY -->
      <div id="monthly-tally" class="content-section active">
        <h2 class="text-success">📊 Monthly Feedback Tally</h2>
        <canvas id="feedbackChart"></canvas>

        <h4 class="mt-4">Monthly Report Table</h4>
        <table class="table table-striped shadow-sm">
          <thead class="table-success">
            <tr>
              <th>Month</th>
              <th>Total</th>
              <th>Resolved</th>
              <th>Pending</th>
              <th>In Process</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($monthly_feedback_report as $r): ?>
            <tr>
              <td><?= $r['month'] ?></td>
              <td><?= $r['total_feedbacks'] ?></td>
              <td><?= $r['resolved_issues'] ?></td>
              <td><?= $r['pending_issues'] ?></td>
              <td><?= $r['in_process_issues'] ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- ✅ ANNOUNCEMENTS -->
      <div id="announcements" class="content-section">
        <h2 class="text-success">📢 Announcements</h2>
        <div class="card mb-3 shadow-sm">
          <div class="card-header bg-success text-white">Post New Announcement</div>
          <div class="card-body">
            <form method="post">
              <textarea name="announcement" class="form-control mb-2" placeholder="Type your announcement..." required></textarea>
              <button type="submit" name="post_announcement" class="btn btn-success">Post</button>
            </form>
          </div>
        </div>
        <table class="table table-striped shadow-sm">
          <thead class="table-success"><tr><th>ID</th><th>Message</th><th>Date</th><th>Actions</th></tr></thead>
          <tbody>
            <?php foreach ($announcements as $a): ?>
            <tr>
              <td><?= $a['id'] ?></td>
              <td><?= htmlspecialchars($a['message']) ?></td>
              <td><?= date('F j, Y', strtotime($a['created_at'])) ?></td>
              <td><a href="delete_announcement.php?id=<?= $a['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this announcement?')">Delete</a></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- ✅ MANAGE USERS -->
      <div id="manage-users" class="content-section">
        <h2 class="text-success">👥 Manage Users</h2>
        <table class="table table-striped shadow-sm">
          <thead class="table-success"><tr><th>ID</th><th>Username</th><th>Role</th><th>Status</th><th>Actions</th></tr></thead>
          <tbody>
            <?php foreach ($users as $u): ?>
            <tr>
              <td><?= $u['id'] ?></td>
              <td><?= htmlspecialchars($u['username']) ?></td>
              <td><?= htmlspecialchars($u['role']) ?></td>
              <td><?= $u['is_active'] ? 'Active' : 'Inactive' ?></td>
              <td>
                <a href="edit_user.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                <a href="delete_user.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger">Delete</a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- ✅ FEEDBACK OVERVIEW -->
      <div id="user-feedback-overview" class="content-section">
        <h2 class="text-success">📈 User Feedback Overview</h2>
        <table class="table table-striped shadow-sm">
          <thead class="table-success"><tr><th>User ID</th><th>Username</th><th>Role</th><th>Feedbacks</th></tr></thead>
          <tbody>
            <?php foreach ($users_with_feedback as $u): ?>
            <tr>
              <td><?= $u['id'] ?></td>
              <td><?= htmlspecialchars($u['username']) ?></td>
              <td><?= htmlspecialchars($u['role']) ?></td>
              <td><?= $u['feedback_count'] ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      
      <!-- ✅ FEEDBACK SECTION -->
      <div id="view-feedback" class="content-section">
        <h2 class="mb-4 text-success">💬 Feedback Management</h2>

        <!-- ✅ Manual Reply Section -->
        <div class="card mb-4 border-success shadow-sm">
          <div class="card-header bg-success text-white">✉️ Reply to Feedback</div>
          <div class="card-body">
            <form method="post">
              <div class="mb-3">
                <label class="form-label">Select Feedback</label>
                <select name="feedback_id" id="feedback_id" class="form-select" required onchange="showFeedbackDetails(this.value)">
                  <option value="">-- Choose feedback to reply --</option>
                  <?php foreach ($pending as $f): ?>
                    <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['type']) ?> — <?= htmlspecialchars($f['username']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div id="feedback_details" class="mb-3" style="display:none;">
                <p><strong>Type:</strong> <span id="detail_type"></span></p>
                <p><strong>Description:</strong></p>
                <p id="detail_description" class="border p-2 bg-light rounded"></p>
                <div id="detail_photo"></div>
              </div>

              <div class="mb-3">
                <label class="form-label">Admin Reply</label>
                <textarea name="admin_reply" class="form-control" rows="3" placeholder="Type your reply..." required></textarea>
              </div>

              <button type="submit" name="submit_admin_reply" class="btn btn-success">Send Reply & Mark as Done</button>
            </form>
          </div>
        </div>

        <!-- ✅ Feedback Tabs -->
        <ul class="nav nav-tabs mb-3" id="feedbackTabs" role="tablist">
          <li class="nav-item"><button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">🕒 Pending (<?= count($pending) ?>)</button></li>
          <li class="nav-item"><button class="nav-link" id="done-tab" data-bs-toggle="tab" data-bs-target="#done" type="button">✅ Done (<?= count($done) ?>)</button></li>
        </ul>

        <div class="tab-content" id="feedbackTabsContent">
          <!-- ✅ Pending -->
          <div class="tab-pane fade show active" id="pending">
            <div class="tab-header mb-3">
              <h4 class="text-secondary">Pending Feedbacks</h4>
              <?php if (!empty($pending)): ?>
              <form method="post" onsubmit="return confirm('Delete all pending feedbacks?');">
                <button type="submit" name="delete_pending" class="btn btn-sm btn-danger">🗑️ Delete All Pending</button>
              </form>
              <?php endif; ?>
            </div>

            <?php if (empty($pending)): ?>
              <div class="alert alert-info">No pending feedback yet.</div>
            <?php else: foreach ($pending as $f): ?>
              <div class="card mb-3 shadow-sm">
                <div class="card-header bg-warning text-dark">
                  <strong><?= htmlspecialchars($f['username']) ?></strong> — <?= htmlspecialchars($f['type']) ?>
                  <span class="float-end badge bg-dark"><?= htmlspecialchars($f['status']) ?></span>
                </div>
                <div class="card-body">
                  <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($f['description'])) ?></p>
                  <?php if ($f['photo']): ?>
                    <p><a href="../uploads/<?= htmlspecialchars($f['photo']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">View Photo</a></p>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; endif; ?>
          </div>

          <!-- ✅ Done -->
          <div class="tab-pane fade" id="done">
            <div class="tab-header mb-3">
              <h4 class="text-success">Completed Feedbacks</h4>
              <?php if (!empty($done)): ?>
              <form method="post" onsubmit="return confirm('Delete all completed feedbacks?');">
                <button type="submit" name="delete_done" class="btn btn-sm btn-danger">🗑️ Delete All Done</button>
              </form>
              <?php endif; ?>
            </div>

            <?php if (empty($done)): ?>
              <div class="alert alert-info">No completed feedback yet.</div>
            <?php else: foreach ($done as $f): ?>
              <div class="card mb-3 shadow-sm">
                <div class="card-header bg-success text-white">
                  <strong><?= htmlspecialchars($f['username']) ?></strong> — <?= htmlspecialchars($f['type']) ?>
                </div>
                <div class="card-body">
                  <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($f['description'])) ?></p>
                  <div class="alert alert-light border"><?= nl2br(htmlspecialchars($f['admin_reply'])) ?></div>
                </div>
              </div>
            <?php endforeach; endif; ?>
          </div>
        </div>
      </div>

      <!-- ✅ ACTIVE USERS SECTION -->
      <div id="active-users" class="content-section">
        <h2 class="text-success mb-3">🟢 Active Users (Login Logs)</h2>
        <?php if (empty($logs)): ?>
          <div class="alert alert-info">No users have logged in yet.</div>
        <?php else: ?>
          <table class="table table-bordered table-hover">
            <thead class="table-success">
              <tr><th>Username</th><th>Login Time</th></tr>
            </thead>
            <tbody>
              <?php foreach ($logs as $log): ?>
                <tr>
                  <td><?= htmlspecialchars($log['username']) ?></td>
                  <td><?= htmlspecialchars($log['login_time']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>

    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // ✅ Sidebar toggle logic
  function showSection(id, link) {
    document.querySelectorAll('.content-section').forEach(sec => sec.classList.remove('active'));
    document.getElementById(id).classList.add('active');
    document.querySelectorAll('.sidebar .nav-link').forEach(a => a.classList.remove('active'));
    link.classList.add('active');
  }

  // ✅ Default active section
  document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const section = urlParams.get('section');

    if (section) {
      showSection(section, document.querySelector(`[onclick="showSection('${section}', this)"]`));
    } else {
      const defaultLink = document.querySelector('.sidebar .nav-link');
      if (defaultLink) defaultLink.classList.add('active');
    }

    // ✅ ChartJS sample
    const ctx = document.getElementById('feedbackChart');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: <?= json_encode(array_column($monthly_feedback_report, 'month')) ?>,
        datasets: [{
          label: 'Feedbacks per Month',
          data: <?= json_encode(array_column($monthly_feedback_report, 'total_feedbacks')) ?>,
          backgroundColor: 'rgba(46, 204, 113, 0.6)',
          borderColor: '#145A32',
          borderWidth: 1
        }]
      },
      options: { scales: { y: { beginAtZero: true } } }
    });
  });

  const feedbackData = <?= json_encode(array_values($pending)) ?>;
  function showFeedbackDetails(id) {
    const details = document.getElementById('feedback_details');
    const type = document.getElementById('detail_type');
    const desc = document.getElementById('detail_description');
    const photoDiv = document.getElementById('detail_photo');
    const selected = feedbackData.find(f => f.id == id);
    if (selected) {
      details.style.display = 'block';
      type.textContent = selected.type;
      desc.textContent = selected.description;
      if (selected.photo) {
        photoDiv.innerHTML = `<a href="../uploads/${selected.photo}" target="_blank" class="btn btn-sm btn-outline-secondary mt-2">View Photo</a>`;
      } else {
        photoDiv.innerHTML = '';
      }
    } else {
      details.style.display = 'none';
    }
  }
</script>

</body>
</html>

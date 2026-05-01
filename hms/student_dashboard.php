<?php
/* student_dashboard.php  */
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: login.html");   // kick out if not a student
    exit();
}
include 'db.php';

$student_id = $_SESSION['user_id'];

/* ────────── 1. basic profile ────────── */
$profile = $conn->prepare(
    "SELECT fullname, email, room_id FROM students WHERE id = ?"
);
$profile->bind_param("i", $student_id);
$profile->execute();
$stu = $profile->get_result()->fetch_assoc();

/* ────────── 2. room details (may be NULL) ────────── */
$room = null;
if ($stu['room_id']) {
    $roomStmt = $conn->prepare("SELECT room_number, capacity, occupied
                                FROM rooms WHERE id = ?");
    $roomStmt->bind_param("i", $stu['room_id']);
    $roomStmt->execute();
    $room = $roomStmt->get_result()->fetch_assoc();
}

/* ────────── 3. payment history ────────── */
$payments = $conn->prepare(
    "SELECT amount, payment_date 
     FROM payments 
     WHERE student_id=? 
     ORDER BY payment_date DESC"
);
$payments->bind_param("i", $student_id);
$payments->execute();
$payRows = $payments->get_result();

/* ────────── 4. maintenance requests ────────── */
$maint = $conn->prepare(
    "SELECT description, status, created_at 
     FROM maintenance_requests
     WHERE student_id=?
     ORDER BY created_at DESC"
);
$maint->bind_param("i", $student_id);
$maint->execute();
$maintRows = $maint->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Student Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand">Student Dashboard</span>
    <div class="text-white me-2">Hi, <?= htmlspecialchars($_SESSION['fullname']) ?></div>
    <a href="logout.php" class="btn btn-outline-light">Logout</a>
  </div>
</nav>

<div class="container my-4">

  <!-- ── Room info ── -->
  <div class="card mb-4 border-primary">
    <div class="card-header bg-primary text-white">My Room</div>
    <div class="card-body">
      <?php if ($room): ?>
         <p><strong>Room #:</strong> <?= $room['room_number'] ?></p>
         <p><strong>Capacity:</strong> <?= $room['capacity'] ?></p>
         <p><strong>Currently occupied:</strong> <?= $room['occupied'] ?></p>
      <?php else: ?>
         <div class="alert alert-warning mb-0">Room not assigned yet.</div>
      <?php endif; ?>
    </div>
  </div>

  <div class="row">
    <!-- ── Payments ── -->
    <div class="col-lg-6">
      <div class="card mb-4 border-success">
        <div class="card-header bg-success text-white">Payment History</div>
        <div class="card-body">
          <?php if ($payRows->num_rows): ?>
            <table class="table table-sm align-middle">
              <thead><tr><th>Amount (₹)</th><th>Date</th></tr></thead>
              <tbody>
              <?php while($row = $payRows->fetch_assoc()): ?>
                 <tr><td><?= $row['amount'] ?></td><td><?= $row['payment_date'] ?></td></tr>
              <?php endwhile; ?>
              </tbody>
            </table>
          <?php else: ?>
            <p>No payments yet.</p>
          <?php endif; ?>
          <a href="make_payment.php" class="btn btn-success">Make Payment</a>
        </div>
      </div>
    </div>

    <!-- ── Maintenance requests ── -->
    <div class="col-lg-6">
      <div class="card mb-4 border-warning">
        <div class="card-header bg-warning">Maintenance Requests</div>
        <div class="card-body">
          <!-- form -->
          <form action="submit_maintenance.php" method="post" class="mb-3">
            <label class="form-label">New issue</label>
            <textarea name="description" class="form-control" rows="2" required></textarea>
            <button class="btn btn-warning mt-2" type="submit">Submit</button>
          </form>

          <!-- list -->
          <?php if ($maintRows->num_rows): ?>
            <ul class="list-group">
              <?php while($m = $maintRows->fetch_assoc()): ?>
                <li class="list-group-item d-flex justify-content-between align-items-start">
                  <div>
                    <?= htmlspecialchars($m['description']) ?><br>
                    <small class="text-muted"><?= $m['created_at'] ?></small>
                  </div>
                  <span class="badge bg-<?= $m['status']=='Resolved'?'success':'danger' ?>">
                    <?= $m['status'] ?>
                  </span>
                </li>
              <?php endwhile; ?>
            </ul>
          <?php else: ?>
            <p class="mb-0">No requests submitted.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

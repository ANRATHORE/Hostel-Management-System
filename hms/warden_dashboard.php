<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'warden') {
    header("Location: login.html");
    exit();
}
include 'db.php';

/* ── 1. Quick stats for cards ────────────────────────── */
$roomStat  = $conn->query("SELECT COUNT(*) AS total,
                                  SUM(CASE WHEN occupied < capacity THEN 1 ELSE 0 END) AS free
                           FROM rooms")->fetch_assoc();
$studentCt = $conn->query("SELECT COUNT(*) AS c FROM students")->fetch_assoc()['c'];
$pendingCt = $conn->query("SELECT COUNT(*) AS c FROM maintenance_requests WHERE status='Pending'")->fetch_assoc()['c'];

/* ── 2. Detailed room assignments ─────────────────────── */
$assignQ = $conn->query("
    SELECT s.fullname, r.room_number
    FROM students s
    JOIN rooms r ON s.room_id = r.id
    ORDER BY r.room_number ASC
");

/* ── 3. Maintenance tickets ───────────────────────────── */
$mRows = $conn->query("
    SELECT mr.id, s.fullname, r.room_number, mr.description, mr.status, mr.created_at
    FROM maintenance_requests mr
    JOIN students s ON mr.student_id = s.id
    LEFT JOIN rooms r ON s.room_id = r.id
    ORDER BY mr.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Warden Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand">Warden Dashboard</span>
    <div class="text-white me-2">Hi, <?= htmlspecialchars($_SESSION['fullname']) ?></div>
    <a href="logout.php" class="btn btn-outline-light">Logout</a>
  </div>
</nav>

<div class="container my-4">

  <!-- ── Summary cards ── -->
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card text-center border-primary">
        <div class="card-body">
          <h5 class="card-title">Rooms</h5>
          <p class="fs-4 mb-0"><?= $roomStat['total'] ?></p>
          <small><?= $roomStat['free'] ?> rooms have free beds</small>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-center border-success">
        <div class="card-body">
          <h5 class="card-title">Students</h5>
          <p class="fs-4 mb-0"><?= $studentCt ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-center border-warning">
        <div class="card-body">
          <h5 class="card-title">Open Tickets</h5>
          <p class="fs-4 mb-0"><?= $pendingCt ?></p>
        </div>
      </div>
    </div>
  </div>

  <!-- ── Room assignments list ── -->
  <div class="card mb-4 border-primary">
    <div class="card-header bg-primary text-white">Room Assignments</div>
    <div class="card-body p-0">
      <table class="table table-sm mb-0">
        <thead class="table-light">
          <tr><th>Room #</th><th>Student</th></tr>
        </thead>
        <tbody>
        <?php while($row = $assignQ->fetch_assoc()): ?>
          <tr>
            <td><?= $row['room_number'] ?></td>
            <td><?= htmlspecialchars($row['fullname']) ?></td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ── Maintenance requests ── -->
  <div class="card border-warning">
    <div class="card-header bg-warning">Maintenance Requests</div>
    <div class="card-body p-0">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr><th>Date</th><th>Room</th><th>Student</th><th>Description</th><th>Status</th><th></th></tr>
        </thead>
        <tbody>
        <?php while($m = $mRows->fetch_assoc()): ?>
          <tr>
            <td><?= date('d-M-Y', strtotime($m['created_at'])) ?></td>
            <td><?= $m['room_number'] ?? 'N/A' ?></td>
            <td><?= htmlspecialchars($m['fullname']) ?></td>
            <td><?= htmlspecialchars($m['description']) ?></td>
            <td>
              <span class="badge bg-<?= $m['status']=='Resolved'?'success':'danger' ?>">
                <?= $m['status'] ?>
              </span>
            </td>
            <td class="text-end">
              <?php if ($m['status']=='Pending'): ?>
                <a class="btn btn-sm btn-outline-success"
                   href="update_status.php?id=<?= $m['id'] ?>&status=Resolved">
                   Mark Resolved
                </a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

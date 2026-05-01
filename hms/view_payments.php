<?php
include 'db.php';
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

$result = $conn->query("SELECT p.*, s.fullname FROM payments p JOIN students s ON p.student_id = s.id");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h3>Student Payment Records</h3>
    <table class="table table-bordered">
        <thead><tr><th>Student</th><th>Amount</th><th>Date</th></tr></thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['fullname'] ?></td>
                <td><?= $row['amount'] ?></td>
                <td><?= $row['payment_date'] ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
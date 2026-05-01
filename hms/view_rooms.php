<?php
include 'db.php';
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

$result = $conn->query("SELECT * FROM rooms");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Room Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h3>Room Occupancy Report</h3>
    <table class="table table-bordered">
        <thead><tr><th>Room Number</th><th>Capacity</th><th>Occupied</th></tr></thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['room_number'] ?></td>
                <td><?= $row['capacity'] ?></td>
                <td><?= $row['occupied'] ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
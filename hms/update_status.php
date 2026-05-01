<?php
include 'db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'warden') {
    header("Location: login.html");
    exit();
}

$id     = intval($_GET['id'] ?? 0);
$status = ($_GET['status'] === 'Resolved') ? 'Resolved' : 'Pending';

if ($id) {
    $stmt = $conn->prepare("UPDATE maintenance_requests SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
}
header("Location: warden_dashboard.php");

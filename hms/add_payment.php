<?php
include 'db.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $amount = $_POST['amount'];
    $date = date('Y-m-d');

    $stmt = $conn->prepare("INSERT INTO payments (student_id, amount, payment_date) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $student_id, $amount, $date);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php#track-payments");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
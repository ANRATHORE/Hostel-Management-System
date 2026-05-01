<?php
include 'db.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_number = $_POST['room_number'];
    $capacity = $_POST['capacity'];

    $stmt = $conn->prepare("INSERT INTO rooms (room_number, capacity) VALUES (?, ?)");
    $stmt->bind_param("si", $room_number, $capacity);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php#manage-rooms");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
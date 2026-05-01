<?php
include 'db.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $room_id = $_POST['room_id'];

    $room = $conn->query("SELECT capacity, occupied FROM rooms WHERE id = $room_id")->fetch_assoc();
    if ($room && $room['occupied'] < $room['capacity']) {
        $conn->query("UPDATE students SET room_id = $room_id WHERE id = $student_id");
        $conn->query("UPDATE rooms SET occupied = occupied + 1 WHERE id = $room_id");
        header("Location: admin_dashboard.php#assign-rooms");
    } else {
        echo "Room is full or does not exist.";
    }
}
?>
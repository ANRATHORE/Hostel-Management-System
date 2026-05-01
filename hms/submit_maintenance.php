<?php
include 'db.php';
session_start();

if ($_SESSION['role'] !== 'student') { header("Location: login.html"); exit(); }

$desc = trim($_POST['description']);
if ($desc === '') { die("Description empty."); }

$student_id = $_SESSION['user_id'];
$stmt = $conn->prepare("INSERT INTO maintenance_requests (student_id, description) VALUES (?, ?)");
$stmt->bind_param("is", $student_id, $desc);
$stmt->execute();

header("Location: student_dashboard.php?msg=request_added");
?>

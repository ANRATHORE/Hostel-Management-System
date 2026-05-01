<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $pass  = $_POST['password'];

    /* 1) Check admin/warden table */
    $u = $conn->prepare("SELECT id, fullname, password, role FROM users WHERE email=?");
    $u->bind_param("s", $email);
    $u->execute();
    $row = $u->get_result()->fetch_assoc();

    /* 2) If not found, check student table */
    if (!$row) {
        $s = $conn->prepare("SELECT id, fullname, password FROM students WHERE email=?");
        $s->bind_param("s", $email);
        $s->execute();
        $stu = $s->get_result()->fetch_assoc();
        if ($stu && password_verify($pass, $stu['password'])) {
            $_SESSION['user_id'] = $stu['id'];
            $_SESSION['fullname'] = $stu['fullname'];
            $_SESSION['role'] = 'student';
            header("Location: student_dashboard.php");
            exit();
        }
    } else {
        if (password_verify($pass, $row['password'])) {
            $_SESSION['user_id']  = $row['id'];
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['role']     = $row['role'];
            header("Location: ".$row['role']."_dashboard.php"); // auto-redirect
            exit();
        }
    }
    die("Invalid email or password. <a href='login.html'>Try again</a>");
}
?>

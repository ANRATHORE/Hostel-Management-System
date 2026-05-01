<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];

    /* Prevent duplicate email across both tables */
    $dup = $conn->prepare(
        "(SELECT id FROM users WHERE email=?)
         UNION
        (SELECT id FROM students WHERE email=?)"
    );
    $dup->bind_param("ss", $email, $email);
    $dup->execute();
    if ($dup->get_result()->num_rows) {
        die("Email already registered. <a href='register.html'>Try again</a>");
    }

    if ($role === 'student') {
        $stmt = $conn->prepare("INSERT INTO students (fullname,email,password) VALUES (?,?,?)");
        $stmt->bind_param("sss", $fullname, $email, $password);
    } else {
        $stmt = $conn->prepare("INSERT INTO users (fullname,email,password,role) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $fullname, $email, $password, $role);
    }

    if ($stmt->execute()) {
        header("Location: login.html?msg=registered");
        exit();
    }
    die("Registration failed: ".$stmt->error);
}
?>

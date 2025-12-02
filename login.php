<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = trim($_POST['password'] ?? '');

    if (!$email || !$pass) {
        echo "<script>alert('All fields are required!'); window.location.href='login.html';</script>";
        exit;
    }

    $sql = "SELECT user_id, name, email, password, role FROM users WHERE email=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        if ($user['password'] === $pass) {

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                echo "<script>window.location.href='admin_dashboard.php';</script>";
                exit;
            } else {
                echo "<script>window.location.href='homes.php';</script>";
                exit;
            }

        } else {
            echo "<script>alert('Incorrect password!'); window.location.href='login.html';</script>";
            exit;
        }

    } else {
        echo "<script>alert('Email not registered!'); window.location.href='login.html';</script>";
        exit;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

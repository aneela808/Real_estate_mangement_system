<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = trim($_POST['password'] ?? '');

    if (!$name || !$email || !$pass) {
        echo "<script>alert('All fields are required!'); window.location.href='signup.html';</script>";
        exit;
    }

    // Check if email already exists
    $check = "SELECT user_id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $check);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email already registered!'); window.location.href='signup.html';</script>";
        exit;
    }

    // Insert new user
    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$pass')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Signup successful! Please login now.');
                window.location.href='login.html';
              </script>";
        exit;
    } else {
        echo "<script>alert('Database error! Try again later.'); window.location.href='signup.html';</script>";
        exit;
    }

    mysqli_close($conn);
}
?>

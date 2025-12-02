<?php
session_start();
include 'connect.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $conn->query("INSERT INTO users (name,email,role) VALUES ('$name','$email','$role')");
    header("Location: admin_crud.php");
    exit();
}
?>

<h1>Add User</h1>
<form method="POST">
    Name: <input type="text" name="name" required><br>
    Email: <input type="email" name="email" required><br>
    Role: <input type="text" name="role" required><br>
    <button type="submit">Add User</button>
</form>

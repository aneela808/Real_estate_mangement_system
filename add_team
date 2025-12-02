<?php
session_start();
include 'connect.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = $_POST['name'];
    $role = $_POST['role'];
    $email = $_POST['email'];

    $conn->query("INSERT INTO team_members (name,role,email) VALUES ('$name','$role','$email')");
    header("Location: admin_crud.php");
    exit();
}
?>

<h1>Add Team Member</h1>
<form method="POST">
    Name: <input type="text" name="name" required><br>
    Role: <input type="text" name="role" required><br>
    Email: <input type="email" name="email" required><br>
    <button type="submit">Add Team Member</button>
</form>

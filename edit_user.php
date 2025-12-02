<?php
session_start();
include 'connect.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit();
}

$id = intval($_GET['id'] ?? 0);
$result = $conn->query("SELECT * FROM users WHERE user_id=$id");
$user = $result->fetch_assoc();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $conn->query("UPDATE users SET name='$name', email='$email', role='$role' WHERE user_id=$id");
    header("Location: admin_crud.php");
    exit();
}
?>

<h1>Edit User</h1>
<form method="POST">
    Name: <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required><br>
    Email: <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>
    Role: <input type="text" name="role" value="<?= htmlspecialchars($user['role']) ?>" required><br>
    <button type="submit">Update User</button>
</form>

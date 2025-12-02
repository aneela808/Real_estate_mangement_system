<?php
session_start();
include 'connect.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit();
}

$id = intval($_GET['id'] ?? 0);
$result = $conn->query("SELECT * FROM properties WHERE property_id=$id");
$property = $result->fetch_assoc();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = $_POST['title'];
    $location = $_POST['location'];
    $price = $_POST['price'];

    $conn->query("UPDATE properties SET title='$title', location='$location', price='$price' WHERE property_id=$id");
    header("Location: admin_dashboard.php");
    exit();
}
?>

<h1>Edit Property</h1>
<form method="POST">
    Title: <input type="text" name="title" value="<?= htmlspecialchars($property['title']) ?>" required><br>
    Location: <input type="text" name="location" value="<?= htmlspecialchars($property['location']) ?>" required><br>
    Price: <input type="text" name="price" value="<?= htmlspecialchars($property['price']) ?>" required><br>
    <button type="submit">Update Property</button>
</form>

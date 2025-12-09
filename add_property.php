<?php
session_start();
include 'connect.php';

// Only admin allowed
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.html");
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = $_POST['title'];
    $price = $_POST['price'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    // Insert Query
    $sql = "INSERT INTO properties (title, price, location, description)
            VALUES ('$title', '$price', '$location', '$description')";

    if($conn->query($sql)){
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<h1>Add New Property</h1>

<form method="POST">
    Title: <input type="text" name="title" required><br><br>

    Price: <input type="number" name="price" required><br><br>

    Location: <input type="text" name="location" required><br><br>

    Description: <textarea name="description" rows="4" required></textarea><br><br>

    <button type="submit">Add Property</button>
</form>

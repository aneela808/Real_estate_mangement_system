<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch favorites with property info
$sql = "SELECT p.title, p.location, p.price
        FROM favourites f
        JOIN properties p ON f.property_id = p.property_id
        WHERE f.user_id = $user_id";
$result = mysqli_query($conn, $sql);

$favorites = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $favorites[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($favorites);
?>

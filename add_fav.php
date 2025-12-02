<?php
session_start();
require_once 'connect.php'; 
header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;
if(!$user_id){
    echo json_encode(['success'=>false, 'message'=>'Not logged in.']);
    exit;
}

$property_id = isset($_POST['property_id']) ? intval($_POST['property_id']) : 0;
if($property_id <= 0){
    echo json_encode(['success'=>false, 'message'=>'Invalid property ID.']);
    exit;
}

// Check if property is already in favorites
$stmt = $conn->prepare("SELECT 1 FROM favourites WHERE user_id=? AND property_id=?");
$stmt->bind_param("ii", $user_id, $property_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    // Remove from favorites
    $del = $conn->prepare("DELETE FROM favourites WHERE user_id=? AND property_id=?");
    $del->bind_param("ii", $user_id, $property_id);
    $del->execute();
    $del->close();
    echo "Removed";
} else {
    // Add to favorites
    $ins = $conn->prepare("INSERT IGNORE INTO favourites (user_id, property_id) VALUES (?, ?)");
    $ins->bind_param("ii", $user_id, $property_id);
    $ins->execute();
    $ins->close();
    echo "Added";
}

$stmt->close();
$conn->close();
?>

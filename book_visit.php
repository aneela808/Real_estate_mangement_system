<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $property_id = mysqli_real_escape_string($conn, $_POST['property_id'] ?? '');
    $full_name   = mysqli_real_escape_string($conn, $_POST['full_name'] ?? '');
    $phone       = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
    $date        = mysqli_real_escape_string($conn, $_POST['date'] ?? '');
    $message     = mysqli_real_escape_string($conn, $_POST['message'] ?? '');
    $user_id     = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;

    if (empty($full_name) || empty($phone) || empty($date)) {
        echo "Please fill all required fields.";
        exit;
    }

    // Insert into bookings table
    $sql = "INSERT INTO bookings (property_id, user_id, full_name, phone, date, message, status, created_at)
            VALUES ('$property_id', '$user_id', '$full_name', '$phone', '$date', '$message', 'pending', NOW())";

    if (mysqli_query($conn, $sql)) {
        echo "Booking successful!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    echo "Invalid request.";
}
?>

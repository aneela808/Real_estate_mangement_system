<?php
session_start();

// Admin session destroy
unset($_SESSION['admin_id']);
session_destroy();

// Redirect to admin login page
header("Location: login.html");
exit();
?>

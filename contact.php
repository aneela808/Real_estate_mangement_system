<?php
session_start();
include 'connect.php';

// Safe session values
$name = $_SESSION['name'] ?? "";
$email = $_SESSION['email'] ?? "";

// Handle form submission
$successMsg = "";
$errorMsg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $c_name = $_POST['name'] ?? '';
    $c_email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    if ($c_name && $c_email && $subject && $message) {
        $stmt = mysqli_prepare($conn, "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $c_name, $c_email, $subject, $message);
        if (mysqli_stmt_execute($stmt)) {
            $successMsg = "Your message has been sent successfully!";
        } else {
            $errorMsg = "Error sending message: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        $errorMsg = "Please fill in all fields.";
    }
}
mysqli_close($conn);
?>

<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us | DreamHomes</title>
<link rel="stylesheet" href="contact.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="contact-page">

<!-- Header -->

<header>
    <div class="header-left">
        <div id="themeToggle" class="theme-btn">🌙</div>
        <div class="hamburger" id="hamburger">&#9776;</div>
    </div>
    <div class="logo">🏠 DreamHomes</div>
</header>

<!-- Sidebar -->

<div id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <div class="logo">🏠 DreamHomes</div>
        <span class="close-btn" id="closeSidebar">&times;</span>
    </div>
    <ul class="sidebar-links">
        <li><strong>Welcome, <?php echo htmlspecialchars($name ?: "Guest"); ?></strong></li>
        <li><a href="homes.php">Home</a></li>
        <li class="dropdown">
            <a href="#" class="dropbtn">About <i class="fas fa-caret-down"></i></a>
            <ul class="dropdown-content">
                <li><a href="about.php#mission">Our Mission</a></li>
                <li><a href="about.php#vision">Our Vision</a></li>
                <li><a href="about.php#team">Our Team</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropbtn">Properties <i class="fas fa-caret-down"></i></a>
            <ul class="dropdown-content">
                <li><a href="properties.php?type=sale">Sale</a></li>
                <li><a href="properties.php?type=rent">Rent</a></li>
            </ul>
        </li>
        <li><a href="clients.php">Clients</a></li>
        <li><a href="contact.php" class="active">Contact</a></li>
        <li><a href="login.html">Login / Signup</a></li>
    </ul>
</div>

<div id="overlay"></div>

<!-- Page Title -->

<section class="page-title">
    <h1>Contact Us</h1>
    <p>We’d love to hear from you! Fill out the form or reach us directly.</p>
</section>

<!-- Contact Section -->

<section class="contact-section">
    <div class="contact-content">
        <form class="contact-form" method="post">
            <input type="text" name="name" placeholder="Your Name" value="<?php echo htmlspecialchars($name); ?>" required>
            <input type="email" name="email" placeholder="Your Email" value="<?php echo htmlspecialchars($email); ?>" required>
            <input type="text" name="subject" placeholder="Subject" required>
            <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
            <button type="submit">Send Message</button>
        </form>


    <?php if($successMsg): ?>
        <p class="success-msg"><?php echo $successMsg; ?></p>
    <?php elseif($errorMsg): ?>
        <p class="error-msg"><?php echo $errorMsg; ?></p>
    <?php endif; ?>

    <div class="contact-details">
        <h2>Contact Info</h2>
        <p><strong>Email:</strong> info@dreamhomes.com</p>
        <p><strong>Phone:</strong> +123 456 789</p>
        <p><strong>Address:</strong> 123 Main Street, New York, USA</p>
    </div>
</div>


</section>

<!-- Map Section -->

<section class="map-section">
    <h2>Find Us on Map</h2>
    <div class="map-container">
        <iframe src="https://maps.google.com/maps?q=new%20york&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="350" style="border:0;" allowfullscreen=""></iframe>
    </div>
</section>

<!-- Footer -->

<footer>
    <p>© 2025 DreamHomes. All Rights Reserved.</p>
    <div class="social-icons">
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-linkedin-in"></i></a>
    </div>
</footer>

<!-- Inline JS -->

<script>
  // Sidebar & Overlay
  const sidebar = document.getElementById("sidebar");
  const overlay = document.getElementById("overlay");
  const hamburger = document.getElementById("hamburger");
  const closeSidebar = document.getElementById("closeSidebar");

  hamburger.addEventListener("click", () => {
    sidebar.classList.add("active");
    overlay.classList.add("active");
  });
  closeSidebar.addEventListener("click", () => {
    sidebar.classList.remove("active");
    overlay.classList.remove("active");
  });
  overlay.addEventListener("click", () => {
    sidebar.classList.remove("active");
    overlay.classList.remove("active");
  });

  // Dark/Light Mode
  const themeBtn = document.getElementById("themeToggle");
  if(themeBtn){
      const savedTheme = localStorage.getItem("theme");
      if(savedTheme){
          document.body.classList.add(savedTheme);
          themeBtn.textContent = savedTheme === "dark-mode" ? "☀" : "🌙";
      }
      themeBtn.addEventListener("click", () => {
          document.body.classList.toggle("dark-mode");
          const currentTheme = document.body.classList.contains("dark-mode") ? "dark-mode" : "light-mode";
          localStorage.setItem("theme", currentTheme);
          themeBtn.textContent = currentTheme === "dark-mode" ? "☀" : "🌙";
      });
  }

  // Sidebar dropdown toggle
  document.querySelectorAll('.sidebar-links li.dropdown').forEach(drop => {
      const btn = drop.querySelector('.dropbtn');
      btn.addEventListener('click', e => {
          e.preventDefault();
          drop.classList.toggle('active');
      });
  });
</script>

</body>
</html>

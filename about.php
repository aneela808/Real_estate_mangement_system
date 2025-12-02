<?php
session_start();
include 'connect.php';

// Safe session values
$name = $_SESSION['name'] ?? "";
$email = $_SESSION['email'] ?? "";

// Fetch team members from database
$team_members = [];
$result = $conn->query("SELECT * FROM team_members ORDER BY id ASC");
if($result){
    while($row = $result->fetch_assoc()){
        $team_members[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us | DreamHomes</title>
<link rel="stylesheet" href="about.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="about-page">

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
            <a href="#" class="dropbtn">Properties <i class="fas fa-caret-down"></i></a>
            <ul class="dropdown-content">
                <li><a href="properties.php?type=sale">Sale</a></li>
                <li><a href="properties.php?type=rent">Rent</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropbtn">About <i class="fas fa-caret-down"></i></a>
            <ul class="dropdown-content">
                <li><a href="about.php#mission">Our Mission</a></li>
                <li><a href="about.php#vision">Our Vision</a></li>
                <li><a href="about.php#team">Our Team</a></li>
            </ul>
        </li>
        <li><a href="clients.php">Clients</a></li>
        <li><a href="contact.php">Contact</a></li>
        <?php if($name): ?>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login / Signup</a></li>
        <?php endif; ?>
    </ul>
</div>

<div id="overlay"></div>

<!-- Page Title -->

<section class="page-title">
    <h1>About Us</h1>
    <p>Learn more about our mission, vision, and dedicated team.</p>
</section>

<!-- About Content -->

<section class="about-content">
    <div class="about-text">
        <h2>Our Mission</h2>
        <p>Our mission is to provide the best real estate services and connect people with their dream properties effortlessly. We ensure trust, transparency, and reliability in all our dealings.</p>


    <h2>Our Vision</h2>
    <p>To become the leading real estate platform where buying, selling, and renting properties is easy, safe, and enjoyable for everyone.</p>
</div>

<div class="team-section" id="team">
    <h2>Our Team</h2>
    <div class="team-grid">
        <?php if(!empty($team_members)): ?>
            <?php foreach($team_members as $member): ?>
                <div class="team-card">
                    <img src="<?php echo htmlspecialchars($member['photo']); ?>" alt="<?php echo htmlspecialchars($member['name']); ?>">
                    <h3><?php echo htmlspecialchars($member['name']); ?></h3>
                    <p><?php echo htmlspecialchars($member['position']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No team members added yet.</p>
        <?php endif; ?>
    </div>
</div>


</section>

<!-- Footer -->

<footer>
    <p>© 2025 DreamHomes. All Rights Reserved.</p>
</footer>

<!-- Inline JS -->

<script>
  // Sidebar toggle
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

  // Dropdown toggle
  document.querySelectorAll(".dropdown").forEach(drop => {
    const btn = drop.querySelector(".dropbtn");
    btn.addEventListener("click", e => {
      e.preventDefault();
      drop.classList.toggle("active");
    });
  });

  // Dark/Light Mode
  const themeBtn = document.getElementById("themeToggle");
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
</script>

</body>
</html>

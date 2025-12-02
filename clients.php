<?php
session_start();
include 'connect.php';

// Safe session values
$user_id = $_SESSION['user_id'] ?? null;
$name = $_SESSION['name'] ?? "Guest";
$email = $_SESSION['email'] ?? "";

// Default favourites count
$favCount = 0;
if ($user_id) {
    $sql = "SELECT COUNT(*) AS total FROM favourites WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $favCount = $row['total'] ?? 0;
        }
        mysqli_stmt_close($stmt);
    }
}
mysqli_close($conn);
?>

<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Clients | DreamHomes</title>
<link rel="stylesheet" href="clients.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="clients-page">

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
        <li><strong>Welcome, <?php echo htmlspecialchars($name); ?></strong></li>
        <li><a href="homes.php">Home</a></li>


    <li class="dropdown">
        <a href="properties.php" class="dropbtn">Properties <i class="fas fa-caret-down"></i></a>
        <ul class="dropdown-content">
            <li><a href="properties.php?type=sale">Sale</a></li>
            <li><a href="properties.php?type=rent">Rent</a></li>
        </ul>
    </li>

    <li class="dropdown">
        <a href="about.php" class="dropbtn">About <i class="fas fa-caret-down"></i></a>
        <ul class="dropdown-content">
            <li><a href="about.php#mission">Our Mission</a></li>
            <li><a href="about.php#vision">Our Vision</a></li>
            <li><a href="about.php#team">Our Team</a></li>
        </ul>
    </li>

    <li><a href="clients.php">Clients</a></li>
    <li><a href="contact.php">Contact</a></li>

    <?php if($user_id): ?>
       
        <li><a href="logout.php">Logout</a></li>
    <?php else: ?>
        <li><a href="login.html">Login</a></li>
    <?php endif; ?>
</ul>


</div>

<div id="overlay"></div>

<!-- Page Title -->

<section class="page-title">
    <h1>Our Valued Clients</h1>
    <p>We have worked with some of the best in the industry</p>
</section>

<!-- Clients Section -->

<section class="clients-logos">
    <h2>Clients We Have Worked With</h2>
    <div class="logo-container">
        <div class="client-card"
            onclick="openClientPopup('John Doe', 'c1.webp', 'Real Estate Purchase', 'John Doe purchased a luxury apartment in downtown. Excellent communication and smooth transaction.')">
            <img src="c1.webp" alt="Client 1">
            <h3>John Doe</h3>
            <button>View Details</button>
        </div>
        <div class="client-card"
            onclick="openClientPopup('Jane Smith', 'c2.webp', 'Property Sale', 'Jane Smith successfully sold her property with our assistance. Fast and reliable process.')">
            <img src="c2.webp" alt="Client 2">
            <h3>Jane Smith</h3>
            <button>View Details</button>
        </div>
        <div class="client-card"
            onclick="openClientPopup('Michael Brown', 'c3.webp', 'Rental Service', 'Michael Brown rented out his commercial property through our platform with full satisfaction.')">
            <img src="c3.webp" alt="Client 3">
            <h3>Michael Brown</h3>
            <button>View Details</button>
        </div>
        <div class="client-card"
            onclick="openClientPopup('Ayesha Khan', 'c4.webp', 'Property Management', 'Ayesha Khan availed property management services and praised our professionalism and efficiency.')">
            <img src="c4.webp" alt="Client 4">
            <h3>Ayesha Khan</h3>
            <button>View Details</button>
        </div>
        <div class="client-card"
            onclick="openClientPopup('Robert Lee', 'c5.webp', 'Investment Consultation', 'Robert Lee consulted with our experts for real estate investment planning and was highly satisfied.')">
            <img src="c5.webp" alt="Client 5">
            <h3>Robert Lee</h3>
            <button>View Details</button>
        </div>
    </div>
</section>

<!-- Testimonials -->

<section class="testimonial-section">
    <h2>What Our Clients Say</h2>
    <div class="slides">
        <div class="testimonial-card card1">
            <img src="c1.webp" alt="Client 1">
            <p>"DreamHomes helped us find the perfect property! Highly recommend their professional service."</p>
            <h3>John Doe</h3>
        </div>
        <div class="testimonial-card card2">
            <img src="c2.webp" alt="Client 2">
            <p>"Amazing experience! The team was supportive and guided us every step of the way."</p>
            <h3>Jane Smith</h3>
        </div>
        <div class="testimonial-card card3">
            <img src="c3.webp" alt="Client 3">
            <p>"Exceptional service and attention to detail. We love our new home!"</p>
            <h3>Michael Brown</h3>
        </div>
    </div>
</section>

<!-- Client Popup -->

<div id="clientPopup" class="popup">
    <div class="popup-content">
        <span class="close-popup" onclick="closeClientPopup()">&times;</span>
        <img id="popup-img" src="" alt="Client Image">
        <h3 id="popup-name"></h3>
        <p><strong>Service:</strong> <span id="popup-service"></span></p>
        <p id="popup-details"></p>
    </div>
</div>

<!-- Footer -->

<footer>
    <p>© 2025 DreamHomes. All Rights Reserved.</p>
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
  if (themeBtn) {
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme) {
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

  // Client Popup
  function openClientPopup(name, img, service, details) {
    document.getElementById("popup-name").innerText = name;
    document.getElementById("popup-img").src = img;
    document.getElementById("popup-service").innerText = service;
    document.getElementById("popup-details").innerText = details;
    document.getElementById("clientPopup").style.display = "flex";
  }
  function closeClientPopup() {
    document.getElementById("clientPopup").style.display = "none";
  }
  document.getElementById("clientPopup").addEventListener("click", function(e) {
    if(e.target === this) closeClientPopup();
  });

  // Sidebar dropdown toggle
  document.querySelectorAll('.sidebar-links li.dropdown').forEach(drop => {
    const btn = drop.querySelector('.dropbtn');
    btn.addEventListener('click', (e) => {
      e.preventDefault(); // prevent navigation
      drop.classList.toggle('active');
    });
  });
</script>

</body>
</html>

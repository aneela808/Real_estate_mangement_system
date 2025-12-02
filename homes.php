<?php
session_start();
include 'connect.php';

// Safe session values
$user_id = $_SESSION['user_id'] ?? null;
$name = $_SESSION['name'] ?? "Guest";

// Default favourites count
$favCount = 0;

// Only run SQL if user is logged in
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
    } else {
        error_log("Failed to prepare statement: " . mysqli_error($conn));
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DreamHomes</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

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
    <li><strong id="sidebarUserName">Welcome, <?php echo htmlspecialchars($name); ?></strong></li>
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

    <?php if ($user_id): ?>
         
  

        <li><a href="logout.php">Logout</a></li>
    <?php else: ?>
        <li><a href="login.html">Login / Signup</a></li>
    <?php endif; ?>

  </ul>
</div>

<!-- Overlay -->
<div id="overlay"></div>


<!-- Hero Section -->
  <section id="hero">
    <div class="hero-content">
      <h1>Find Your Dream Home</h1>
      <p>Explore the best properties with modern style and comfort. Our experts are here to guide you at every step.</p>
      <div class="search-bar">
        <input type="text" placeholder="Search by city, area, or property type...">
       <button type="button">Search</button>
      </div>
      <label for="book-visit-toggle" class="book-visit-btn">Book a Visit</label>
    </div>

    <!-- Book Visit Modal -->
    <input type="checkbox" id="book-visit-toggle">
    <div class="book-visit-modal">
      <div class="modal-content">
        <label for="book-visit-toggle" class="close-btn">&times;</label>
        <h2>Book a Visit</h2>
        <form action="book_visit.php" method="POST">
  <input type="hidden" name="property_id" value="<?php echo $property_id; ?>">

  <input type="text" name="full_name" placeholder="Your Name" required>
  <input type="email" name="email" placeholder="Your Email">
  <input type="tel" name="phone" placeholder="Your Phone" required>
  <input type="date" name="date" required>
  <textarea name="message" rows="4" placeholder="Additional Notes"></textarea>

  <button type="submit">Submit</button>
</form>

      </div>
    </div>
  </section>

  <!-- Intro Section -->
  <section class="section" id="intro">
    <h2>Who We Are</h2>
    <p>DreamHomes is a leading real estate platform committed to connecting buyers, sellers, and renters with premium
    properties across major cities. We combine advanced technology, extensive market research, and a dedicated team of real
    estate professionals to provide a seamless and transparent property experience. Our mission is to help clients make
    informed decisions by offering verified listings, personalized guidance, and end-to-end support throughout the property
    journey. With a focus on trust, innovation, and client satisfaction, DreamHomes has become a reliable partner for anyone
    looking to invest, rent, or sell properties. Whether you are a first-time buyer, an experienced investor, or a real
    estate professional, our platform ensures a smooth, efficient, and rewarding property experience.</p>
  </section>

  <!-- Latest Listings Section -->
  <section class="section" id="listings">
    <h2>Latest Listings by Our Realtors</h2>
    <div class="cards">
      <div class="card">
        <video width="280" height="180" muted loop autoplay>
          <source src="video1.mp4" type="video/mp4">
          Your browser does not support the video tag.
        </video>
        <h3>Luxury Villa in LA</h3>
        <p>4 Beds | 3 Baths | 3500 sq.ft | $1,200,000. Modern amenities, private pool, and garden with scenic views.</p>
        <div class="card-buttons">
          
          
          <button class="share-btn">🔗 </button>
        </div>
      </div>

      <div class="card">
        <video width="280" height="180" muted loop autoplay>
          <source src="video2.mp4" type="video/mp4">
          Your browser does not support the video tag.
        </video>
        <h3>Modern Apartment NYC</h3>
        <p>2 Beds | 2 Baths | 1200 sq.ft | $850,000. Central location, rooftop access, and smart home features included.
        </p>
        <div class="card-buttons">
        
          
          <button class="share-btn">🔗 </button>
        </div>
      </div>

      <div class="card">
        <video width="280" height="180" muted loop autoplay>
          <source src="video3.mp4" type="video/mp4">
         
        </video>
        <h3>Beach House Miami</h3>
        <p>5 Beds | 4 Baths | 4000 sq.ft | $2,500,000. Stunning ocean view, spacious living area, and private dock for
          boats.</p>
        <div class="card-buttons">
          
         
          <button class="share-btn">🔗 </button>
        </div>
      </div>
    </div>
  </section>

  <!-- ScamAdvisor Section -->
  <section class="section" id="scamadvisor">
    <h2>ScamAdvisor Certified</h2>
    <p>At DreamHomes, client safety and trust are our top priorities. All our property listings are thoroughly verified and
    monitored through ScamAdvisor to ensure that every transaction is secure, reliable, and transparent. Our rigorous
    verification process includes validating property ownership, checking agent credentials, and reviewing transaction
    histories to protect our clients from fraud or misrepresentation. By partnering with ScamAdvisor, we provide peace of
    mind to buyers, sellers, and renters, allowing them to confidently explore and invest in properties. With this
    certification, DreamHomes guarantees a trustworthy platform where clients can make informed decisions without worrying
    about scams or unethical practices..</p>
  </section>

  <!-- Growth Section -->
  <section class="section" id="growth">
    <h2>How We Drive Growth for Real Estate Professionals</h2>
    <div class="cards">
      <div class="card">
        <h3>Customer Support</h3>
        <p>Our dedicated Customer Support team is available 24/7 to assist both clients and real estate agents with any inquiries,
        concerns, or issues. We provide personalized guidance throughout every stage of the property journey — from searching
        and booking visits to finalizing deals. Our support includes live chat, email assistance, and phone consultations to
        ensure that clients receive timely and accurate information. With a focus on building trust and strong relationships,
        our team helps resolve problems efficiently, answers questions about listings, and offers expert advice on property
        investments. By combining professional expertise with a client-first approach, DreamHomes ensures a seamless and
        satisfying experience for everyone using our platform..</p>
      </div>
      <div class="card">
        <h3>Our Workflow</h3>
        <p>At DreamHomes, we follow a streamlined and transparent workflow to make real estate transactions smooth and efficient
        for both clients and agents. Every property is carefully verified, photographed, and listed with accurate details,
        ensuring reliability and clarity. Our system manages buyer inquiries, schedules property visits, and tracks engagement
        to keep all parties informed in real-time. From initial contact to final transaction, our workflow incorporates checks
        and balances to prevent errors and delays, while providing agents with analytical insights to optimize their processes.
        By maintaining clear communication, systematic procedures, and dedicated support, we create a professional environment
        that saves time, reduces stress, and ensures successful property deals.</p>
      </div>
      <div class="card">
        <h3>Market Comparison</h3>
        <p>DreamHomes provides comprehensive market comparison insights to help clients and real estate professionals make informed
        decisions. Our platform analyzes current property listings, historical sales data, and neighborhood trends to determine
        accurate and competitive pricing. We provide detailed reports on property values, market demand, and investment
        potential, allowing buyers, sellers, and agents to compare options effectively. With these insights, clients can
        identify high-value opportunities while minimizing risks, and realtors can adjust their strategies to stay ahead of the
        competition. By combining data-driven analysis with expert guidance, we ensure transparency, accuracy, and confidence in
        every property transaction..</p>
      </div>
    </div>
  </section>

  
  <section class="section" id="features">
    <h2>Features & Membership</h2>
    <p>DreamHomes offers an exclusive membership program designed to provide our clients and real estate professionals with
    premium advantages and advanced tools. Members gain access to verified property listings, detailed analytics reports,
    and market trend insights that help make smarter investment decisions. Additional benefits include priority customer
    support, personalized recommendations, and early access to new properties before they go public. Our platform also
    offers interactive tools for virtual property tours, comparison charts, and financial calculators to simplify property
    evaluation. By joining our membership program, clients and realtors alike can enjoy a seamless, professional, and
    enhanced property experience, staying ahead in a competitive real estate market..</p>
    
  </section>

<!-- Inside your #features section -->

<div class="features-video">
  <video src="video4.mp4" autoplay muted loop playsinline></video>
</div>


<!--videos-->
  <!-- Video Slider -->
<section class="video-slider">
  <video src="video4.mp4" muted></video>
  <video src="video2.mp4" muted></video>
  <video src="video3.mp4" muted></video>
</section>


<!-- Latest News Section -->
<section class="section" id="news">
  <h2>Our Latest News</h2>
  <div class="cards">
    <div class="card">
      <h3>New Office Launch</h3>
      <p>
        We’ve opened a new office in downtown NYC to expand our services and better serve our clients. 
        The new office features modern workspaces, state-of-the-art meeting rooms, and dedicated client 
        service areas. Our team is excited to welcome visitors and host community events, workshops, 
        and property seminars to connect buyers, investors, and agents. This launch marks a significant 
        milestone in our growth and commitment to providing excellent real estate solutions. 
        We are also introducing a specialized concierge service for our premium clients to make their 
        property search and investment journey seamless and personalized.
      </p>
    </div>
    <div class="card">
      <h3>Exclusive Property Event</h3>
      <p>
        We recently hosted an exclusive property showcase event featuring our latest luxury listings 
        across multiple prime locations. Attendees were given a first look at newly launched apartments, 
        villas, and commercial spaces. The event included expert talks on real estate market trends, 
        investment strategies, and home financing options. Our goal was to provide potential buyers and 
        investors with detailed insights and a unique opportunity to experience properties in a curated, 
        immersive environment. Guests enjoyed networking with industry professionals and gaining valuable 
        advice on maximizing property value and selecting the perfect investment.
      </p>
    </div>
    <div class="card">
      <h3>Market Update</h3>
      <p>
        Our latest monthly market report provides comprehensive insights into property trends, pricing, 
        and investment opportunities. The real estate sector continues to evolve, with demand rising 
        in urban and suburban areas alike. We analyze neighborhood growth, infrastructure development, 
        and emerging hotspots for buyers and investors. Our report also includes tips for sellers on 
        improving property value, guidance on navigating mortgage options, and expert opinions on 
        market fluctuations. Whether you are a first-time buyer, seasoned investor, or looking to 
        sell, these insights will help you make informed decisions in a dynamic real estate market.
      </p>
    </div>
  </div>
</section>


<!-- News Detail Modal -->
<div id="newsModal" class="modal">
  <div class="modal-content">
    <span class="close-btn">&times;</span>
    <h2 id="modalTitle"></h2>
    <p id="modalContent"></p>
  </div>
</div>




  <!-- Footer -->
<!-- Footer -->
<footer>
  <div class="footer-content">
    <div class="footer-left">
      <p>© 2025 DreamHomes. All Rights Reserved.</p>
      <p>Email: info@dreamhomes.com | Phone: +1 234 567 890</p>
    </div>
    <div class="footer-right">
      <a href="https://facebook.com" target="_blank" class="social-link">📘 Facebook</a>
      <a href="https://instagram.com" target="_blank" class="social-link">📸 Instagram</a>
      <a href="https://twitter.com" target="_blank" class="social-link">🐦 Twitter</a>
      <a href="https://linkedin.com" target="_blank" class="social-link">💼 LinkedIn</a>
    </div>
  </div>
</footer>




<script src="home.js"></script>

</body>
</html>

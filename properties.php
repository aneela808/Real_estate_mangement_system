<?php
session_start();
include 'connect.php';

$user_id = $_SESSION['user_id'] ?? null;

// Fetch properties
$sql = "SELECT * FROM properties ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
$properties = [];
while($row = mysqli_fetch_assoc($result)){
    $properties[] = $row;
}

// Fetch user's favorites
$fav_ids = [];
if($user_id){
    $fav_sql = "SELECT property_id FROM favourites WHERE user_id=?";
    $stmt = mysqli_prepare($conn, $fav_sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while($row = mysqli_fetch_assoc($res)){
        $fav_ids[] = (int)$row['property_id'];
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Properties - Real Estate</title>
<link rel="stylesheet" href="properties.css">
<style>
.modal {position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); opacity:0; pointer-events:none; display:flex; justify-content:center; align-items:center; transition:0.3s;}
.modal-content {background:#fff; padding:20px; border-radius:10px; max-width:600px; width:90%; max-height:80%; overflow-y:auto; position:relative;}
.modal-content h2 {margin-top:0;}
.modal-content .close-btn {position:absolute; top:10px; right:15px; cursor:pointer; font-size:22px;}
.fav-card {border:1px solid #ddd; padding:10px; margin:10px 0; display:flex; gap:10px; align-items:center;}
.fav-card img {width:80px; height:60px; object-fit:cover; border-radius:5px;}
</style>
</head>
<body>

<header>
  <div class="header-controls">
    <div id="themeToggle" class="theme-btn">🌙</div>
    <div class="hamburger" id="hamburger">&#9776;</div>
  </div>
  <div class="logo">🏠 RealEstate</div>
</header>

<div id="sidebar" class="sidebar">
  <div class="sidebar-header">
    <div class="logo">🏠 RealEstate</div>
    <span class="close-btn" id="closeSidebar">&times;</span>
  </div>
  <ul class="sidebar-links">
    <li><a href="homes.php">Home</a></li>
    <li><a href="properties.php" class="active">Properties</a></li>
    <?php if($user_id): ?>
      <li><a href="javascript:void(0)" onclick="openFavModal()">Favorites ❤️</a></li>
      <li><a href="logout.php">Logout</a></li>
       <li><a href="clients.php">Clients</a></li>
    <li><a href="contact.php">Contact</a></li>
    <?php else: ?>
       <li><a href="clients.php">Clients</a></li>
    <li><a href="contact.php">Contact</a></li>
      <li><a href="login.php">Login</a></li>
    <?php endif; ?>
  </ul>
</div>
<div id="overlay"></div>

<section class="page-title">
  <h1>Explore Properties</h1>
  <p>Find your dream home, apartment, or villa with us.</p>
</section>

<section class="filter-search">
  <input type="text" id="searchInput" placeholder="Search by title or location...">
  <select id="typeFilter">
    <option value="">All Types</option>
    <option value="Apartment">Apartment</option>
    <option value="Villa">Villa</option>
    <option value="House">House</option>
  </select>
  <select id="priceFilter">
    <option value="">All Prices</option>
    <option value="200-500">$200k - $500k</option>
    <option value="500-1000">$500k - $1M</option>
    <option value="1000+">$1M+</option>
  </select>
  <select id="statusFilter">
    <option value="">For Sale / Rent</option>
    <option value="Sale">For Sale</option>
    <option value="Rent">For Rent</option>
  </select>
</section>

<section id="properties">
  <div id="propertiesGrid" class="properties-grid"></div>
</section>

<!-- Property Details Modal -->

<div id="detailsModal" class="modal">
  <div class="modal-content">
    <span class="close-btn" id="closeModal">&times;</span>
    <h2 id="modalTitle"></h2>
    <img id="modalImg" src="" alt="">
    <p id="modalDesc"></p>
  </div>
</div>

<!-- Favorites Modal -->

<div id="favModal" class="modal">
  <div class="modal-content">
    <span class="close-btn" id="closeFavModal">&times;</span>
    <h2>Your Favorites ❤️</h2>
    <div id="favList"></div>
  </div>
</div>

<script>
const properties = <?php echo json_encode($properties); ?>;
let favIds = <?php echo json_encode($fav_ids); ?>;
const userLoggedIn = <?php echo json_encode($user_id ? true : false); ?>;

const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("overlay");
const hamburger = document.getElementById("hamburger");
const closeSidebar = document.getElementById("closeSidebar");
hamburger.addEventListener("click", ()=>{sidebar.classList.add("active"); overlay.classList.add("active");});
closeSidebar.addEventListener("click", ()=>{sidebar.classList.remove("active"); overlay.classList.remove("active");});
overlay.addEventListener("click", ()=>{sidebar.classList.remove("active"); overlay.classList.remove("active");});

const themeBtn = document.getElementById("themeToggle");
const savedTheme = localStorage.getItem("theme");
if(savedTheme){document.body.classList.add(savedTheme); themeBtn.textContent = savedTheme==="dark-mode"?"☀":"🌙";}
themeBtn.addEventListener("click", ()=>{
  document.body.classList.toggle("dark-mode");
  let current = document.body.classList.contains("dark-mode")?"dark-mode":"light-mode";
  localStorage.setItem("theme", current);
  themeBtn.textContent = current==="dark-mode"?"☀":"🌙";
});

const modal = document.getElementById("detailsModal");
const modalTitle = document.getElementById("modalTitle");
const modalImg = document.getElementById("modalImg");
const modalDesc = document.getElementById("modalDesc");
const closeModalBtn = document.getElementById("closeModal");
closeModalBtn.addEventListener("click", ()=>{modal.style.opacity="0"; modal.style.pointerEvents="none";});
function openModal(index){
  const prop = properties[index];
  modalTitle.textContent = prop.title;
  modalImg.src = prop.image || prop.img;
  modalDesc.textContent = `$${prop.price}k · ${prop.location} · ${prop.status} · ${prop.type}`;
  modal.style.opacity="1"; modal.style.pointerEvents="auto";
}

const favModal = document.getElementById("favModal");
const favList = document.getElementById("favList");
const closeFavModal = document.getElementById("closeFavModal");
closeFavModal.addEventListener("click", ()=>{favModal.style.opacity="0"; favModal.style.pointerEvents="none";});
function openFavModal(){
  if(!userLoggedIn){ alert("Please login first!"); return; }
  favList.innerHTML = "";
  const favProperties = properties.filter(p => favIds.includes(Number(p.property_id)));
  if(favProperties.length === 0){ favList.innerHTML="<p>No favorites yet.</p>"; }
  else {
    favProperties.forEach(p=>{
      const div=document.createElement("div");
      div.className="fav-card";
      div.innerHTML=`<img src="${p.image || p.img}" alt="${p.title}"><strong>${p.title}</strong>
      <p>$${p.price}k · ${p.location} · ${p.status} · ${p.type}</p>`;
      favList.appendChild(div);
    });
  }
  favModal.style.opacity="1"; favModal.style.pointerEvents="auto";
}

function displayProperties(list){
  const grid = document.getElementById("propertiesGrid");
  grid.innerHTML="";
  list.forEach((prop,index)=>{
    const card = document.createElement("div");
    card.className="property-card"; card.dataset.id = prop.property_id;
    card.innerHTML=`
      <img src="${prop.image || prop.img}" alt="${prop.title}">
      <h3>${prop.title}</h3>
      <p>$${prop.price}k · ${prop.location} · ${prop.status} · ${prop.type}</p>
      <div class="card-buttons">
        <button onclick="openModal(${index})">View Details</button>
        <button class="fav-btn">${favIds.includes(Number(prop.property_id))?"💛":"❤️"}</button>
        <button class="share-btn">🔗</button>
      </div>`;
    grid.appendChild(card);
  });

  // Favorites toggle
document.querySelectorAll(".fav-btn").forEach(btn=>{
  btn.addEventListener("click", function(){
    const pid = parseInt(this.closest(".property-card").dataset.id);
    if(!userLoggedIn){ alert("Please login first!"); return; }
    const formData = new FormData();
    formData.append("property_id", pid);
    fetch("add_fav.php", {
      method:"POST",
      body: formData,
      credentials: "same-origin"
    })
    .then(res=>res.text())
    .then(data=>{
      if(data==="Added"){
        btn.innerHTML="💛";
        if(!favIds.includes(pid)) favIds.push(pid);
      }
      else if(data==="Removed"){
        btn.innerHTML="❤️";
        favIds = favIds.filter(id=>id!==pid);
      } else {
        alert(data);
      }

      // **Update Favorites modal immediately**
      if(favModal.style.pointerEvents === "auto"){ // if modal is open
        favList.innerHTML="";
        const favProperties = properties.filter(p => favIds.includes(parseInt(p.property_id)));
        if(favProperties.length === 0){ favList.innerHTML="<p>No favorites yet.</p>"; }
        else {
          favProperties.forEach(p=>{
            const div=document.createElement("div");
            div.className="fav-card";
            div.innerHTML=`<img src="${p.image || p.img}" alt="${p.title}"><strong>${p.title}</strong>
            <p>$${p.price}k · ${p.location} · ${p.status} · ${p.type}</p>`;
            favList.appendChild(div);
          });
        }
      }
    })
    .catch(err=>console.error(err));
  });
});


  // Share button
  document.querySelectorAll(".share-btn").forEach(btn=>{
    btn.addEventListener("click", ()=>{
      const title = btn.closest(".property-card").querySelector("h3").textContent;
      navigator.clipboard.writeText(`${title} - ${window.location.href}`).then(()=>alert("🔗 Property link copied!"));
    });
  });
}

const searchInput = document.getElementById("searchInput");
const typeFilter = document.getElementById("typeFilter");
const priceFilter = document.getElementById("priceFilter");
const statusFilter = document.getElementById("statusFilter");
function filterProperties(){
  const sv = searchInput.value.toLowerCase();
  const tv = typeFilter.value;
  const pv = priceFilter.value;
  const stv = statusFilter.value;
  const filtered = properties.filter(p=>{
    let matchSearch = p.title.toLowerCase().includes(sv) || p.location.toLowerCase().includes(sv);
    let matchType = tv==="" || p.type.toLowerCase()===tv.toLowerCase();
    let matchPrice = true;
    if(pv){if(pv==="1000+") matchPrice=p.price>=1000; else {let [min,max]=pv.split("-"); matchPrice=p.price>=parseInt(min)&&p.price<=parseInt(max);} }
    let matchStatus = stv==="" || p.status.toLowerCase()===stv.toLowerCase();
    return matchSearch && matchType && matchPrice && matchStatus;
  });
  displayProperties(filtered);
}
searchInput.addEventListener("input", filterProperties);
typeFilter.addEventListener("change", filterProperties);
priceFilter.addEventListener("change", filterProperties);
statusFilter.addEventListener("change", filterProperties);

window.addEventListener("DOMContentLoaded", ()=>{displayProperties(properties);});
</script>

</body>
</html>

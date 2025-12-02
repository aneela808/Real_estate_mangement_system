document.addEventListener("DOMContentLoaded", () => {
  // --------------------------
  // Sidebar toggle & overlay
  // --------------------------
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

  // --------------------------
  // Sidebar dropdowns
  // --------------------------
  document.querySelectorAll(".sidebar .dropdown .dropbtn").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      document.querySelectorAll(".sidebar .dropdown").forEach((d) => {
        if (d !== btn.parentElement) {
          d.classList.remove("active");
          const otherMenu = d.querySelector(".dropdown-content");
          if (otherMenu) otherMenu.style.maxHeight = null;
        }
      });
      const dropdown = btn.parentElement;
      const menu = dropdown.querySelector(".dropdown-content");
      dropdown.classList.toggle("active");
      menu.style.maxHeight = dropdown.classList.contains("active")
        ? menu.scrollHeight + "px"
        : null;
    });
  });

  // Dark/Light Mode
  // --------------------
  const themeBtn = document.getElementById("themeToggle");

  if (themeBtn) {
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme) {
      document.body.classList.add(savedTheme);
      themeBtn.textContent = savedTheme === "dark-mode" ? "☀" : "🌙";
    }

    themeBtn.addEventListener("click", () => {
      document.body.classList.toggle("dark-mode");
      const currentTheme = document.body.classList.contains("dark-mode")
        ? "dark-mode"
        : "light-mode";
      localStorage.setItem("theme", currentTheme);
      themeBtn.textContent = currentTheme === "dark-mode" ? "☀" : "🌙";
    });
  }

  // --------------------------
  // Book Visit Modal
  // --------------------------
  const bookVisitToggle = document.getElementById("book-visit-toggle");
  const bookVisitForm = document.querySelector(".book-visit-modal form");
  if (bookVisitForm) {
    const visitMsg = document.createElement("p");
    bookVisitForm.appendChild(visitMsg);
    bookVisitForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const formData = new FormData(bookVisitForm);
      fetch("book_visit.php", { method: "POST", body: formData })
        .then((res) => res.text())
        .then((msg) => {
          visitMsg.textContent = msg;
          visitMsg.style.color = msg.toLowerCase().includes("successful")
            ? "lightgreen"
            : "red";
          if (msg.toLowerCase().includes("successful")) {
            setTimeout(() => {
              bookVisitToggle.checked = false;
              bookVisitForm.reset();
              visitMsg.textContent = "";
            }, 2000);
          }
        })
        .catch((err) => {
          console.error("Book visit error:", err);
          visitMsg.textContent = "Something went wrong. Try again.";
          visitMsg.style.color = "red";
        });
    });
  }


  // --------------------------
  // Search Bar - redirect to properties.php with query
  // --------------------------
  const searchInput = document.getElementById("searchInput");
  const searchBtn = document.getElementById("searchBtn");

  if (searchInput && searchBtn) {
    function doSearch() {
      const query = searchInput.value.trim();
      if (!query) return;
      window.location.href =
        "properties.php?search=" + encodeURIComponent(query);
    }

    searchBtn.addEventListener("click", (e) => {
      e.preventDefault();
      doSearch();
    });

    searchInput.addEventListener("keypress", (e) => {
      if (e.key === "Enter") {
        e.preventDefault();
        doSearch();
      }
    });
  } else {
    console.warn("Search input or button not found for search bar");
  }
});

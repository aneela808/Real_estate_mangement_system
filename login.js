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

/* SIGNUP */
const signupForm = document.getElementById("signupForm");
if (signupForm) {
  signupForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const name = document.getElementById("signupName").value.trim();
    const email = document.getElementById("signupEmail").value.trim();
    const password = document.getElementById("signupPassword").value;
    if (!name || !email || !password) return alert("Fill all fields!");
    const bodyData = `name=${encodeURIComponent(
      name
    )}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(
      password
    )}`;
    try {
      const res = await fetch("signup.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: bodyData,
      });
      const data = await res.text();
      alert(data);
      if (data.includes("Successful")) {
        signupForm.reset();
        window.location.href = "homes.php";
      }
    } catch (err) {
      console.error(err);
      alert("Signup failed!");
    }
  });
}

/* LOGIN */
const loginForm = document.getElementById("loginForm");
if (loginForm) {
  loginForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const email = document.getElementById("loginEmail").value.trim();
    const password = document.getElementById("loginPassword").value;

    if (!email || !password) {
      alert("Fill all fields!");
      return;
    }

    const bodyData = `email=${encodeURIComponent(
      email
    )}&password=${encodeURIComponent(password)}`;

    try {
      const res = await fetch("login.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: bodyData,
      });

      const data = (await res.text()).trim(); // trim whitespace

      if (data === "success") {
        window.location.href = "home.php"; // change to your actual home page
      } else {
        alert(data);
      }
    } catch (err) {
      console.error(err);
      alert("Login failed!");
    }
  });
}

/* ------------------------------------
LOGOUT BUTTON
------------------------------------ */
const logoutBtn = document.getElementById("logoutBtn");
if (logoutBtn) {
  logoutBtn.addEventListener("click", async () => {
    try {
      await fetch("logout.php"); // destroy session
      window.location.href = "login.php";
    } catch (err) {
      console.error(err);
      alert("Logout failed!");
    }
  });
}

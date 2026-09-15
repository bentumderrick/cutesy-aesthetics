//dashboard.js//
const routes = {
    dashboard: "dashboard-content.php",
    profile: "my-profile.php",
    orders: "orders.php",
    favourite: "favourite.php",
    upload: "upload-content.php"
};

const content = document.getElementById("dashboard-content");

// ==============================
// Actually fetch + inject a page
// ==============================
async function loadPage(url, pageName) {
    try {
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error("Page failed loading");
        }

        const html = await response.text();
        content.innerHTML = html;
if(pageName === "upload"){
  initUploadArtwork();
}
        if (pageName === "profile") {
            initProfilePage();
        }

    } catch (error) {
        content.innerHTML = `
            <h2>Error loading page</h2>
            <p>${error.message}</p>
        `;
    }
}

// ==============================
// Load page from current URL
// ==============================
function loadCurrentPage() {
    const params = new URLSearchParams(window.location.search);
    const page = params.get("page") || "dashboard";
    if (routes[page]) {
        loadPage(routes[page], page);
        setActiveLinkByPage(page);
    }
    const themeBtn = document.getElementById("themeToggle");

if(localStorage.getItem("theme") === "dark"){
    document.body.classList.add("dark-mode");
}

themeBtn.addEventListener("click",()=>{
    document.body.classList.toggle("dark-mode");

    localStorage.setItem(
        "theme",
        document.body.classList.contains("dark-mode")
            ? "dark"
            : "light"
    );
});
}

// ==============================
// Highlight active nav link
// ==============================
function setActiveLink(clickedLink) {
    document.querySelectorAll(".ajax-link").forEach(link => {
        link.classList.remove("active");
    });
    clickedLink.classList.add("active");
}

function setActiveLinkByPage(page) {
    document.querySelectorAll(".ajax-link").forEach(link => {
        link.classList.toggle("active", link.dataset.page === page);
    });
}

// ==============================
// Single click handler for nav
// ==============================
document.querySelectorAll(".ajax-link").forEach(link => {
    link.addEventListener("click", (e) => {
        e.preventDefault();

        const page = link.dataset.page;
        if (!routes[page]) return;

        setActiveLink(link);
        history.pushState({ page }, "", "?page=" + page);
        loadPage(routes[page], page);
    });
});

// ==============================
// Browser Back / Forward
// ==============================
window.addEventListener("popstate", loadCurrentPage);

// ==============================
// First load
// ==============================
document.addEventListener("DOMContentLoaded", loadCurrentPage);
<?php
session_start();
include_once "auth.php";
$display_name = $_SESSION['user-details']['display_name'];
$profile = $_SESSION['user-details']['profile_picture'];
$bio = $_SESSION['user-details']['bio'];
?>
<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Cutesy Aesthetics | Dashboard</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Raleway:wght@100..900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.12.2/build/css/intlTelInput.css" />
<link rel="stylesheet" href="assets/css/dashboard.css" type="text/css" media="all" />
<link rel="stylesheet" href="assets/css/ai.css" type="text/css" media="all" />
<link rel="stylesheet" href="assets/css/my-profile.css" type="text/css" media="all" />
<link rel="stylesheet" href="assets/css/default.css" type="text/css" media="all" />
<link rel="stylesheet" href="assets/css/error-display.css" type="text/css" media="all" />
<link rel="stylesheet" href="assets/css/upload.css" type="text/css" media="all" />

<style>
.material-symbols-outlined {
    font-variation-settings:
    'FILL' 0,
    'wght' 100,
    'GRAD' 0,
    'opsz' 24
}
</style>

</head>
<body>
      <div class="error-container" id="errorContainer" role="alert">
            <div class="error-box">
                <div id="errorMessages"></div>
            </div>
        </div>
        <!-- Confirmation Modal -->
<div class="confirm-overlay" id="confirmOverlay">

    <div class="confirm-modal">

        <div class="confirm-icon">
            <i class="fa-solid fa-floppy-disk"></i>
        </div>

        <h2>Save Changes?</h2>

        <p>
            Your profile information will be updated immediately.
            Are you sure you want to continue?
        </p>

        <div class="confirm-buttons">

            <button
                type="button"
                class="cancel-btn"
                id="cancelSave">
                Cancel
            </button>

            <button
                type="button"
                class="save-btn"
                id="confirmSave">
                Save Changes
            </button>

        </div>

    </div>

</div>
 
<header aria-label="Dashboard Header">
<button
  type="button"
  class="menu-toggle"
  id="menuToggle"
  aria-label="Open navigation menu"
  aria-controls="dashboardSidebar"
  aria-expanded="false">
  <span class="material-symbols-outlined" aria-hidden="true">menu</span>
</button>
<nav class="brand-name" aria-label="Website Branding">
<div style="display: flex; flex-direction: row; justify-content: center;align-items: center;">
<div>
<h1 class="cutesy">Cutesy Aesthetics</h1>
</div>
<div>
<span class="love">❣️</span>
</div>

</div>
<div>
<p class="compliment-cutesy">
Art. Creativity. Cuteness.
</p>
</div>
</nav>
<div class="search-box" role="search">
<input type="search"
class="search-field"
name="search"
id="search-field"
placeholder="Search for art, crochet, and more..."
aria-label="Search products"
autocomplete="off"/>
<span class="material-symbols-outlined" aria-hidden="true">
search
</span>
</div>
<div class="right-conner-options">
<button class="right-conner-btn" type="button" aria-label="View favorites">
    <span class="material-symbols-outlined" aria-hidden="true">favorite</span>
</button>

<button class="right-conner-btn" type="button" aria-label="View shopping cart">
    <span class="material-symbols-outlined" aria-hidden="true">local_mall</span>
</button>
<img
id="top-header-profile"
src="<?= htmlspecialchars($profile) ?>"
alt="<?= htmlspecialchars($display_name) ?>'s profile picture"
class="profile-avatar"
loading="lazy">

<span><?= htmlspecialchars($display_name) ?></span>

<button
type="button"
class="right-conner-btn"
aria-label="Open account menu">

<span
class="material-symbols-outlined"
aria-hidden="true">
expand_more
</span>

</button>
<button
    id="themeToggle"
    class="theme-toggle"
    type="button"
    aria-label="Toggle dark mode">

    <span class="material-symbols-outlined light-icon">
        light_mode
    </span>

    <span class="material-symbols-outlined dark-icon">
        dark_mode
    </span>

</button>
</div>

</header>
<div class="line"></div>
<button type="button" class="sidebar-backdrop" id="sidebarBackdrop" aria-label="Close navigation menu" tabindex="-1"></button>
<div class="dashboard-container">
<aside class="aside-sidebar" id="dashboardSidebar">
<nav class="sidebar" aria-label="Dashboard Navigation">

<a href="dashboard-content.php" class="dashboard active ajax-link" data-page="dashboard"><span class="material-symbols-outlined"aria-hidden="true">
home
</span>Dashboard</a>

<a class="profile ajax-link" href="my-profile.php" data-page="profile"><span class="material-symbols-outlined" aria-hidden="true">
person
</span>My Profile</a>
<a 
data-page="orders"class="orders ajax-link" href="#"><span class="material-symbols-outlined" aria-hidden="true">
local_mall
</span>Orders</a>
<a 
data-page="upload"class="upload ajax-link" href="upload-content.html"><span class="material-symbols-outlined" aria-hidden="true">
upload
</span>Upload Artwork</a>
<a data-page="favourite"class="favorite ajax-link" href="#"><span class="material-symbols-outlined" aria-hidden="true">
favorite
</span>Favorites</a>
<a class="address" href="#"><span class="material-symbols-outlined" aria-hidden="true">
location_on
</span>Addresses</a>
<a class="review" href="#"><span class="material-symbols-outlined" aria-hidden="true">reviews</span>
Reviews</a>
<a class="notification" href="#"><span class="material-symbols-outlined" aria-hidden="true">
notifications
</span>Notification</a>
<a class="setting" href="#"><span class="material-symbols-outlined" aria-hidden="true">
settings
</span>Settings</a>
<a class="password" href="#"><span class="material-symbols-outlined" aria-hidden="true">
lock
</span>Change Password</a>
<div class="lightning-wrapper">
    <!-- These four spans serve as the physical lightning arcs -->
    <span class="bolt-line top-bolt"></span>
    <span class="bolt-line right-bolt"></span>
    <span class="bolt-line bottom-bolt"></span>
    <span class="bolt-line left-bolt"></span>

    <a class="menu tech-portal" href="#" target="_blank">
        <i class="material-symbols-outlined tech-icon">terminal</i>
        Chuck VT
        <span class="lightning-bolt">⚡</span>
    </a>
</div>


<a
class="logout"
href="logout.php"
aria-label="Log out of your account">
<span class="material-symbols-outlined" aria-hidden="true">
logout
</span>Logout</a>

</nav>
<div class="promo-box">
<img
src="letter.webp"
alt="Friends sharing artwork invitation illustration"><p style="font-size: 1.2rem; text-align: center;margin-top: -15px;">
<b>Share the Cuteness!</b>
</p>
<p style="text-align: center;">
Invite your friends and get 10% off your next order
</p>
<button
type="button" class="invite-btn"
aria-label="Invite friends">
Invite Friends >
</button>
</div>
</aside>
     
<main id="dashboard-content" aria-label="Dashboard Content">
<section class="welcome-banner">

<div class="welcome-text">
<h1 class="h1-welcome">Hello there, <?= htmlspecialchars($display_name) ?>! <span>👋</span></h1>

<p>
Welcome back to your creative space.
</p>
<p>
Here's what's happening with your account today.
</p>
</div>

<div class="welcome-image">
<img
class="teddy"
src="welcome-tedy.webp"
alt="Cute teddy bear welcoming the user">
</div>

</section>

<section class="stats-overview">
<div class="stats-single-div">
<div>
<span class="material-symbols-outlined">
local_mall
</span>
</div>
<div>
<p>
Total Orders
</p>
<p class="stats-num">
8
</p>
<p>
View your order history
</p>
</div>
</div>
<div class="stats-single-div">
<div>
<span>❤️</span>
</div>
<div>
<p>
Favorite
</p>
<p class="stats-num">
12
</p>
<p>
Your saved items
</p>

</div>

</div>
<div class="stats-single-div">
<div>
<span>📦</span>
</div>
<div>
<p>
Reviews
</p>
<p class="stats-num">
8
</p>
<p>
The reviews and rating
</p>
</div>
</div>
<div class="stats-single-div">
<div>
<i class="fa-regular fa-bell" style="color: #dc5c7c; font-size: 2rem;"></i>
</div>
<div>
<p>
Notifications
</p>
<p class="stats-num">
3
</p>
<p>
Check your unread alerts
</p>
</div>
</div>

</section>

<div class="dashboard-grid">

    <!-- Recent Orders -->
    <section class="recent-orders">

        <div class="grid-head">
            <p class="grid-title">
                <span class="material-symbols-outlined">local_mall</span>
                <b>Recent Orders</b>
            </p>

            <a href="#" class="view-all">View all</a>
        </div>

        <div class="order-item">
            <img
src="pic.png"
alt="Pastel Blooms art print">

            <div class="order-details">
                <h4>Pastel Blooms Art Print</h4>
                <p>May 20, 2024</p>
            </div>

            <span class="status delivered">Delivered</span>

            <div class="price">
                <p>$24.99</p>
                <span class="material-symbols-outlined">chevron_right</span>
            </div>
        </div>

        <div class="order-item">
           <img
src="pic2.jpg"
alt="Cute bunny crochet doll">

            <div class="order-details">
                <h4>Cutesy Bunny Crochet Doll</h4>
                <p>May 18, 2024</p>
            </div>

            <span class="status shipped">Shipped</span>

            <div class="price">
                <p>$34.99</p>
                <span class="material-symbols-outlined">chevron_right</span>
            </div>
        </div>

        <div class="order-item">
            <img src="pic3.jpg" alt="">

            <div class="order-details">
                <h4>Strawberry Aesthetic Mug</h4>
                <p>May 15, 2024</p>
            </div>

            <span class="status delivered">Delivered</span>

            <div class="price">
                <p>$19.99</p>
                <span class="material-symbols-outlined">chevron_right</span>
            </div>
        </div>

       <a href="#" class="bottom-link">
View all orders

<span
class="material-symbols-outlined"
aria-hidden="true">
chevron_right
</span>

</a>

    </section>



    <!-- Saved Favorites -->
    <section class="saved-favorites">

        <div class="grid-head">
            <p class="grid-title">
               <span
class="material-symbols-outlined"
aria-hidden="true">
favorite
</span>
                <b>Saved Favorites</b>
            </p>

            <a href="#" class="view-all">View all</a>
        </div>

        <div class="favorite-grid">

            <div class="favorite-card">
                <img src="pic4.jpg" alt="">
                <div class="favorite-info">
                    <p>$22.99</p>
                   <span
class="material-symbols-outlined"
aria-hidden="true">
favorite
</span>
                </div>
            </div>

            <div class="favorite-card">
                <img src="pic11.webp" alt="">
                <div class="favorite-info">
                    <p>$14.99</p>
                    <span
class="material-symbols-outlined"
aria-hidden="true">
favorite
</span>
                </div>
            </div>

            <div class="favorite-card">
                <img src="pic6.jpg" alt="">
                <div class="favorite-info">
                    <p>$18.99</p>
                    <span
class="material-symbols-outlined"
aria-hidden="true">
favorite
</span>
                </div>
            </div>

            <div class="favorite-card">
                <img src="pic7.webp" alt="">
                <div class="favorite-info">
                    <p>$29.99</p>
                  <span
class="material-symbols-outlined"
aria-hidden="true">
favorite
</span>
                </div>
            </div>

        </div>

    </section>

</div>
<section class="quick-actions">

    <div class="grid-head">
        <p style="display:inline-flex;align-items:center;">
            <span class="material-symbols-outlined">bolt</span>
            <b>Quick Actions</b>
        </p>
    </div>

    <div class="quick-actions-grid">

        <a href="#" class="quick-card">
            <span class="material-symbols-outlined">person</span>
            <div>
                <h4>Edit Profile</h4>
                <p>Update your personal information.</p>
            </div>
        </a>

        <a href="#" class="quick-card">
            <span class="material-symbols-outlined">location_on</span>
            <div>
                <h4>Manage Addresses</h4>
                <p>Add or edit delivery locations.</p>
            </div>
        </a>

        <a href="#" class="quick-card">
            <span class="material-symbols-outlined">lock</span>
            <div>
                <h4>Change Password</h4>
                <p>Keep your account secure.</p>
            </div>
        </a>

        <a href="#" class="quick-card">
            <span class="material-symbols-outlined">credit_card</span>
            <div>
                <h4>Payment Methods</h4>
                <p>Manage saved payment options.</p>
            </div>
        </a>

    </div>

</section>
</main>
</div>
<!-- AI Floating Button -->
<div class="ai-toggle" id="aiToggle">
    🤖
</div>

<!-- AI Chat Window -->
<div class="ai-window" id="aiWindow">

    <div class="ai-header">
        <div class="ai-title">
            <div class="ai-avatar">🤖</div>
            <div>
                <h3>Cutesy AI</h3>
                <small>Ask me anything</small>
            </div>
        </div>

        <button id="closeAI">&times;</button>
    </div>

    <div class="ai-body" id="chatBox">

        <div class="msg ai-msg">
            Hi! 🌸 I'm Cutesy AI.
            How can I help you today?
        </div>

    </div>

    <form id="aiForm" class="ai-footer">

       <textarea id="userInput" rows="1"></textarea>
        <button type="submit">
            ➜
        </button>

    </form>

</div>
<div id="follower"></div>
<script src="assets/js/profile.js"></script>

<script src="assets/js/ai.js"></script>
<script src="assets/js/dashboard.js"></script>
<script src="assets/js/upload.js"></script>
<script>
document.getElementById('dashboard-content').addEventListener('click', (e) => {
  const check = e.target.closest('.public-private .check');
  if (!check) return; // click wasn't on a check circle

  document.querySelectorAll('.public-private .check').forEach(c => c.innerHTML = '');

  const fill = document.createElement('div');
  fill.className = 'check-fill';
  check.appendChild(fill);
});
  const follower = document.getElementById("follower");

if (follower) {

    document.addEventListener("mousemove", (e) => {
        follower.style.left = e.clientX + "px";
        follower.style.top = e.clientY + "px";
    });

    document.querySelectorAll("a, button, input, textarea, label").forEach(el => {

        el.addEventListener("mouseenter", () => {
            follower.style.width = "20px";
            follower.style.height = "20px";
            follower.style.background = "rgba(157,78,221,.15)";
        });

        el.addEventListener("mouseleave", () => {
            follower.style.width = "10px";
            follower.style.height = "10px";
            follower.style.background = "rgba(157,78,221,.25)";
        });

    });

}</script>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.12.2/build/js/intlTelInput.min.js"></script>
        <script src="assets/js/error.js"></script>
<script>
/* ---- Sidebar hamburger (mobile drawer). Independent of the AJAX router. ---- */
(function () {
  var body = document.body;
  var toggle = document.getElementById('menuToggle');
  var backdrop = document.getElementById('sidebarBackdrop');
  var sidebar = document.getElementById('dashboardSidebar');
  if (!toggle || !sidebar) return;

  function isMobile() {
    return window.matchMedia('(max-width: 900px)').matches;
  }

  function setOpen(open) {
    body.classList.toggle('sidebar-open', open);
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    toggle.setAttribute('aria-label', open ? 'Close navigation menu' : 'Open navigation menu');
  }

  toggle.addEventListener('click', function () {
    setOpen(!body.classList.contains('sidebar-open'));
  });

  if (backdrop) {
    backdrop.addEventListener('click', function () { setOpen(false); });
  }

  /* Close the drawer after any sidebar link is tapped, including AJAX links,
     without interfering with their own handlers. */
  sidebar.addEventListener('click', function (e) {
    if (e.target.closest('a') && isMobile()) setOpen(false);
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') setOpen(false);
  });

  window.addEventListener('resize', function () {
    if (!isMobile()) setOpen(false);
  });
})();
</script>
</body>
</html>

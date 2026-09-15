<?php
session_start();
include_once 'auth.php';
$display_name = $_SESSION['user-details']['display_name'];
$profile = $_SESSION['user-details']['profile_picture'];
$bio = $_SESSION['user-details']['bio'];
?>
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

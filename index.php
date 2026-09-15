<?php 
session_start();
$is_logged_in = isset($_SESSION['user_id']);
$display_name = $_SESSION['user-details']['display_name'] ?? '';
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />

        <title>Cutesy Aesthetics - Home</title>

        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        />

        <link
            href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap"
            rel="stylesheet"
        />
        <link rel="stylesheet" href="assets/css/styles.css" />
        <link rel="stylesheet" href="assets/css/gallery.css" />
      
    </head>

    <body>
     
        <div id="load-id" class="load-class">
            <div class="f">
                <img id="load-img" src="character.webp" alt="" />
            </div>
        </div>
        <div id="h" class="h">
            <!-- NAVBAR -->
            <aside class="navbar" id="site-navigation">
                <div class="navbar-top">
                    <img class="logo" src="logo-original.webp" alt="Cutesy Aesthetics" />
                    <button class="navbar-toggle" id="navbar-toggle"
                            type="button"
                            aria-label="Open navigation"
                            aria-expanded="false"
                            aria-controls="site-navigation">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>

                <a class="menu home selected" href="index.php?page=feed" data-page="feed">
                    <i class="fa-solid fa-house"></i>
                    Home
                </a>

                <a class="menu gallery" href="index.php?page=gallery" data-page="gallery">
                    <i class="fa-solid fa-palette"></i>
                    Gallery
                </a>
<a class="menu shop" href="index.php?page=shop" data-page="shop">
                    <i class="fa-solid fa-basket-shopping"></i>
                    Shop
                </a>

                <a class="menu" href="index.php?page=about" data-page="about">
                    <i class="fa-solid fa-user"></i>
                    About the Artist
                </a>

                <a class="menu blog" href="index.php?page=blog" data-page="blog">
                    <i class="fa-solid fa-pen"></i>
                    Blog
                </a>

                <a class="menu login" href="login.php">
                    <i class="fa-solid fa-envelope"></i>
                    Login
                </a>

                <div class="navbar-down">
                    <img class="tools" src="tools.png" alt="" />
                </div>
            </aside>

            <!-- MAIN CONTENT -->
            <main class="content">
                <!-- TOP BAR -->
                <header class="top">
                    <div>
                        <p>Welcome to my creative World! 💕</p>
                    </div>

                    <div class="icons">
                        <a href="https://vm.tiktok.com/ZS9NW9MR4UWLn-z7w8y/">
                            <i class="fa-brands fa-tiktok"></i
                        ></a>
                        <i class="fa-brands fa-youtube"></i>
                    </div>
                </header>
<div class="ajax" id="ajax">
                
              </div>
                <!-- FOOTER -->
                <footer class="footer">
                    <p>Developed By | Derrick Bentum</p>
                    <p>© 2026 Cutesy Aesthetics. All rights reserved.</p>
                </footer>
            </main>
        </div>

        <script src="assets/js/load.js"></script>
      <script src="assets/js/customer.js"></script>
      <script src="assets/js/gallery.js"></script>
      <script>
        (() => {
            const navbar = document.getElementById('site-navigation');
            const toggle = document.getElementById('navbar-toggle');

            if (!navbar || !toggle) return;

            toggle.addEventListener('click', () => {
                const open = navbar.classList.toggle('navbar-open');
                toggle.setAttribute('aria-expanded', String(open));
                toggle.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');

                const icon = toggle.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fa-bars', !open);
                    icon.classList.toggle('fa-xmark', open);
                }
            });

            navbar.querySelectorAll('.menu').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 768) {
                        navbar.classList.remove('navbar-open');
                        toggle.setAttribute('aria-expanded', 'false');
                        toggle.setAttribute('aria-label', 'Open navigation');

                        const icon = toggle.querySelector('i');
                        if (icon) {
                            icon.classList.add('fa-bars');
                            icon.classList.remove('fa-xmark');
                        }
                    }
                });
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth > 768) {
                    navbar.classList.remove('navbar-open');
                    toggle.setAttribute('aria-expanded', 'false');
                    toggle.setAttribute('aria-label', 'Open navigation');

                    const icon = toggle.querySelector('i');
                    if (icon) {
                        icon.classList.add('fa-bars');
                        icon.classList.remove('fa-xmark');
                    }
                }
            });
        })();
      </script>
    </body>
</html>

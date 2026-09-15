<?php
session_start();
if(isset($_SESSION["user_id"])) {
  header('Location: dashboard.php');
exit();
}
include_once 'error-display.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<meta
http-equiv="X-UA-Compatible"
content="IE=edge">

<title>Cutesy Aesthetics | Login</title>

<link rel="preconnect" href="https://fonts.googleapis.com">

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link
href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Raleway:wght@100..900&display=swap"
rel="stylesheet">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<link
rel="stylesheet"
href="assets/css/login.css">
<link
rel="stylesheet"
href="assets/css/error-display.css">
</head>

<body>

 <div class="error-container" id="errorContainer" role="alert">
            <div class="error-box">
                <div id="errorMessages"></div>
            </div>
        </div>
<main class="main-contain">

<section class="flex-container">

<aside class="image-side">

<div class="hero-content">

<p class="brand-name">

🩷 Cutesy Aesthetics

<br>

Art. Creativity. Cuteness.

</p>

<h1>

Welcome

<br>

back!

<span>♡</span>

</h1>

<p class="hero-text">

Login to continue your creative journey with
Cutesy Aesthetics.

</p>

</div>

<img

src="login.webp"

class="login-img"

alt="Cute illustration welcoming users back to Cutesy Aesthetics"

loading="lazy">

</aside>

<section class="form-section">

<form id="profileForm"
novalidate>

<header class="form-header">

<h1>

Login

</h1>

<div class="divider">

<span>

🩷

</span>

</div>

<p>

Enter your details to access your account.

</p>

</header>



<div class="form-body">

<div class="form-group">

<label for="email">

<strong>Email Address</strong>

</label>

<div class="input-box">

<i
class="fa-regular fa-envelope"
aria-hidden="true"></i>

<input

id="email"

type="email"

name="email"

placeholder="Enter your email"

autocomplete="email"

required

value="<?= htmlspecialchars($old_email) ?>">

</div>

</div>

<div class="form-group">

<label for="password">

<strong>Password</strong>

</label>

<div class="input-box">

<i
class="fa-solid fa-lock"
aria-hidden="true"></i>

<input

id="password"

type="password"

name="password"

placeholder="Enter your password"

autocomplete="current-password"

minlength="8"

required>

</div>

</div>



<div class="form-options">

<label>

<input

class="check"

type="checkbox"

name="remember"

value="1">

Remember me

</label>

<a href="forgot-password.php">

Forgot Password?

</a>

</div>

<button

class="login"

type="submit">

Login

</button>



<div class="or-divider">

<span>

or

</span>

</div>


<button

type="button"

class="signup-btn"

onclick="window.location.href='signup.php'">

Create an Account

</button>

</div>



<footer class="form-footer">

<p>

By continuing you agree to our

<a href="#">

Terms of Service

</a>

and

<a href="#">

Privacy Policy

</a>

</p>

</footer>

</form>

</section>

</section>

</main>

<script src="assets/js/login.js"></script>

</body>

</html>
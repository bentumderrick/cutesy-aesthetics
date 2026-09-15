<?php
session_start();
if (isset($_SESSION["error"])) {
  $error = $_SESSION["error"];
  echo "<div class='error-container'><div class='error-box'>";
  echo "<p class='error-txt'>".$error."</p>";
  echo "</div></div>";
 unset($_SESSION["error"]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cutesy Aesthetics | Sign Up</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Raleway:wght@100..900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="login.css">
</head>

<body>

<main class="main-contain">

    <section class="flex-container">

        <!-- LEFT SIDE -->
        <aside class="image-side">

            <div class="hero-content">

                <p class="brand-name" style="color:#8E5SB6B;">
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

                <p style="font-weight:700;">
                    Login to continue your creative journey with
                    Cutesy Aesthetics.
                </p>

            </div>

            <img
                class="login-img"
                src="login.webp"
                alt="Painting supplies with a heart artwork">

        </aside>

        <!-- RIGHT SIDE -->
        <section class="form-section">

            <form action="signup_redirect.php" method="POST">

                <header class="form-header">

                    <h1>Create Account</h1>

                    <div class="divider">
                        <span>🩷</span>
                    </div>

                    <p>Create your account to begin your creative journey.</p>

                </header>

                <div class="form-body">

                    <!-- Display Name -->
                    <div class="form-group">

                        <label for="display_name">
                            <strong>Display Name</strong>
                        </label>

                        <div class="input-box">

                            <i class="fa-regular fa-user"></i>

                            <input 
                                id="display_name"
                                type="text"
                                name="display_name"
autocomplete="name"
                                placeholder="Enter your display name" value="<?=htmlspecialchars($_SESSION["old"]["display_name"] ??"")?>"
                                required>

                        </div>

                    </div>

                    <!-- Username -->
                    <div class="form-group">

                        <label for="username">
                            <strong>Username</strong>
                        </label>

                        <div class="input-box">

                            <i class="fa-solid fa-at"></i>

                            <input
                                id="username"
                                type="text"
                                name="username"
autocomplete="username"
                                placeholder="Choose a username"
                                required
                                value="<?=htmlspecialchars($_SESSION["old"]["username"] ??"")?>"
                                >

                        </div>

                    </div>

                    <!-- Email -->
                    <div class="form-group">

                        <label for="email">
                            <strong>Email Address</strong>
                        </label>

                        <div class="input-box">

                            <i class="fa-regular fa-envelope"></i>

                            <input
                                id="email"
                                type="email"
                                name="email"
autocomplete="email"
                                placeholder="Enter your email address"
                                value="<?=htmlspecialchars($_SESSION["old"]["email"] ?? ""); ?>"
                                required>

                        </div>

                    </div>

                    <!-- Password -->
                    <div class="form-group">

                        <label for="password">
                            <strong>Password</strong>
                        </label>

                        <div class="input-box">

                            <i class="fa-solid fa-lock"></i>

                            <input
                                id="password"
                                type="password"
autocomplete="new-password"
                                name="password" minlength="8"
                                placeholder="Create a password"
                                required>

                        </div>

                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">

                        <label for="confirm_password">
                            <strong>Confirm Password</strong>
                        </label>

                        <div class="input-box">

                            <i class="fa-solid fa-lock"></i>

                            <input
                                id="confirm_password"
                                type="password"
autocomplete="new-password"
                                name="confirm_password"
                                placeholder="Confirm your password"
                                required>

                        </div>
                    </div>
                    <div class="inline"><input type="checkbox" name="checkbox" id="checkbox" required /><p>I agree to the <a href="terms">Terms</a> & <a href="#">Privacy Policy</a></p></div>
                    <button class="login" type="submit">
                        Create Account
                    </button>
                    <div class="or-divider">
                        <span>or</span>
                    </div>

                    <p style="text-align:center;">
                        Already have an account?
                        <a href="login.php">Login</a>
                    </p>
<a href="signup_redirect.php">sign.php</a>
                </div>

            </form>

        </section>

    </section>

</main>
<script src="error.js"></script>
<?php unset($_SESSION["old"]); ?>
</body>
</html>
<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
include_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $display_name = trim($_POST["display_name"] ?? '');
    $username = trim($_POST["username"] ?? '');
    $email = strtolower(trim($_POST["email"] ?? ""));
    $password = $_POST["password"] ?? "";
$confirm_password = $_POST["confirm_password"] ?? "";
    // 1. Check individual empty fields for better error messages
    $_SESSION["old"] = [
      "display_name" => $display_name,
      "username" => $username,
      "email" => $email];
    if (empty($display_name)) {
        $_SESSION["error"] = "Display name is required name🥺";
        header('Location: signup.php');
        exit();
    }
elseif (strlen($display_name) > 50) {
    $_SESSION["error"] = "Display name is too long.";
    header("Location: signup.php");
    exit();
}
elseif (empty($username)) {
        $_SESSION["error"] = "Username is required username🥺";
        header('Location: signup.php');
        exit();
    }
elseif (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $username)) {

    $_SESSION["error"] =
    "Username can only contain letters, numbers and underscores.";

    header("Location: signup.php");
    exit();
}
    
    elseif (empty($email)) {
        $_SESSION["error"] = "Email address is required email🥺";
        header('Location: signup.php');
        exit();
    }
    elseif (empty($password)) {
        $_SESSION["error"] = "Password is required password🥺";
        header('Location: signup.php');
        exit();
    }
    
    // 2. Check the checkbox directly in the $_POST array safely
    elseif (!isset($_POST["checkbox"])) {
        $_SESSION["error"] = "You must agree to the terms and privacy policy📜";
        header('Location: signup.php');
        exit();
    }

    // 3. Format and validation checks
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["error"] = "Invalid email😬";
        header('Location: signup.php');
        exit();
    }
    elseif ($password !== $confirm_password) {
        $_SESSION["error"] = "Passwords do not match🤕";
        header('Location: signup.php');
        exit();
    }
    
    elseif (strlen($password) < 8) {
        $_SESSION["error"] = "Password must be at least 8 characters long 📏";
        header('Location: signup.php');
        exit();
    }
    elseif (!preg_match('/[A-Z]/', $password)) {
        $_SESSION["error"] = "Password needs at least one uppercase letter (A-Z)🔠";
        header('Location: signup.php');
        exit();
    }
    elseif (!preg_match('/[a-z]/', $password)) {
        $_SESSION["error"] = "Password needs at least one lowercase letter (a-z)🔡";
        header('Location: signup.php');
        exit();
    }
    elseif (!preg_match('/[0-9]/', $password)) {
        $_SESSION["error"] = "Password needs at least one number (0-9)🔢";
        header('Location: signup.php');
        exit();
    }
    elseif (!preg_match('/[^A-Za-z0-9]/', $password)) { 
        $_SESSION["error"] = "Password needs at least one special character (like @, #, $, %, etc.)✨";
        header('Location: signup.php');
        exit();
    }

    // 4. Validate Email Existence
    $sql = "SELECT id FROM users WHERE email=?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die("Database error.");
    }
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result=mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION["error"] = "Email already exists 👤";
        mysqli_stmt_close($stmt);
        header("Location: signup.php");
        exit();
    }
    mysqli_stmt_close($stmt);

    // 5. Validate Username Existence
    $sql = "SELECT id FROM users WHERE username=?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die("Database error.");
    }
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result=mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION["error"] = "Username already exists 👤";
        mysqli_stmt_close($stmt);
        header("Location: signup.php");
        exit();
    }
    mysqli_stmt_close($stmt);

    // 6. Insert into database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users(display_name, username, email, password) VALUES(?,?,?,?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $display_name, $username, $email, $hashed_password);
    $success = mysqli_stmt_execute($stmt);

    if ($success) {
        unset($_SESSION["old"]);
        $_SESSION["success"] = "🎉 Account created successfully! Please log in.";
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header("Location: login.php");
        exit();
    } else { // <--- Added the missing closing curly brace right here
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        $_SESSION["error"] = "Something went wrong.";
        header("Location: signup.php");
        exit();
    }

} else {
    $_SESSION["error"] = "Please please go through the right procedure";
    header('Location: signup.php');
    exit();
}
?>

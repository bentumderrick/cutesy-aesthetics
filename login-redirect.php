<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json; charset=utf-8');

include_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Check if both are empty
        if (empty($email) && empty($password)) {
            echo json_encode([
                "error" => true,
                "message" => "Please fill in all forms 🥺"
            ]);
            exit();
        }

        // Check if email is empty
        if (empty($email)) {
            echo json_encode([
                "error" => true,
                "message" => "Email address is required 🥺"
            ]);
            exit();
        }

        // Check if password is empty
        if (empty($password)) {
            echo json_encode([
                "error" => true,
                "message" => "Password is required 🥺"
            ]);
            exit();
        }

        // Look up user by email
        $sql = "SELECT * FROM users WHERE email = ?";

        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
            throw new Exception("Failed to prepare database query: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "s", $email);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        // Email not found
        if (mysqli_num_rows($result) === 0) {
            echo json_encode([
                "error" => true,
                "message" => "Email not found 😗"
            ]);
            exit();
        }

        $details = mysqli_fetch_assoc($result);

        // Wrong password
        if (!password_verify($password, $details['password'])) {
            echo json_encode([
                "error" => true,
                "message" => "Password is wrong 🫣"
            ]);
            exit();
        }

        // Login successful
        $_SESSION['user_id'] = $details['id'];
        session_regenerate_id(true);
        $_SESSION['user-details'] = $details;

        echo json_encode([
            "error" => false,
            "message" => "Login successful! Redirecting..."
        ]);

        exit();

    } catch (Throwable $e) {

        http_response_code(500);

        echo json_encode([
            "error" => true,
            "message" => "Server error: " . $e->getMessage()
        ]);

        exit();
    }

} else {

    echo json_encode([
        "error" => true,
        "message" => "Invalid request method."
    ]);

    exit();
}
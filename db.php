<?php

require_once __DIR__ . '/private/env.php';
loadEnv(__DIR__ . '/private/.env');

$conn = mysqli_connect(
    $_ENV['DB_HOST'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS'],
    $_ENV['DB_NAME']
);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

   
mysqli_set_charset($conn, "utf8mb4");

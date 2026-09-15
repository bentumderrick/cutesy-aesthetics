<?php
$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
$old_email = $_SESSION['old']['email'] ?? '';
unset($_SESSION['success']);
unset($_SESSION['error']);
unset($_SESSION['old']);
?>

 <?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

include_once 'auth.php';
include_once 'error-handling.php';
include_once 'db.php';

header("Content-Type: application/json");

// ============================================
// Helper Functions
// ============================================

function success($msg) {
    echo json_encode(["error" => false, "message" => $msg]);
    exit();
}

function fail($msg) {
    echo json_encode(["error" => true, "message" => $msg]);
    exit();
}

// ============================================
// Main Logic
// ============================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    fail("Invalid request method.");
}

$display_name = trim($_POST['display_name'] ?? '');
$username     = trim($_POST['username'] ?? '');
$email        = trim($_POST['email'] ?? '');
$bio          = trim($_POST['bio'] ?? '');
$phone        = trim($_POST['phone'] ?? '');

// ==========================
// 1. Text Field Validations
// ==========================

if (empty($display_name) || strlen($display_name) < 2) {
    fail("Display name must be at least 2 characters.");
}

if (strlen($display_name) > 225) {
    fail("Display name must be 225 characters or less.");
}

if (!empty($phone) && strlen($phone) > 20) {
    fail("Phone number is too long.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    fail("Invalid Email.");
}

if (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $username)) {
    fail("Username can only contain letters, numbers and underscores. No changes made.");
}

// Check if email exists for another user
$sql = 'SELECT id FROM users WHERE email = ? AND id != ?';
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    fail("Failed to prepare SQL statement.");
}
mysqli_stmt_bind_param($stmt, 'si', $email, $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    mysqli_stmt_close($stmt);
    fail("Email already exists 👤. No changes made.");
}
mysqli_stmt_close($stmt);

// Check if username exists for another user
$sql = 'SELECT id FROM users WHERE username = ? AND id != ?';
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    fail("Failed to prepare SQL statement.");
}
mysqli_stmt_bind_param($stmt, 'si', $username, $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    mysqli_stmt_close($stmt);
    fail("Username already exists 👤. No changes made.");
}
mysqli_stmt_close($stmt);

// ==========================
// 2. Image Upload Handling
// ==========================

$profile_picture_path = null;
$absolute_path = null;
$old = __DIR__ . "/" . ($_SESSION["user-details"]["profile_picture"] ?? 'uploads/profile-pictures/default-profile.webp');

if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE) {

    // Check file size
    if (
        $_FILES['profile_picture']['error'] === UPLOAD_ERR_INI_SIZE ||
        $_FILES['profile_picture']['error'] === UPLOAD_ERR_FORM_SIZE ||
        $_FILES['profile_picture']['size'] > 2 * 1024 * 1024
    ) {
        fail("Image size must be less than 2MB. No changes made.");
    }

    // Catch other upload errors
    if ($_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
        fail("Failed to upload image. No changes made.");
    }

    // Validate extension
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $extension = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, $allowed)) {
        fail("Unsupported image format. No changes made.");
    }

    // Validate it's a real image
    $imageInfo = getimagesize($_FILES['profile_picture']['tmp_name']);
    if ($imageInfo === false) {
        fail("The uploaded file is not a valid image.");
    }

    // Build paths with cryptographically secure unique filename
    $filename = 'profile_' . $_SESSION['user_id'] . '_' . bin2hex(random_bytes(16)) . '.' . $extension;
    $relative_path = 'uploads/profile-pictures/' . $filename;
    $absolute_path = __DIR__ . '/' . $relative_path;

    // Ensure upload directory exists
    $uploadDir = __DIR__ . "/uploads/profile-pictures/";
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        fail("Failed to create upload directory.");
    }

    // Move new file
    $tmp = $_FILES['profile_picture']['tmp_name'];
    if (!move_uploaded_file($tmp, $absolute_path)) {
        fail("Failed to save profile picture. No changes made.");
    }

    $profile_picture_path = $relative_path;
}

// ==========================
// 3. Update Database Record
// ==========================

if (!mysqli_begin_transaction($conn)) {
    fail("Database error. Please try again.");
}

try {
    if ($profile_picture_path) {
        $sql = "UPDATE users
                SET display_name = ?, username = ?, email = ?, phone = ?, bio = ?, profile_picture = ?
                WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare SQL statement.");
        }
        mysqli_stmt_bind_param($stmt, 'ssssssi', $display_name, $username, $email, $phone, $bio, $profile_picture_path, $_SESSION['user_id']);
    } else {
        $sql = "UPDATE users
                SET display_name = ?, username = ?, email = ?, phone = ?, bio = ?
                WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare SQL statement.");
        }
        mysqli_stmt_bind_param($stmt, 'sssssi', $display_name, $username, $email, $phone, $bio, $_SESSION['user_id']);
    }

    if (!mysqli_stmt_execute($stmt)) {
        $error = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        throw new Exception($error);
    }

    $updated = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    // ==========================
    // 4. Refresh User Session
    // ==========================

    $sql = 'SELECT * FROM users WHERE id = ?';
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        throw new Exception("Failed to prepare SQL statement.");
    }
    mysqli_stmt_bind_param($stmt, 'i', $_SESSION['user_id']);
    
    if (!mysqli_stmt_execute($stmt)) {
        $error = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        throw new Exception($error);
    }
    
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if (!$user) {
        mysqli_stmt_close($stmt);
        throw new Exception("Failed to refresh session.");
    }

    $_SESSION["user-details"] = $user;
    mysqli_stmt_close($stmt);

    // Commit transaction
    if (!mysqli_commit($conn)) {
        throw new Exception("Failed to commit transaction: " . mysqli_error($conn));
    }

    // Delete OLD file AFTER successful commit
    if (
        $profile_picture_path &&
        file_exists($old) &&
        basename($old) !== "default-profile.webp"
    ) {
        if (!unlink($old)) {
            error_log("Failed to delete old profile picture: " . $old);
        }
    }

    // Success response
    if ($updated > 0 || $profile_picture_path) {
        success("Profile updated.");
    } else {
        success("No changes made.");
    }

} catch (Exception $e) {
    mysqli_rollback($conn);

    // Delete new file if it was created during this request
    if (isset($absolute_path) && file_exists($absolute_path)) {
        if (!unlink($absolute_path)) {
            error_log("Failed to delete file after rollback: " . $absolute_path);
        }
    }

    fail($e->getMessage());
}
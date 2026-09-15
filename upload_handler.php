<?php
session_start();
require_once "db.php";
require_once "auth.php";

header("Content-Type: application/json");

// ---------- Session & role ----------
$nickname = $_SESSION["user-details"]["display_name"] ?? "buddy";

if (!isset($_SESSION["user-details"]["id"])) {
    echo json_encode(["success" => false, "message" => "Session error."]);
    exit();
}
$user_id = $_SESSION["user-details"]["id"];

if (
    !isset($_SESSION["user-details"]["role"]) ||
    $_SESSION["user-details"]["role"] !== "creator"
) {
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized access. Get moving bro😤",
    ]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Unauthorised Request Method.",
    ]);
    exit();
}

// ---------- Collect input ----------
$title        = trim($_POST["title"] ?? "");
$description  = trim($_POST["description"] ?? "");
$category     = trim($_POST["category"] ?? "");
$tags         = trim($_POST["tags"] ?? "");
$price        = trim($_POST["price"] ?? "");
$not_for_sale = isset($_POST["not_for_sale"]) ? 1 : 0;
$visibility   = trim($_POST["visibility"] ?? "public");
$action       = trim($_POST["action"] ?? "publish");

// ---------- Basic validation ----------
if (empty($title)) {
    echo json_encode([
        "success" => false,
        "message" => "{$nickname}😙, u forgot to type in the title",
    ]);
    exit();
}

if (empty($category)) {
    echo json_encode([
        "success" => false,
        "message" => "{$nickname}😙, u forgot to select a category",
    ]);
    exit();
}

// Price validation (only relevant when not_for_sale is 0)
if ($not_for_sale == 0 && $price !== "" && !is_numeric($price)) {
    echo json_encode([
        "success" => false,
        "message" => "{$nickname}, price must be a number.",
    ]);
    exit();
}

// ---------- File presence ----------
if (
    !isset($_FILES["upload_file"]) ||
    $_FILES["upload_file"]["error"] !== UPLOAD_ERR_OK
) {
    echo json_encode([
        "success" => false,
        "message" => "Please upload an image or video.",
    ]);
    exit();
}

$tmp_filepath = $_FILES["upload_file"]["tmp_name"];
$file_size    = $_FILES["upload_file"]["size"];
$filename     = $_FILES["upload_file"]["name"];

// ---------- Size check ----------
$max_size = 10 * 1024 * 1024; // 10MB
if ($file_size > $max_size) {
    echo json_encode([
        "success" => false,
        "message" => "Sorry {$nickname}, the file size limit is 10mb🥲",
    ]);
    exit();
}

// ---------- Extension check ----------
$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
$allowed_extensions = ["jpg", "jpeg", "png", "mp4", "mov", "webp", "gif"];
if (!in_array($extension, $allowed_extensions)) {
    echo json_encode([
        "success" => false,
        "message" => "Sorry {$nickname}, we don't accept this file type😙",
    ]);
    exit();
}

// ---------- Uploaded-file check ----------
if (!is_uploaded_file($tmp_filepath)) {
    echo json_encode([
        "success" => false,
        "message" => "Sorry {$nickname}, something went wrong with the upload.",
    ]);
    exit();
}

// ---------- MIME check ----------
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime_type = finfo_file($finfo, $tmp_filepath);
finfo_close($finfo);

$allowed_mime = [
    "image/jpeg",
    "image/png",
    "image/webp",
    "image/gif",
    "video/mp4",
    "video/quicktime",
];
if (!in_array($mime_type, $allowed_mime)) {
    echo json_encode([
        "success" => false,
        "message" => "Sorry {$nickname}, this file ain't legit, looks fishy😑",
    ]);
    exit();
}

// ---------- Media type ----------
$media_type = strpos($mime_type, "image/") === 0 ? "image" : "video";

// Videos are never sellable — enforce this server-side too, don't just trust the client.
if ($media_type === "video") {
    $not_for_sale = 1;
    $price = "";
}

// ---------- Prepare upload directory ----------
$uploadDir = __DIR__ . "/uploads/post/";
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        echo json_encode([
            "success" => false,
            "message" => "Oops, sorry {$nickname} we are facing technical difficulties with a folder",
        ]);
        exit();
    }
}

// ---------- Move file ----------
$random_str   = bin2hex(random_bytes(16));
$new_filename = $user_id . "_" . $random_str . "." . $extension;
$destination  = $uploadDir . $new_filename;

if (!move_uploaded_file($tmp_filepath, $destination)) {
    echo json_encode([
        "success" => false,
        "message" => "Sorry {$nickname}, unable to move new file",
    ]);
    exit();
}

$relative_path = "uploads/post/" . $new_filename;

// ---------- Category lookup ----------
$sql = "SELECT id FROM categories WHERE slug = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $category);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
$category_id = $row["id"] ?? null;
mysqli_stmt_close($stmt);

if (!$category_id) {
    unlink($destination); // clean up file since we're aborting
    echo json_encode([
        "success" => false,
        "message" => "Category not found😗",
    ]);
    exit();
}

// ---------- Determine post status ----------
$post_status = ($action === "publish") ? "published" : "draft";

// ---------- Transaction ----------
mysqli_begin_transaction($conn);

try {
    $product_id = null;

    // 1. Insert product if for sale
    if ($not_for_sale == 0 && $price !== "" && $price > 0) {
        $currency = "ZMW";
        $stock    = 1;
        $status   = "available";

        $sql = "INSERT INTO products 
                (user_id, name, description, price, currency, stock, status, main_image, category_id) 
                VALUES (?,?,?,?,?,?,?,?,?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "issdsissi",
            $user_id,
            $title,
            $description,
            $price,
            $currency,
            $stock,
            $status,
            $relative_path,
            $category_id
        );
        mysqli_stmt_execute($stmt);
        $product_id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
    }

    // 2. Insert post
    $sql = "INSERT INTO posts 
            (user_id, caption, media_type, media_url, product_id, category_id, status) 
            VALUES (?,?,?,?,?,?,?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "isssiis",
        $user_id,
        $description,
        $media_type,
        $relative_path,
        $product_id,
        $category_id,
        $post_status
    );
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // 3. Commit
    mysqli_commit($conn);

    echo json_encode([
        "success" => true,
        "message" => $post_status === "published"
            ? "Post published!"
            : "Draft saved!",
    ]);

} catch (Exception $e) {
    mysqli_rollback($conn);

    // Clean up the file we moved
    if (file_exists($destination)) {
        unlink($destination);
    }

    echo json_encode([
        "success" => false,
        "message" => "Something went wrong saving to the database.",
    ]);
}
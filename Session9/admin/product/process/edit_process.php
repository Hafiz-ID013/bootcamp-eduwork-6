<?php
require_once __DIR__ . "/../../../config/connection_pdo.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request.");
}

$id          = (int) ($_POST['id'] ?? 0);
$name        = trim($_POST['name'] ?? '');
$price       = trim($_POST['price'] ?? '');
$description = trim($_POST['description'] ?? '');
$category    = trim($_POST['category'] ?? '');

if (!$id || !$name || !$price || !$description || !$category) {
    die("All fields are required.");
}

/* ============================
   Get current image
============================ */
$stmt = $pdo->prepare("SELECT image FROM products WHERE id = :id");
$stmt->execute([':id' => $id]);
$current = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$current) {
    die("Product not found.");
}

$oldImage = $current['image'];

/* ============================
   Upload new image (optional)
============================ */
$newImage = $oldImage;

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','webp'];

    if (!in_array($ext, $allowed)) {
        die("Invalid image format.");
    }

    if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
        die("Image size exceeds 2MB.");
    }

    $newImage = md5(uniqid()) . "." . $ext;

    $uploadDir = __DIR__ . "/../../../uploaded_files/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $dest = $uploadDir . $newImage;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
        die("Failed to upload image.");
    }

    /* ============================
       DELETE OLD IMAGE
    ============================ */
    if (!empty($oldImage)) {
        $oldPath = $uploadDir . $oldImage;
        if (file_exists($oldPath)) {
            unlink($oldPath);
        }
    }
}

/* ============================
   Update database
============================ */
$sql = "UPDATE products
        SET name = :name,
            price = :price,
            description = :description,
            category = :category,
            image = :image
        WHERE id = :id";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':name'        => $name,
    ':price'       => $price,
    ':description' => $description,
    ':category'    => $category,
    ':image'       => $newImage,
    ':id'          => $id
]);

header("Location: ../index.php?updated=1");
exit;

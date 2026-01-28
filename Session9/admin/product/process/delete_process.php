<?php
// load database connection
require_once __DIR__ . "/../../../config/connection_pdo.php";

// validate id from url
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../index.php?message=Invalid product ID.");
    exit;
}

$productId = (int) $_GET['id'];

// delete product first, then get image using affected row? (no)
$stmt = $pdo->prepare("SELECT image FROM products WHERE id = :id");
$stmt->execute([':id' => $productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: ../index.php?message=Product not found.");
    exit;
}

$oldImage = trim((string)$product['image']);

// delete product from database
$stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
$stmt->execute([':id' => $productId]);

if ($stmt->rowCount() <= 0) {
    header("Location: ../index.php?message=Delete failed.");
    exit;
}

// delete image file from uploaded_files (if exists)
if ($oldImage !== "") {
    $uploadDir = __DIR__ . "/../../../uploaded_files/";
    $oldPath = $uploadDir . basename($oldImage);

    if (file_exists($oldPath)) {
        @unlink($oldPath);
    }
}

// redirect back to list page
header("Location: ../index.php?message=Product deleted successfully.");
exit;

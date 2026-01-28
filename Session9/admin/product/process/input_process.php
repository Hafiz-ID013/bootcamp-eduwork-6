<?php
// Session9/admin/product/process/input_process.php

require_once __DIR__ . "/../../../config/connection_pdo.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

// ==================
// Get form data
// ==================
$name        = trim($_POST['name'] ?? '');
$price       = trim($_POST['price'] ?? '');
$description = trim($_POST['description'] ?? '');
$category    = trim($_POST['category'] ?? '');

if ($name === '' || $price === '' || $description === '' || $category === '') {
    die("All fields are required.");
}

// ==================
// Handle image upload
// ==================
$imageName = null;

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

    $fileTmpPath = $_FILES['image']['tmp_name'];
    $fileName    = $_FILES['image']['name'];
    $fileSize    = $_FILES['image']['size'];

    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($fileExt, $allowed)) {
        die("Invalid file type.");
    }

    if ($fileSize > 2 * 1024 * 1024) {
        die("File size exceeds 2MB.");
    }

    $imageName = md5(uniqid()) . '.' . $fileExt;

    $uploadDir = __DIR__ . "/../../../uploaded_files/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $destPath = $uploadDir . $imageName;

    if (!move_uploaded_file($fileTmpPath, $destPath)) {
        die("Failed to upload image.");
    }

} else {
    die("Image upload is required.");
}

// ==================
// Insert into database
// ==================
$sql = "INSERT INTO products (name, price, description, category, image)
        VALUES (:name, :price, :description, :category, :image)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':name'        => $name,
    ':price'       => $price,
    ':description' => $description,
    ':category'    => $category,
    ':image'       => $imageName
]);

// ==================
// Redirect after success with message
// ==================
header("Location: ../index.php?message=" . urlencode("Product added successfully."));
exit;

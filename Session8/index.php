<?php
require_once "connection_pdo.php";

/* 🔒 Safety check */
if (!isset($conn)) {
    die("Database connection variable not found.");
}

$selectedCategory = isset($_GET['category']) ? $_GET['category'] : "";

/* Get categories */
$categoryQuery = $conn->query("SELECT DISTINCT category FROM products ORDER BY category ASC");
$categories = $categoryQuery->fetchAll(PDO::FETCH_ASSOC);

/* Get products */
$sql = "SELECT id, name, description, price, category, image FROM products";
$params = [];

if ($selectedCategory !== "") {
    $sql .= " WHERE category = :category";
    $params[':category'] = $selectedCategory;
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

function rupiah($price) {
    return "Rp " . number_format($price, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>

    <!-- Local Bootstrap -->
    <link rel="stylesheet" href="../bootstrap-5.3.8-dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-4">

    <div class="mb-4 p-4 bg-primary text-white rounded">
        <h2 class="mb-1">Product List</h2>
        <p class="mb-0">Filter products by category</p>
    </div>

    <!-- Filter -->
    <form method="GET" class="mb-4 d-flex gap-2">
        <select name="category" class="form-select w-auto">
            <option value="">All Categories</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['category']; ?>" <?= ($selectedCategory == $c['category']) ? 'selected' : ''; ?>>
                    <?= $c['category']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button class="btn btn-primary">Filter</button>
        <a href="index.php" class="btn btn-secondary">Reset</a>
    </form>

    <!-- Products -->
    <div class="row g-3">
        <?php foreach ($products as $p): ?>
            <div class="col-md-3">
                <div class="card h-100 shadow-sm">
                    <img src="<?= $p['image'] ?: 'https://via.placeholder.com/400x250'; ?>"
                         class="card-img-top"
                         style="height:160px;object-fit:cover;">

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= $p['name']; ?></h5>
                        <span class="badge bg-dark mb-2"><?= $p['category']; ?></span>
                        <p class="card-text small text-muted"><?= $p['description']; ?></p>

                        <div class="mt-auto d-flex justify-content-between">
                            <strong><?= rupiah($p['price']); ?></strong>
                            <small>#<?= $p['id']; ?></small>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<script src="../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

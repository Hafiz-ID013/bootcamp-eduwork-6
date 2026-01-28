<?php
// Session9/admin/product/edit_page.php

require_once __DIR__ . "/../../config/connection_pdo.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid product ID.");
}

$productId = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT id, name, price, description, category, image FROM products WHERE id = :id");
$stmt->execute([':id' => $productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found.");
}

$title = "Edit Product";
ob_start();
?>

<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-8 col-xl-7">

      <div class="card shadow-sm border-0">
        <div class="card-body p-4 p-md-5">
          <div class="d-flex align-items-start justify-content-between gap-3 mb-4">
            <div>
              <h1 class="h4 fw-bold mb-1">Edit Product</h1>
              <p class="text-secondary mb-0">Update the product information and save changes.</p>
            </div>
            <a href="./index.php" class="btn btn-outline-secondary btn-sm">Back</a>
          </div>

          <form action="./process/edit_process.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= (int)$product['id'] ?>">

            <div class="row g-3">

              <div class="col-md-6">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="name"
                       value="<?= htmlspecialchars($product['name']) ?>" required>
              </div>

              <div class="col-md-6">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" min="0"
                       value="<?= htmlspecialchars($product['price']) ?>" required>
              </div>

              <div class="col-12">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?= htmlspecialchars($product['description']) ?></textarea>
              </div>

              <div class="col-md-6">
                <label for="category" class="form-label">Category</label>
                <input type="text" class="form-control" id="category" name="category"
                       value="<?= htmlspecialchars($product['category']) ?>" required>
              </div>

              <div class="col-md-6">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                <div class="form-text">Leave empty if you don't want to change the image.</div>
              </div>

              <?php
              $rawImage = trim((string)$product['image']);
              if ($rawImage !== ""):
                  $imgSrc = "/bootcamp-eduwork-6/Session9/uploaded_files/" . ltrim($rawImage, "/");
              ?>
                <div class="col-12">
                  <div class="d-flex align-items-center gap-3">
                    <img
                      src="<?= htmlspecialchars($imgSrc) ?>"
                      alt="<?= htmlspecialchars($product['name']) ?>"
                      style="width: 110px; height: 110px; object-fit: cover; border-radius: 12px;"
                      onerror="this.onerror=null;this.src='https://via.placeholder.com/220x220?text=No+Image';"
                    >
                    <div>
                      <div class="fw-semibold">Current Image</div>
                      <div class="text-muted small"><?= htmlspecialchars($rawImage) ?></div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>

              <div class="col-12 d-flex gap-2 mt-2">
                <button type="submit" class="btn btn-primary px-4">Update Product</button>
                <a href="./index.php" class="btn btn-outline-secondary px-4">Cancel</a>
              </div>

            </div>
          </form>

        </div>
      </div>

    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . "/../../template/main.php";
?>

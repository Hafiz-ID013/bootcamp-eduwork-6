<?php
require_once __DIR__ . "/../config/connection_pdo.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid product id");
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT id, name, description, price, category, image FROM products WHERE id = :id");
$stmt->execute([':id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found");
}

function rupiah($price) {
    return "Rp " . number_format((float)$price, 0, ',', '.');
}

$img = trim((string)$product['image']);
$imgSrc = $img !== ""
  ? "/bootcamp-eduwork-6/Session9/uploaded_files/" . $img
  : "https://via.placeholder.com/600x400?text=No+Image";

$title = "Product Detail";
ob_start();
?>

<div class="container py-4">
  <div class="row g-4">
    <div class="col-12 col-lg-5">
      <div class="card shadow-sm">
        <img
          src="<?= htmlspecialchars($imgSrc) ?>"
          class="card-img-top bg-light"
          style="height:360px;object-fit:contain;padding:18px;"
          onerror="this.onerror=null;this.src='https://via.placeholder.com/600x400?text=No+Image';"
        >
      </div>
    </div>

    <div class="col-12 col-lg-7">
      <div class="card shadow-sm border-0">
        <div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-start gap-3">
            <div>
              <h1 class="h4 fw-bold mb-1"><?= htmlspecialchars($product['name']) ?></h1>
              <span class="badge bg-dark"><?= htmlspecialchars($product['category']) ?></span>
            </div>
            <a href="/bootcamp-eduwork-6/Session9/index.php" class="btn btn-outline-secondary btn-sm">Back</a>
          </div>

          <hr>

          <p class="text-muted mb-3"><?= nl2br(htmlspecialchars($product['description'])) ?></p>

          <div class="d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted small">Price</div>
              <div class="fs-4 fw-bold"><?= rupiah($product['price']) ?></div>
            </div>

            <a href="cart.php?action=add&id=<?= (int)$product['id'] ?>" class="btn btn-primary btn-lg">
              Add to Cart
            </a>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . "/../template/main.php";
?>

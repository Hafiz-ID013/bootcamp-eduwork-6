<?php
session_start();

// load database connection
require_once __DIR__ . "/../config/connection_pdo.php";

// init cart session
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// fetch cart items
$cartIds = array_keys($_SESSION['cart']);
$items = [];
$total = 0;

if (!empty($cartIds)) {
    $placeholders = implode(',', array_fill(0, count($cartIds), '?'));
    $stmt = $pdo->prepare("SELECT id, name, price, image FROM products WHERE id IN ($placeholders)");
    $stmt->execute($cartIds);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($items as $p) {
        $qty = (int)($_SESSION['cart'][$p['id']] ?? 0);
        $total += $qty * (float)$p['price'];
    }
}

function rupiah($price) {
    return "Rp " . number_format((float)$price, 0, ',', '.');
}

$title = "Checkout";
ob_start();
?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h1 class="h4 fw-bold mb-1">Checkout</h1>
      <div class="text-muted small">Review your order and fill your details.</div>
    </div>
    <a href="/bootcamp-eduwork-6/Session9/user/cart.php" class="btn btn-outline-secondary btn-sm">Back to Cart</a>
  </div>

  <?php if (empty($_SESSION['cart']) || empty($items)): ?>
    <div class="alert alert-warning mb-0">
      Your cart is empty. <a href="/bootcamp-eduwork-6/Session9/index.php" class="alert-link">Go shopping</a>.
    </div>
  <?php else: ?>

    <div class="row g-3">
      <div class="col-12 col-lg-7">
        <div class="card shadow-sm border-0">
          <div class="card-body p-4">
            <h2 class="h6 fw-bold mb-3">Customer Details</h2>

            <div class="mt-4">
              <form action="transaction_status.php" method="POST">
                <div class="mb-3">
                  <label for="name" class="form-label">Full Name</label>
                  <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                  <label for="phone" class="form-label">Phone Number</label>
                  <input type="text" class="form-control" id="phone" name="phone" required>
                </div>

                <div class="mb-3">
                  <label for="address" class="form-label">Address</label>
                  <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                </div>

                <div class="d-flex gap-2 mt-2">
                  <button type="submit" class="btn btn-primary px-4" onclick="transaction_status.php">Place Order</button>
                  <a href="/bootcamp-eduwork-6/Session9/index.php" class="btn btn-outline-secondary px-4">Continue Shopping</a>
                </div>
              </form>
            </div>

          </div>
        </div>
      </div>

      <div class="col-12 col-lg-5">
        <div class="card shadow-sm">
          <div class="card-body p-4">
            <h2 class="h6 fw-bold mb-3">Order Summary</h2>

            <div class="vstack gap-3">
              <?php foreach ($items as $p): ?>
                <?php
                  $qty = (int)($_SESSION['cart'][$p['id']] ?? 0);
                  if ($qty <= 0) continue;

                  $subtotal = $qty * (float)$p['price'];
                  $img = trim((string)$p['image']);
                  $imgSrc = $img !== ""
                    ? "/bootcamp-eduwork-6/Session9/uploaded_files/" . $img
                    : "https://via.placeholder.com/80x80?text=No+Image";
                ?>
                <div class="d-flex align-items-center gap-3">
                  <img
                    src="<?= htmlspecialchars($imgSrc) ?>"
                    class="bg-light"
                    style="width:56px;height:56px;object-fit:contain;padding:6px;border-radius:8px"
                    onerror="this.onerror=null;this.src='https://via.placeholder.com/80x80?text=No+Image';"
                    alt="<?= htmlspecialchars($p['name']) ?>"
                  >
                  <div class="flex-grow-1">
                    <div class="fw-semibold"><?= htmlspecialchars($p['name']) ?></div>
                    <div class="text-muted small"><?= $qty ?> x <?= rupiah($p['price']) ?></div>
                  </div>
                  <div class="fw-semibold"><?= rupiah($subtotal) ?></div>
                </div>
              <?php endforeach; ?>

              <hr class="my-2">

              <div class="d-flex justify-content-between">
                <span class="text-muted">Total</span>
                <span class="fw-bold fs-5"><?= rupiah($total) ?></span>
              </div>

            </div>

          </div>
        </div>
      </div>
    </div>

  <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . "/../template/main.php";
?>

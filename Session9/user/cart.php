<?php
// user/cart.php
session_start();
require_once __DIR__ . "/../config/connection_pdo.php";

// init cart session
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // [productId => qty]
}

// read action + id
$action = $_GET['action'] ?? '';
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

// add item
if ($action === 'add' && $id > 0) {
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    header("Location: cart.php");
    exit;
}

// minus qty
if ($action === 'minus' && $id > 0) {
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]--;
        if ($_SESSION['cart'][$id] <= 0) unset($_SESSION['cart'][$id]);
    }
    header("Location: cart.php");
    exit;
}

// remove item
if ($action === 'remove' && $id > 0) {
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit;
}

// clear cart
if ($action === 'clear') {
    $_SESSION['cart'] = [];
    header("Location: cart.php");
    exit;
}

// fetch cart items from DB
$cartIds = array_keys($_SESSION['cart']);
$items = [];
$total = 0;

if (!empty($cartIds)) {
    $placeholders = implode(',', array_fill(0, count($cartIds), '?'));
    $stmt = $pdo->prepare("SELECT id, name, price, image FROM products WHERE id IN ($placeholders)");
    $stmt->execute($cartIds);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function rupiah($price) {
    return "Rp " . number_format((float)$price, 0, ',', '.');
}

$title = "Cart";
ob_start();
?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 fw-bold mb-0">Cart</h1>
    <div class="d-flex gap-2">
      <a href="/bootcamp-eduwork-6/Session9/index.php" class="btn btn-outline-secondary btn-sm">Continue Shopping</a>
      <a href="cart.php?action=clear" class="btn btn-outline-danger btn-sm" onclick="return confirm('Clear cart?')">Clear</a>
    </div>
  </div>

  <?php if (empty($_SESSION['cart'])): ?>
    <div class="alert alert-warning">Cart is empty.</div>
  <?php else: ?>

    <div class="card shadow-sm">
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Product</th>
              <th style="width:160px;">Qty</th>
              <th style="width:160px;">Price</th>
              <th style="width:160px;">Subtotal</th>
              <th style="width:80px;"></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $p): ?>
              <?php
                $qty = $_SESSION['cart'][$p['id']] ?? 0;
                $subtotal = $qty * (float)$p['price'];
                $total += $subtotal;

                $img = trim((string)$p['image']);
                $imgSrc = $img !== ""
                  ? "/bootcamp-eduwork-6/Session9/uploaded_files/" . $img
                  : "https://via.placeholder.com/80x80?text=No+Image";
              ?>
              <tr>
                <td>
                  <div class="d-flex align-items-center gap-3">
                    <img
                      src="<?= htmlspecialchars($imgSrc) ?>"
                      style="width:56px;height:56px;object-fit:contain;padding:6px;background:#f8f9fa;border-radius:8px"
                      onerror="this.onerror=null;this.src='https://via.placeholder.com/80x80?text=No+Image';"
                    >
                    <div class="fw-semibold"><?= htmlspecialchars($p['name']) ?></div>
                  </div>
                </td>

                <td>
                  <div class="d-flex align-items-center gap-2">
                    <a class="btn btn-outline-secondary btn-sm" href="cart.php?action=minus&id=<?= (int)$p['id'] ?>">-</a>
                    <span class="fw-semibold"><?= (int)$qty ?></span>
                    <a class="btn btn-outline-secondary btn-sm" href="cart.php?action=add&id=<?= (int)$p['id'] ?>">+</a>
                  </div>
                </td>

                <td><?= rupiah($p['price']) ?></td>
                <td class="fw-semibold"><?= rupiah($subtotal) ?></td>

                <td>
                  <a class="btn btn-outline-danger btn-sm" href="cart.php?action=remove&id=<?= (int)$p['id'] ?>">x</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="d-flex justify-content-end mt-3">
      <div class="card shadow-sm" style="min-width: 320px;">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <span class="text-muted">Total</span>
            <span class="fw-bold fs-5"><?= rupiah($total) ?></span>
          </div>
          <button class="btn btn-primary w-100 mt-3" onclick="location.href='checkout.php'">Checkout (next)</button>
        </div>
      </div>
    </div>

  <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . "/../template/main.php";
?>

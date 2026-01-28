<?php
// Session9/user/transaction_status.php

session_start();
require_once __DIR__ . "/../config/connection_pdo.php";

// ---------- helpers ----------
function rupiah($price) {
    return "Rp " . number_format((float)$price, 0, ',', '.');
}

function pickColumn(array $cols, array $candidates) {
    $lower = array_map('strtolower', $cols);
    foreach ($candidates as $cand) {
        $i = array_search(strtolower($cand), $lower, true);
        if ($i !== false) return $cols[$i];
    }
    return null;
}

function getTableColumns(PDO $pdo, string $table): array {
    $stmt = $pdo->query("DESCRIBE `$table`");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return array_map(fn($r) => $r['Field'], $rows);
}

function getUserColumnMap(PDO $pdo): array {
    $cols = getTableColumns($pdo, "users");

    $map = [
        'name'    => pickColumn($cols, ['name', 'fullname', 'full_name', 'nama', 'username']),
        'email'   => pickColumn($cols, ['email', 'mail']),
        'phone'   => pickColumn($cols, ['phone', 'telp', 'telephone', 'no_telp', 'no_hp', 'hp']),
        'address' => pickColumn($cols, ['address', 'alamat']),
    ];

    foreach ($map as $k => $v) {
        if ($v === null) {
            die("Missing column in users table for: {$k}");
        }
    }

    return $map;
}

// ---------- POST = create transaction then redirect ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        die("Cart is empty.");
    }

    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if ($name === '' || $email === '' || $phone === '' || $address === '') {
        die("All fields are required.");
    }

    try {
        $pdo->beginTransaction();

        // map users columns dynamically (fix: unknown column name/fullname/etc)
        $u = getUserColumnMap($pdo);

        // insert user
        $sqlUser = "INSERT INTO users (`{$u['name']}`, `{$u['email']}`, `{$u['phone']}`, `{$u['address']}`)
                    VALUES (:name, :email, :phone, :address)";
        $stmtUser = $pdo->prepare($sqlUser);
        $stmtUser->execute([
            ':name'    => $name,
            ':email'   => $email,
            ':phone'   => $phone,
            ':address' => $address,
        ]);
        $user_id = (int)$pdo->lastInsertId();

        // fetch products in cart
        $cart = $_SESSION['cart'];
        $productIds = array_keys($cart);
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));

        $stmtProducts = $pdo->prepare("SELECT id, price FROM products WHERE id IN ($placeholders)");
        $stmtProducts->execute($productIds);
        $products = $stmtProducts->fetchAll(PDO::FETCH_ASSOC);

        // calculate total + prepare items
        $total = 0;
        $items = [];

        foreach ($products as $p) {
            $pid = (int)$p['id'];
            $qty = (int)($cart[$pid] ?? 0);
            if ($qty <= 0) continue;

            $line = $qty * (float)$p['price'];
            $total += $line;

            $items[] = [
                'product_id'  => $pid,
                'quantity'    => $qty,
                'total_price' => $line
            ];
        }

        if (empty($items)) {
            $pdo->rollBack();
            die("Cart is empty.");
        }

        // insert transaction
        $stmtTrx = $pdo->prepare("
            INSERT INTO transactions (status, total, user_id)
            VALUES ('pending', :total, :user_id)
        ");
        $stmtTrx->execute([
            ':total'   => $total,
            ':user_id' => $user_id
        ]);
        $transaction_id = (int)$pdo->lastInsertId();

        // insert transaction items
        $stmtItem = $pdo->prepare("
            INSERT INTO transaction_items (transaction_id, product_id, quantity, total_price)
            VALUES (:transaction_id, :product_id, :quantity, :total_price)
        ");
        foreach ($items as $it) {
            $stmtItem->execute([
                ':transaction_id' => $transaction_id,
                ':product_id'     => $it['product_id'],
                ':quantity'       => $it['quantity'],
                ':total_price'    => $it['total_price'],
            ]);
        }

        $pdo->commit();

        unset($_SESSION['cart']);

        header("Location: transaction_status.php?transaction_id={$transaction_id}&success=1");
        exit;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        die("Transaction failed: " . $e->getMessage());
    }
}

// ---------- GET = show transaction ----------
if (!isset($_GET['transaction_id']) || !is_numeric($_GET['transaction_id'])) {
    die("Invalid transaction id.");
}
$transaction_id = (int)$_GET['transaction_id'];

$u = getUserColumnMap($pdo);

// transaction + user
$sqlTrx = "
    SELECT 
        t.id, t.status, t.total,
        u.`{$u['name']}`    AS user_name,
        u.`{$u['email']}`   AS user_email,
        u.`{$u['phone']}`   AS user_phone,
        u.`{$u['address']}` AS user_address
    FROM transactions t
    JOIN users u ON u.id = t.user_id
    WHERE t.id = :id
";
$stmt = $pdo->prepare($sqlTrx);
$stmt->execute([':id' => $transaction_id]);
$trx = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$trx) {
    die("Transaction not found.");
}

$title = "Transaction Status";
ob_start();
?>

<div class="container py-4" style="max-width: 900px;">
  <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
    <div class="alert alert-success mb-3">Transaction successful</div>
  <?php endif; ?>

  <h1 class="h3 fw-bold mb-3">Transaction Details</h1>

  <div class="mb-4">
    <div class="mb-2"><span class="fw-semibold">Transaction ID:</span> <?= (int)$trx['id'] ?></div>
    <div class="mb-2"><span class="fw-semibold">Status:</span> <?= htmlspecialchars((string)$trx['status']) ?></div>
    <div class="mb-2"><span class="fw-semibold">Total Amount:</span> <?= rupiah($trx['total']) ?></div>
  </div>

  <h2 class="h4 fw-bold mb-3">User Information</h2>

  <div class="mb-2"><span class="fw-semibold">Name:</span> <?= htmlspecialchars((string)$trx['user_name']) ?></div>
  <div class="mb-2"><span class="fw-semibold">Email:</span> <?= htmlspecialchars((string)$trx['user_email']) ?></div>
  <div class="mb-2"><span class="fw-semibold">Phone:</span> <?= htmlspecialchars((string)$trx['user_phone']) ?></div>
  <div class="mb-4"><span class="fw-semibold">Address:</span> <?= htmlspecialchars((string)$trx['user_address']) ?></div>

  <div class="d-flex gap-2">
    <a class="btn btn-primary" href="/bootcamp-eduwork-6/Session9/index.php">Back to Home</a>
    <a class="btn btn-outline-secondary" href="/bootcamp-eduwork-6/Session9/user/cart.php">Go to Cart</a>
  </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . "/../template/main.php";
?>

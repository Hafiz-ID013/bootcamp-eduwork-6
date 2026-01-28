<?php
require_once __DIR__ . "/config/connection_pdo.php";

// // safety check
if (!isset($pdo) || !($pdo instanceof PDO)) {
    die("Database connection variable (\$pdo) not found.");
}

// // get filter inputs
$selectedCategory = isset($_GET['category']) ? trim($_GET['category']) : "";
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

// // get categories
$categoryQuery = $pdo->query("SELECT DISTINCT category FROM products ORDER BY category ASC");
$categories = $categoryQuery->fetchAll(PDO::FETCH_ASSOC);

// // build products query
$sql = "SELECT id, name, description, price, category, image FROM products";
$params = [];
$where = [];

// // category filter
if ($selectedCategory !== "") {
    $where[] = "category = :category";
    $params[':category'] = $selectedCategory;
}

// // search filter (name/description/category)
if ($search !== "") {
    $where[] = "(name LIKE :q OR description LIKE :q OR category LIKE :q)";
    $params[':q'] = "%" . $search . "%";
}

// // apply where if needed
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

// // sort newest first
$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

function rupiah($price) {
    return "Rp " . number_format((float)$price, 0, ',', '.');
}

$title = "Home";
ob_start();
?>

<div class="container py-4">

  <div class="mb-4 p-4 bg-primary text-white rounded shadow-sm">
    <h2 class="mb-1">Product List</h2>
    <p class="mb-0 opacity-75">Filter products by category</p>
  </div>

  <!-- // filter + search info -->
  <?php if ($search !== ""): ?>
    <div class="alert alert-info py-2">
      Showing results for: <b><?= htmlspecialchars($search) ?></b>
    </div>
  <?php endif; ?>

  <form method="GET" class="mb-4 d-flex gap-2 flex-wrap">
    <select name="category" class="form-select w-auto">
      <option value="">All Categories</option>
      <?php foreach ($categories as $c): ?>
        <?php $cat = (string)$c['category']; ?>
        <option value="<?= htmlspecialchars($cat) ?>" <?= ($selectedCategory === $cat) ? 'selected' : ''; ?>>
          <?= htmlspecialchars($cat) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <!-- // keep search when changing category -->
    <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">

    <button class="btn btn-primary" type="submit">Filter</button>
    <a href="index.php" class="btn btn-secondary">Reset</a>
  </form>

  <div class="row g-3">
    <?php if (count($products) === 0): ?>
      <div class="col-12">
        <div class="alert alert-warning mb-0">No products found.</div>
      </div>
    <?php endif; ?>

    <?php foreach ($products as $p): ?>
      <?php
        $img = trim((string)$p['image']);
        $imgSrc = $img !== ""
          ? "/bootcamp-eduwork-6/Session9/uploaded_files/" . $img
          : "https://via.placeholder.com/400x250?text=No+Image";
      ?>
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card h-100 shadow-sm">
          <img
            src="<?= htmlspecialchars($imgSrc) ?>"
            class="card-img-top bg-light"
            alt="<?= htmlspecialchars((string)$p['name']) ?>"
            style="height:180px;object-fit:contain;padding:12px;"
            onerror="this.onerror=null;this.src='https://via.placeholder.com/400x250?text=No+Image';"
          >

          <div class="card-body d-flex flex-column">
            <h5 class="card-title mb-1"><?= htmlspecialchars((string)$p['name']) ?></h5>
            <span class="badge bg-dark mb-2"><?= htmlspecialchars((string)$p['category']) ?></span>

            <p class="card-text small text-muted mb-3" style="min-height:60px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:3;line-clamp:3;-webkit-box-orient:vertical;">
              <?= htmlspecialchars((string)$p['description']) ?>
            </p>

            <div class="mt-auto">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <strong><?= rupiah($p['price']) ?></strong>
                <small class="text-muted">#<?= (int)$p['id'] ?></small>
              </div>

              <!-- // detail button -->
              <a href="/bootcamp-eduwork-6/Session9/user/product_detail.php?id=<?= (int)$p['id'] ?>" class="btn btn-outline-primary w-100">
                Detail
              </a>
            </div>

          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

</div>

<?php
$content = ob_get_clean();
include __DIR__ . "/template/main.php";
?>

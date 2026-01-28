<?php
// load database connection
include __DIR__ . '/../../config/connection_pdo.php';

// page title for template
$title = "Admin Product List";

// get product data from database
$stmt = $pdo->query("SELECT id, name, category, price, image FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// start capturing page content
ob_start();
?>

<!-- show success message after action -->
<?php if (isset($_GET['message'])): ?>
  <div class="alert alert-success">
    <?= htmlspecialchars($_GET['message']) ?>
  </div>
<?php endif; ?>

<h1>Product List</h1>

<div class="mb-3">
  <!-- button to add new product -->
  <a href="input_page.php" class="btn btn-primary">Add Product</a>
</div>

<table id="productTable" class="table table-bordered table-striped align-middle">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Category</th>
      <th>Price</th>
      <th>Image</th>
      <th>Actions</th>
    </tr>
  </thead>

  <tbody>
    <?php foreach ($products as $product): ?>
      <?php
        // build image path from uploaded_files folder
        $img = $product['image']
          ? "/bootcamp-eduwork-6/Session9/uploaded_files/" . $product['image']
          : "https://via.placeholder.com/80x80?text=No+Image";
      ?>
      <tr>
        <!-- product id -->
        <td><?= $product['id'] ?></td>

        <!-- product name -->
        <td><?= htmlspecialchars($product['name']) ?></td>

        <!-- product category -->
        <td><?= htmlspecialchars($product['category']) ?></td>

        <!-- product price -->
        <td>Rp <?= number_format((float)$product['price'], 0, ',', '.') ?></td>

        <!-- product image preview -->
        <td>
          <img
            src="<?= htmlspecialchars($img) ?>"
            style="width:80px;height:80px;object-fit:cover;border-radius:6px"
            onerror="this.onerror=null;this.src='https://via.placeholder.com/80x80?text=No+Image';"
          >
        </td>

        <!-- action buttons -->
        <td>
          <a href="edit_page.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
          <a
            href="./process/delete_process.php?id=<?= $product['id'] ?>"
            class="btn btn-sm btn-danger"
            onclick="return confirm('Delete this product?');"
          >
            Delete
          </a>  
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php
// stop capturing page content
$content = ob_get_clean();

// load main layout template
require __DIR__ . "/../../template/main.php";
?>

<!-- jquery for datatables -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- datatables javascript -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<!-- datatables css -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<script>
// initialize datatable
$(document).ready(function () {
    $('#productTable').DataTable({
        pageLength: 10,
        lengthMenu: [[5,10,25,50,-1],[5,10,25,50,"All"]]
    });
});
</script>

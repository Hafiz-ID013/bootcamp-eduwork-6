<?php
// proses.php

// Biar kalau error nggak white screen diam-diam:
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: index.php");
  exit;
}

function e($str) {
  return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

// Ambil input
$name     = trim($_POST["productName"] ?? "");
$category = trim($_POST["productCategory"] ?? "");
$price    = $_POST["productPrice"] ?? "";
$stock    = $_POST["productStock"] ?? "";

// Validasi sederhana (tugas validasi)
$errors = [];
if ($name === "") $errors[] = "Nama produk wajib diisi.";
if ($category === "") $errors[] = "Kategori produk wajib diisi.";
if ($price === "" || !is_numeric($price) || (int)$price < 0) $errors[] = "Harga harus angka >= 0.";
if ($stock === "" || !is_numeric($stock) || (int)$stock < 0) $errors[] = "Stok harus angka >= 0.";

// Proses upload gambar
$imagePath = "";
if (!isset($_FILES["productImage"])) {
  $errors[] = "Gambar produk wajib diupload.";
} else {
  $file = $_FILES["productImage"];

  if ($file["error"] !== UPLOAD_ERR_OK) {
    $errors[] = "Upload gambar gagal. Coba pilih file gambar lagi.";
  } else {
    // Validasi ukuran (2MB)
    if ($file["size"] > 2 * 1024 * 1024) {
      $errors[] = "Ukuran gambar terlalu besar (maks 2MB).";
    }

    // Validasi ekstensi
    $allowedExt = ["jpg", "jpeg", "png", "webp"];
    $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt, true)) {
      $errors[] = "Format gambar harus JPG/PNG/WEBP.";
    }

    // Kalau lolos validasi, simpan ke folder uploads/
    if (!$errors) {
      $uploadDir = __DIR__ . "/uploads";
      if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
      }

      $safeName = "produk_" . uniqid() . "." . $ext;
      $dest = $uploadDir . "/" . $safeName;

      if (!move_uploaded_file($file["tmp_name"], $dest)) {
        $errors[] = "Gagal menyimpan file upload ke folder uploads.";
      } else {
        // Path untuk ditampilkan di browser
        $imagePath = "uploads/" . $safeName;
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hasil Input Produk</title>
  <link rel="stylesheet" href="../bootstrap-5.3.8-dist/css/bootstrap.min.css" />
</head>
<body class="bg-light">

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-9">

        <?php if ($errors): ?>
          <div class="alert alert-danger shadow-sm">
            <div class="fw-bold mb-2">Data belum bisa diproses:</div>
            <ul class="mb-3">
              <?php foreach ($errors as $err): ?>
                <li><?= e($err) ?></li>
              <?php endforeach; ?>
            </ul>
            <a href="index.php" class="btn btn-outline-dark">Kembali ke Form</a>
          </div>

        <?php else: ?>
          <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
              <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <h1 class="h4 fw-bold m-0">Hasil Input Produk</h1>
                <span class="badge text-bg-success">Berhasil</span>
              </div>

              <div class="row g-4">
                <div class="col-md-7">
                  <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                      <tbody>
                        <tr>
                          <th style="width: 35%">Nama Produk</th>
                          <td><?= e($name) ?></td>
                        </tr>
                        <tr>
                          <th>Kategori</th>
                          <td><?= e($category) ?></td>
                        </tr>
                        <tr>
                          <th>Harga</th>
                          <td>Rp <?= number_format((int)$price, 0, ",", ".") ?></td>
                        </tr>
                        <tr>
                          <th>Stok</th>
                          <td><?= (int)$stock ?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <div class="d-flex gap-2">
                    <a href="index.php" class="btn btn-outline-secondary">Back ke Form</a>
                    <a href="proses.php" class="btn btn-outline-danger">Refresh (tanpa data)</a>
                  </div>
                </div>

                <div class="col-md-5">
                  <div class="border rounded-3 bg-white p-3 text-center">
                    <div class="fw-semibold mb-2">Preview Gambar</div>
                    <?php if ($imagePath): ?>
                      <img src="<?= e($imagePath) ?>" alt="Gambar Produk" class="img-fluid rounded-3">
                    <?php else: ?>
                      <div class="text-secondary">Tidak ada gambar.</div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>

            </div>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>

  <script src="../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

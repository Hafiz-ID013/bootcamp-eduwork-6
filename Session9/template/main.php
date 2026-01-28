<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Form Input Produk</title>

  <!-- Bootstrap (lokal) -->
  <link rel="stylesheet" href="/bootcamp-eduwork-6/bootstrap-5.3.8-dist/css/bootstrap.min.css">

</head>
<body>

<?php include __DIR__ . '/partials/navbar.php'; ?>

<div class="container mt-4">
    <?php
    echo $content ?? '';
    ?>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="/bootcamp-eduwork-6/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

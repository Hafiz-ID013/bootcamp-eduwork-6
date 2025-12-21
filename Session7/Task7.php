<?php
// index.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Form Input Produk</title>

  <!-- Bootstrap (lokal) -->
  <link rel="stylesheet" href="../bootstrap-5.3.8-dist/css/bootstrap.min.css" />
</head>
<body class="bg-light">

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">

        <div class="card shadow-sm border-0">
          <div class="card-body p-4 p-md-5">
            <h1 class="h4 fw-bold mb-1">Form Input Product Data</h1>
            <p class="text-secondary mb-4">Fill the Product Data and then <b>Save</b>.</p>

            <!-- PENTING: enctype untuk upload file -->
            <form action="process.php" method="POST" enctype="multipart/form-data">
              <div class="row g-3">

                <div class="col-md-6">
                  <label class="form-label">Product Name</label>
                  <input type="text" class="form-control" id="name" name="name" placeholder="Example: Hoodie" required>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Product Price</label>
                  <input type="number" class="form-control" id="price" name="price" placeholder="Example: 200000" min="0" required>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Product Description</label>
                  <input type="text" class="form-control" id="description" name="description" placeholder="Example: Black Hoodie" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Product Category</label>                  
                  <select class="form-select" id="category" name="category" required>
                    <option value="" selected disabled>Choos Category</option>
                    <option value="Electronic">Electronic</option>
                    <option value="Clothes">Clothes</option>
                    <option value="Food">Food</option>
                    <option value="Drink">Drink</option>
                    <option value="Others">Others</option>
                  </select>
                </div>

                <div class="col-12">
                  <label class="form-label">Upload Product Image</label>
                  <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                  <div class="form-text">Allowed formats: JPG/PNG/WEBP. Max 2MB.</div>
                </div>

                <div class="col-12 d-flex gap-2 mt-2">
                  <button type="submit" class="btn btn-primary px-4">Save</button>
                  <button type="reset" class="btn btn-outline-secondary px-4">Reset</button>
                </div>

              </div>
            </form>

          </div>
        </div>

      </div>
    </div>
  </div>

  <script>
    //Form validation
    let form = document.querySelectorAll('form');
    Array.prototype.slice.call(forms).forEach(function(form) {
      form.addEventListener('submit', function(event) {
        let name = document.getElementById('name').value;
        let price = document.getElementById('price').value;
        let description = document.getElementById('description').value;
        let category = document.getElementById('category').value;
        let image = document.getElementById('image').value;
        if (!name || !price || !description || !category || !image) {
          event.preventDefault();
          event.stopPropagation();
          alert('All fields are required.');
        } 
      }, false);
    });
  </script>
  <script src="../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<nav class="navbar navbar-expand-lg navbar-light bg-body-tertiary">
  <div class="container-fluid">

    <a class="navbar-brand" href="/bootcamp-eduwork-6/Session9/index.php">Navbar</a>

    <button class="navbar-toggler" type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent"
      aria-expanded="false"
      aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">

      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) === 'index.php' && strpos($_SERVER['REQUEST_URI'],'/Session9/index.php') !== false) ? 'active' : '' ?>"
             href="/bootcamp-eduwork-6/Session9/index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link"
             href="/bootcamp-eduwork-6/Session9/admin/product/index.php">Admin</a>
        </li>
        <li class="nav-item">
          <a class="nav-link"
             href="/bootcamp-eduwork-6/Session9/user/cart.php">My Cart</a>
        </li>
      </ul>

      <!-- search: keep current category filter when searching -->
      <form class="d-flex" role="search" method="GET" action="/bootcamp-eduwork-6/Session9/index.php">
        <?php if (isset($_GET['category']) && $_GET['category'] !== ''): ?>
          <input type="hidden" name="category" value="<?= htmlspecialchars($_GET['category']) ?>">
        <?php endif; ?>

        <input
          class="form-control me-2"
          type="search"
          name="search"
          placeholder="Search products..."
          value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
        >
        <button class="btn btn-outline-success" type="submit">Search</button>

        <?php if (!empty($_GET['search'])): ?>
          <a class="btn btn-outline-secondary ms-2" href="/bootcamp-eduwork-6/Session9/index.php">Reset</a>
        <?php endif; ?>
      </form>

    </div>
  </div>
</nav>

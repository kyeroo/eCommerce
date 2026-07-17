<?php
require_once __DIR__ . "/session.php";

$folder = basename(dirname($_SERVER["PHP_SELF"] ?? ""));
$prefix = in_array($folder, ["admin", "auth", "customer"], true) ? "../" : "";
$user = current_user();
$current = basename($_SERVER["PHP_SELF"] ?? "index.php");

function active_nav($file, $current)
{
    return $file === $current ? " active" : "";
}
?>
<header class="glass-navbar">
  <div class="container">
    <nav class="navbar navbar-expand-lg custom_nav-container liquid-nav">
      <a class="navbar-brand brand-glass" href="<?= $prefix ?>index.php">
        <img
          width="170"
          src="<?= $prefix ?>images/logo.png"
          alt="Famms Store"
        />
      </a>
      <button
        class="navbar-toggler glass-toggle"
        type="button"
        data-toggle="collapse"
        data-target="#navbarSupportedContent"
        aria-label="Toggle navigation"
      >
        <span></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto align-items-lg-center">
          <li class="nav-item">
            <a
              class="nav-link<?= active_nav("index.php", $current) ?>"
              href="<?= $prefix ?>index.php"
              >Beranda</a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link<?= active_nav("products.php", $current) ?>"
              href="<?= $prefix ?>products.php"
              >Katalog</a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link<?= active_nav("cart.php", $current) ?>"
              href="<?= $prefix ?>cart.php"
            >
              Tas Belanja
              <span id="cartCount" class="badge badge-danger">0</span>
            </a>
          </li>
          <?php if (is_logged_in()): ?>
          <li class="nav-item">
            <a
              class="nav-link<?= active_nav("wishlist.php", $current) ?>"
              href="<?= $prefix ?>wishlist.php"
              >Favorit</a
            >
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $prefix ?>customer/orders.php"
              >Transaksi</a
            >
          </li>
          <?php if (($user["role"] ?? "") === "admin"): ?>
          <li class="nav-item">
            <a
              class="nav-link nav-admin"
              href="<?= $prefix ?>admin/dashboard.php"
              >Panel Admin</a
            >
          </li>
          <?php endif; ?>
          <li class="nav-item dropdown">
            <a
              class="nav-link dropdown-toggle"
              href="#"
              id="accountMenu"
              role="button"
              data-toggle="dropdown"
            >
              <?= htmlspecialchars($user["name"] ?? "Akun") ?>
            </a>
            <div
              class="dropdown-menu dropdown-menu-right glass-card border-0 p-2"
              aria-labelledby="accountMenu"
            >
              <a class="dropdown-item rounded" href="<?= $prefix ?>profile.php"
                >Pengaturan Profil</a
              >
              <a
                class="dropdown-item rounded"
                href="<?= $prefix ?>customer/orders.php"
                >Catatan Transaksi</a
              >
              <div class="dropdown-divider"></div>
              <a
                class="dropdown-item rounded text-danger"
                href="<?= $prefix ?>auth/logout.php"
                >Keluar</a
              >
            </div>
          </li>
          <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= $prefix ?>auth/login.php">Masuk</a>
          </li>
          <li class="nav-item">
            <a
              class="btn btn-glass-small"
              href="<?= $prefix ?>auth/register.php"
              >Buat Akun</a
            >
          </li>
          <?php endif; ?>
        </ul>
      </div>
    </nav>
  </div>
</header>

<script>
const isLoggedIn = <?= isset($_SESSION['user']) ? 'true' : 'false' ?>;
</script>
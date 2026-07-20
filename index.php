<?php
require_once "config/database.php";
require_once "includes/session.php";

/** @var PDO $pdo */
if (!isset($pdo) || !($pdo instanceof PDO)) {
    exit("Database connection tidak tersedia.");
}

$featured = $pdo
    ->query(
        "SELECT p.*, c.name AS category_name,
COALESCE(AVG(r.rating),0) rating, COUNT(r.id) review_count " .
            "FROM products p
JOIN categories c ON c.id=p.category_id " .
            "LEFT JOIN product_reviews r ON
r.product_id=p.id " .
            "WHERE p.is_featured=1 GROUP BY p.id ORDER BY RAND()
DESC LIMIT 8",
    )
    ->fetchAll();
$categories = $pdo
    ->query(
        "SELECT c.*,
COUNT(p.id) AS product_count FROM categories c " .
            "LEFT JOIN products p ON
p.category_id=c.id GROUP BY c.id ORDER BY c.name LIMIT 6",
    )
    ->fetchAll();
$stats = [
    "products" => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
    "categories" => $pdo
        ->query("SELECT COUNT(*) FROM categories")
        ->fetchColumn(),
    "orders" => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
    "reviews" => $pdo
        ->query("SELECT COUNT(*) FROM product_reviews")
        ->fetchColumn(),
];
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Famms Store | Fashion Online Profesional</title>
    <link rel="stylesheet" href="css/bootstrap.css" />
    <link rel="stylesheet" href="css/font-awesome.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/complex.css" />
    <link rel="stylesheet" href="css/liquid-glass.css" />
    <link rel="stylesheet" href="css/professional.css" />
  </head>
  <body>
    <?php include "includes/header.php"; ?>
    <section class="liquid-hero">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-7">
            <div class="glass-panel">
              <div class="chip">Famms Fashion Store</div>
              <h1 class="hero-title">
                Koleksi Fashion Modern untuk Gaya Harian Anda.
              </h1>
              <p class="hero-text">
                Temukan pakaian, aksesori, sepatu, dan outerwear pilihan dengan
                tampilan katalog yang rapi, proses belanja yang mudah, serta
                pengalaman checkout yang nyaman di semua perangkat.
              </p>
              <div class="hero-actions mt-4">
                <a class="btn-liquid" href="products.php">Belanja Sekarang</a>
                <a class="btn-soft" href="products.php?sort=rating"
                  >Lihat Koleksi Favorit</a
                >
              </div>
            </div>
          </div>
          <div class="col-lg-5 mt-4 mt-lg-0">
            <div class="floating-card">
              <img
                src="images/hero-fashion.png"
                alt="Tampilan toko digital profesional"
              />
              <div class="row mt-3 text-center">
                <div class="col-4">
                  <b><?= $stats["products"] ?></b>
                  <br />
                  <small>Produk</small>
                </div>
                <div class="col-4">
                  <b><?= $stats["orders"] ?></b>
                  <br />
                  <small>Transaksi</small>
                </div>
                <div class="col-4">
                  <b><?= $stats["reviews"] ?></b>
                  <br />
                  <small>Ulasan</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="trust-strip">
      <div class="container">
        <div class="row">
          <div class="col-md-4 mb-3">
            <div class="trust-item">
              <div class="trust-icon">01</div>
              <div>
                <b>Produk Siap Dipilih</b>
                <br />
                <small
                  >Koleksi tersusun rapi berdasarkan kategori dan stok
                  terbaru.</small
                >
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="trust-item">
              <div class="trust-icon">02</div>
              <div>
                <b>Transaksi Lebih Praktis</b>
                <br />
                <small
                  >Tas belanja, pembayaran, dan pengiriman dibuat dalam alur
                  yang jelas.</small
                >
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="trust-item">
              <div class="trust-icon">03</div>
              <div>
                <b>Pengelolaan Toko Rapi</b>
                <br />
                <small
                  >Admin dapat memperbarui katalog, promo, dan pesanan dengan
                  mudah.</small
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="section-pad">
      <div class="container">
        <div
          class="d-flex justify-content-between align-items-end flex-wrap mb-4"
        >
          <div>
            <span class="section-kicker">Kategori Pilihan</span>
            <h2 class="section-title mb-1">Temukan kategori fashion pilihan</h2>
            <p class="section-subtitle mb-0">
              Pilih kategori sesuai kebutuhan: pakaian pria, pakaian wanita,
              aksesori, sepatu, dan outerwear.
            </p>
          </div>
          <a class="btn-soft mt-3" href="products.php">Lihat Semua</a>
        </div>
        <div class="row">
          <?php foreach ($categories as $c): ?>
          <div class="col-sm-6 col-lg-4 mb-3">
            <a
              class="category-tile"
              href="products.php?category=<?= (int) $c["id"] ?>"
            >
              <h5><?= htmlspecialchars($c["name"]) ?></h5>
              <small
                ><?= (int) $c["product_count"] ?>
                pilihan tersedia</small
              >
            </a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <section class="section-pad pt-0">
      <div class="container">
        <div
          class="d-flex justify-content-between align-items-end flex-wrap mb-4"
        >
          <div>
            <span class="section-kicker">Koleksi Unggulan</span>
            <h2 class="section-title mb-1">
              Rekomendasi produk untuk tampilan terbaik
            </h2>
            <p class="section-subtitle mb-0">
              Setiap produk dilengkapi harga, kategori, stok, ulasan, dan tombol
              pembelian yang mudah digunakan.
            </p>
          </div>
          <a class="btn-soft mt-3" href="products.php?sort=newest"
            >Jelajahi Katalog</a
          >
        </div>
        <div class="row">
          <?php foreach ($featured as $p): ?>
          <div class="col-sm-6 col-lg-3 mb-4">
            <div class="product-card">
              <a href="product_detail.php?id=<?= (int) $p["id"] ?>">
                <img
                  src="<?= htmlspecialchars($p["image"]) ?>"
                  alt="<?= htmlspecialchars($p["name"]) ?>"
                />
              </a>
              <small
                ><?= htmlspecialchars($p["category_name"]) ?>
                · ★ <?= number_format((float) $p["rating"], 1) ?> (<?= (int) $p[
     "review_count"
 ] ?>)</small
              >
              <h5><?= htmlspecialchars($p["name"]) ?></h5>
              <p class="price">
                Rp <?= number_format($p["price"], 0, ",", ".") ?>
              </p>
              <div class="quick-actions">
                <button
                  class="btn btn-sm btn-primary rounded-pill"
                  data-product='<?= htmlspecialchars(
                      json_encode([
                          "id" => (int) $p["id"],
                          "name" => $p["name"],
                          "price" => (float) $p["price"],
                          "image" => $p["image"],
                      ]),
                      ENT_QUOTES,
                  ) ?>'
                  onclick="addToCart(JSON.parse(this.dataset.product))"
                >
                  Masukkan
                </button>
                <a
                  class="btn btn-sm btn-outline-dark rounded-pill"
                  href="product_detail.php?id=<?= (int) $p["id"] ?>"
                  >Lihat Detail</a
                >
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <section class="section-pad pt-0">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6 mb-4">
            <div class="glass-panel">
              <span class="section-kicker">Keunggulan Layanan</span>
              <h2 class="section-title">
                Belanja fashion lebih cepat, nyaman, dan tertata.
              </h2>
              <p class="section-subtitle">
                Famms Store menghadirkan katalog fashion modern dengan desain
                premium, navigasi sederhana, informasi produk yang jelas, dan
                proses transaksi yang lebih efisien.
              </p>
              <div class="feature-list">
                <div class="feature-row">
                  <div class="feature-dot">✓</div>
                  <div>
                    <b>Pencarian produk lebih mudah</b>
                    <br />
                    <span class="professional-note"
                      >Pelanggan dapat menelusuri kategori, menyimpan produk
                      favorit, memasukkan barang ke tas belanja, dan
                      menyelesaikan pembelian secara bertahap.</span
                    >
                  </div>
                </div>
                <div class="feature-row">
                  <div class="feature-dot">✓</div>
                  <div>
                    <b>Katalog selalu dapat diperbarui</b>
                    <br />
                    <span class="professional-note"
                      >Admin dapat mengubah data produk, kategori, promo, stok,
                      dan status pesanan melalui panel pengelolaan yang
                      rapi.</span
                    >
                  </div>
                </div>
                <div class="feature-row">
                  <div class="feature-dot">✓</div>
                  <div>
                    <b>Riwayat pesanan lebih jelas</b>
                    <br />
                    <span class="professional-note"
                      >Data pelanggan, pesanan, pembayaran, pengiriman, ulasan,
                      dan favorit saling terhubung sehingga riwayat belanja
                      mudah dipantau.</span
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="row">
              <div class="col-sm-6 mb-3">
                <div class="glass-card metric">
                  <h3><?= $stats["products"] ?></h3>
                  <p>Produk Tersedia</p>
                </div>
              </div>
              <div class="col-sm-6 mb-3">
                <div class="glass-card metric">
                  <h3><?= $stats["categories"] ?></h3>
                  <p>Kategori Pilihan</p>
                </div>
              </div>
              <div class="col-sm-6 mb-3">
                <div class="glass-card metric">
                  <h3><?= $stats["orders"] ?></h3>
                  <p>Transaksi</p>
                </div>
              </div>
              <div class="col-sm-6 mb-3">
                <div class="glass-card metric">
                  <h3><?= $stats["reviews"] ?></h3>
                  <p>Ulasan</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php if (!is_logged_in()) { ?>
    <section class="section-pad pt-0">
      <div class="container">
        <div class="newsletter-box">
          <div class="row align-items-center">
            <div class="col-lg-8">
              <h3 class="font-weight-bold mb-2">
                Lengkapi gaya harian dengan koleksi pilihan Famms.
              </h3>
              <p class="mb-lg-0">
                Jelajahi katalog fashion, pilih produk favorit, dan selesaikan
                pembelian dengan tampilan yang bersih, modern, serta responsif.
              </p>
            </div>
            <div class="col-lg-4 text-lg-right">
              <a
                class="btn btn-light rounded-pill px-4 font-weight-bold"
                href="auth/login.php"
                >Masuk dan Belanja</a
              >
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php }?>
    <?php include "includes/footer.php"; ?>
  </body>
</html>

<?php
require_once "config/database.php";
require_once "includes/session.php";
$id = (int) ($_GET["id"] ?? 0);
$stmt = $pdo->prepare("SELECT p.*, c.name category_name, COALESCE(AVG(r.rating),0)
rating, COUNT(r.id) review_count FROM products p JOIN categories c ON
c.id=p.category_id LEFT JOIN product_reviews r ON r.product_id=p.id WHERE p.id=?
GROUP BY p.id");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) {
    http_response_code(404);
    die("Produk tidak ditemukan.");
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && is_logged_in()) {
    $rating = max(1, min(5, (int) $_POST["rating"]));
    $comment = trim($_POST["comment"]);
    $ins = $pdo->prepare('INSERT INTO
product_reviews(product_id,user_id,rating,comment) VALUES(?,?,?,?)');
    $ins->execute([$id, current_user()["id"], $rating, $comment]);
    header(
        'Location:
product_detail.php?id=' . $id,
    );
    exit();
}
$reviews = $pdo->prepare('SELECT r.*, u.name
FROM product_reviews r JOIN users u ON u.id=r.user_id WHERE r.product_id=? ORDER
BY r.created_at DESC');
$reviews->execute([$id]);
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title><?= htmlspecialchars($p["name"]) ?></title>
    <link rel="stylesheet" href="css/bootstrap.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/complex.css" />
    <link rel="stylesheet" href="css/liquid-glass.css" />
    <link rel="stylesheet" href="css/professional.css" />
  </head>
  <body class="sub_page">
    <?php include "includes/header.php"; ?>
    <section class="section-pad">
      <div class="container">
        <div class="glass-panel">
          <div class="row align-items-center">
            <div class="col-lg-5">
              <img
                class="img-fluid"
                style="max-height: 430px; object-fit: contain; width: 100%"
                src="<?= htmlspecialchars($p["image"]) ?>
"
                alt=""
              />
            </div>
            <div class="col-lg-7">
              <div class="chip">
                <?= htmlspecialchars(
                    $p["category_name"],
                ) ?> · ★ <?= number_format(
     (float) $p["rating"],
     1,
 ) ?> (<?= (int) $p["review_count"] ?> ulasan)
              </div>
              <h1 class="section-title"><?= htmlspecialchars($p["name"]) ?></h1>
              <p class="section-subtitle">
                <?= htmlspecialchars($p["description"]) ?>
              </p>
              <h3 class="price">
                Rp <?= number_format($p["price"], 0, ",", ".") ?>
              </h3>
              <p>Stok tersedia: <b> <?= (int) $p["stock"] ?> </b></p>
              <button
                class="btn-liquid"
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
                Tambah ke Keranjang
              </button>
              <a
                class="btn btn-outline-dark rounded-pill px-4"
                href="wishlist_action.php?id=<?= (int) $p["id"] ?>"
                >Simpan Wishlist</a
              >
            </div>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-lg-5 mb-3">
            <div class="glass-card p-4">
              <h4>Tulis Ulasan</h4>
              <?php if (is_logged_in()): ?>
              <form method="post">
                <label>Rating</label>
                <select name="rating" class="form-control mb-3">
                  <option>5</option>
                  <option>4</option>
                  <option>3</option>
                  <option>2</option>
                  <option>1</option>
                </select>
                <label>Komentar</label>
                <textarea
                  name="comment"
                  class="form-control mb-3"
                  required
                ></textarea>
                <button class="btn-liquid">Kirim Ulasan</button>
              </form>
              <?php else: ?>
              <p>Login untuk menulis ulasan.</p>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-lg-7">
            <div class="glass-card p-4">
              <h4>Ulasan Pembeli</h4>
              <?php foreach ($reviews as $r): ?>
              <div class="border-bottom py-2">
                <b> <?= htmlspecialchars($r["name"]) ?> </b> · ★ <?= (int) $r[
     "rating"
 ] ?>
                <p class="mb-0"><?= htmlspecialchars($r["comment"]) ?></p>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php include "includes/footer.php"; ?>
  </body>
</html>

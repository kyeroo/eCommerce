<?php
require_once "../config/database.php";
require_once "../includes/session.php";
require_admin();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["delete"])) {
        $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([
            (int) $_POST["id"],
        ]);
    } else {
        $slug =
            strtolower(
                trim(preg_replace("/[^a-z0-9]+/", "-", $_POST["name"]), "-"),
            ) .
            "-" .
            rand(10, 99);
        $stmt = $pdo->prepare('INSERT
INTO products(category_id,name,slug,description,price,stock,image,is_featured)
VALUES(?,?,?,?,?,?,?,?)');
        $stmt->execute([
            $_POST["category_id"],
            $_POST["name"],
            $slug,
            $_POST["description"],
            $_POST["price"],
            $_POST["stock"],
            $_POST["image"],
            isset($_POST["is_featured"]) ? 1 : 0,
        ]);
    }
    header('Location:products_manage.php');
    exit();
}
$cats = $pdo
    ->query(
        'SELECT * FROM
categories',
    )
    ->fetchAll();
$products = $pdo
    ->query(
        'SELECT p.*, c.name category
FROM products p JOIN categories c ON c.id = p.category_id ORDER BY p.id
DESC',
    )
    ->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kelola Produk</title>
    <link rel="stylesheet" href="../css/bootstrap.css" />
    <link rel="stylesheet" href="../css/liquid-glass.css" />
    <link rel="stylesheet" href="../css/professional.css" />
  </head>
  <body>
    <div class="admin-shell">
      <aside class="admin-sidebar glass-card">
        <h4>Admin</h4>
        <a href="dashboard.php">Dashboard</a>
        <a href="products_manage.php">Produk</a>
        <a href="categories_manage.php">Kategori</a>
        <a href="coupons_manage.php">Kupon</a>
        <a href="orders_manage.php">Pesanan</a>
      </aside>

      <main>
        <div class="glass-panel">
          <h2>Kelola Produk</h2>

          <form method="post">
            <div class="row">
              <div class="col-md-4 mb-2">
                <input
                  class="form-control"
                  name="name"
                  placeholder="Nama produk"
                  required
                />
              </div>
              <div class="col-md-3 mb-2">
                <select class="form-control" name="category_id">
                  <?php foreach ($cats as $c): ?>
                  <option value="<?= $c["id"] ?>">
                    <?= htmlspecialchars($c["name"]) ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-2 mb-2">
                <input
                  class="form-control"
                  name="price"
                  type="number"
                  placeholder="Harga"
                  required
                />
              </div>
              <div class="col-md-2 mb-2">
                <input
                  class="form-control"
                  name="stock"
                  type="number"
                  placeholder="Stok"
                  required
                />
              </div>
            </div>

            <textarea
              class="form-control mt-2"
              name="description"
              placeholder="Deskripsi"
            ></textarea>
            <input
              class="form-control mt-2"
              name="image"
              value="images/p1.png"
              placeholder="Path gambar"
            />
            <label class="mt-2">
              <input type="checkbox" name="is_featured" /> Featured
            </label>
            <br />
            <button class="btn-liquid">Tambah Produk</button>
          </form>
        </div>

        <div class="glass-card p-4 mt-4">
          <div class="table-responsive">
            <table class="table">
              <tr>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
              </tr>
              <?php foreach ($products as $p): ?>
              <tr>
                <td><?= htmlspecialchars($p["name"]) ?></td>
                <td><?= htmlspecialchars($p["category"]) ?></td>
                <td>Rp <?= number_format($p["price"], 0, ",", ".") ?></td>
                <td><?= $p["stock"] ?></td>
                <td>
                  <form
                    method="post"
                    onsubmit="return confirm('Hapus produk?');"
                  >
                    <input type="hidden" name="id" value="<?= $p["id"] ?>" />
                    <button name="delete" class="btn btn-sm btn-outline-danger">
                      Hapus
                    </button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
            </table>
          </div>
        </div>
      </main>
    </div>
  </body>
</html>

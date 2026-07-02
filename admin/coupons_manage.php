<?php
require_once "../config/database.php";
require_once "../includes/session.php";
require_admin();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["toggle"])) {
        $pdo->prepare('UPDATE coupons SET is_active=1-is_active WHERE id=?',)->execute([(int) $_POST["id"]]);
    } else {
        $pdo->prepare('INSERT INTO coupons(code,description,discount_type,discount_value,is_active) VALUES(?,?,?,?,1)',)->execute([
            strtoupper($_POST["code"]),
            $_POST["description"],
            $_POST["discount_type"],
            $_POST["discount_value"],
        ]);
    }
    header("Location: coupons_manage.php");
    exit();
}
$items = $pdo
    ->query('SELECT * FROM coupons ORDER BY created_at DESC',)->fetchAll();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Kupon</title>
    <link rel="stylesheet" href="../css/bootstrap.css" />
    <link rel="stylesheet" href="../css/liquid-glass.css" />
    <link rel="stylesheet" href="../css/professional.css" />
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
          <h2>Kelola Kupon</h2>
          <form method="post" class="row">
            <div class="col-md-2">
              <input
                class="form-control"
                name="code"
                placeholder="Kode"
                required
              />
            </div>
            <div class="col-md-4">
              <input
                class="form-control"
                name="description"
                placeholder="Deskripsi"
              />
            </div>
            <div class="col-md-2">
              <select class="form-control" name="discount_type">
                <option value="percent">Persen</option>
                <option value="fixed">Nominal</option>
              </select>
            </div>
            <div class="col-md-2">
              <input
                class="form-control"
                type="number"
                name="discount_value"
                placeholder="Nilai"
                required
              />
            </div>
            <div class="col-md-2">
              <button class="btn-liquid w-100">Tambah</button>
            </div>
          </form>
        </div>
        <div class="glass-card p-4 mt-4">
          <table class="table">
            <tr>
              <th>Kode</th>
              <th>Jenis</th>
              <th>Nilai</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
            <?php foreach ($items as $i): ?>
            <tr>
              <td><?= htmlspecialchars($i["code"]) ?></td>
              <td><?= $i["discount_type"] ?></td>
              <td><?= number_format($i["discount_value"], 0, ",", ".") ?></td>
              <td><?= $i["is_active"] ? "Aktif" : "Nonaktif" ?></td>
              <td>
                <form method="post">
                  <input
                    type="hidden"
                    name="id"
                    value="<?= $i["id"] ?>"
                  />
                  <button name="toggle" class="btn btn-sm btn-outline-dark">
                    Toggle
                  </button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
          </table>
        </div>
      </main>
    </div>
  </body>
</html>

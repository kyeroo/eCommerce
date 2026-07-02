<?php
require_once "../config/database.php";
require_once "../includes/session.php";
require_admin();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["delete"])) {
        $pdo->prepare('DELETE FROM categories WHERE id=?',)->execute([(int) $_POST["id"]]);
    } else {
        $pdo->prepare(
            'INSERT INTO categories(name,description) VALUES(?,?)',)->execute([$_POST["name"], $_POST["description"]]);
    }
    header("Location: categories_manage.php");
    exit();
}
$items = $pdo
    ->query(
        'SELECT c.*, COUNT(p.id) total_products FROM categories c LEFT JOIN products p ON p.category_id=c.id GROUP BY c.id ORDER BY c.name',)
    ->fetchAll();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Kategori</title>
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
          <h2>Kelola Kategori</h2>
          <form method="post" class="row">
            <div class="col-md-4">
              <input
                class="form-control"
                name="name"
                placeholder="Nama kategori"
                required
              />
            </div>
            <div class="col-md-6">
              <input
                class="form-control"
                name="description"
                placeholder="Deskripsi"
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
              <th>Kategori</th>
              <th>Deskripsi</th>
              <th>Produk</th>
              <th>Aksi</th>
            </tr>
            <?php foreach ($items as $i): ?>
            <tr>
              <td><?= htmlspecialchars($i["name"]) ?></td>
              <td><?= htmlspecialchars($i["description"]) ?></td>
              <td><?= (int) $i["total_products"] ?></td>
              <td>
                <form
                  method="post"
                  onsubmit="return confirm('Hapus kategori?');"
                >
                  <input
                    type="hidden"
                    name="id"
                    value="<?= $i["id"] ?>
"
                  />
                  <button name="delete" class="btn btn-sm btn-outline-danger">
                    Hapus
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

<?php
require_once "config/database.php";
require_once "includes/session.php";
require_login();
$u = current_user();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pdo->prepare(
        'UPDATE users SET name=?, phone=?, address=? WHERE
id=?',
    )->execute([$_POST["name"], $_POST["phone"], $_POST["address"], $u["id"]]);
    $_SESSION["user"]["name"] = $_POST["name"];
    $_SESSION["user"]["phone"] = $_POST["phone"];
    $_SESSION["user"]["address"] = $_POST["address"];
    header('Location:
profile.php?saved=1');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Profil</title>
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
          <h2 class="section-title">Profil Pelanggan</h2>
          <?php if (isset($_GET["saved"])): ?>
          <div class="alert alert-success">Profil berhasil diperbarui.</div>
          <?php endif; ?>
          <form method="post">
            <div class="form-group">
              <label>Nama</label>
              <input
                class="form-control"
                name="name"
                value="<?= htmlspecialchars($u["name"]) ?>
"
                required
              />
            </div>
            <div class="form-group">
              <label>Email</label>
              <input
                class="form-control"
                value="<?= htmlspecialchars($u["email"]) ?>
"
                disabled
              />
            </div>
            <div class="form-group">
              <label>No. HP</label>
              <input
                class="form-control"
                name="phone"
                value="<?= htmlspecialchars($u["phone"] ?? "") ?>
"
              />
            </div>
            <div class="form-group">
              <label>Alamat Utama</label>
              <textarea class="form-control" name="address">
<?= htmlspecialchars($u["address"] ?? "") ?>
</textarea>
            </div>
            <button class="btn-liquid">Simpan Profil</button>
          </form>
        </div>
      </div>
    </section>
    <?php include "includes/footer.php"; ?>
  </body>
</html>

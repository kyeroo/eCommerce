<?php
require_once "../config/database.php";
require_once "../includes/session.php";
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute([$_POST["email"]]);
    $user = $stmt->fetch();
    if ($user && password_verify($_POST["password"], $user["password"])) {
        $_SESSION["user"] = $user;

        if (($user["role"] ?? "") === "admin") {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../index.php");
        }

        exit();
    }
    $error = 'Email
atau password salah.';
}
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Login</title>
    <link rel="stylesheet" href="../css/bootstrap.css" />
    <link rel="stylesheet" href="../css/complex.css" />
    <link rel="stylesheet" href="../css/liquid-glass.css" />
    <link rel="stylesheet" href="../css/professional.css" />
    <link rel="stylesheet" href="../css/professional.css" />
  </head>
  <body class="sub_page">
    <?php include "../includes/header.php"; ?>
    <section class="section-pad">
      <div class="container" style="max-width: 560px">
        <div class="glass-panel">
          <h3 class="section-title">Login</h3>
          <?php if ($error): ?>
          <div class="alert alert-danger"><?= $error ?></div>
          <?php endif; ?>
          <form method="post">
            <div class="form-group">
              <label>Email</label>
              <input class="form-control" name="email" type="email" required />
            </div>
            <div class="form-group">
              <label>Password</label>
              <input
                class="form-control"
                name="password"
                type="password"
                required
              />
            </div>
            <button class="btn-liquid w-100">Login</button>
            <p class="mt-3">
              Belum punya akun? <a href="register.php">Daftar</a>
            </p>
            <small>Demo admin: admin@famms.test / password</small>
          </form>
        </div>
      </div>
    </section>
    <?php include "../includes/footer.php"; ?>
  </body>
</html>

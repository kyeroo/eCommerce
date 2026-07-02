<?php
require_once "../config/database.php";
require_once "../includes/session.php";
require_login();
$u = current_user();
$stmt = $pdo->prepare('SELECT o.*, p.status payment_status, s.status
shipment_status FROM orders o LEFT JOIN payments p ON p.order_id=o.id LEFT JOIN
shipments s ON s.order_id=o.id WHERE o.user_id=? ORDER BY o.created_at DESC');
$stmt->execute([$u["id"]]);
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Pesanan Saya</title>
    <link rel="stylesheet" href="../css/bootstrap.css" />
    <link rel="stylesheet" href="../css/complex.css" />
    <link rel="stylesheet" href="../css/liquid-glass.css" />
    <link rel="stylesheet" href="../css/professional.css" />
    <link rel="stylesheet" href="../css/professional.css" />
  </head>
  <body class="sub_page">
    <?php include "../includes/header.php"; ?>
    <section class="inner_page_head">
      <div class="container">
        <h3>Pesanan Saya</h3>
      </div>
    </section>
    <section class="section-pad">
      <div class="container">
        <?php if (isset($_GET["success"])): ?>
        <div class="alert alert-success">
          Pesanan berhasil dibuat: <?= htmlspecialchars($_GET["success"]) ?>
        </div>
        <?php endif; ?>
        <div class="glass-card p-4">
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Invoice</th>
                  <th>Tanggal</th>
                  <th>Total</th>
                  <th>Pembayaran</th>
                  <th>Pengiriman</th>
                  <th>Status</th>
                  <th>Detail</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($orders as $o): ?>
                <tr>
                  <td><?= htmlspecialchars($o["invoice_code"]) ?></td>
                  <td><?= $o["created_at"] ?></td>
                  <td>Rp <?= number_format(
                      $o["total_amount"],
                      0,
                      ",",
                      ".",
                  ) ?></td>
                  <td><?= htmlspecialchars($o["payment_status"] ?? "-") ?></td>
                  <td><?= htmlspecialchars($o["shipment_status"] ?? "-") ?></td>
                  <td>
                    <span class="status-pill"> <?= $o["status"] ?> </span>
                  </td>
                  <td>
                    <a
                      class="btn btn-sm btn-dark rounded-pill"
                      href="order_detail.php?id=<?= $o["id"] ?>
"
                      >Lihat</a
                    >
                  </td>
                </tr>
                <?php endforeach;
                if (!$orders): ?>
                <tr>
                  <td colspan="7" class="text-center">Belum ada pesanan.</td>
                </tr>
                <?php endif;
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
    <?php include "../includes/footer.php"; ?>
  </body>
</html>

<?php
require_once "includes/session.php"; ?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Keranjang</title>
    <link rel="stylesheet" href="css/bootstrap.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/complex.css" />
    <link rel="stylesheet" href="css/liquid-glass.css" />
    <link rel="stylesheet" href="css/professional.css" />
  </head>
  <body class="sub_page">
    <?php include "includes/header.php"; ?>
    <section class="inner_page_head">
      <div class="container">
        <h3>Keranjang Belanja</h3>
      </div>
    </section>
    <section class="section-pad">
      <div class="container">
        <div class="glass-card p-4">
          <div class="table-responsive glass-table">
            <table class="table">
              <thead>
                <tr>
                  <th>Produk</th>
                  <th>Harga</th>
                  <th>Qty</th>
                  <th>Subtotal</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="cartBody"></tbody>
            </table>
          </div>
          <div
            class="d-flex flex-wrap justify-content-between align-items-center mt-4"
            style="gap: 12px"
          >
            <h4>Total: <span id="cartTotal">Rp 0</span></h4>
            <a href="checkout.php" class="btn-liquid">Lanjut Checkout</a>
          </div>
        </div>
      </div>
    </section>
    <?php include "includes/footer.php"; ?>
  </body>
</html>

<?php
require_once "includes/session.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keranjang Belanja</title>

    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/complex.css">
    <link rel="stylesheet" href="css/liquid-glass.css">
    <link rel="stylesheet" href="css/professional.css">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="sub_page">

<?php include "includes/header.php"; ?>

<section class="inner_page_head mt-5">
    <div class="container pt-2">
        <h3 class="fw-bold">Keranjang Belanja</h3>
        <p class="text-muted mb-0">Periksa kembali produk sebelum checkout.</p>
    </div>
</section>

<section class="cart-section">

    <div class="container">

        <div class="cart-layout">

            <!-- LIST PRODUK -->
            <div>

                <div id="cartBody"></div>

                <div id="cartPagination" class="mt-4"></div>

            </div>

            <!-- SIDEBAR -->
            <aside class="cart-summary">

              <h5>Ringkasan Belanja</h5>

              <div id="summaryItems" class="summary-items"></div>

              <div class="summary-divider"></div>

              <div class="coupon-label">
                  Punya kode kupon?
              </div>

              <div class="coupon-box">
                  <input
                      type="text"
                      class="form-control"
                      placeholder="Masukkan kode">
                  <button class="coupon-btn">
                      Pakai
                  </button>
              </div>

              <div class="summary-divider"></div>

              <div class="summary-row">
                  <span>Subtotal</span>
                  <span id="summarySubtotal">Rp0</span>
              </div>

              <div class="summary-row">
                  <span>Diskon</span>
                  <span id="summaryDiscount">-Rp0</span>
              </div>

              <div class="summary-row">
                  <span>Ongkir</span>
                  <span class="text-success fw-semibold">Gratis</span>
              </div>

              <div class="summary-divider"></div>

              <div class="summary-total">
                  <span class="label">Total</span>
                  <span class="value" id="cartTotal">Rp0</span>
              </div>

              <a href="checkout.php" class="checkout-btn">
                  Lanjut Checkout
              </a>

              <div class="summary-footer">
                  🔒 Pembayaran aman & terenkripsi
              </div>

          </aside>

        </div>

    </div>

</section>

<?php include "includes/footer.php"; ?>

</body>
</html>
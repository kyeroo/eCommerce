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

<?php if(isset($_SESSION['user'])) { ?>
<section class="inner_page_head">
    <div class="container">
        <h3 class="fw-bold">Keranjang Belanja</h3>
        <p class="text-muted mb-0">Periksa kembali produk sebelum checkout.</p>
    </div>
</section>
<?php } ?>

<section class="cart-section">

    <div class="container">

<?php if (isset($_SESSION['user'])): ?>

    <!-- Empty Cart -->
    <div id="emptyCart" class="empty-cart" style="display:none;">

        <div class="empty-cart-icon">
            <i class="bi bi-cart-x"></i>
        </div>

        <h2>Keranjangmu masih kosong</h2>

        <p>
            Belum ada produk yang ditambahkan ke keranjang.
            Yuk mulai belanja sekarang!
        </p>

        <a href="index.php" class="btn-shop-now">
            <i class="bi bi-bag"></i>
            Mulai Belanja
        </a>

    </div>

    <!-- Cart Layout -->
    <div id="cartLayout" class="cart-layout">

        <!-- Product List -->
        <div>

            <div id="cartBody"></div>

            <div id="cartPagination" class="mt-4"></div>

        </div>

        <!-- Summary -->
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
                    placeholder="Masukkan kode kupon">

                <button class="coupon-btn">
                    Pakai
                </button>

            </div>

            <div class="summary-divider"></div>

            <div class="summary-row">

                <span>Subtotal</span>

                <span id="summarySubtotal">
                    Rp0
                </span>

            </div>

            <div class="summary-row">

                <span>Diskon</span>

                <span id="summaryDiscount">
                    -Rp0
                </span>

            </div>

            <div class="summary-row">

                <span>Ongkir</span>

                <span class="text-success fw-semibold">
                    Gratis
                </span>

            </div>

            <div class="summary-divider"></div>

            <div class="summary-total">

                <span class="label">
                    Total
                </span>

                <span class="value" id="cartTotal">
                    Rp0
                </span>

            </div>

            <a href="checkout.php" class="checkout-btn">
                Lanjut Checkout
            </a>

        </aside>

    </div>

<?php else: ?>

    <!-- Login Required -->
    <div class="login-required">

        <div class="login-card">

            <div class="login-icon">
                <i class="bi bi-cart3"></i>
            </div>

            <h2>Login untuk Melihat Keranjang</h2>

            <p>
                Masuk ke akunmu agar dapat menyimpan produk,
                mengelola keranjang, dan melanjutkan checkout.
            </p>

            <div class="login-actions">

                <a href="auth/login.php" class="btn-login">
                    Login
                </a>

            </div>

        </div>

    </div>

<?php endif; ?>

</div>

</section>

<?php include "includes/footer.php"; ?>

</body>
</html>
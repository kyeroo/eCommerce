<?php
$folder = basename(dirname($_SERVER["PHP_SELF"] ?? ""));
$prefix = in_array($folder, ["admin", "auth", "customer"], true) ? "../" : "";
?>
<footer class="footer_section mt-5">
<div class="container footer-grid">
<p>&copy; <?= date("Y") ?>
Famms Store. Fashion modern dengan pengalaman belanja yang rapi dan nyaman.</p>
<div class="footer-links">
<a href="<?= $prefix ?>products.php">Katalog</a>
<a href="<?= $prefix ?>cart.php">Tas Belanja</a>
<a href="<?= $prefix ?>auth/login.php">Masuk</a>
</div>
</div>
</footer>
<script src="<?= $prefix ?>js/jquery-3.4.1.min.js"></script>
<script src="<?= $prefix ?>js/popper.min.js"></script>
<script src="<?= $prefix ?>js/bootstrap.js"></script>
<script src="<?= $prefix ?>js/shop.js"></script>
</body>
</html>

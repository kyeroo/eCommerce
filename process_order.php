<?php
require_once "config/database.php";
require_once "includes/session.php";
require_login();
$cart = json_decode($_POST["cart"] ?? "[]", true);
if (!$cart) {
    die("Keranjang kosong.");
}
$user = current_user();
try {
    $pdo->beginTransaction();
    $subtotal = 0;
    foreach ($cart as $item) {
        $subtotal += (float) $item["price"] * (int) $item["qty"];
    }
    $couponId = null;
    $discount = 0;
    $code = trim($_POST["coupon_code"] ?? "");
    if ($code !== "") {
        $c = $pdo->prepare(
            "SELECT * FROM coupons WHERE code=? AND is_active=1",
        );
        $c->execute([$code]);
        $coupon = $c->fetch();
        if ($coupon) {
            $couponId = $coupon["id"];
            $discount =
                $coupon["discount_type"] === "percent"
                    ? min(
                        $subtotal,
                        $subtotal * ((float) $coupon["discount_value"] / 100),
                    )
                    : min($subtotal, (float) $coupon["discount_value"]);
        }
    }
    $shippingCost = $subtotal >= 250000 ? 0 : 15000;
    $total = max(0, $subtotal - $discount + $shippingCost);
    $invoice = "INV-" . date("YmdHis") . "-" . rand(100, 999);
    $stmt = $pdo->prepare('INSERT
INTO
orders(user_id,coupon_id,invoice_code,customer_name,customer_email,customer_phone,shipping_address,payment_method,subtotal,discount_amount,shipping_cost,total_amount)
VALUES(?,?,?,?,?,?,?,?,?,?,?,?)');
    $stmt->execute([
        $user["id"],
        $couponId,
        $invoice,
        $_POST["customer_name"],
        $_POST["customer_email"],
        $_POST["customer_phone"],
        $_POST["shipping_address"],
        $_POST["payment_method"],
        $subtotal,
        $discount,
        $shippingCost,
        $total,
    ]);
    $orderId = $pdo->lastInsertId();
    $itemStmt = $pdo->prepare('INSERT INTO
order_items(order_id,product_id,quantity,price,subtotal) VALUES(?,?,?,?,?)');
    $stockStmt = $pdo->prepare('UPDATE products SET stock=stock-? WHERE id=? AND
stock>=?');
    foreach ($cart as $item) {
        $qty = (int) $item["qty"];
        $price = (float) $item["price"];
        $sub = $qty * $price;
        $stockStmt->execute([$qty, $item["id"], $qty]);
        if ($stockStmt->rowCount() < 1) {
            throw new Exception("Stok produk tidak mencukupi.");
        }
        $itemStmt->execute([$orderId, $item["id"], $qty, $price, $sub]);
    }
    $pdo->prepare(
        'INSERT INTO payments(order_id,payment_method,amount,status)
VALUES(?,?,?,?)',
    )->execute([
        $orderId,
        $_POST["payment_method"],
        $total,
        $_POST["payment_method"] === "cod" ? "unpaid" : "waiting",
    ]);
    $pdo->prepare(
        'INSERT INTO
shipments(order_id,courier,service,shipping_cost,status)
VALUES(?,?,?,?,?)',
    )->execute([$orderId, "JNE", "REG", $shippingCost, "waiting"]);
    $pdo->commit();
    echo '<script>
  localStorage.removeItem("cart");
  location.href = "customer/orders.php?success=' .
        htmlspecialchars($invoice) .
        '";
</script>';
} catch (Exception $e) {
    $pdo->rollBack();
    die("Gagal membuat pesanan:" . htmlspecialchars($e->getMessage()));
}

<?php
require_once "config/database.php";
require_once "includes/session.php";

require_login();

$id = (int) ($_GET["id"] ?? 0);
$uid = current_user()["id"];

$check = $pdo->prepare(
    'SELECT id FROM wishlists WHERE user_id = ? AND product_id = ?'
);
$check->execute([$uid, $id]);

if ($row = $check->fetch()) {
    $pdo->prepare('DELETE FROM wishlists WHERE id = ?')
        ->execute([$row["id"]]);
} else {
    $pdo->prepare(
        'INSERT IGNORE INTO wishlists(user_id, product_id) VALUES(?, ?)'
    )
        ->execute([$uid, $id]);
}

header("Location: wishlist.php");

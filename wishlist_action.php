<?php

require_once 'config/database.php';
require_once 'includes/session.php';

require_login();

$id = (int) ($_GET['id'] ?? 0);
$uid = current_user()['id'];

$check = $pdo->prepare(
    'SELECT id
     FROM wishlists
     WHERE user_id = ?
       AND product_id = ?'
);

$check->execute([
    $uid,
    $id
]);

$row = $check->fetch();

if ($row) {

    $stmt = $pdo->prepare(
        'DELETE
         FROM wishlists
         WHERE id = ?'
    );

    $stmt->execute([
        $row['id']
    ]);

} else {

    $stmt = $pdo->prepare(
        'INSERT IGNORE INTO wishlists
        (user_id, product_id)
        VALUES (?, ?)'
    );

    $stmt->execute([
        $uid,
        $id
    ]);
}

header('Location: wishlist.php');
exit;
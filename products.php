<?php

require_once 'config/database.php';
require_once 'includes/session.php';

$keyword = $_GET['q'] ?? '';
$cat = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? 'newest';

$categories = $pdo->query(
    'SELECT *
     FROM categories
     ORDER BY name'
)->fetchAll();

$sql = "
    SELECT
        p.*,
        c.name AS category_name,
        COALESCE(AVG(r.rating), 0) AS rating,
        COUNT(r.id) AS review_count
    FROM products p
    JOIN categories c
        ON c.id = p.category_id
    LEFT JOIN product_reviews r
        ON r.product_id = p.id
    WHERE 1 = 1
";

$params = [];

if ($keyword !== '') {

    $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";

    $params[] = "%{$keyword}%";
    $params[] = "%{$keyword}%";
}

if ($cat !== '') {

    $sql .= " AND p.category_id = ?";

    $params[] = $cat;
}

$sql .= " GROUP BY p.id ";

$sql .= match ($sort) {

    'price_low'  => ' ORDER BY p.price ASC',
    'price_high' => ' ORDER BY p.price DESC',
    'rating'     => ' ORDER BY rating DESC',

    default      => ' ORDER BY p.created_at DESC'
};

$stmt = $pdo->prepare($sql);

$stmt->execute($params);

$products = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>Produk</title>

    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/complex.css">
    <link rel="stylesheet" href="css/liquid-glass.css">
    <link rel="stylesheet" href="css/professional.css">

</head>

<body class="sub_page">

    <?php include 'includes/header.php'; ?>

    <section class="inner_page_head">

        <div class="container">

            <div class="breadcrumb-pro">
                Home / Katalog
            </div>

            <h3>Katalog Produk</h3>

            <p class="section-subtitle">
                Temukan produk berdasarkan nama, kategori,
                harga, dan rating.
            </p>

        </div>

    </section>

    <section class="section-pad">

        <div class="container">

            <form class="glass-panel mb-4 pro-filter">

                <div class="row">

                    <div class="col-lg-4 mb-2">

                        <input
                            class="form-control"
                            name="q"
                            value="<?= htmlspecialchars($keyword) ?>"
                            placeholder="Cari produk...">

                    </div>

                    <div class="col-lg-3 mb-2">

                        <select
                            class="form-control"
                            name="category">

                            <option value="">
                                Semua kategori
                            </option>

                            <?php foreach ($categories as $c): ?>

                                <option
                                    value="<?= $c['id'] ?>"
                                    <?= $cat == $c['id'] ? 'selected' : '' ?>>

                                    <?= htmlspecialchars($c['name']) ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="col-lg-3 mb-2">

                        <select
                            class="form-control"
                            name="sort">

                            <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>
                                Terbaru
                            </option>

                            <option value="price_low" <?= $sort === 'price_low' ? 'selected' : '' ?>>
                                Harga termurah
                            </option>

                            <option value="price_high" <?= $sort === 'price_high' ? 'selected' : '' ?>>
                                Harga tertinggi
                            </option>

                            <option value="rating" <?= $sort === 'rating' ? 'selected' : '' ?>>
                                Rating terbaik
                            </option>

                        </select>

                    </div>

                    <div class="col-lg-2 mb-2">

                        <button class="btn-liquid w-100">
                            Filter
                        </button>

                    </div>

                </div>

            </form>

            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

                <p class="mb-2 professional-note">

                    <b><?= count($products) ?></b>
                    produk ditemukan.

                </p>

            </div>

            <div class="row">

                <?php if (!$products): ?>

                    <div class="col-12">

                        <div class="empty-state">

                            <h4>Produk tidak ditemukan</h4>

                            <p class="professional-note mb-0">
                                Coba gunakan kata kunci lain atau
                                pilih kategori berbeda.
                            </p>

                        </div>

                    </div>

                <?php endif; ?>

                <?php foreach ($products as $p): ?>

                    <div class="col-sm-6 col-lg-3 mb-4">

                        <div class="product-card">

                            <a href="product_detail.php?id=<?= (int) $p['id'] ?>">

                                <img
                                    src="<?= htmlspecialchars($p['image']) ?>"
                                    alt="">

                            </a>

                            <small>

                                <?= htmlspecialchars($p['category_name']) ?>
                                · Stok <?= (int) $p['stock'] ?>
                                · ★ <?= number_format((float) $p['rating'], 1) ?>

                            </small>

                            <h5>

                                <?= htmlspecialchars($p['name']) ?>

                            </h5>

                            <p>

                                <?= htmlspecialchars(substr($p['description'], 0, 72)) ?>...

                            </p>

                            <p class="price">

                                Rp <?= number_format($p['price'], 0, ',', '.') ?>

                            </p>

                            <button
                                class="btn btn-sm btn-primary rounded-pill"
                                onclick='addToCart(<?= json_encode([
                                    "id" => (int) $p["id"],
                                    "name" => $p["name"],
                                    "price" => (float) $p["price"],
                                    "image" => $p["image"]
                                ]) ?>)'>

                                Tambah

                            </button>

                            <a
                                class="btn btn-sm btn-outline-dark rounded-pill"
                                href="product_detail.php?id=<?= (int) $p['id'] ?>">

                                Detail

                            </a>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        </div>

    </section>

    <?php include 'includes/footer.php'; ?>

</body>

</html>
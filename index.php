<?php

require_once 'config/database.php';
require_once 'includes/session.php';

/** @var PDO $pdo */
if (!isset($pdo) || !$pdo instanceof PDO) {
    exit('Database connection tidak tersedia.');
}

$featured = $pdo->query(
    "SELECT
        p.*,
        c.name AS category_name,
        COALESCE(AVG(r.rating), 0) AS rating,
        COUNT(r.id) AS review_count
     FROM products p
     JOIN categories c
        ON c.id = p.category_id
     LEFT JOIN product_reviews r
        ON r.product_id = p.id
     WHERE p.is_featured = 1
     GROUP BY p.id
     ORDER BY p.created_at DESC
     LIMIT 12"
)->fetchAll();

$categories = $pdo->query(
    "SELECT
        c.*,
        COUNT(p.id) AS product_count
     FROM categories c
     LEFT JOIN products p
        ON p.category_id = c.id
     GROUP BY c.id
     ORDER BY c.name
     LIMIT 6"
)->fetchAll();

$stats = [
    'products' => $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn(),
    'categories' => $pdo->query('SELECT COUNT(*) FROM categories')->fetchColumn(),
    'orders' => $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn(),
    'reviews' => $pdo->query('SELECT COUNT(*) FROM product_reviews')->fetchColumn(),
];

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Famms Store | Fashion Online Profesional</title>

    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/complex.css">
    <link rel="stylesheet" href="css/liquid-glass.css">
    <link rel="stylesheet" href="css/professional.css">

</head>

<body>

    <?php include 'includes/header.php'; ?>

    <section class="liquid-hero">

        <div class="container">

            <div class="row align-items-center">

                <div class="col-lg-7">

                    <div class="glass-panel">

                        <div class="chip">
                            Famms Fashion Store
                        </div>

                        <h1 class="hero-title">
                            Koleksi Fashion Modern untuk Gaya Harian Anda.
                        </h1>

                        <p class="hero-text">
                            Temukan pakaian, aksesori, sepatu, dan outerwear pilihan
                            dengan tampilan katalog yang rapi, proses belanja yang
                            mudah, serta pengalaman checkout yang nyaman di semua
                            perangkat.
                        </p>

                        <div class="hero-actions mt-4">

                            <a
                                class="btn-liquid"
                                href="products.php">

                                Belanja Sekarang

                            </a>

                            <a
                                class="btn-soft"
                                href="products.php?sort=rating">

                                Lihat Koleksi Favorit

                            </a>

                        </div>

                    </div>

                </div>

                <div class="col-lg-5 mt-4 mt-lg-0">

                    <div class="floating-card">

                        <img
                            src="images/hero-fashion.png"
                            alt="Tampilan toko digital profesional">

                        <div class="row mt-3 text-center">

                            <div class="col-4">
                                <b><?= $stats['products'] ?></b>
                                <br>
                                <small>Produk</small>
                            </div>

                            <div class="col-4">
                                <b><?= $stats['orders'] ?></b>
                                <br>
                                <small>Transaksi</small>
                            </div>

                            <div class="col-4">
                                <b><?= $stats['reviews'] ?></b>
                                <br>
                                <small>Ulasan</small>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>
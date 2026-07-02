<?php
$host = "localhost";
$user = "root";
$pass = "";
$databaseList = ["ecommerce", "eccomerce", "ecommerce_complex"];

$koneksi = null;
foreach ($databaseList as $db) {
    $koneksi = mysqli_connect($host, $user, $pass, $db);
    if ($koneksi) {
        mysqli_set_charset($koneksi, "utf8mb4");
        break;
    }
}

if (!$koneksi) {
    die(
        "Koneksi database gagal. Buat database ecommerce lalu import database/ecommerce.sql. Detail: " .
            mysqli_connect_error()
    );
}
?>

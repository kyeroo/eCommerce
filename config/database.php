<?php
/*
|--------------------------------------------------------------------------
| Konfigurasi Database
|--------------------------------------------------------------------------
| Project ini dibuat agar langsung cocok dengan database di phpMyAdmin.
| Nama utama database: ecommerce
| Jika database dibuat dengan typo "eccomerce", sistem juga tetap mencoba connect.
*/

$host = "localhost";
$username = "root";
$password = "";
$candidateDatabases = ["ecommerce", "eccomerce", "ecommerce_complex"];

$pdo = null;
$lastError = "";

foreach ($candidateDatabases as $dbname) {
    try {
        $pdo = new PDO(
            "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
            $username,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ],
        );
        break;
    } catch (PDOException $e) {
        $lastError = $e->getMessage();
    }
}
if (!$pdo) {
    die(
        'Koneksi database gagal.
Pastikan database bernama ecommerce sudah dibuat dan file database/ecommerce.sql
sudah di-import. Detail: ' . $lastError
    );
}

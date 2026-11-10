<?php
// ============================================================
// Database Config — Tjap Daoen (InfinityFree)
// Isi dari panel InfinityFree > MySQL Databases
// ============================================================
$host     = "sqlXXX.infinityfree.com"; // MySQL Hostname (BUKAN localhost)
$user     = "if0_XXXXXXXX";            // MySQL Username
$password = "ISI_PASSWORD_AKUN_HOSTING";
$database = "if0_XXXXXXXX_toko_kue";   // nama database lengkap dengan prefix

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    error_log("DB error: " . mysqli_connect_error());
    die("Layanan sedang tidak tersedia.");
}

mysqli_set_charset($conn, "utf8mb4");

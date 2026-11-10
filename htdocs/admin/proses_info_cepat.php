<?php
// ============================================================
// admin/proses_info_cepat.php
// Quick-edit jam & alamat dari panel kanan Kelola Menu
// ============================================================
session_start();
require_once "../includes/auth.php";

$data_file = '../data/lokasi.json';
$data      = json_decode(file_get_contents($data_file), true) ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Parse jam buka input: "10.00 - 23.00 WIB" or just "10.00"
    $jam_buka = trim($_POST['jam_buka'] ?? '');
    if (preg_match('/(\d{1,2}[\.:]\d{2})\s*[-–]\s*(\d{1,2}[\.:]\d{2})/', $jam_buka, $m)) {
        $data['jam_senin_jumat_buka']  = str_replace(':', '.', $m[1]);
        $data['jam_senin_jumat_tutup'] = str_replace(':', '.', $m[2]);
    }

    $data['alamat'] = trim($_POST['alamat'] ?? $data['alamat']);

    file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $_SESSION['success'] = 'Informasi kafe berhasil diperbarui.';
}

header("Location: produk.php");
exit;

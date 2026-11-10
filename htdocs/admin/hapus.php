<?php

session_start();

require_once "../includes/auth.php";

require_once "../config/database.php";

$id = isset($_GET['id'])
    ? (int)$_GET['id']
    : 0;

/*
|--------------------------------------------------------------------------
| AMBIL DATA GAMBAR
|--------------------------------------------------------------------------
*/

$query = mysqli_query(
    $conn,
    "SELECT gambar FROM produk
     WHERE id = $id"
);

$data = mysqli_fetch_assoc($query);

/*
|--------------------------------------------------------------------------
| HAPUS FILE GAMBAR
|--------------------------------------------------------------------------
*/

if (
    $data &&
    $data['gambar'] &&
    file_exists(
        "../uploads/produk/" .
        $data['gambar']
    )
) {

    unlink(
        "../uploads/produk/" .
        $data['gambar']
    );
}

/*
|--------------------------------------------------------------------------
| HAPUS DATABASE
|--------------------------------------------------------------------------
*/

$delete = mysqli_query(
    $conn,
    "DELETE FROM produk
     WHERE id = $id"
);

if ($delete) {

    $_SESSION['success'] =
        "Produk berhasil dihapus.";

} else {

    $_SESSION['error'] =
        "Produk gagal dihapus.";
}

header("Location: produk.php");

exit;
<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

session_start();

$aksi = $_POST['aksi'] ?? '';

/*
|--------------------------------------------------------------------------
| TAMBAH PRODUK
|--------------------------------------------------------------------------
*/

if ($aksi == 'tambah') {

    $nama = trim($_POST['nama'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $harga = $_POST['harga'] ?? '';
    $stok = $_POST['stok'] ?? '';

    // Validasi
    if (
        $nama == '' ||
        $kategori == '' ||
        $harga == '' ||
        $stok == ''
    ) {

        $_SESSION['error'] =
            "Semua data wajib diisi.";

        header("Location: tambah.php");

        exit;
    }

    // Validasi harga
    if (!is_numeric($harga)) {

        $_SESSION['error'] =
            "Harga harus berupa angka.";

        header("Location: tambah.php");

        exit;
    }

    // Validasi stok
    if (!is_numeric($stok)) {

        $_SESSION['error'] =
            "Stok harus berupa angka.";

        header("Location: tambah.php");

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | UPLOAD GAMBAR
    |--------------------------------------------------------------------------
    */

    $nama_file = null;

    if (
        isset($_FILES['gambar']) &&
        $_FILES['gambar']['error'] == 0
    ) {

        $file = $_FILES['gambar'];

        $extension = strtolower(
            pathinfo(
                $file['name'],
                PATHINFO_EXTENSION
            )
        );

        $allowed = [
            'jpg',
            'jpeg',
            'png',
            'webp'
        ];

        if (!in_array($extension, $allowed)) {

            $_SESSION['error'] =
                "Format gambar tidak diperbolehkan.";

            header("Location: tambah.php");

            exit;
        }

        if ($file['size'] > 2 * 1024 * 1024) {

            $_SESSION['error'] =
                "Ukuran gambar maksimal 2 MB.";

            header("Location: tambah.php");

            exit;
        }

        $nama_file =
            uniqid() . "." . $extension;

        move_uploaded_file(
            $file['tmp_name'],
            "../uploads/produk/" . $nama_file
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ESCAPE DATA
    |--------------------------------------------------------------------------
    */

    $nama = mysqli_real_escape_string(
        $conn,
        $nama
    );

    $kategori = mysqli_real_escape_string(
        $conn,
        $kategori
    );

    $deskripsi = mysqli_real_escape_string(
        $conn,
        $deskripsi
    );

    /*
    |--------------------------------------------------------------------------
    | INSERT DATA
    |--------------------------------------------------------------------------
    */

    $query = mysqli_query(
        $conn,
        "INSERT INTO produk
        (nama, kategori, deskripsi, harga, stok, gambar)
        VALUES
        ('$nama', '$kategori', '$deskripsi',
         '$harga', '$stok', '$nama_file')"
    );

    if ($query) {

        $_SESSION['success'] =
            "Produk berhasil ditambahkan.";

    } else {

        $_SESSION['error'] =
            "Produk gagal ditambahkan.";
    }

    header("Location: produk.php");

    exit;
}


/*
|--------------------------------------------------------------------------
| EDIT PRODUK
|--------------------------------------------------------------------------
*/

if ($aksi == 'edit') {

    $id = (int)$_POST['id'];

    $nama = trim($_POST['nama'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $harga = $_POST['harga'] ?? '';
    $stok = $_POST['stok'] ?? '';

    /*
    |--------------------------------------------------------------------------
    | VALIDASI
    |--------------------------------------------------------------------------
    */

    if (
        $nama == '' ||
        $kategori == '' ||
        $deskripsi == '' ||
        $harga == '' ||
        $stok == ''
    ) {

        $_SESSION['error'] =
            "Semua data wajib diisi.";

        header("Location: edit.php?id=$id");

        exit;
    }

    if (!is_numeric($harga)) {

        $_SESSION['error'] =
            "Harga harus berupa angka.";

        header("Location: edit.php?id=$id");

        exit;
    }

    if (!is_numeric($stok)) {

        $_SESSION['error'] =
            "Stok harus berupa angka.";

        header("Location: edit.php?id=$id");

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | ESCAPE DATA
    |--------------------------------------------------------------------------
    */

    $nama = mysqli_real_escape_string(
        $conn,
        $nama
    );

    $kategori = mysqli_real_escape_string(
        $conn,
        $kategori
    );

    $deskripsi = mysqli_real_escape_string(
        $conn,
        $deskripsi
    );

    /*
    |--------------------------------------------------------------------------
    | AMBIL DATA LAMA
    |--------------------------------------------------------------------------
    */

    $old_query = mysqli_query(
        $conn,
        "SELECT gambar
         FROM produk
         WHERE id = $id"
    );

    $old_data = mysqli_fetch_assoc(
        $old_query
    );

    $nama_file = $old_data['gambar'];

    /*
    |--------------------------------------------------------------------------
    | JIKA ADA GAMBAR BARU
    |--------------------------------------------------------------------------
    */

    if (
        isset($_FILES['gambar']) &&
        $_FILES['gambar']['error'] == 0
    ) {

        $file = $_FILES['gambar'];

        $extension = strtolower(
            pathinfo(
                $file['name'],
                PATHINFO_EXTENSION
            )
        );

        $allowed = [
            'jpg',
            'jpeg',
            'png',
            'webp'
        ];

        if (!in_array($extension, $allowed)) {

            $_SESSION['error'] =
                "Format gambar tidak diperbolehkan.";

            header("Location: edit.php?id=$id");

            exit;
        }

        if ($file['size'] > 2 * 1024 * 1024) {

            $_SESSION['error'] =
                "Ukuran gambar maksimal 2 MB.";

            header("Location: edit.php?id=$id");

            exit;
        }

        /*
        |----------------------------------------------------------------------
        | NAMA FILE BARU
        |----------------------------------------------------------------------
        */

        $nama_file =
            uniqid() . "." . $extension;

        /*
        |----------------------------------------------------------------------
        | UPLOAD
        |----------------------------------------------------------------------
        */

        move_uploaded_file(
            $file['tmp_name'],
            "../uploads/produk/" . $nama_file
        );

        /*
        |----------------------------------------------------------------------
        | HAPUS GAMBAR LAMA
        |----------------------------------------------------------------------
        */

        if (
            $old_data['gambar'] &&
            file_exists(
                "../uploads/produk/" .
                $old_data['gambar']
            )
        ) {

            unlink(
                "../uploads/produk/" .
                $old_data['gambar']
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE DATABASE
    |--------------------------------------------------------------------------
    */

    $query = mysqli_query(
        $conn,
        "UPDATE produk SET

        nama = '$nama',
        kategori = '$kategori',
        deskripsi = '$deskripsi',
        harga = '$harga',
        stok = '$stok',
        gambar = '$nama_file'

        WHERE id = $id"
    );

    /*
    |--------------------------------------------------------------------------
    | FLASH MESSAGE
    |--------------------------------------------------------------------------
    */

    if ($query) {

        $_SESSION['success'] =
            "Produk berhasil diperbarui.";

    } else {

        $_SESSION['error'] =
            "Produk gagal diperbarui.";
    }

    header("Location: produk.php");

    exit;
}
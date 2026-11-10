<?php
// SATU KALI PAKAI: isi password baru di bawah, upload, buka sekali lewat browser.
// File ini otomatis menghapus dirinya sendiri setelah dijalankan.
require_once "../config/database.php";

$nama     = "Administrator";
$username = "admin";
$password = "GANTI_DENGAN_PASSWORD_KUAT_MIN_12_KARAKTER";

if (strlen($password) < 12 || strpos($password, "GANTI_DENGAN") === 0) {
    die("Edit file ini dulu: isi \$password dengan password kuat (min. 12 karakter).");
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = mysqli_prepare($conn, "INSERT INTO admin (nama, username, password) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt, "sss", $nama, $username, $hash);
$ok = mysqli_stmt_execute($stmt);

if ($ok) {
    @unlink(__FILE__);
    echo "Admin dibuat. File ini sudah dihapus otomatis. Cek bahwa auth/setup_admin.php tidak ada lagi.";
} else {
    echo "Gagal membuat admin (mungkin username sudah ada).";
}

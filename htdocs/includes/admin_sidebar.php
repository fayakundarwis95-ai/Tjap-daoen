<?php
// ============================================================
// includes/admin_sidebar.php
// Shared sidebar layout for all admin pages
// Requires: $active_admin (string) to highlight active link
// ============================================================
$active_admin = $active_admin ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($admin_page_title ?? 'Admin') ?> — Tjap Daoen Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="../assets/style.css?v=<?= filemtime(__DIR__ . '/../assets/style.css') ?>">
    <link rel="stylesheet" href="../assets/admin.css">
</head>
<body>

<div class="admin-layout">

    <!-- ===== SIDEBAR ===== -->
    <aside class="admin-sidebar">

        <div class="sidebar-brand">
            <a class="sidebar-brand-name" href="index.php">TJAP DAOEN</a>
            <span class="sidebar-subtitle">Panel Pengelola</span>
        </div>

        <nav class="sidebar-nav">
            <a href="index.php"     class="<?= $active_admin === 'ringkasan' ? 'active' : '' ?>">Ringkasan</a>
            <a href="produk.php"    class="<?= $active_admin === 'menu'      ? 'active' : '' ?>">Kelola Menu</a>
            <a href="popup.php"     class="<?= $active_admin === 'popup'     ? 'active' : '' ?>">Pop-up Beranda</a>
            <a href="tentang.php"   class="<?= $active_admin === 'tentang'   ? 'active' : '' ?>">Tentang &amp; Cerita Kafe</a>
            <a href="fasilitas.php" class="<?= $active_admin === 'fasilitas' ? 'active' : '' ?>">Kelola Fasilitas</a>
            <a href="lokasi.php"    class="<?= $active_admin === 'lokasi'    ? 'active' : '' ?>">Lokasi &amp; Jam Operasional</a>
            <a href="kontak.php"    class="<?= $active_admin === 'kontak'    ? 'active' : '' ?>">Pengaturan Kontak</a>
        </nav>

        <div class="sidebar-footer">
            <a href="../index.php" target="_blank" style="display:block;margin-bottom:8px;color:var(--muted);font-size:12px;text-decoration:none;">↗ Lihat Website</a>
            <a href="logout.php">Logout</a>
        </div>

    </aside>

    <!-- ===== MAIN CONTENT starts here — close in admin_footer.php ===== -->
    <main class="admin-main">
    <div class="admin-content">

<?php
require_once "../includes/auth.php";
require_once "../config/database.php";

$total_menu     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM produk"))['c'];
$total_kopi     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM produk WHERE kategori='Kopi'"))['c'];
$total_makanan  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM produk WHERE kategori IN ('Makanan','Dessert')"))['c'];
$total_habis    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM produk WHERE stok=0"))['c'];

$recent = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC LIMIT 5");

$admin_page_title = 'Ringkasan';
$active_admin     = 'ringkasan';
require_once "../includes/admin_sidebar.php";
?>

<!-- Page header -->
<div class="admin-page-header">
    <h1 class="admin-page-title">Ringkasan Panel</h1>
    <p class="admin-page-desc">Selamat datang, <strong style="color:var(--white);"><?= htmlspecialchars($_SESSION['admin_nama']) ?></strong> — berikut ringkasan kondisi website Tjap Daoen.</p>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-label">Total Menu</div>
        <div class="stat-card-value">(<?= $total_menu ?>)</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label">Menu Kopi</div>
        <div class="stat-card-value">(<?= $total_kopi ?>)</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label">Makanan &amp; Kudapan</div>
        <div class="stat-card-value">(<?= $total_makanan ?>)</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-label">Status Website</div>
        <div class="stat-card-value green">(Aktif)</div>
    </div>
</div>

<!-- Recent menu & Quick links -->
<div class="admin-cols">

    <!-- Recent menu -->
    <div class="admin-panel">
        <div class="admin-panel-head">
            <span class="admin-panel-title">Menu Terbaru Ditambahkan</span>
            <a href="produk.php" class="btn btn-primary btn-sm">Kelola Semua</a>
        </div>
        <div style="overflow-x:auto;">
            <table class="menu-table">
                <thead>
                    <tr>
                        <th style="width:72px;">Foto</th>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($recent)): ?>
                    <tr>
                        <td>
                            <?php if ($row['gambar']): ?>
                            <img class="menu-table-thumb"
                                 src="../uploads/produk/<?= htmlspecialchars($row['gambar']) ?>"
                                 alt="">
                            <?php else: ?>
                            <div class="menu-table-thumb-empty">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td style="font-weight:500;"><?= htmlspecialchars($row['nama']) ?></td>
                        <td style="color:var(--muted);"><?= htmlspecialchars($row['kategori']) ?></td>
                        <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                        <td>
                            <span class="badge-status <?= $row['stok'] > 0 ? 'badge-tersedia' : 'badge-habis' ?>">
                                <?= $row['stok'] > 0 ? 'Tersedia' : 'Habis' ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if ($total_menu === 0): ?>
                    <tr><td colspan="5" style="color:var(--muted);">Belum ada menu.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick nav -->
    <div style="display:flex;flex-direction:column;gap:16px;">

        <?php if ($total_habis > 0): ?>
        <div class="admin-panel" style="border-color:#c0392b;">
            <div class="admin-panel-body">
                <div style="font-size:13px;font-weight:600;color:#ff6b6b;margin-bottom:4px;">⚠ Perhatian</div>
                <div style="font-size:15px;color:var(--white);"><?= $total_habis ?> menu stok habis</div>
                <a href="produk.php" style="color:var(--green);font-size:13px;text-decoration:none;margin-top:8px;display:inline-block;">Update stok →</a>
            </div>
        </div>
        <?php endif; ?>

        <div class="quick-panel">
            <div class="quick-panel-title">Aksi Cepat</div>
            <div style="display:flex;flex-direction:column;gap:10px;">
                <a href="tambah.php" class="btn btn-primary" style="justify-content:center;">+ Tambah Menu Baru</a>
                <a href="tentang.php" class="btn btn-outline" style="justify-content:center;">Edit Tentang Kafe</a>
                <a href="lokasi.php" class="btn btn-outline" style="justify-content:center;">Edit Jam &amp; Lokasi</a>
                <a href="kontak.php" class="btn btn-outline" style="justify-content:center;">Edit Kontak</a>
            </div>
        </div>

    </div>

</div>

<?php require_once "../includes/admin_footer.php"; ?>
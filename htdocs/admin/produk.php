<?php
session_start();
require_once "../includes/auth.php";
require_once "../config/database.php";

$keyword  = $_GET['keyword']  ?? '';
$kategori = $_GET['kategori'] ?? '';
$where    = [];

if ($keyword !== '') {
    $kw = mysqli_real_escape_string($conn, $keyword);
    $where[] = "nama LIKE '%$kw%'";
}
if ($kategori !== '') {
    $kt = mysqli_real_escape_string($conn, $kategori);
    $where[] = "kategori = '$kt'";
}

$where_sql = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';
$total     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM produk $where_sql"))['c'];
$total_kopi    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM produk WHERE kategori='Kopi'"))['c'];
$total_makanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM produk WHERE kategori IN ('Makanan','Dessert')"))['c'];

$per_page   = 10;
$page       = max(1, (int)($_GET['page'] ?? 1));
$offset     = ($page - 1) * $per_page;
$total_page = ceil($total / $per_page);

$query = mysqli_query($conn,
    "SELECT * FROM produk $where_sql ORDER BY id DESC LIMIT $per_page OFFSET $offset");

$admin_page_title = 'Kelola Menu';
$active_admin     = 'menu';
require_once "../includes/admin_sidebar.php";

// Flash messages
if (!empty($_SESSION['success'])) {
    echo '<div class="admin-flash admin-flash-success">✓ ' . htmlspecialchars($_SESSION['success']) . '</div>';
    unset($_SESSION['success']);
}
if (!empty($_SESSION['error'])) {
    echo '<div class="admin-flash admin-flash-error">⚠ ' . htmlspecialchars($_SESSION['error']) . '</div>';
    unset($_SESSION['error']);
}
?>

<!-- Topbar -->
<div class="admin-topbar">
    <div class="admin-topbar-left">
        <h1 class="admin-page-title">Kelola Menu &amp; Informasi Website</h1>
    </div>
    <div class="admin-search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
        <form method="GET" style="display:contents;">
            <input class="field-input" type="text" name="keyword"
                   placeholder="Cari menu..."
                   value="<?= htmlspecialchars($keyword) ?>"
                   onchange="this.form.submit()">
        </form>
    </div>
    <a href="tambah.php" class="btn btn-primary">+ Tambah Menu Baru</a>
</div>

<!-- Stats row -->
<div class="stats-grid" style="margin-bottom:32px;">
    <div class="stat-card">
        <div class="stat-card-label">Total Menu</div>
        <div class="stat-card-value">(<?= mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM produk"))['c'] ?>)</div>
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

<!-- Main cols: table + quick panel -->
<div class="admin-cols">

    <!-- Menu table -->
    <div class="admin-panel">
        <div class="admin-panel-head">
            <span class="admin-panel-title">Menu Data Table</span>
            <?php if ($keyword || $kategori): ?>
            <a href="produk.php" style="font-size:13px;color:var(--muted);text-decoration:none;">Reset filter</a>
            <?php endif; ?>
        </div>

        <!-- Category filter tabs -->
        <div style="display:flex;gap:0;border-bottom:1px solid var(--border);padding:0 24px;">
            <?php
            $cats = [''=>'Semua', 'Kopi'=>'Kopi', 'Non-Kopi'=>'Non-Kopi', 'Makanan'=>'Makanan', 'Dessert'=>'Dessert'];
            foreach ($cats as $val => $lbl):
            ?>
            <a href="?kategori=<?= urlencode($val) ?><?= $keyword ? '&keyword='.urlencode($keyword) : '' ?>"
               style="padding:12px 16px;font-size:13px;font-weight:500;text-decoration:none;border-bottom:2px solid <?= $kategori===$val ? 'var(--green)' : 'transparent' ?>;color:<?= $kategori===$val ? 'var(--green)' : 'var(--muted)' ?>;transition:color 0.2s;white-space:nowrap;">
                <?= $lbl ?>
            </a>
            <?php endforeach; ?>
        </div>

        <div style="overflow-x:auto;">
            <table class="menu-table">
                <thead>
                    <tr>
                        <th style="width:80px;">Foto Menu</th>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th style="width:160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td>
                            <?php if ($row['gambar']): ?>
                            <img class="menu-table-thumb"
                                 src="../uploads/produk/<?= htmlspecialchars($row['gambar']) ?>"
                                 alt="<?= htmlspecialchars($row['nama']) ?>">
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
                        <td>
                            <div style="display:flex;gap:8px;">
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-edit btn-sm">Ubah</a>
                                <a href="hapus.php?id=<?= $row['id'] ?>"
                                   class="btn btn-del btn-sm"
                                   onclick="return confirm('Hapus menu ini?')">Hapus</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>

                    <?php if ($total === 0): ?>
                    <tr><td colspan="6" style="color:var(--muted);text-align:left;">
                        <?= $keyword ? 'Tidak ada hasil untuk "'.htmlspecialchars($keyword).'".' : 'Belum ada menu.' ?>
                    </td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_page > 1): ?>
        <div style="padding:16px 24px;border-top:1px solid var(--border);">
            <nav class="pagination" style="margin:0;">
                <?php for ($i = 1; $i <= $total_page; $i++): ?>
                <a href="?page=<?= $i ?>&keyword=<?= urlencode($keyword) ?>&kategori=<?= urlencode($kategori) ?>"
                   class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </nav>
        </div>
        <?php endif; ?>
    </div>

    <!-- Quick panel right -->
    <div class="quick-panel">
        <div class="quick-panel-title">Panel Pengaturan Cepat</div>
        <p style="font-size:13px;color:var(--muted);margin-bottom:16px;">Edit Informasi Kafe</p>

        <form action="proses_info_cepat.php" method="POST">
            <div class="field-group">
                <input class="field-input" type="text" name="jam_buka"
                       placeholder="Jam Buka (10.00 - 23.00)"
                       value="<?php
                           $lokasi = json_decode(file_get_contents('../data/lokasi.json'), true);
                           echo htmlspecialchars($lokasi['jam_senin_jumat_buka'].' - '.$lokasi['jam_senin_jumat_tutup'].' WIB');
                       ?>">
            </div>
            <div class="field-group">
                <input class="field-input" type="text" name="alamat"
                       placeholder="Alamat Kafe (Bondowoso)"
                       value="<?= htmlspecialchars($lokasi['alamat'] ?? '') ?>">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:8px;">
                Simpan Perubahan
            </button>
        </form>

        <div style="margin-top:24px;padding-top:16px;border-top:1px solid var(--border);">
            <div style="font-size:13px;color:var(--muted);margin-bottom:12px;">Kelola Halaman Lain</div>
            <div style="display:flex;flex-direction:column;gap:8px;">
                <a href="tentang.php" style="color:var(--muted);font-size:14px;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='var(--white)'" onmouseout="this.style.color='var(--muted)'">→ Tentang &amp; Cerita Kafe</a>
                <a href="fasilitas.php" style="color:var(--muted);font-size:14px;text-decoration:none;" onmouseover="this.style.color='var(--white)'" onmouseout="this.style.color='var(--muted)'">→ Kelola Fasilitas</a>
                <a href="lokasi.php" style="color:var(--muted);font-size:14px;text-decoration:none;" onmouseover="this.style.color='var(--white)'" onmouseout="this.style.color='var(--muted)'">→ Lokasi &amp; Jam</a>
            </div>
        </div>
    </div>

</div>

<?php require_once "../includes/admin_footer.php"; ?>
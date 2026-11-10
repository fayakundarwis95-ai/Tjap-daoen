<?php
require_once "config/database.php";

$keyword = $_GET['keyword'] ?? '';
$page    = max(1, (int)($_GET['page'] ?? 1));
$limit   = 12;
$offset  = ($page - 1) * $limit;

if ($keyword !== '') {
    $kw_safe = mysqli_real_escape_string($conn, $keyword);
    $query   = mysqli_query($conn,
        "SELECT * FROM produk WHERE nama LIKE '%$kw_safe%' ORDER BY id DESC LIMIT $limit OFFSET $offset");
    $countQ  = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COUNT(*) as c FROM produk WHERE nama LIKE '%$kw_safe%'"))['c'];
} else {
    $query  = mysqli_query($conn,
        "SELECT * FROM produk ORDER BY id DESC LIMIT $limit OFFSET $offset");
    $countQ = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COUNT(*) as c FROM produk"))['c'];
}

$total_pages = ceil($countQ / $limit);

$page_title = 'Menu';
$active_nav = 'menu';
require_once "includes/header.php";
?>

<section class="menu-section section">
    <div class="container">

        <div class="section-header">
            <span class="section-label" data-i18n="menu_label">Menu Kami</span>
            <h1 class="section-title" data-i18n="menu_title">Daftar Menu</h1>
            <p class="section-desc" data-i18n="menu_desc">Pilihan kopi dan makanan terbaik yang siap menemani harimu.</p>
        </div>

        <!-- Search -->
        <div class="search-wrap">
            <form class="search-form" method="GET" role="search">
                <input
                    class="search-input"
                    type="search"
                    name="keyword"
                    placeholder="<?= $keyword ? htmlspecialchars($keyword) : 'Cari menu...' ?>"
                    value="<?= htmlspecialchars($keyword) ?>"
                    aria-label="Cari menu"
                >
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;">
                        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                    </svg>
                    Cari
                </button>
                <?php if ($keyword): ?>
                <a href="produk.php" class="btn btn-outline">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Grid -->
        <div class="menu-grid">
            <?php if (mysqli_num_rows($query) === 0): ?>
            <div style="grid-column:1/-1; text-align:left; padding:40px 0; color:var(--muted);">
                <?php if ($keyword): ?>
                    Tidak ada menu yang cocok dengan "<strong style="color:var(--white);"><?= htmlspecialchars($keyword) ?></strong>".
                <?php else: ?>
                    Belum ada menu yang ditambahkan.
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php while ($row = mysqli_fetch_assoc($query)): ?>
            <div class="menu-card">

                <?php if ($row['stok'] > 0): ?>
                    <span class="menu-badge">Stok Tersedia</span>
                <?php else: ?>
                    <span class="menu-badge out-of-stock">Habis</span>
                <?php endif; ?>

                <?php if ($row['gambar']): ?>
                    <img class="menu-card-img"
                         src="uploads/produk/<?= htmlspecialchars($row['gambar']) ?>"
                         alt="<?= htmlspecialchars($row['nama']) ?>"
                         loading="lazy">
                <?php else: ?>
                    <div class="menu-card-img-placeholder">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                    </div>
                <?php endif; ?>

                <div class="menu-card-body">
                    <div class="menu-card-name"><?= htmlspecialchars($row['nama']) ?></div>
                    <div class="menu-card-desc"><?= htmlspecialchars($row['deskripsi']) ?></div>
                    <div class="menu-card-footer">
                        <span class="menu-card-price">
                            Rp <?= number_format($row['harga'], 0, ',', '.') ?>
                        </span>
                        <span class="menu-card-stock <?= $row['stok'] <= 0 ? 'habis' : '' ?>">
                            <?= $row['stok'] > 0 ? 'Tersedia' : 'Habis' ?>
                        </span>
                    </div>
                </div>

            </div>
            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <nav class="pagination" aria-label="Paginasi menu">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?><?= $keyword ? '&keyword='.urlencode($keyword) : '' ?>"
               class="<?= $i === $page ? 'active' : '' ?>"
               aria-current="<?= $i === $page ? 'page' : 'false' ?>">
                <?= $i ?>
            </a>
            <?php endfor; ?>
        </nav>
        <?php endif; ?>

    </div>
</section>

<?php require_once "includes/footer.php"; ?>
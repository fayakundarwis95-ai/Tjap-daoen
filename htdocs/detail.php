<?php
require_once "config/database.php";

$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query  = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id");
$produk = mysqli_fetch_assoc($query);

if (!$produk) {
    header("Location: produk.php");
    exit;
}

$wa_number  = '628123456789'; // GANTI nomor WA kamu
$wa_message = rawurlencode("Halo Tjap Daoen! Saya tertarik dengan menu: " . $produk['nama'] . " (Rp " . number_format($produk['harga'], 0, ',', '.') . "). Apakah masih tersedia?");

$page_title = htmlspecialchars($produk['nama']);
$active_nav = 'menu';
require_once "includes/header.php";
?>

<section class="menu-section section">
    <div class="container">

        <!-- Breadcrumb -->
        <nav aria-label="Breadcrumb" style="margin-bottom:24px;">
            <a href="index.php" style="color:var(--muted);text-decoration:none;font-size:14px;">Beranda</a>
            <span style="color:var(--border);margin:0 8px;">/</span>
            <a href="produk.php" style="color:var(--muted);text-decoration:none;font-size:14px;">Menu</a>
            <span style="color:var(--border);margin:0 8px;">/</span>
            <span style="color:var(--white);font-size:14px;"><?= htmlspecialchars($produk['nama']) ?></span>
        </nav>

        <div class="detail-grid">

            <!-- Image -->
            <div>
                <?php if ($produk['gambar']): ?>
                    <img class="detail-img"
                         src="uploads/produk/<?= htmlspecialchars($produk['gambar']) ?>"
                         alt="<?= htmlspecialchars($produk['nama']) ?>"
                         loading="eager">
                <?php else: ?>
                    <div class="detail-img" style="background:var(--card-bg);display:flex;align-items:center;justify-content:center;border:1px solid var(--border);border-radius:8px;aspect-ratio:4/3;">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Info -->
            <div class="detail-content">
                <div class="detail-kategori"><?= htmlspecialchars($produk['kategori']) ?></div>
                <h1 class="detail-name"><?= htmlspecialchars($produk['nama']) ?></h1>
                <p class="detail-desc"><?= nl2br(htmlspecialchars($produk['deskripsi'])) ?></p>
                <div class="detail-price">
                    Rp <?= number_format($produk['harga'], 0, ',', '.') ?>
                </div>
                <div class="detail-stock">
                    Stok: <strong><?= $produk['stok'] > 0 ? $produk['stok'] . ' tersedia' : 'Habis' ?></strong>
                </div>

                <div style="display:flex;gap:16px;flex-wrap:wrap;">
                    <?php if ($produk['stok'] > 0): ?>
                    <a href="https://wa.me/<?= $wa_number ?>?text=<?= $wa_message ?>"
                       target="_blank" rel="noopener"
                       class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Pesan via WhatsApp
                    </a>
                    <?php endif; ?>
                    <a href="produk.php" class="btn btn-outline">
                        &larr; Kembali ke Menu
                    </a>
                </div>
            </div>

        </div>

    </div>
</section>

<?php require_once "includes/footer.php"; ?>
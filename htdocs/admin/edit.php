<?php
session_start();
require_once "../includes/auth.php";
require_once "../config/database.php";

$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query  = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id");
$produk = mysqli_fetch_assoc($query);

if (!$produk) {
    header("Location: produk.php");
    exit;
}

$admin_page_title = 'Edit Menu';
$active_admin     = 'menu';
require_once "../includes/admin_sidebar.php";

if (!empty($_SESSION['error'])) {
    echo '<div class="admin-flash admin-flash-error">⚠ ' . htmlspecialchars($_SESSION['error']) . '</div>';
    unset($_SESSION['error']);
}
?>

<div class="admin-page-header">
    <a href="produk.php" style="color:var(--muted);text-decoration:none;font-size:14px;display:inline-block;margin-bottom:12px;">← Kembali ke Kelola Menu</a>
    <h1 class="admin-page-title">Edit Menu</h1>
    <p class="admin-page-desc">Editing: <strong style="color:var(--white);"><?= htmlspecialchars($produk['nama']) ?></strong></p>
</div>

<div class="admin-cols">

    <div class="admin-panel">
        <div class="admin-panel-head">
            <span class="admin-panel-title">Informasi Menu</span>
        </div>
        <div class="admin-panel-body">

            <form action="proses_produk.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="aksi" value="edit">
                <input type="hidden" name="id" value="<?= $produk['id'] ?>">

                <div class="field-group">
                    <label class="field-label" for="nama">Nama Menu</label>
                    <input class="field-input" type="text" id="nama" name="nama"
                           value="<?= htmlspecialchars($produk['nama']) ?>" required>
                </div>

                <div class="field-group">
                    <label class="field-label" for="kategori">Kategori</label>
                    <select class="field-input" id="kategori" name="kategori" required>
                        <?php foreach (['Kopi','Non-Kopi','Makanan','Dessert','Lainnya'] as $k): ?>
                        <option value="<?= $k ?>" <?= $produk['kategori'] === $k ? 'selected' : '' ?>><?= $k ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field-group">
                    <label class="field-label" for="deskripsi">Deskripsi</label>
                    <textarea class="field-input" id="deskripsi" name="deskripsi" rows="4"><?= htmlspecialchars($produk['deskripsi']) ?></textarea>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="field-group">
                        <label class="field-label" for="harga">Harga (Rp)</label>
                        <input class="field-input" type="number" id="harga" name="harga"
                               value="<?= $produk['harga'] ?>" min="0" required>
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="stok">Stok</label>
                        <input class="field-input" type="number" id="stok" name="stok"
                               value="<?= $produk['stok'] ?>" min="0" required>
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Foto Menu Saat Ini</label>
                    <?php if ($produk['gambar']): ?>
                    <div id="foto-preview-box" style="margin-bottom:12px;">
                        <img id="foto-preview"
                             src="../uploads/produk/<?= htmlspecialchars($produk['gambar']) ?>"
                             alt="" style="width:100%;max-height:200px;object-fit:cover;border-radius:6px;border:1px solid var(--border);">
                    </div>
                    <?php else: ?>
                    <div id="foto-preview-box" style="margin-bottom:12px;display:none;">
                        <img id="foto-preview" src="" alt="" style="width:100%;max-height:200px;object-fit:cover;border-radius:6px;border:1px solid var(--border);">
                    </div>
                    <p style="font-size:13px;color:var(--muted);margin-bottom:12px;">Belum ada foto</p>
                    <?php endif; ?>

                    <label class="btn btn-outline" style="display:inline-flex;cursor:pointer;">
                        Ganti Foto
                        <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.webp"
                               style="display:none;" onchange="previewGambar(this)">
                    </label>
                    <span style="font-size:13px;color:var(--muted);margin-left:12px;">Biarkan kosong untuk tidak mengganti.</span>
                </div>

                <div style="display:flex;gap:12px;margin-top:8px;">
                    <button type="submit" class="btn btn-primary">Update Menu</button>
                    <a href="produk.php" class="btn btn-outline">Batal</a>
                </div>

            </form>

        </div>
    </div>

    <!-- Right panel -->
    <div style="display:flex;flex-direction:column;gap:16px;">

        <!-- Delete danger zone -->
        <div class="admin-panel" style="border-color:#c0392b;">
            <div class="admin-panel-head" style="border-bottom-color:#c0392b;">
                <span class="admin-panel-title" style="color:#ff6b6b;">Hapus Menu</span>
            </div>
            <div class="admin-panel-body">
                <p style="font-size:13px;color:var(--muted);margin-bottom:16px;">
                    Tindakan ini tidak dapat dibatalkan. Menu akan dihapus permanen.
                </p>
                <a href="hapus.php?id=<?= $produk['id'] ?>"
                   class="btn btn-del" style="width:100%;display:flex;justify-content:center;"
                   onclick="return confirm('Yakin hapus menu ini? Tidak dapat dibatalkan.')">
                    Hapus Menu Ini
                </a>
            </div>
        </div>

        <!-- Info -->
        <div class="quick-panel">
            <div class="quick-panel-title">Informasi</div>
            <div style="font-size:13px;color:var(--muted);line-height:1.8;margin-top:8px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                    <span>ID Menu</span>
                    <span style="color:var(--white);">#<?= $produk['id'] ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                    <span>Kategori</span>
                    <span style="color:var(--white);"><?= htmlspecialchars($produk['kategori']) ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span>Status</span>
                    <span style="color:<?= $produk['stok'] > 0 ? 'var(--green)' : '#ff6b6b' ?>;">
                        <?= $produk['stok'] > 0 ? 'Tersedia' : 'Habis' ?>
                    </span>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
function previewGambar(input) {
    if (!input.files[0]) return;
    const url = URL.createObjectURL(input.files[0]);
    document.getElementById('foto-preview').src = url;
    document.getElementById('foto-preview-box').style.display = 'block';
}
</script>

<?php require_once "../includes/admin_footer.php"; ?>
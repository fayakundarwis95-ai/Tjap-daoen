<?php
session_start();
require_once "../includes/auth.php";

$admin_page_title = 'Tambah Menu Baru';
$active_admin     = 'menu';
require_once "../includes/admin_sidebar.php";

if (!empty($_SESSION['error'])) {
    echo '<div class="admin-flash admin-flash-error">⚠ ' . htmlspecialchars($_SESSION['error']) . '</div>';
    unset($_SESSION['error']);
}
if (!empty($_SESSION['success'])) {
    echo '<div class="admin-flash admin-flash-success">✓ ' . htmlspecialchars($_SESSION['success']) . '</div>';
    unset($_SESSION['success']);
}
?>

<div class="admin-page-header">
    <a href="produk.php" style="color:var(--muted);text-decoration:none;font-size:14px;display:inline-block;margin-bottom:12px;">← Kembali ke Kelola Menu</a>
    <h1 class="admin-page-title">Tambah Menu Baru</h1>
</div>

<div class="admin-cols">

    <div class="admin-panel">
        <div class="admin-panel-head">
            <span class="admin-panel-title">Informasi Menu</span>
        </div>
        <div class="admin-panel-body">

            <form action="proses_produk.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="aksi" value="tambah">

                <div class="field-group">
                    <label class="field-label" for="nama">Nama Menu</label>
                    <input class="field-input" type="text" id="nama" name="nama"
                           placeholder="Contoh: Kopi Susu Tjap Daoen" required>
                </div>

                <div class="field-group">
                    <label class="field-label" for="kategori">Kategori</label>
                    <select class="field-input" id="kategori" name="kategori" required>
                        <option value="">Pilih kategori</option>
                        <option value="Kopi">Kopi</option>
                        <option value="Non-Kopi">Non-Kopi</option>
                        <option value="Makanan">Makanan</option>
                        <option value="Dessert">Dessert</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="field-group">
                    <label class="field-label" for="deskripsi">Deskripsi (Opsional)</label>
                    <textarea class="field-input" id="deskripsi" name="deskripsi" rows="4"
                              placeholder="Deskripsi singkat tentang menu..."></textarea>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="field-group">
                        <label class="field-label" for="harga">Harga (Rp)</label>
                        <input class="field-input" type="number" id="harga" name="harga"
                               min="0" placeholder="22000" required>
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="stok">Stok</label>
                        <input class="field-input" type="number" id="stok" name="stok"
                               min="0" value="1" required>
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Foto Menu</label>
                    <div id="foto-preview-box" style="margin-bottom:12px;display:none;">
                        <img id="foto-preview" src="" alt="" style="width:100%;max-height:200px;object-fit:cover;border-radius:6px;border:1px solid var(--border);">
                    </div>
                    <label class="btn btn-outline" style="display:inline-flex;cursor:pointer;">
                        Pilih Foto
                        <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.webp"
                               style="display:none;" onchange="previewGambar(this)">
                    </label>
                    <span style="font-size:13px;color:var(--muted);margin-left:12px;">JPG, PNG, WebP. Maks 2MB.</span>
                </div>

                <div style="display:flex;gap:12px;margin-top:8px;">
                    <button type="submit" class="btn btn-primary">Simpan Menu</button>
                    <a href="produk.php" class="btn btn-outline">Batal</a>
                </div>

            </form>

        </div>
    </div>

    <!-- Tips panel -->
    <div class="quick-panel">
        <div class="quick-panel-title">Tips Pengisian</div>
        <div style="font-size:13px;color:var(--muted);line-height:1.8;margin-top:8px;">
            <p><strong style="color:var(--white);">Nama Menu</strong> — gunakan nama yang jelas dan mudah dimengerti pelanggan.</p>
            <br>
            <p><strong style="color:var(--white);">Deskripsi</strong> — singkat tapi menarik, 1-2 kalimat sudah cukup.</p>
            <br>
            <p><strong style="color:var(--white);">Foto</strong> — ukuran ideal 800×600px, rasio 4:3, latar bersih.</p>
            <br>
            <p><strong style="color:var(--white);">Stok 0</strong> — item akan tampil dengan label "Habis" di website.</p>
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
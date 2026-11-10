<?php
session_start();
require_once "../includes/auth.php";

$data_file = '../data/kontak.json';
$data      = json_decode(file_get_contents($data_file), true) ?? [];

// Handle save
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['whatsapp']          = preg_replace('/\D/', '', trim($_POST['whatsapp'] ?? ''));
    $data['instagram']         = ltrim(trim($_POST['instagram'] ?? ''), '@');
    $data['tiktok']            = ltrim(trim($_POST['tiktok'] ?? ''), '@');
    $data['email']             = trim($_POST['email'] ?? '');
    $data['wa_pesan_default']  = trim($_POST['wa_pesan_default'] ?? '');

    file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $_SESSION['success'] = 'Pengaturan kontak berhasil disimpan!';
    header("Location: kontak.php");
    exit;
}

$admin_page_title = 'Pengaturan Kontak';
$active_admin     = 'kontak';
require_once "../includes/admin_sidebar.php";

if (!empty($_SESSION['success'])) {
    echo '<div class="admin-flash admin-flash-success">✓ ' . htmlspecialchars($_SESSION['success']) . '</div>';
    unset($_SESSION['success']);
}
?>

<div class="admin-page-header">
    <h1 class="admin-page-title">Pengaturan Kontak</h1>
    <p class="admin-page-desc">Atur nomor WhatsApp, Instagram, TikTok, dan informasi kontak lainnya</p>
</div>

<div class="admin-cols">

    <!-- Left: Form -->
    <div class="admin-panel">
        <div class="admin-panel-head">
            <span class="admin-panel-title">Informasi Kontak</span>
        </div>
        <div class="admin-panel-body">

            <form method="POST">

                <div class="field-group">
                    <label class="field-label">Nomor WhatsApp</label>
                    <div class="field-input-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        <input class="field-input" type="text" name="whatsapp"
                               value="<?= htmlspecialchars($data['whatsapp'] ?? '') ?>"
                               placeholder="628123456789 (tanpa + atau spasi)">
                    </div>
                    <p style="font-size:12px;color:var(--muted);margin-top:6px;">Format: 628xxxxxxxxxx (kode negara 62 + nomor tanpa angka 0 di depan)</p>
                </div>

                <div class="field-group">
                    <label class="field-label">Username Instagram</label>
                    <div class="field-input-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        <input class="field-input" type="text" name="instagram"
                               value="<?= htmlspecialchars($data['instagram'] ?? '') ?>"
                               placeholder="tjapdaoen (tanpa @)">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Username TikTok</label>
                    <div class="field-input-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.72a4.85 4.85 0 01-1.01-.03z"/></svg>
                        <input class="field-input" type="text" name="tiktok"
                               value="<?= htmlspecialchars($data['tiktok'] ?? '') ?>"
                               placeholder="tjapdaoen (tanpa @)">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Email (opsional)</label>
                    <input class="field-input" type="email" name="email"
                           value="<?= htmlspecialchars($data['email'] ?? '') ?>"
                           placeholder="info@tjapdaoen.com">
                </div>

                <div class="field-group">
                    <label class="field-label">Pesan Default WhatsApp</label>
                    <textarea class="field-input" name="wa_pesan_default" rows="3"><?= htmlspecialchars($data['wa_pesan_default'] ?? '') ?></textarea>
                    <p style="font-size:12px;color:var(--muted);margin-top:6px;">Pesan ini muncul otomatis saat pengunjung klik tombol WhatsApp.</p>
                </div>

                <div style="display:flex;gap:12px;">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="index.php" class="btn btn-outline">Batal</a>
                </div>

            </form>

        </div>
    </div>

    <!-- Right: Preview + test -->
    <div style="display:flex;flex-direction:column;gap:16px;">

        <div class="quick-panel">
            <div class="quick-panel-title">Pratinjau Tombol Kontak</div>

            <div style="display:flex;flex-direction:column;gap:12px;margin-top:16px;">

                <a href="https://wa.me/<?= htmlspecialchars($data['whatsapp'] ?? '628123456789') ?>?text=<?= rawurlencode($data['wa_pesan_default'] ?? '') ?>"
                   target="_blank" rel="noopener"
                   class="btn btn-primary" style="justify-content:center;">
                    <svg viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Tes WhatsApp
                </a>

                <a href="https://instagram.com/<?= htmlspecialchars($data['instagram'] ?? 'tjapdaoen') ?>"
                   target="_blank" rel="noopener"
                   class="btn btn-outline" style="justify-content:center;">
                    Instagram @<?= htmlspecialchars($data['instagram'] ?? 'tjapdaoen') ?>
                </a>

                <a href="https://tiktok.com/@<?= htmlspecialchars($data['tiktok'] ?? 'tjapdaoen') ?>"
                   target="_blank" rel="noopener"
                   class="btn btn-outline" style="justify-content:center;">
                    TikTok @<?= htmlspecialchars($data['tiktok'] ?? 'tjapdaoen') ?>
                </a>

            </div>
        </div>

        <div class="quick-panel">
            <div class="quick-panel-title">Info</div>
            <div style="font-size:13px;color:var(--muted);line-height:1.7;margin-top:8px;">
                <p>Perubahan nomor WA akan otomatis diterapkan ke tombol floating WhatsApp dan semua halaman website.</p>
                <br>
                <p>Tombol <strong style="color:var(--white);">Tes WhatsApp</strong> di atas membuka WA dengan nomor yang tersimpan saat ini.</p>
            </div>
        </div>

    </div>

</div>

<?php require_once "../includes/admin_footer.php"; ?>

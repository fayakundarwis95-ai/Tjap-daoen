<?php
require_once "../includes/auth.php";

$data_file = __DIR__ . '/../data/popup.json';
$default = [
    'enabled' => true,
    'title' => 'Bangun Brand Kopi Anda Bersama Kami',
    'description' => 'Jadilah mitra Tjap Daoen dan ciptakan produk kopi dengan merek Anda sendiri menggunakan biji kopi terbaik dari Bondowoso.',
    'button_label' => 'Konsultasi Sekarang',
    'button_url' => 'https://wa.me/628123456789',
    'image' => 'assets/images/hero.jpg',
];
$data = file_exists($data_file) ? json_decode(file_get_contents($data_file), true) : $default;
$data = is_array($data) ? array_merge($default, $data) : $default;

function popup_button_url_is_valid($url)
{
    if ($url === '' || preg_match('/[\x00-\x20\\\\]/', $url)) {
        return false;
    }

    $parts = parse_url($url);
    if ($parts === false) {
        return false;
    }

    if (isset($parts['scheme'])) {
        return in_array(strtolower($parts['scheme']), ['http', 'https'], true)
            && filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    return strpos($url, '//') !== 0
        && preg_match('/^(?:\/|#|\?|[A-Za-z0-9._~-])/', $url) === 1;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $button_label = trim($_POST['button_label'] ?? '');
    $button_url = trim($_POST['button_url'] ?? '');

    if ($title === '' || $button_label === '' || !popup_button_url_is_valid($button_url)) {
        $_SESSION['error'] = 'Judul dan teks tombol wajib diisi. Tautan tombol harus berupa URL HTTP(S) atau tautan internal yang valid.';
        header('Location: popup.php');
        exit;
    }

    $new_image_path = '';
    $image = $data['image'];
    if (!empty($_POST['remove_image'])) {
        $image = '';
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['image'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = $file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE
                ? 'Ukuran gambar melebihi batas upload PHP.'
                : 'Terjadi kesalahan saat mengunggah gambar (Kode: ' . (int) $file['error'] . ').';
            header('Location: popup.php');
            exit;
        }

        $max_size = 5 * 1024 * 1024;
        $image_info = getimagesize($file['tmp_name']);
        $allowed_mime = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
        ];
        $mime = $image_info['mime'] ?? '';
        if ($file['size'] > $max_size || !isset($allowed_mime[$mime])) {
            $_SESSION['error'] = 'Gambar tidak valid atau terlalu besar. Gunakan JPG, PNG, WebP, atau GIF maksimal 5 MB.';
            header('Location: popup.php');
            exit;
        }

        $upload_dir = __DIR__ . '/../uploads/popup';
        if (!is_dir($upload_dir) && !mkdir($upload_dir, 0755, true) && !is_dir($upload_dir)) {
            $_SESSION['error'] = 'Folder upload gambar pop-up tidak dapat dibuat. Periksa izin folder uploads.';
            header('Location: popup.php');
            exit;
        }

        $filename = 'popup_' . bin2hex(random_bytes(8)) . '.' . $allowed_mime[$mime];
        $new_image_path = $upload_dir . '/' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $new_image_path)) {
            $_SESSION['error'] = 'Gagal menyimpan gambar pop-up. Periksa izin folder uploads/popup.';
            header('Location: popup.php');
            exit;
        }
        $image = 'uploads/popup/' . $filename;
    }

    $updated = [
        'enabled' => isset($_POST['enabled']),
        'title' => $title,
        'description' => $description,
        'button_label' => $button_label,
        'button_url' => $button_url,
        'image' => $image,
    ];
    $saved = file_put_contents(
        $data_file,
        json_encode($updated, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        LOCK_EX
    );

    if ($saved === false) {
        if ($new_image_path !== '' && file_exists($new_image_path)) {
            unlink($new_image_path);
        }
        $_SESSION['error'] = 'Pengaturan pop-up gagal disimpan. Periksa izin tulis file data/popup.json.';
        header('Location: popup.php');
        exit;
    }

    $old_image = $data['image'] ?? '';
    if (($image !== $old_image || !empty($_POST['remove_image'])) && strpos($old_image, 'uploads/popup/') === 0) {
        $old_filename = basename($old_image);
        $old_image_path = __DIR__ . '/../uploads/popup/' . $old_filename;
        if (is_file($old_image_path)) {
            unlink($old_image_path);
        }
    }

    $_SESSION['success'] = 'Pengaturan pop-up berhasil disimpan.';
    header('Location: popup.php');
    exit;
}

$admin_page_title = 'Pop-up Beranda';
$active_admin = 'popup';
require_once "../includes/admin_sidebar.php";

$image_exists = !empty($data['image']) && is_file(__DIR__ . '/../' . $data['image']);
?>

<?php if (!empty($_SESSION['success'])): ?>
<div class="admin-flash admin-flash-success"><?= htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') ?></div>
<?php unset($_SESSION['success']); endif; ?>
<?php if (!empty($_SESSION['error'])): ?>
<div class="admin-flash admin-flash-error"><?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?></div>
<?php unset($_SESSION['error']); endif; ?>

<div class="admin-page-header">
    <h1 class="admin-page-title">Pengaturan Pop-up Beranda</h1>
    <p class="admin-page-desc">Atur promosi yang muncul saat pengunjung pertama kali membuka website.</p>
</div>

<form method="POST" enctype="multipart/form-data">
    <div class="admin-cols">
        <div style="display:flex;flex-direction:column;gap:20px;">
            <div class="admin-panel">
                <div class="admin-panel-head">
                    <span class="admin-panel-title">Konten Promosi</span>
                </div>
                <div class="admin-panel-body">
                    <div class="field-group">
                        <label class="field-label" for="popup-title">Judul</label>
                        <input class="field-input" type="text" id="popup-title" name="title" maxlength="160" required
                               value="<?= htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="popup-description">Deskripsi</label>
                        <textarea class="field-input" id="popup-description" name="description" rows="5"
                                  style="min-height:120px;line-height:1.6;"><?= htmlspecialchars($data['description'], ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="popup-button-label">Teks Tombol</label>
                        <input class="field-input" type="text" id="popup-button-label" name="button_label" maxlength="80" required
                               value="<?= htmlspecialchars($data['button_label'], ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="popup-button-url">Tautan Tombol</label>
                        <input class="field-input" type="text" id="popup-button-url" name="button_url" required
                               value="<?= htmlspecialchars($data['button_url'], ENT_QUOTES, 'UTF-8') ?>"
                               placeholder="https://wa.me/628... atau kontak.php">
                        <p style="font-size:12px;color:var(--muted);margin-top:6px;">Gunakan tautan WhatsApp, URL website, atau halaman internal seperti kontak.php.</p>
                    </div>
                    <label class="toggle-wrap" style="cursor:pointer;">
                        <span class="toggle-switch">
                            <input type="checkbox" name="enabled" <?= !empty($data['enabled']) ? 'checked' : '' ?>>
                            <span class="toggle-slider"></span>
                        </span>
                        <span class="toggle-label">Tampilkan pop-up di website</span>
                    </label>
                </div>
            </div>

            <div class="admin-panel">
                <div class="admin-panel-head">
                    <span class="admin-panel-title">Gambar Pop-up</span>
                </div>
                <div class="admin-panel-body">
                    <div class="field-group">
                        <label class="field-label" for="popup-image">Upload Gambar (maks. 5 MB)</label>
                        <input class="field-input" type="file" id="popup-image" name="image" accept=".jpg,.jpeg,.png,.webp,.gif,image/jpeg,image/png,image/webp,image/gif">
                        <p style="font-size:12px;color:var(--muted);margin-top:6px;">Format JPG, PNG, WebP, atau GIF. Gambar sebelumnya tetap digunakan jika tidak memilih file baru.</p>
                    </div>
                    <?php if ($image_exists): ?>
                    <label class="toggle-wrap" style="cursor:pointer;">
                        <span class="toggle-switch">
                            <input type="checkbox" name="remove_image" value="1">
                            <span class="toggle-slider"></span>
                        </span>
                        <span class="toggle-label">Hapus gambar saat ini</span>
                    </label>
                    <?php endif; ?>
                    <div style="display:flex;gap:12px;margin-top:24px;flex-wrap:wrap;">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="../index.php" target="_blank" rel="noopener" class="btn btn-outline">Lihat Website</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-panel">
            <div class="admin-panel-head">
                <span class="admin-panel-title">Gambar Saat Ini</span>
            </div>
            <div class="admin-panel-body">
                <?php if ($image_exists): ?>
                <img src="../<?= htmlspecialchars($data['image'], ENT_QUOTES, 'UTF-8') ?>"
                     alt="Gambar pop-up saat ini"
                     style="display:block;width:100%;max-height:320px;object-fit:cover;border-radius:6px;">
                <?php else: ?>
                <p style="color:var(--muted);font-size:14px;">Belum ada gambar. Unggah gambar untuk ditampilkan pada pop-up.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</form>

<?php require_once "../includes/admin_footer.php"; ?>

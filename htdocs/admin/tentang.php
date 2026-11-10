<?php
session_start();
require_once "../includes/auth.php";

$data_file = __DIR__ . '/../data/tentang.json';

// Buat file JSON default jika belum ada
if (!file_exists($data_file)) {
    $default = [
        "judul"        => "Tentang Tjap Daoen",
        "tagline"      => "Secangkir Cerita di Bondowoso",
        "narasi"       => "Cap Daoen bermula dari kecintaan mendalam pada kopi lokal Bondowoso dan keinginan untuk menciptakan ruang temu yang nyaman dan inspiratif bagi anak muda. Kami menyajikan kopi berkualitas dan suasana hangat untuk setiap cerita yang tercipta.",
        "nilai_utama"  => ["Biji Kopi Pilihan", "Ruang Nyaman & Tenang", "Pelayanan Ramah"],
        "foto_suasana" => ""
    ];
    file_put_contents($data_file, json_encode($default, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$data = json_decode(file_get_contents($data_file), true);

// ============================================================
// Handle POST — Simpan Perubahan Teks & Foto
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Aksi hapus foto
    if (isset($_POST['aksi_hapus_foto']) && $_POST['aksi_hapus_foto'] === '1') {
        if (!empty($data['foto_suasana']) && file_exists(__DIR__ . '/../assets/images/' . $data['foto_suasana'])) {
            @unlink(__DIR__ . '/../assets/images/' . $data['foto_suasana']);
        }
        $data['foto_suasana'] = '';
        file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $_SESSION['success'] = 'Foto suasana berhasil dihapus.';
        header("Location: tentang.php");
        exit;
    }

    $data['judul']   = trim($_POST['judul']   ?? '');
    $data['tagline'] = trim($_POST['tagline'] ?? '');
    $data['narasi']  = trim($_POST['narasi']  ?? '');

    // Nilai utama dari hidden field CSV
    $nilai_raw = trim($_POST['nilai_utama_hidden'] ?? '');
    if ($nilai_raw !== '') {
        $items = array_map('trim', explode(',', $nilai_raw));
        $data['nilai_utama'] = array_values(array_filter($items, fn($v) => $v !== ''));
    } else {
        $data['nilai_utama'] = [];
    }

    // Upload Foto Baru jika ada
    if (isset($_FILES['foto_suasana']) && $_FILES['foto_suasana']['error'] === UPLOAD_ERR_OK) {
        $file     = $_FILES['foto_suasana'];
        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed  = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $max_size = 10 * 1024 * 1024; // 10MB

        if (!in_array($ext, $allowed)) {
            $_SESSION['error'] = 'Format foto tidak didukung. Harap gunakan format JPG, JPEG, PNG, atau WebP.';
        } elseif ($file['size'] > $max_size) {
            $_SESSION['error'] = 'Ukuran file terlalu besar (maksimal 10MB).';
        } else {
            // Hapus file foto lama jika ada
            if (!empty($data['foto_suasana']) && file_exists(__DIR__ . '/../assets/images/' . $data['foto_suasana'])) {
                @unlink(__DIR__ . '/../assets/images/' . $data['foto_suasana']);
            }

            $new_filename = 'tentang_' . time() . '.' . $ext;
            $destination  = __DIR__ . '/../assets/images/' . $new_filename;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $data['foto_suasana'] = $new_filename;
            } else {
                $_SESSION['error'] = 'Gagal menyimpan file foto ke folder assets/images/. Periksa izin folder.';
            }
        }
    } elseif (isset($_FILES['foto_suasana']) && $_FILES['foto_suasana']['error'] !== UPLOAD_ERR_NO_FILE) {
        $err_code = $_FILES['foto_suasana']['error'];
        if ($err_code === UPLOAD_ERR_INI_SIZE || $err_code === UPLOAD_ERR_FORM_SIZE) {
            $_SESSION['error'] = 'File foto melebihi batas ukuran upload PHP.';
        } else {
            $_SESSION['error'] = 'Terjadi kesalahan saat mengunggah file (Kode: ' . $err_code . ').';
        }
    }

    // Tulis ke JSON
    $save_result = file_put_contents(
        $data_file,
        json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );

    if ($save_result !== false && empty($_SESSION['error'])) {
        $_SESSION['success'] = 'Perubahan berhasil disimpan! Konten halaman Tentang Kami (tentang.php) telah diperbarui.';
    }

    header("Location: tentang.php");
    exit;
}

$admin_page_title = 'Tentang & Cerita Kafe';
$active_admin     = 'tentang';
require_once "../includes/admin_sidebar.php";

$foto_nama = $data['foto_suasana'] ?? '';
$foto_ada  = $foto_nama && file_exists(__DIR__ . '/../assets/images/' . $foto_nama);
$foto_url  = $foto_ada ? '../assets/images/' . htmlspecialchars($foto_nama) : '';
?>

<!-- Notifikasi Flash -->
<?php if (!empty($_SESSION['success'])): ?>
<div class="admin-flash admin-flash-success" style="display:flex;align-items:center;justify-content:space-between;">
    <span>✓ <?= htmlspecialchars($_SESSION['success']) ?></span>
    <a href="../tentang.php" target="_blank" style="color:#4caf50;font-weight:600;text-decoration:underline;font-size:13px;">Lihat Halaman Publik →</a>
</div>
<?php unset($_SESSION['success']); endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
<div class="admin-flash admin-flash-error">
    <span>⚠ <?= htmlspecialchars($_SESSION['error']) ?></span>
</div>
<?php unset($_SESSION['error']); endif; ?>

<!-- Header Halaman -->
<div class="admin-page-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
    <div>
        <h1 class="admin-page-title">Kelola Tentang &amp; Cerita Kafe</h1>
        <p class="admin-page-desc">Atur teks profil kafe, narasi, nilai utama, dan foto suasana yang tampil di halaman <strong>Tentang Kami (tentang.php)</strong></p>
    </div>
    <a href="../tentang.php" target="_blank" class="btn btn-outline" style="font-size:14px;padding:8px 16px;">
        ↗ Buka Halaman Asli
    </a>
</div>

<!-- Form Utama -->
<form method="POST" enctype="multipart/form-data" id="tentang-form">

<div class="admin-cols">

    <!-- ========================================================
         KOLOM KIRI: FORM PENGATURAN TEKS
         ======================================================== -->
    <div style="display:flex;flex-direction:column;gap:20px;">

        <div class="admin-panel">
            <div class="admin-panel-head">
                <span class="admin-panel-title">Profil &amp; Narasi Kafe</span>
            </div>
            <div class="admin-panel-body">

                <!-- Judul Bagian -->
                <div class="field-group">
                    <label class="field-label" for="f-judul">Judul Bagian</label>
                    <input class="field-input" type="text" id="f-judul" name="judul"
                           value="<?= htmlspecialchars($data['judul'] ?? '') ?>"
                           placeholder="Contoh: Tentang Tjap Daoen" required>
                    <p style="font-size:12px;color:var(--muted);margin-top:6px;">Judul utama yang tampil paling atas di halaman Tentang Kami.</p>
                </div>

                <!-- Tagline Kafe -->
                <div class="field-group">
                    <label class="field-label" for="f-tagline">Tagline Kafe</label>
                    <input class="field-input" type="text" id="f-tagline" name="tagline"
                           value="<?= htmlspecialchars($data['tagline'] ?? '') ?>"
                           placeholder="Contoh: Secangkir Cerita di Bondowoso">
                    <p style="font-size:12px;color:var(--muted);margin-top:6px;">Slogan atau kalimat pengantar di bawah judul.</p>
                </div>

                <!-- Narasi Cerita Kafe -->
                <div class="field-group">
                    <label class="field-label" for="f-narasi">Narasi Cerita Kafe</label>
                    <textarea class="field-input" id="f-narasi" name="narasi" rows="7"
                              style="min-height:160px;line-height:1.6;"
                              placeholder="Tuliskan cerita, latar belakang, dan filosofi kafe..."><?= htmlspecialchars($data['narasi'] ?? '') ?></textarea>
                    <p style="font-size:12px;color:var(--muted);margin-top:6px;">Cerita lengkap tentang perjalanan dan dedikasi kafe.</p>
                </div>

                <!-- Nilai Utama Tags -->
                <div class="field-group">
                    <label class="field-label">Nilai Utama Kafe</label>
                    <div class="values-tags" id="nilai-container" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px;">
                        <?php foreach ($data['nilai_utama'] ?? [] as $nilai_item): ?>
                        <span class="value-tag" data-value="<?= htmlspecialchars($nilai_item) ?>"
                              style="background:var(--green);color:#fff;padding:6px 14px;border-radius:20px;font-size:13px;font-weight:500;display:inline-flex;align-items:center;gap:8px;">
                            <?= htmlspecialchars($nilai_item) ?>
                            <button type="button" class="value-tag-remove" onclick="removeNilai(this)"
                                    style="background:none;border:none;color:rgba(255,255,255,0.8);cursor:pointer;font-size:16px;line-height:1;" title="Hapus">×</button>
                        </span>
                        <?php endforeach; ?>
                    </div>

                    <div style="display:flex;gap:8px;">
                        <input class="field-input" type="text" id="nilai-input"
                               placeholder="Ketik nilai baru (misal: Biji Kopi Pilihan)..."
                               style="max-width:320px;">
                        <button type="button" class="btn btn-outline btn-sm" onclick="addNilai()">+ Tambah</button>
                    </div>
                    <p style="font-size:12px;color:var(--muted);margin-top:6px;">Tekan Enter atau klik '+ Tambah' untuk menambahkan ke daftar.</p>

                    <input type="hidden" name="nilai_utama_hidden" id="nilai-hidden"
                           value="<?= htmlspecialchars(implode(',', $data['nilai_utama'] ?? [])) ?>">
                </div>

                <!-- Tombol Aksi -->
                <div style="display:flex;gap:12px;margin-top:24px;padding-top:16px;border-top:1px solid var(--border);">
                    <button type="submit" class="btn btn-primary" style="padding:12px 28px;">Simpan Perubahan</button>
                    <a href="index.php" class="btn btn-outline">Batal</a>
                </div>

            </div>
        </div>

    </div>

    <!-- ========================================================
         KOLOM KANAN: FOTO SUASANA & PRATINJAU LANGSUNG
         ======================================================== -->
    <div style="display:flex;flex-direction:column;gap:20px;">

        <!-- Panel Foto Suasana -->
        <div class="admin-panel">
            <div class="admin-panel-head">
                <span class="admin-panel-title">Foto Suasana Utama</span>
                <?php if ($foto_ada): ?>
                <span style="font-size:11px;color:var(--green);background:rgba(61,139,55,0.15);padding:3px 8px;border-radius:4px;font-weight:600;">✓ Foto Aktif</span>
                <?php endif; ?>
            </div>
            <div class="admin-panel-body" style="padding-top:16px;">

                <!-- Kotak display foto -->
                <div class="photo-upload-box" id="foto-box" style="border-radius:8px;overflow:hidden;border:1px solid var(--border);background:var(--dark);height:220px;position:relative;">
                    <?php if ($foto_ada): ?>
                    <img id="foto-img"
                         src="<?= $foto_url ?>"
                         alt="Foto Suasana Kafe"
                         style="width:100%;height:100%;object-fit:cover;display:block;">
                    <?php else: ?>
                    <div id="foto-placeholder" class="photo-upload-empty" style="height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#666;gap:12px;">
                        <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <path d="M21 15l-5-5L5 21"/>
                        </svg>
                        <span style="font-size:13px;color:var(--muted);">Belum ada foto suasana</span>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Info file baru dipilih -->
                <div id="file-info-badge" style="display:none;background:rgba(61,139,55,0.15);border:1px solid rgba(61,139,55,0.4);border-radius:6px;padding:8px 12px;margin-top:12px;font-size:12px;color:var(--white);">
                    📷 <span id="file-info-text">File baru dipilih</span>
                </div>

                <!-- Tombol Ganti Foto -->
                <div style="display:flex;gap:8px;margin-top:12px;">
                    <label class="btn btn-outline" style="flex:1;display:inline-flex;cursor:pointer;justify-content:center;font-size:14px;padding:10px;">
                        Ganti Foto
                        <input type="file" name="foto_suasana" id="foto-input"
                               accept=".jpg,.jpeg,.png,.webp,.gif"
                               style="display:none;">
                    </label>

                    <?php if ($foto_ada): ?>
                    <button type="submit" name="aksi_hapus_foto" value="1" class="btn btn-del btn-sm"
                            onclick="return confirm('Yakin ingin menghapus foto suasana ini?')"
                            style="padding:10px 14px;" title="Hapus foto saat ini">
                        Hapus
                    </button>
                    <?php endif; ?>
                </div>

                <p style="font-size:12px;color:var(--muted);margin-top:8px;">
                    Format: JPG, PNG, WebP (maks. 10MB). Klik <strong>Simpan Perubahan</strong> setelah memilih foto.
                </p>

            </div>
        </div>

        <!-- ====================================================
             PRATINJAU LANGSUNG: HALAMAN TENTANG KAMI (tentang.php)
             ==================================================== -->
        <div class="admin-panel">
            <div class="admin-panel-head" style="background:#181818;">
                <span class="admin-panel-title" style="display:flex;align-items:center;gap:6px;">
                    <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:var(--green);"></span>
                    Pratinjau Halaman: Tentang Kami
                </span>
                <span style="font-size:11px;color:var(--muted);">tentang.php</span>
            </div>

            <div class="admin-panel-body" style="padding:0;overflow:hidden;background:#151515;">

                <!-- Mockup frame browser -->
                <div style="background:#111;padding:8px 12px;border-bottom:1px solid #282828;display:flex;align-items:center;gap:8px;">
                    <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#ff5f56;"></span>
                    <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#ffbd2e;"></span>
                    <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#27c93f;"></span>
                    <span style="margin-left:8px;font-size:10px;color:#777;background:#202020;padding:2px 10px;border-radius:4px;font-family:monospace;">
                        localhost/Tjap daoen/tentang.php
                    </span>
                </div>

                <!-- Mini Navbar -->
                <div style="background:#1c1c1c;padding:10px 16px;border-bottom:1px solid #282828;display:flex;align-items:center;justify-content:space-between;">
                    <div style="font-size:11px;font-weight:700;color:var(--white);letter-spacing:0.5px;">TJAP DAOEN</div>
                    <div style="display:flex;gap:12px;font-size:10px;">
                        <span style="color:#666;">Beranda</span>
                        <span style="color:var(--green);font-weight:600;border-bottom:1px solid var(--green);">Tentang Kami</span>
                        <span style="color:#666;">Menu</span>
                        <span style="color:#666;">Kontak</span>
                    </div>
                </div>

                <!-- Mini Hero Section dari tentang.php -->
                <div style="padding:18px 16px;background:var(--black);">
                    <div style="display:grid;grid-template-columns:1.2fr 0.8fr;gap:14px;align-items:start;">

                        <!-- Kiri: Teks -->
                        <div>
                            <span style="font-size:9px;font-weight:700;color:var(--green);letter-spacing:1px;text-transform:uppercase;display:block;margin-bottom:4px;">
                                TENTANG KAMI
                            </span>

                            <div id="prev-judul" style="font-size:13px;font-weight:700;color:var(--white);line-height:1.25;margin-bottom:6px;">
                                <?= htmlspecialchars($data['judul'] ?? 'Tentang Tjap Daoen') ?>
                            </div>

                            <div id="prev-tagline" style="font-size:10px;font-weight:600;color:var(--white);line-height:1.3;margin-bottom:8px;">
                                <?= htmlspecialchars($data['tagline'] ?? 'Secangkir Cerita di Bondowoso') ?>
                            </div>

                            <div id="prev-narasi" style="font-size:9.5px;color:var(--muted);line-height:1.5;margin-bottom:12px;
                                                        display:-webkit-box;-webkit-line-clamp:4;-webkit-box-orient:vertical;overflow:hidden;">
                                <?= htmlspecialchars($data['narasi'] ?? '') ?>
                            </div>

                            <!-- Nilai Utama Badges di Preview -->
                            <div id="prev-nilai" style="display:flex;flex-wrap:wrap;gap:4px;">
                                <?php foreach ($data['nilai_utama'] ?? [] as $n_item): ?>
                                <span style="background:rgba(61,139,55,0.2);border:1px solid rgba(61,139,55,0.45);
                                             color:var(--green);padding:2px 8px;border-radius:12px;font-size:8.5px;font-weight:600;">
                                    <?= htmlspecialchars($n_item) ?>
                                </span>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Kanan: Foto Suasana di Preview -->
                        <div id="prev-foto-wrap" style="height:120px;border-radius:6px;overflow:hidden;border:1px solid #333;background:#202020;">
                            <?php if ($foto_ada): ?>
                            <img id="prev-foto" src="<?= $foto_url ?>"
                                 alt="Preview foto suasana"
                                 style="width:100%;height:100%;object-fit:cover;display:block;">
                            <?php else: ?>
                            <div id="prev-foto-empty" style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#555;font-size:10px;text-align:center;padding:8px;">
                                Foto suasana
                            </div>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>

                <!-- Footer Mini Preview -->
                <div style="background:#161616;padding:8px 16px;border-top:1px solid #252525;display:flex;align-items:center;justify-content:space-between;">
                    <span style="font-size:10px;color:#777;">● Pratinjau langsung aktif</span>
                    <a href="../tentang.php" target="_blank" style="font-size:10px;color:var(--green);text-decoration:none;">Buka Halaman Asli →</a>
                </div>

            </div>
        </div>

    </div>

</div>
</form>

<!-- ============================================================
     JAVASCRIPT: REAL-TIME LIVE PREVIEW & DYNAMIC TAGS
     ============================================================ -->
<script>
// ---- Live Update: Judul, Tagline, Narasi ----
const inputJudul   = document.getElementById('f-judul');
const inputTagline = document.getElementById('f-tagline');
const inputNarasi  = document.getElementById('f-narasi');

const prevJudul   = document.getElementById('prev-judul');
const prevTagline = document.getElementById('prev-tagline');
const prevNarasi  = document.getElementById('prev-narasi');

if (inputJudul && prevJudul) {
    inputJudul.addEventListener('input', () => {
        prevJudul.textContent = inputJudul.value.trim() || 'Tentang Tjap Daoen';
    });
}

if (inputTagline && prevTagline) {
    inputTagline.addEventListener('input', () => {
        prevTagline.textContent = inputTagline.value.trim() || 'Secangkir Cerita di Bondowoso';
    });
}

if (inputNarasi && prevNarasi) {
    inputNarasi.addEventListener('input', () => {
        prevNarasi.textContent = inputNarasi.value.trim() || 'Narasi cerita kafe...';
    });
}

// ---- Nilai Utama: Tambah & Hapus Tag ----
function updateHidden() {
    const tags = document.querySelectorAll('#nilai-container .value-tag');
    const vals = Array.from(tags).map(t => t.dataset.value);
    document.getElementById('nilai-hidden').value = vals.join(',');
    renderPreviewNilai(vals);
}

function addNilai() {
    const input = document.getElementById('nilai-input');
    const val   = input.value.trim();
    if (!val) return;

    // Hindari duplikat
    const currentTags = Array.from(document.querySelectorAll('#nilai-container .value-tag')).map(t => t.dataset.value);
    if (currentTags.includes(val)) {
        input.value = '';
        return;
    }

    const container = document.getElementById('nilai-container');
    const span = document.createElement('span');
    span.className   = 'value-tag';
    span.dataset.value = val;
    span.style.cssText = 'background:var(--green);color:#fff;padding:6px 14px;border-radius:20px;font-size:13px;font-weight:500;display:inline-flex;align-items:center;gap:8px;';
    span.innerHTML   = `${escapeHtml(val)} <button type="button" class="value-tag-remove" onclick="removeNilai(this)" style="background:none;border:none;color:rgba(255,255,255,0.8);cursor:pointer;font-size:16px;line-height:1;" title="Hapus">×</button>`;
    container.appendChild(span);

    input.value = '';
    updateHidden();
}

function removeNilai(btn) {
    btn.closest('.value-tag').remove();
    updateHidden();
}

document.getElementById('nilai-input')?.addEventListener('keydown', e => {
    if (e.key === 'Enter') {
        e.preventDefault();
        addNilai();
    }
});

function renderPreviewNilai(vals) {
    const wrap = document.getElementById('prev-nilai');
    if (!wrap) return;
    wrap.innerHTML = vals.map(v =>
        `<span style="background:rgba(61,139,55,0.2);border:1px solid rgba(61,139,55,0.45);color:var(--green);padding:2px 8px;border-radius:12px;font-size:8.5px;font-weight:600;">${escapeHtml(v)}</span>`
    ).join('');
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Pastikan tag yang sedang diketik otomatis ditambahkan saat submit
document.getElementById('tentang-form')?.addEventListener('submit', () => {
    const pendingInput = document.getElementById('nilai-input');
    if (pendingInput && pendingInput.value.trim()) {
        addNilai();
    }
});

// ---- Foto Preview Real-Time Saat File Dipilih ----
const fotoInput = document.getElementById('foto-input');
if (fotoInput) {
    fotoInput.addEventListener('change', function() {
        if (!this.files || !this.files[0]) return;
        const file = this.files[0];
        const url  = URL.createObjectURL(file);

        // Update kotak foto di kolom kanan
        const box = document.getElementById('foto-box');
        box.innerHTML = `<img id="foto-img" src="${url}" style="width:100%;height:100%;object-fit:cover;display:block;">`;

        // Update preview foto di miniatur tentang.php
        const prevWrap = document.getElementById('prev-foto-wrap');
        prevWrap.innerHTML = `<img id="prev-foto" src="${url}" style="width:100%;height:100%;object-fit:cover;display:block;">`;

        // Tampilkan badge info file terpilih
        const badge = document.getElementById('file-info-badge');
        const badgeText = document.getElementById('file-info-text');
        const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
        badgeText.textContent = `File baru: "${file.name}" (${sizeMB} MB) — Siap diupload! Klik "Simpan Perubahan" untuk menyimpan.`;
        badge.style.display = 'block';
    });
}
</script>

<?php require_once "../includes/admin_footer.php"; ?>

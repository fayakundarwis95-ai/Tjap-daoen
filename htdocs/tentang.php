<?php
// ============================================================
// tentang.php — Halaman Publik Tentang Kami (Tjap Daoen)
// Membaca data dinamis dari data/tentang.json yang dikelola admin
// ============================================================
$page_title = 'Tentang Kami';
$active_nav = 'tentang';
require_once __DIR__ . '/includes/maps.php';

// Baca data tentang dari JSON
$tentang_file = __DIR__ . '/data/tentang.json';
$data_tentang = file_exists($tentang_file)
    ? json_decode(file_get_contents($tentang_file), true)
    : [];

$judul   = !empty($data_tentang['judul'])   ? $data_tentang['judul']   : 'Tentang Tjap Daoen';
$tagline = !empty($data_tentang['tagline']) ? $data_tentang['tagline'] : 'Secangkir Cerita di Bondowoso';
$narasi  = !empty($data_tentang['narasi'])  ? $data_tentang['narasi']  : 'Cap Daoen bermula dari kecintaan mendalam pada kopi lokal Bondowoso dan keinginan untuk menciptakan ruang temu yang nyaman dan inspiratif bagi anak muda. Kami menyajikan kopi berkualitas dan suasana hangat untuk setiap cerita yang tercipta.';
$nilai   = !empty($data_tentang['nilai_utama']) ? $data_tentang['nilai_utama'] : ['Biji Kopi Pilihan', 'Ruang Nyaman & Tenang', 'Pelayanan Ramah'];
$foto    = $data_tentang['foto_suasana'] ?? '';

// Cek keberadaan file foto suasana
$foto_src = '';
if ($foto && file_exists(__DIR__ . '/assets/images/' . $foto)) {
    $foto_src = 'assets/images/' . $foto;
} elseif (file_exists(__DIR__ . '/assets/images/about.jpg')) {
    $foto_src = 'assets/images/about.jpg';
}

// Baca fasilitas & lokasi
$fasilitas_file = __DIR__ . '/data/fasilitas.json';
$fasilitas_data = file_exists($fasilitas_file)
    ? json_decode(file_get_contents($fasilitas_file), true)
    : [];
$fasilitas_aktif = array_filter($fasilitas_data, fn($f) => $f['aktif'] ?? true);

$lokasi_file = __DIR__ . '/data/lokasi.json';
$lokasi_data = file_exists($lokasi_file)
    ? json_decode(file_get_contents($lokasi_file), true)
    : [];

require_once "includes/header.php";

$icon_svg = [
    'wifi'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12.55a11 11 0 0114.08 0"/><path d="M1.42 9a16 16 0 0121.16 0"/><path d="M8.53 16.11a6 6 0 016.95 0"/><circle cx="12" cy="20" r="1" fill="currentColor"/></svg>',
    'outlet' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="7" y="2" width="10" height="20" rx="2"/><path d="M12 18h.01"/></svg>',
    'area'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
    'parkir' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 17V7h4a3 3 0 010 6H9"/></svg>',
    'ac'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="7" width="20" height="8" rx="2"/><path d="M9 19l-2 2m8-2l2 2M9 19h6"/></svg>',
    'musik'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>',
    'toilet' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M7 22V12M17 22V12M3 12h18M3 12V9a9 9 0 0118 0v3"/></svg>',
    'smoking'=> '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 12h2a2 2 0 012 2v1a2 2 0 01-2 2H2a2 2 0 01-2-2v-1a2 2 0 012-2h2m14-6v3m-3-6v6"/></svg>',
];
?>

<!-- ===== ABOUT HERO SECTION ===== -->
<section class="about-section section" id="tentang-hero">
    <div class="container">
        <div class="about-inner">

            <!-- Kiri: Konten Teks -->
            <div class="about-content">
                <span class="section-label">Tentang Kami</span>
                <h1 class="section-title">
                    <?= nl2br(htmlspecialchars($judul)) ?>
                </h1>
                <?php if (!empty($tagline)): ?>
                <p style="font-size:18px;font-weight:600;color:var(--white);margin-bottom:16px;line-height:1.4;">
                    <?= nl2br(htmlspecialchars($tagline)) ?>
                </p>
                <?php endif; ?>
                <p class="section-desc" style="color:var(--muted);line-height:1.8;font-size:16px;">
                    <?= nl2br(htmlspecialchars($narasi)) ?>
                </p>

                <!-- Nilai Utama Badges -->
                <?php if (!empty($nilai)): ?>
                <div class="value-list-wrap">
                    <div class="value-list-title">Nilai Utama Kami</div>
                    <div class="value-list">
                        <?php foreach ($nilai as $n): ?>
                        <span class="value-tag">
                            <?= htmlspecialchars($n) ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Kanan: Foto Suasana Kafe -->
            <div class="about-image">
                <?php if ($foto_src): ?>
                <img src="<?= htmlspecialchars($foto_src) ?>"
                     alt="<?= htmlspecialchars($judul) ?>"
                     loading="eager"
                     style="width:100%;height:460px;object-fit:cover;border-radius:12px;display:block;border:1px solid var(--border);">
                <?php else: ?>
                <div class="image-placeholder about-page-placeholder">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <path d="M21 15l-5-5L5 21"/>
                    </svg>
                    <span>Foto suasana kafe</span>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>

<!-- ===== FASILITAS KAFE ===== -->
<section class="facilities-section section" style="background:var(--black);" id="fasilitas">
    <div class="container">

        <div class="section-header">
            <span class="section-label">Fasilitas</span>
            <h2 class="section-title">Kenyamanan Untuk Anda</h2>
            <p class="section-desc" style="color:var(--muted);font-size:15px;margin-top:8px;">Berbagai fasilitas yang kami sediakan untuk pengalaman ngopi terbaik.</p>
        </div>

        <div class="facilities-grid">
            <?php if (!empty($fasilitas_aktif)): ?>
                <?php foreach ($fasilitas_aktif as $fas): ?>
                <div class="facility-item">
                    <div class="facility-icon">
                        <?= $icon_svg[$fas['icon']] ?? $icon_svg['wifi'] ?>
                    </div>
                    <div class="facility-name"><?= htmlspecialchars($fas['nama']) ?></div>
                    <div class="facility-desc"><?= htmlspecialchars($fas['deskripsi']) ?></div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="facility-item">
                    <div class="facility-icon"><?= $icon_svg['wifi'] ?></div>
                    <div class="facility-name">Wi-Fi Cepat &amp; Stabil</div>
                    <div class="facility-desc">Mendukung tugas kuliah dan kerja daring</div>
                </div>
                <div class="facility-item">
                    <div class="facility-icon"><?= $icon_svg['outlet'] ?></div>
                    <div class="facility-name">Stopkontak di Setiap Meja</div>
                    <div class="facility-desc">Daya gawai Anda selalu terjaga</div>
                </div>
                <div class="facility-item">
                    <div class="facility-icon"><?= $icon_svg['area'] ?></div>
                    <div class="facility-name">Area Terbuka &amp; Tertutup</div>
                    <div class="facility-desc">Pilihan ruangan ber-AC dan area santai luar</div>
                </div>
                <div class="facility-item">
                    <div class="facility-icon"><?= $icon_svg['parkir'] ?></div>
                    <div class="facility-name">Area Parkir</div>
                    <div class="facility-desc">Tempat parkir motor dan mobil teratur</div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Lokasi & Jam Operasional -->
        <div class="location-row" style="margin-top:48px;">
            <div class="location-info">
                <h3>Lokasi &amp; Jam Operasional</h3>
                <?php if ($lokasi_data): ?>
                <div class="location-hours">
                    Senin – Jumat: <?= htmlspecialchars($lokasi_data['jam_senin_jumat_buka'] ?? '10.00') ?> – <?= htmlspecialchars($lokasi_data['jam_senin_jumat_tutup'] ?? '23.00') ?> WIB
                </div>
                <div class="location-hours" style="margin-top:8px;">
                    Sabtu – Minggu: <?= htmlspecialchars($lokasi_data['jam_sabtu_minggu_buka'] ?? '09.00') ?> – <?= htmlspecialchars($lokasi_data['jam_sabtu_minggu_tutup'] ?? '24.00') ?> WIB
                </div>
                <?php else: ?>
                <div class="location-hours">Setiap Hari: 10.00 – 23.00 WIB</div>
                <?php endif; ?>

                <div class="location-address" style="margin-top:16px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s-8-4.5-8-11.8A8 8 0 0112 2a8 8 0 018 8.2c0 7.3-8 11.8-8 11.8z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <span>
                        <?= nl2br(htmlspecialchars($lokasi_data['alamat'] ?? 'Jl. Raya Bondowoso, Dabasah, Kab. Bondowoso, Jawa Timur')) ?>
                    </span>
                </div>
            </div>

            <div class="map-embed">
                <iframe
                    src="<?= htmlspecialchars(maps_embed_url($lokasi_data['maps_embed'] ?? '', $lokasi_data['alamat'] ?? '')) ?>"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Lokasi Tjap Daoen di Bondowoso">
                </iframe>
            </div>
        </div>

    </div>
</section>

<?php require_once "includes/footer.php"; ?>
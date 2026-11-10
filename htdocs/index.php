<?php
require_once "config/database.php";
require_once "includes/maps.php";

$lokasi_data = json_decode(file_get_contents(__DIR__ . '/data/lokasi.json'), true) ?? [];
$maps_src = maps_embed_url($lokasi_data['maps_embed'] ?? '', $lokasi_data['alamat'] ?? '');

// Ambil 8 produk terbaru untuk preview di homepage
$query = mysqli_query(
    $conn,
    "SELECT * FROM produk ORDER BY id DESC LIMIT 8"
);

$page_title = 'Beranda';
$active_nav = 'beranda';
require_once "includes/header.php";
?>

<!-- ===== HERO SECTION ===== -->
<section class="hero" id="beranda">
    <div class="hero-inner">

        <div class="hero-content">
            <span class="hero-eyebrow" data-i18n="hero_eyebrow">Coffee Bar — Bondowoso</span>
            <h1>
                Secangkir Cerita<br>
                <span>Tjap Daoen</span>
            </h1>
            <p data-i18n="hero_desc">
                Temukan kehangatan dan aroma kopi nusantara terbaik di Bondowoso. Cita rasa otentik di dalam setiap cangkir.
            </p>
            <a href="produk.php" class="btn btn-primary" data-i18n="hero_cta">
                Lihat Menu
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="hero-image">
            <img src="assets/images/hero.jpg"
                 alt="Tampak depan kafe Tjap Daoen Bondowoso"
                 loading="eager"
                 style="width:100%;height:100%;object-fit:cover;border-radius:8px;display:block;"
                 onerror="this.outerHTML='<div class=\'image-placeholder hero-placeholder\'><svg width=\'48\' height=\'48\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'1\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\'/><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'/><path d=\'M21 15l-5-5L5 21\'/></svg><span class=\'placeholder-label\'>Upload hero.jpg</span></div>'">
        </div>

    </div>
</section>

<!-- ===== ABOUT TEASER ===== -->
<section class="about-section section" id="tentang">
    <div class="container">
        <div class="about-inner">

            <div class="about-image">
                <?php
                $aboutImg = '';
                if (file_exists('assets/images/about.jpg')) {
                    $aboutImg = 'assets/images/about.jpg';
                } else {
                    $tjData = file_exists('data/tentang.json') ? json_decode(file_get_contents('data/tentang.json'), true) : [];
                    if (!empty($tjData['foto_suasana']) && file_exists('assets/images/' . $tjData['foto_suasana'])) {
                        $aboutImg = 'assets/images/' . $tjData['foto_suasana'];
                    }
                }
                if ($aboutImg):
                ?>
                <img src="<?= htmlspecialchars($aboutImg) ?>"
                     alt="Interior kafe Tjap Daoen"
                     loading="lazy"
                     style="width:100%;height:100%;object-fit:cover;border-radius:8px;">
                <?php else: ?>
                <div class="image-placeholder about-placeholder">
                    <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <path d="M21 15l-5-5L5 21"/>
                    </svg>
                    <span class="placeholder-label">Upload foto di admin → Tentang &amp; Cerita Kafe</span>
                </div>
                <?php endif; ?>
            </div>

            <div class="about-content">
                <span class="section-label" data-i18n="about_label">Tentang Kami</span>
                <h2 class="section-title" data-i18n="about_title">
                    TJAP DAOEN,<br>Lebih dari Sekadar Tempat Ngopi
                </h2>
                <p class="section-desc" data-i18n="about_desc">
                    TJAP DAOEN adalah kedai kopi yang hadir dengan konsep sederhana namun penuh makna. Kami percaya bahwa secangkir kopi bukan hanya soal rasa, tetapi juga tentang suasana, cerita, dan kebersamaan.
                </p>
                <a href="tentang.php" class="btn btn-outline" data-i18n="about_cta">
                    Kenali Kami
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>

        </div>
    </div>
</section>

<!-- ===== MENU PREVIEW ===== -->
<section class="menu-section section" id="menu">
    <div class="container">

        <div class="section-header">
            <span class="section-label" data-i18n="menu_label">Menu Kami</span>
            <h2 class="section-title" data-i18n="menu_title">Daftar Menu</h2>
            <p class="section-desc" data-i18n="menu_desc">Pilihan kopi dan makanan terbaik yang siap menemani harimu.</p>
        </div>

        <div class="menu-grid">
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
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
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

        <?php
        $total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM produk"))['c'];
        if ($total > 8):
        ?>
        <div style="margin-top:40px;text-align:left;">
            <a href="produk.php" class="btn btn-outline" data-i18n="menu_view_all">
                Lihat Semua Menu
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
        <?php endif; ?>

    </div>
</section>

<!-- ===== TESTIMONIALS ===== -->
<?php
$testimonials = [
    [
        'name' => 'Thareq Prima Harianto',
        'initials' => 'TP',
        'profile' => 'Local Guide · 35 ulasan · 211 foto',
        'details' => 'Rp 25–50 rb',
        'review' => 'Salah satu kafe nyaman di deretan Jalan Pelita, Bondowoso. Tempatnya tepat terletak di pinggiran ruas Jalan Pelita dan menyatu dengan laundry. Tempat ini menyediakan berbagai makanan ringan, minuman manis, dan olahan kopi. Suasana di tempat ini nyaman. Dekorasi yang dominan kayu menambah kenyamanan pengunjung pada saat berkunjung.',
    ],
    [
        'name' => 'Rossita Dian Sunanto',
        'initials' => 'RS',
        'profile' => 'Local Guide · 18 ulasan · 27 foto',
        'details' => 'Makan di tempat · Makan malam',
        'review' => 'Pelayanan sangat baik dan ramah. Makanan dan minumannya oke. Tempatnya juga nyaman hanya saja kurang luas dan colokannya sedikit.',
    ],
    [
        'name' => 'Haris Nurtanios',
        'initials' => 'HN',
        'profile' => 'Local Guide · 18 ulasan · 470 foto',
        'details' => 'Makan siang · Rp 1–25.000',
        'review' => 'Internet kenceng. Suasana laki banget khususnya buat pekerja lapangan seperti kami.',
    ],
    [
        'name' => 'Azizah',
        'initials' => 'A',
        'profile' => '1 ulasan · 3 foto',
        'details' => 'Makan di tempat · Makan malam · Rp 1–25.000',
        'review' => 'Tempatnya oke buat nongkrong dan ngerjain tugas.',
    ],
    [
        'name' => 'alief dara',
        'initials' => 'AD',
        'profile' => 'Local Guide · 47 ulasan · 25 foto',
        'details' => '',
        'review' => 'Es kopinya enak, lebih enak dari cafe-cafe di sekitarnya. Harga juga bersahabat. Dan yang penting lagi isi banyak, bukan es cubenya yang banyak. Coklat lattenya juga enak. Cuma tempat parkir susah karena di daerah tersebut jalannya sempit. Tapi masih bisa parkir kok.',
    ],
];
?>
<section class="testimonials-section section" id="testimoni" aria-labelledby="testimonials-title">
    <div class="container">
        <div class="testimonials-heading">
            <span class="testimonials-watermark" aria-hidden="true">Cerita</span>
            <span class="section-label">Cerita dari Tjap Daoen</span>
            <h2 class="section-title" id="testimonials-title">Testimoni</h2>
            <p class="section-desc">Ulasan asli dari pengunjung Tjap Daoen.</p>
        </div>

        <div class="testimonials-carousel" role="region" aria-label="Testimoni pengunjung">
            <button class="testimonial-control testimonial-previous" type="button" aria-label="Lihat testimoni sebelumnya" disabled>
                <span aria-hidden="true">&lsaquo;</span>
            </button>
            <div class="testimonials-viewport" id="testimonials-viewport" tabindex="0">
                <div class="testimonials-track" role="list">
            <?php foreach ($testimonials as $testimonial): ?>
                <article class="testimonial-card" role="listitem">
                    <div class="testimonial-stars" role="img" aria-label="5 dari 5 bintang">★★★★★</div>
                    <blockquote><?= htmlspecialchars($testimonial['review'], ENT_QUOTES, 'UTF-8') ?></blockquote>
                    <?php if ($testimonial['details'] !== ''): ?>
                        <p class="testimonial-details"><?= htmlspecialchars($testimonial['details'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                    <div class="testimonial-author">
                        <span class="testimonial-avatar" aria-hidden="true"><?= htmlspecialchars($testimonial['initials'], ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="testimonial-author-info">
                            <strong><?= htmlspecialchars($testimonial['name'], ENT_QUOTES, 'UTF-8') ?></strong>
                            <small><?= htmlspecialchars($testimonial['profile'], ENT_QUOTES, 'UTF-8') ?></small>
                        </span>
                    </div>
                </article>
            <?php endforeach; ?>
                </div>
            </div>
            <button class="testimonial-control testimonial-next" type="button" aria-label="Lihat testimoni berikutnya">
                <span aria-hidden="true">&rsaquo;</span>
            </button>
        </div>
    </div>
</section>

<script>
(function() {
    const viewport = document.getElementById('testimonials-viewport');
    const previous = document.querySelector('.testimonial-previous');
    const next = document.querySelector('.testimonial-next');
    if (!viewport || !previous || !next) return;

    const updateControls = () => {
        previous.disabled = viewport.scrollLeft <= 1;
        next.disabled = viewport.scrollLeft + viewport.clientWidth >= viewport.scrollWidth - 1;
    };

    const scrollOneCard = (direction) => {
        const card = viewport.querySelector('.testimonial-card');
        if (!card) return;
        const gap = parseFloat(getComputedStyle(viewport.querySelector('.testimonials-track')).columnGap) || 0;
        viewport.scrollBy({ left: direction * (card.getBoundingClientRect().width + gap), behavior: 'smooth' });
    };

    previous.addEventListener('click', () => scrollOneCard(-1));
    next.addEventListener('click', () => scrollOneCard(1));
    viewport.addEventListener('scroll', updateControls, { passive: true });
    window.addEventListener('resize', updateControls);
    updateControls();
})();
</script>

<!-- ===== FACILITIES & LOCATION ===== -->
<section class="facilities-section section" id="fasilitas">
    <div class="container">

        <div class="section-header">
            <span class="section-label" data-i18n="facility_label">Fasilitas</span>
            <h2 class="section-title" data-i18n="facility_title">Fasilitas Kafe</h2>
        </div>

        <div class="facilities-grid">

            <div class="facility-item">
                <div class="facility-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M5 12.55a11 11 0 0114.08 0"/>
                        <path d="M1.42 9a16 16 0 0121.16 0"/>
                        <path d="M8.53 16.11a6 6 0 016.95 0"/>
                        <circle cx="12" cy="20" r="1" fill="currentColor"/>
                    </svg>
                </div>
                <div class="facility-name" data-i18n="facility_wifi">Wi-Fi Cepat</div>
                <div class="facility-desc" data-i18n="facility_wifi_desc">Koneksi internet stabil untuk bekerja atau bersantai</div>
            </div>

            <div class="facility-item">
                <div class="facility-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="7" y="2" width="10" height="20" rx="2"/>
                        <path d="M12 18h.01"/>
                    </svg>
                </div>
                <div class="facility-name" data-i18n="facility_outlet">Stopkontak Listrik</div>
                <div class="facility-desc" data-i18n="facility_outlet_desc">Tersedia di setiap meja untuk perangkatmu</div>
            </div>

            <div class="facility-item">
                <div class="facility-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </div>
                <div class="facility-name" data-i18n="facility_area">Area Terbuka & Tertutup</div>
                <div class="facility-desc" data-i18n="facility_area_desc">Pilih suasana indoor atau nikmati udara luar</div>
            </div>

        </div>

        <!-- Location -->
        <div class="location-row">
            <div class="location-info">
                <h3 data-i18n="location_title">Lokasi &amp; Jam Buka</h3>
                <div class="location-hours" data-i18n="location_hours">Setiap Hari: 10.00 – 23.00</div>
                <div class="location-address">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s-8-4.5-8-11.8A8 8 0 0112 2a8 8 0 018 8.2c0 7.3-8 11.8-8 11.8z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <span data-i18n="location_address">
                        Jl. Jale Caffe Odiq Adi Batan, No 138n,<br>
                        Bondowoso, Jebrin 5297
                    </span>
                </div>
            </div>

            <div class="map-embed">
                <iframe
                    src="<?= htmlspecialchars($maps_src) ?>"
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
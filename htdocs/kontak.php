<?php
require_once "includes/maps.php";
$lokasi_data = json_decode(file_get_contents(__DIR__ . '/data/lokasi.json'), true) ?? [];
$maps_src = maps_embed_url($lokasi_data['maps_embed'] ?? '', $lokasi_data['alamat'] ?? '');

$wa_number  = '628123456789'; // GANTI nomor WA kamu
$wa_message = rawurlencode('Halo Tjap Daoen! Saya ingin menghubungi kalian.');

$page_title = 'Kontak';
$active_nav = 'kontak';
require_once "includes/header.php";
?>

<section class="facilities-section section">
    <div class="container">

        <div class="section-header">
            <span class="section-label" data-i18n="kontak_label">Hubungi Kami</span>
            <h1 class="section-title" data-i18n="kontak_title">Kontak &amp; Lokasi</h1>
            <p class="section-desc" data-i18n="kontak_desc">Ada pertanyaan atau ingin reservasi? Kami siap membantu.</p>
        </div>

        <!-- Contact Cards -->
        <div class="facilities-grid" style="margin-bottom:64px;">

            <div class="facility-item">
                <div class="facility-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </div>
                <div class="facility-name">WhatsApp</div>
                <div class="facility-desc" style="margin-bottom:16px;">Respons cepat via WhatsApp</div>
                <a href="https://wa.me/<?= $wa_number ?>?text=<?= $wa_message ?>"
                   target="_blank" rel="noopener"
                   class="btn btn-primary" style="font-size:15px;padding:8px 16px;">
                    Chat Sekarang
                </a>
            </div>

            <div class="facility-item">
                <div class="facility-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                        <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/>
                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                    </svg>
                </div>
                <div class="facility-name">Instagram</div>
                <div class="facility-desc" style="margin-bottom:16px;">Follow kami untuk update menu terbaru</div>
                <a href="https://instagram.com/tjapdaoen"
                   target="_blank" rel="noopener"
                   class="btn btn-outline" style="font-size:15px;padding:8px 16px;">
                    @tjapdaoen
                </a>
            </div>

            <div class="facility-item">
                <div class="facility-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.72a4.85 4.85 0 01-1.01-.03z"/>
                    </svg>
                </div>
                <div class="facility-name">TikTok</div>
                <div class="facility-desc" style="margin-bottom:16px;">Tonton konten seru seputar kopi kami</div>
                <a href="https://tiktok.com/@tjapdaoen"
                   target="_blank" rel="noopener"
                   class="btn btn-outline" style="font-size:15px;padding:8px 16px;">
                    @tjapdaoen
                </a>
            </div>

        </div>

        <!-- Location -->
        <div class="location-row">
            <div class="location-info">
                <h2 style="font-size:28px;font-weight:700;color:var(--white);margin-bottom:24px;" data-i18n="location_title">
                    Lokasi &amp; Jam Buka
                </h2>
                <div class="location-hours" data-i18n="location_hours">Setiap Hari: 10.00 – 23.00</div>
                <div class="location-address" style="margin-top:16px;">
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

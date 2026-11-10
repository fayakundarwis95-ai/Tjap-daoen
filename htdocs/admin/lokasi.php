<?php
session_start();
require_once "../includes/auth.php";
require_once "../includes/maps.php";

$data_file = '../data/lokasi.json';
$data      = json_decode(file_get_contents($data_file), true) ?? [];

// Handle save
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['jam_senin_jumat_buka']    = trim($_POST['jam_sfbuka']   ?? '10.00');
    $data['jam_senin_jumat_tutup']   = trim($_POST['jam_sftutup']  ?? '23.00');
    $data['jam_sabtu_minggu_buka']   = trim($_POST['jam_smbuka']   ?? '09.00');
    $data['jam_sabtu_minggu_tutup']  = trim($_POST['jam_smtutup']  ?? '24.00');
    $data['status_buka']             = isset($_POST['status_buka']);
    $data['alamat']                  = trim($_POST['alamat']       ?? '');
    $maps_input = trim($_POST['maps_embed'] ?? '');
    $maps_embed = maps_normalize_embed_url($maps_input, $data['alamat']);
    if ($maps_input !== '' && $maps_embed === null) {
        $_SESSION['error'] = 'Tautan Maps tidak dapat diurai. Gunakan URL lokasi Google Maps lengkap atau aktifkan ekstensi cURL PHP untuk tautan pendek.';
        header("Location: lokasi.php");
        exit;
    }
    $data['maps_embed'] = $maps_embed ?? '';

    file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $_SESSION['success'] = 'Lokasi & jam operasional berhasil disimpan!';
    header("Location: lokasi.php");
    exit;
}

$admin_page_title = 'Lokasi & Jam Operasional';
$active_admin     = 'lokasi';
require_once "../includes/admin_sidebar.php";

if (!empty($_SESSION['success'])) {
    echo '<div class="admin-flash admin-flash-success">✓ ' . htmlspecialchars($_SESSION['success']) . '</div>';
    unset($_SESSION['success']);
}
if (!empty($_SESSION['error'])) {
    echo '<div class="admin-flash admin-flash-error">' . htmlspecialchars($_SESSION['error']) . '</div>';
    unset($_SESSION['error']);
}
?>

<div class="admin-page-header">
    <h1 class="admin-page-title">Lokasi &amp; Jam Operasional</h1>
    <p class="admin-page-desc">Atur jadwal buka harian, alamat kafe di Bondowoso, dan tautan Google Maps</p>
</div>

<form method="POST" id="lokasi-form">

<div class="admin-cols">

    <!-- Left: Form -->
    <div style="display:flex;flex-direction:column;gap:20px;">

        <!-- Hours panel -->
        <div class="admin-panel">
            <div class="admin-panel-head">
                <span class="admin-panel-title">Jadwal Operasional Harian</span>
            </div>
            <div class="admin-panel-body">

                <!-- Senin - Jumat -->
                <div class="hours-grid" style="margin-bottom:16px;">
                    <div class="hours-grid-label">Senin – Jumat</div>
                    <input class="field-input" type="text" name="jam_sfbuka"
                           id="sf-buka"
                           value="<?= htmlspecialchars($data['jam_senin_jumat_buka'] ?? '10.00') ?>"
                           placeholder="10.00">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <input class="field-input" type="text" name="jam_sftutup"
                               id="sf-tutup"
                               value="<?= htmlspecialchars($data['jam_senin_jumat_tutup'] ?? '23.00') ?>"
                               placeholder="23.00">
                        <span style="color:var(--muted);font-size:14px;white-space:nowrap;">WIB</span>
                    </div>
                </div>

                <!-- Sabtu - Minggu -->
                <div class="hours-grid" style="margin-bottom:24px;">
                    <div class="hours-grid-label">Sabtu – Minggu</div>
                    <input class="field-input" type="text" name="jam_smbuka"
                           id="sm-buka"
                           value="<?= htmlspecialchars($data['jam_sabtu_minggu_buka'] ?? '09.00') ?>"
                           placeholder="09.00">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <input class="field-input" type="text" name="jam_smtutup"
                               id="sm-tutup"
                               value="<?= htmlspecialchars($data['jam_sabtu_minggu_tutup'] ?? '24.00') ?>"
                               placeholder="24.00">
                        <span style="color:var(--muted);font-size:14px;white-space:nowrap;">WIB</span>
                    </div>
                </div>

                <!-- Status toggle -->
                <label class="toggle-wrap" style="cursor:pointer;">
                    <span class="toggle-switch">
                        <input type="checkbox" name="status_buka" id="toggle-status"
                               <?= !empty($data['status_buka']) ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </span>
                    <span class="toggle-label" id="status-label">
                        Status Kafe: <?= !empty($data['status_buka']) ? 'Buka Hari Ini' : 'Tutup Hari Ini' ?>
                    </span>
                </label>

            </div>
        </div>

        <!-- Address panel -->
        <div class="admin-panel">
            <div class="admin-panel-head">
                <span class="admin-panel-title">Alamat &amp; Google Maps</span>
            </div>
            <div class="admin-panel-body">

                <div class="field-group">
                    <label class="field-label">Alamat Lengkap</label>
                    <input class="field-input" type="text" name="alamat" id="inp-alamat"
                           value="<?= htmlspecialchars($data['alamat'] ?? '') ?>"
                           placeholder="Jl. Raya Bondowoso, Dabasah, Kab. Bondowoso, Jawa Timur 68211">
                </div>

                <div class="field-group">
                    <label class="field-label">Tautan Sematan Google Maps (Embed URL)</label>
                    <input class="field-input" type="text" name="maps_embed" id="inp-maps"
                           value="<?= htmlspecialchars($data['maps_embed'] ?? '') ?>"
                           placeholder="https://maps.google.com/maps?...&output=embed">
                    <p style="font-size:12px;color:var(--muted);margin-top:6px;">
                        Gunakan Google Maps → Bagikan → Sematkan Peta → salin src="...". Tautan Bagikan biasa tidak dapat dimuat langsung; peta akan memakai alamat di atas.
                    </p>
                </div>

                <div style="display:flex;gap:12px;">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="index.php" class="btn btn-outline">Batal</a>
                </div>

            </div>
        </div>

    </div>

    <!-- Right: Previews -->
    <div style="display:flex;flex-direction:column;gap:16px;">

        <!-- Map preview -->
        <div class="admin-panel">
            <div class="admin-panel-head">
                <span class="admin-panel-title">Pratinjau Peta Google Maps</span>
            </div>
            <div class="admin-panel-body" style="padding:0;">
                <div class="map-preview-box">
                    <iframe id="map-preview-frame"
                            src="<?= htmlspecialchars(maps_embed_url($data['maps_embed'] ?? '', $data['alamat'] ?? '')) ?>"
                            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                            title="Pratinjau peta"></iframe>
                </div>
            </div>
        </div>

        <!-- Hours preview card -->
        <div class="admin-panel">
            <div class="admin-panel-head">
                <span class="admin-panel-title">Pratinjau Tampilan Beranda</span>
            </div>
            <div class="admin-panel-body">
                <div class="loc-preview">
                    <div class="loc-preview-row">
                        <span class="loc-preview-day">Senin – Jumat</span>
                        <span class="loc-preview-time" id="preview-sf">
                            <?= htmlspecialchars($data['jam_senin_jumat_buka'] ?? '10.00') ?> –
                            <?= htmlspecialchars($data['jam_senin_jumat_tutup'] ?? '23.00') ?> WIB
                        </span>
                    </div>
                    <div class="loc-preview-row">
                        <span class="loc-preview-day">Sabtu – Minggu</span>
                        <span class="loc-preview-time" id="preview-sm">
                            <?= htmlspecialchars($data['jam_sabtu_minggu_buka'] ?? '09.00') ?> –
                            <?= htmlspecialchars($data['jam_sabtu_minggu_tutup'] ?? '24.00') ?> WIB
                        </span>
                    </div>
                    <div style="border-top:1px solid var(--border);margin:12px 0 8px;"></div>
                    <div style="font-size:13px;color:var(--muted);">Alamat Lengkap</div>
                    <div style="font-size:14px;color:var(--white);margin-top:4px;line-height:1.5;" id="preview-alamat">
                        <?= nl2br(htmlspecialchars($data['alamat'] ?? '')) ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

</form>

<script>
// Live preview: hours
function updateHoursPreview() {
    const sfB = document.getElementById('sf-buka').value;
    const sfT = document.getElementById('sf-tutup').value;
    const smB = document.getElementById('sm-buka').value;
    const smT = document.getElementById('sm-tutup').value;
    document.getElementById('preview-sf').textContent = `${sfB} – ${sfT} WIB`;
    document.getElementById('preview-sm').textContent = `${smB} – ${smT} WIB`;
}

['sf-buka','sf-tutup','sm-buka','sm-tutup'].forEach(id => {
    document.getElementById(id)?.addEventListener('input', updateHoursPreview);
});

// Live preview: alamat
document.getElementById('inp-alamat')?.addEventListener('input', e => {
    document.getElementById('preview-alamat').textContent = e.target.value;
});

// Status label
document.getElementById('toggle-status')?.addEventListener('change', e => {
    document.getElementById('status-label').textContent =
        'Status Kafe: ' + (e.target.checked ? 'Buka Hari Ini' : 'Tutup Hari Ini');
});

// Maps preview uses an embed URL or falls back to the current address.
function updateMapPreview() {
    const rawUrl = document.getElementById('inp-maps').value.trim();
    const address = document.getElementById('inp-alamat').value.trim() || 'Bondowoso, Jawa Timur';
    const fallbackUrl = `https://www.google.com/maps?output=embed&q=${encodeURIComponent(address)}`;

    try {
        const url = new URL(rawUrl);
        const host = url.hostname.toLowerCase();
        const googleMapsHost = ['google.com', 'www.google.com', 'maps.google.com', 'maps.app.goo.gl', 'goo.gl'].includes(host);

        if (url.protocol !== 'https:' || !googleMapsHost) {
            document.getElementById('map-preview-frame').src = fallbackUrl;
            return;
        }
        if (url.pathname.startsWith('/maps/embed') || (url.pathname.startsWith('/maps') && url.searchParams.get('output') === 'embed')) {
            document.getElementById('map-preview-frame').src = url.href;
            return;
        }
        if (host === 'maps.app.goo.gl' || host === 'goo.gl') {
            document.getElementById('map-preview-frame').src = fallbackUrl;
            return;
        }

        let target = url.searchParams.get('q') || url.searchParams.get('query');
        const coordinates = url.pathname.match(/@(-?[\d.]+),(-?[\d.]+)/);
        const place = url.pathname.match(/\/maps\/place\/([^/]+)/);
        if (!target && coordinates) target = `${coordinates[1]},${coordinates[2]}`;
        if (!target && place) target = decodeURIComponent(place[1].replace(/\+/g, ' '));
        document.getElementById('map-preview-frame').src = target
            ? `https://www.google.com/maps?output=embed&q=${encodeURIComponent(target)}`
            : fallbackUrl;
    } catch (error) {
        document.getElementById('map-preview-frame').src = fallbackUrl;
    }
}

document.getElementById('inp-maps')?.addEventListener('input', updateMapPreview);
document.getElementById('inp-alamat')?.addEventListener('input', updateMapPreview);
</script>

<?php require_once "../includes/admin_footer.php"; ?>

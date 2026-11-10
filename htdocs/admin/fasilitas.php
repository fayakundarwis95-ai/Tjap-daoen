<?php
session_start();
require_once "../includes/auth.php";

$data_file = '../data/fasilitas.json';
$data      = json_decode(file_get_contents($data_file), true) ?? [];

// Icon options map
$icon_map = [
    'wifi'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12.55a11 11 0 0114.08 0"/><path d="M1.42 9a16 16 0 0121.16 0"/><path d="M8.53 16.11a6 6 0 016.95 0"/><circle cx="12" cy="20" r="1" fill="currentColor"/></svg>',
    'outlet' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="7" y="2" width="10" height="20" rx="2"/><path d="M12 18h.01"/></svg>',
    'area'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
    'parkir' => '<span style="font-size:20px;font-weight:800;color:var(--white);">P</span>',
    'smoking'=> '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 12h2a2 2 0 012 2v1a2 2 0 01-2 2H2a2 2 0 01-2-2v-1a2 2 0 012-2h2m14-6v3m-3-6v6"/></svg>',
    'toilet' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M7 22V12M17 22V12M3 12h18M3 12V9a9 9 0 0118 0v3"/></svg>',
    'ac'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="7" width="20" height="8" rx="2"/><path d="M9 19l-2 2m8-2l2 2M9 19h6"/></svg>',
    'musik'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>',
];

// Handle actions
$aksi = $_POST['aksi'] ?? '';

if ($aksi === 'tambah' || $aksi === 'edit') {
    $id    = (int)($_POST['id'] ?? 0);
    $item  = [
        'id'       => $id ?: (count($data) ? max(array_column($data, 'id')) + 1 : 1),
        'icon'     => $_POST['icon'] ?? 'wifi',
        'nama'     => trim($_POST['nama'] ?? ''),
        'deskripsi'=> trim($_POST['deskripsi'] ?? ''),
        'aktif'    => isset($_POST['aktif']),
    ];

    if ($aksi === 'tambah') {
        $data[] = $item;
    } else {
        foreach ($data as &$row) {
            if ($row['id'] === $id) { $row = $item; break; }
        }
        unset($row);
    }

    file_put_contents($data_file, json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $_SESSION['success'] = $aksi === 'tambah' ? 'Fasilitas berhasil ditambahkan.' : 'Fasilitas berhasil diperbarui.';
    header("Location: fasilitas.php");
    exit;
}

if ($aksi === 'hapus') {
    $id   = (int)($_POST['id'] ?? 0);
    $data = array_values(array_filter($data, fn($r) => $r['id'] !== $id));
    file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $_SESSION['success'] = 'Fasilitas berhasil dihapus.';
    header("Location: fasilitas.php");
    exit;
}

if ($aksi === 'toggle') {
    $id = (int)($_POST['id'] ?? 0);
    foreach ($data as &$row) {
        if ($row['id'] === $id) { $row['aktif'] = !$row['aktif']; break; }
    }
    unset($row);
    file_put_contents($data_file, json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header("Location: fasilitas.php");
    exit;
}

// Edit mode (GET)
$edit_item = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    foreach ($data as $row) {
        if ($row['id'] === $edit_id) { $edit_item = $row; break; }
    }
}

$admin_page_title = 'Kelola Fasilitas';
$active_admin     = 'fasilitas';
require_once "../includes/admin_sidebar.php";

if (!empty($_SESSION['success'])) {
    echo '<div class="admin-flash admin-flash-success">✓ ' . htmlspecialchars($_SESSION['success']) . '</div>';
    unset($_SESSION['success']);
}
?>

<!-- Topbar -->
<div class="admin-topbar">
    <div class="admin-topbar-left">
        <h1 class="admin-page-title">Kelola Fasilitas Kafe</h1>
        <p class="admin-page-desc">Atur daftar fasilitas kenyamanan yang tampil di website</p>
    </div>
    <button type="button" class="btn btn-primary"
            onclick="document.getElementById('edit-panel').scrollIntoView({behavior:'smooth'})">
        Tambah Fasilitas Baru
    </button>
</div>

<div class="admin-cols">

    <!-- Left: Facility list -->
    <div class="admin-panel">
        <div class="admin-panel-head">
            <span class="admin-panel-title">Daftar Fasilitas Aktif</span>
        </div>

        <?php if (empty($data)): ?>
        <div style="padding:32px 24px;color:var(--muted);">Belum ada fasilitas.</div>
        <?php endif; ?>

        <?php foreach ($data as $item): ?>
        <div class="facility-row">
            <div class="facility-row-icon">
                <?= $icon_map[$item['icon']] ?? $icon_map['wifi'] ?>
            </div>
            <div class="facility-row-info">
                <div class="facility-row-name"><?= htmlspecialchars($item['nama']) ?></div>
                <div class="facility-row-desc"><?= htmlspecialchars($item['deskripsi']) ?></div>
            </div>
            <div class="facility-row-actions">
                <span class="badge-aktif"><?= $item['aktif'] ? 'Aktif' : 'Nonaktif' ?></span>
                <form method="POST" style="display:contents;">
                    <input type="hidden" name="aksi" value="toggle">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                    <button type="submit" class="btn btn-outline btn-sm" title="Toggle aktif/nonaktif"
                            style="padding:6px 12px;font-size:12px;">
                        <?= $item['aktif'] ? 'Nonaktifkan' : 'Aktifkan' ?>
                    </button>
                </form>
                <a href="fasilitas.php?edit=<?= $item['id'] ?>"
                   class="btn btn-edit btn-sm">Ubah</a>
                <form method="POST" style="display:contents;"
                      onsubmit="return confirm('Hapus fasilitas ini?')">
                    <input type="hidden" name="aksi" value="hapus">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                    <button type="submit" class="btn btn-del btn-sm">Hapus</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Right: Add/Edit form -->
    <div class="admin-panel" id="edit-panel">
        <div class="admin-panel-head">
            <span class="admin-panel-title"><?= $edit_item ? 'Edit Fasilitas' : 'Tambah Fasilitas Baru' ?></span>
            <?php if ($edit_item): ?>
            <a href="fasilitas.php" style="font-size:12px;color:var(--muted);text-decoration:none;">+ Baru</a>
            <?php endif; ?>
        </div>
        <div class="admin-panel-body">

            <form method="POST">
                <input type="hidden" name="aksi" value="<?= $edit_item ? 'edit' : 'tambah' ?>">
                <?php if ($edit_item): ?>
                <input type="hidden" name="id" value="<?= $edit_item['id'] ?>">
                <?php endif; ?>

                <div class="field-group">
                    <label class="field-label">Pilih Ikon Fasilitas</label>
                    <div style="display:grid;grid-template-columns:repeat(4,40px);gap:8px;margin-bottom:4px;">
                        <?php foreach ($icon_map as $key => $svg): ?>
                        <label title="<?= $key ?>" style="cursor:pointer;">
                            <input type="radio" name="icon" value="<?= $key ?>"
                                   <?= ($edit_item['icon'] ?? 'wifi') === $key ? 'checked' : '' ?>
                                   style="display:none;" class="icon-radio">
                            <div class="icon-opt" data-key="<?= $key ?>"
                                 style="width:40px;height:40px;border:1px solid var(--border);border-radius:6px;display:flex;align-items:center;justify-content:center;background:var(--dark);cursor:pointer;transition:border-color 0.2s;">
                                <?= $svg ?>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Nama Fasilitas</label>
                    <input class="field-input" type="text" name="nama"
                           value="<?= htmlspecialchars($edit_item['nama'] ?? '') ?>"
                           placeholder="Nama Fasilitas" required>
                </div>

                <div class="field-group">
                    <label class="field-label">Deskripsi Singkat</label>
                    <textarea class="field-input" name="deskripsi" rows="3"
                              style="min-height:80px;"
                              placeholder="Deskripsi Singkat"><?= htmlspecialchars($edit_item['deskripsi'] ?? '') ?></textarea>
                </div>

                <div class="field-group">
                    <label class="field-label">Tampilkan di Beranda</label>
                    <label class="toggle-wrap" style="cursor:pointer;">
                        <span class="toggle-switch">
                            <input type="checkbox" name="aktif"
                                   <?= ($edit_item['aktif'] ?? true) ? 'checked' : '' ?>>
                            <span class="toggle-slider"></span>
                        </span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                    <?= $edit_item ? 'Update Fasilitas' : 'Simpan Fasilitas' ?>
                </button>

            </form>
        </div>
    </div>

</div>

<script>
// Highlight selected icon
document.querySelectorAll('.icon-radio').forEach(r => {
    const opt = r.closest('label').querySelector('.icon-opt');
    if (r.checked) opt.style.borderColor = 'var(--green)';
    r.addEventListener('change', () => {
        document.querySelectorAll('.icon-opt').forEach(o => o.style.borderColor = 'var(--border)');
        opt.style.borderColor = 'var(--green)';
    });
});
</script>

<?php require_once "../includes/admin_footer.php"; ?>

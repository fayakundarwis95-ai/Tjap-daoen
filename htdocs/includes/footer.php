<?php
// ============================================================
// INCLUDES — shared footer component
// ============================================================
$wa_number  = '628123456789'; // GANTI dengan nomor WA kamu
$wa_message = rawurlencode('Halo Tjap Daoen! Saya ingin menanyakan informasi lebih lanjut.');
$base       = isset($base_path) ? $base_path : '';
$popup_file = __DIR__ . '/../data/popup.json';
$popup_data = file_exists($popup_file) ? json_decode(file_get_contents($popup_file), true) : [];
$popup_data = is_array($popup_data) ? $popup_data : [];
$popup_enabled = !empty($popup_data['enabled']);
?>

</div><!-- /.page-wrap -->

<?php if ($popup_enabled): ?>
<div class="promo-overlay" id="promo-overlay" aria-hidden="true">
    <section class="promo-dialog" id="promo-dialog" role="dialog" aria-modal="true" aria-labelledby="promo-title" aria-describedby="promo-description" tabindex="-1">
        <button class="promo-close" id="promo-close" type="button" aria-label="Tutup pop-up">&times;</button>
        <div class="promo-image">
            <?php if (!empty($popup_data['image'])): ?>
            <img src="<?= htmlspecialchars($base . $popup_data['image'], ENT_QUOTES, 'UTF-8') ?>"
                 alt=""
                 loading="eager">
            <?php endif; ?>
        </div>
        <div class="promo-content">
            <h2 id="promo-title"><?= htmlspecialchars($popup_data['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></h2>
            <p id="promo-description"><?= nl2br(htmlspecialchars($popup_data['description'] ?? '', ENT_QUOTES, 'UTF-8')) ?></p>
            <?php if (!empty($popup_data['button_label']) && !empty($popup_data['button_url'])): ?>
            <a class="promo-button"
               href="<?= htmlspecialchars($popup_data['button_url'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($popup_data['button_label'], ENT_QUOTES, 'UTF-8') ?></a>
            <?php endif; ?>
        </div>
    </section>
</div>
<?php endif; ?>

<!-- ===== FOOTER ===== -->
<footer class="footer" role="contentinfo">
    <div class="container">
        <div class="footer-grid">

            <!-- Brand -->
            <div class="footer-brand">
                <a href="<?= $base ?>index.php" class="nav-logo" style="margin-bottom:16px;display:inline-flex;">
                    <img src="<?= $base ?>assets/images/logo.png"
                         alt="Tjap Daoen Logo"
                         style="height:36px;width:36px;object-fit:contain;"
                         onerror="this.style.display='none'">
                    <span class="nav-logo-text">Tjap<br>Daoen</span>
                </a>
                <p>Kedai kopi dengan konsep sederhana namun penuh makna. Setiap tegukan kopi menjadi bagian dari pengalaman yang hangat dan berkesan.</p>
                <div class="footer-social">
                    <a href="https://instagram.com/tjapdaoen" target="_blank" rel="noopener" aria-label="Instagram">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        Instagram
                    </a>
                    <a href="https://tiktok.com/@tjapdaoen" target="_blank" rel="noopener" aria-label="TikTok">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.72a4.85 4.85 0 01-1.01-.03z"/></svg>
                        TikTok
                    </a>
                </div>
            </div>

            <!-- Nav links -->
            <div class="footer-col">
                <h4>Navigasi</h4>
                <ul>
                    <li><a href="<?= $base ?>index.php">Beranda</a></li>
                    <li><a href="<?= $base ?>tentang.php">Tentang Kami</a></li>
                    <li><a href="<?= $base ?>produk.php">Menu</a></li>
                    <li><a href="<?= $base ?>kontak.php">Kontak</a></li>
                </ul>
            </div>

            <!-- Info links -->
            <div class="footer-col">
                <h4>Info</h4>
                <ul>
                    <li><a href="https://instagram.com/tjapdaoen" target="_blank" rel="noopener">Instagram</a></li>
                    <li><a href="https://tiktok.com/@tjapdaoen" target="_blank" rel="noopener">TikTok</a></li>
                    <li><a href="https://wa.me/<?= $wa_number ?>?text=<?= $wa_message ?>" target="_blank" rel="noopener">WhatsApp</a></li>
                </ul>
            </div>

        </div>

        <div class="footer-bottom">
            <span class="footer-copy">&copy; <?= date('Y') ?> Tjap Daoen. All rights reserved.</span>
        </div>
    </div>
</footer>

<!-- ===== WA FLOATING BUTTON + POPUP ===== -->
<div class="wa-float" id="wa-float">

    <!-- Popup -->
    <div class="wa-popup" id="wa-popup" role="dialog" aria-label="Customer Service WhatsApp" aria-modal="true">
        <div class="wa-popup-header">
            <div class="wa-popup-header-left">
                <div class="wa-popup-header-icon">
                    <svg viewBox="0 0 24 24" fill="#fff"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </div>
                <div class="wa-popup-header-text">
                    <strong>Customer Service</strong>
                    <span>&#x1F7E2; Online</span>
                </div>
            </div>
            <button class="wa-popup-close" id="wa-popup-close" aria-label="Tutup popup">&times;</button>
        </div>

        <div class="wa-popup-body">
            <div class="wa-chat-bubble">
                <span class="emoji">👋</span>
                <strong>Halo!</strong><br>
                Ada yang bisa kami bantu hari ini?
            </div>
        </div>

        <div class="wa-popup-footer">
            <a href="https://wa.me/<?= $wa_number ?>?text=<?= $wa_message ?>"
               target="_blank" rel="noopener"
               class="wa-start-btn">
                <svg viewBox="0 0 24 24" fill="#fff"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Mulai Chat
            </a>
        </div>
    </div>

    <!-- Float Button -->
    <button class="wa-btn" id="wa-btn" aria-label="Hubungi via WhatsApp" aria-expanded="false">
        <svg viewBox="0 0 24 24" fill="#fff"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    </button>

</div>

<!-- ===== LANGUAGE SWITCHER — Fixed bottom-left, 2 tombol terpisah ditumpuk vertikal ===== -->
<div class="lang-fixed-bl" role="group" aria-label="Pilih bahasa">
    <button class="lang-fixed-btn" id="btn-id" onclick="setLang('id')" aria-pressed="true">
        <img src="https://flagcdn.com/18x14/id.png"
             onerror="this.src='https://flagcdn.com/16x12/id.png'"
             alt="ID"> ID
    </button>
    <button class="lang-fixed-btn" id="btn-en" onclick="setLang('en')" aria-pressed="false">
        <img src="https://flagcdn.com/18x14/gb.png"
             onerror="this.src='https://flagcdn.com/16x12/gb.png'"
             alt="EN"> EN
    </button>
</div>

<!-- ===== SCRIPTS ===== -->
<script>
// --- Color theme ---
const themeToggle = document.getElementById('theme-toggle');
function setTheme(theme) {
    document.documentElement.dataset.theme = theme;
    localStorage.setItem('td_theme', theme);
    themeToggle.setAttribute('aria-pressed', theme === 'dark');
    themeToggle.setAttribute('aria-label', theme === 'dark' ? 'Aktifkan mode terang' : 'Aktifkan mode gelap');
}
setTheme(document.documentElement.dataset.theme === 'dark' ? 'dark' : 'light');
themeToggle.addEventListener('click', () => {
    setTheme(document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark');
});

// --- Hamburger ---
(function() {
    const hamburger = document.getElementById('hamburger');
    const navLinks  = document.getElementById('nav-links');
    if (hamburger && navLinks) {
        hamburger.addEventListener('click', () => {
            const isOpen = navLinks.classList.toggle('open');
            hamburger.setAttribute('aria-expanded', isOpen);
        });
    }
})();

// --- WA Popup ---
const waBtn   = document.getElementById('wa-btn');
const waPopup = document.getElementById('wa-popup');
const waClose = document.getElementById('wa-popup-close');

waBtn.addEventListener('click', () => {
    const isOpen = waPopup.classList.toggle('open');
    waBtn.setAttribute('aria-expanded', isOpen);
});

waClose.addEventListener('click', () => {
    waPopup.classList.remove('open');
    waBtn.setAttribute('aria-expanded', false);
});

document.addEventListener('click', (e) => {
    const waFloat = document.getElementById('wa-float');
    if (waFloat && !waFloat.contains(e.target)) {
        waPopup.classList.remove('open');
        waBtn.setAttribute('aria-expanded', false);
    }
});

// --- Language Switcher (fixed bottom-left) ---
const btnId = document.getElementById('btn-id');
const btnEn = document.getElementById('btn-en');

function setLang(lang) {
    btnId.classList.toggle('active', lang === 'id');
    btnEn.classList.toggle('active', lang === 'en');
    btnId.setAttribute('aria-pressed', lang === 'id');
    btnEn.setAttribute('aria-pressed', lang === 'en');

    if (lang === 'id') {
        // Hapus cookie terjemahan → kembali ke bahasa asli
        document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=.' + window.location.hostname;
        localStorage.setItem('td_lang', 'id');
        location.reload();
    } else {
        // Set cookie terjemahan → terjemahkan ke English
        document.cookie = 'googtrans=/id/en; path=/;';
        document.cookie = 'googtrans=/id/en; path=/; domain=.' + window.location.hostname;
        localStorage.setItem('td_lang', 'en');
        location.reload();
    }
}

// Pulihkan state tombol saat halaman dimuat
(function() {
    const saved = localStorage.getItem('td_lang') || 'id';
    if (saved === 'en') {
        btnId.classList.remove('active');
        btnEn.classList.add('active');
        btnId.setAttribute('aria-pressed', false);
        btnEn.setAttribute('aria-pressed', true);
    } else {
        btnId.classList.add('active');
        btnEn.classList.remove('active');
    }
})();
</script>

<?php if ($popup_enabled): ?>
<script>
(function() {
    const overlay = document.getElementById('promo-overlay');
    const dialog = document.getElementById('promo-dialog');
    const closeButton = document.getElementById('promo-close');
    const storageKey = 'td_promo_popup_dismissed';

    function closePromo() {
        overlay.classList.remove('is-visible');
        overlay.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('promo-open');
        sessionStorage.setItem(storageKey, '1');
    }

    if (sessionStorage.getItem(storageKey) === '1') {
        return;
    }

    window.setTimeout(function() {
        overlay.classList.add('is-visible');
        overlay.setAttribute('aria-hidden', 'false');
        document.body.classList.add('promo-open');
        closeButton.focus();
    }, 350);

    closeButton.addEventListener('click', closePromo);
    overlay.addEventListener('click', function(event) {
        if (event.target === overlay) {
            closePromo();
        }
    });
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && overlay.classList.contains('is-visible')) {
            closePromo();
        }
        if (event.key === 'Tab' && overlay.classList.contains('is-visible')) {
            const focusable = dialog.querySelectorAll('a[href], button:not([disabled])');
            const first = focusable[0];
            const last = focusable[focusable.length - 1];
            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        }
    });
})();
</script>
<?php endif; ?>

</body>
</html>

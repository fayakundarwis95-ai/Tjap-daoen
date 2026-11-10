<?php
// ============================================================
// INCLUDES — shared header component
// ============================================================
$page_title = $page_title ?? 'Tjap Daoen';
$active_nav = $active_nav ?? '';
$wa_number  = '628123456789'; // GANTI dengan nomor WA kamu
$wa_message = rawurlencode('Halo Tjap Daoen! Saya ingin menanyakan informasi lebih lanjut.');
?>
<!DOCTYPE html>
<html lang="id" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        document.documentElement.dataset.theme = localStorage.getItem('td_theme') === 'dark' ? 'dark' : 'light';
    </script>
    <title><?= htmlspecialchars($page_title) ?> — Tjap Daoen</title>
    <meta name="description" content="Tjap Daoen Coffee Bar — Secangkir Cerita di Bondowoso. Temukan kehangatan dan aroma kopi nusantara terbaik.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="<?= isset($base_path) ? $base_path : '' ?>assets/style.css?v=<?= filemtime(__DIR__ . '/../assets/style.css') ?>">
    <!-- Google Translate -->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'id',
                includedLanguages: 'en,id',
                autoDisplay: false,
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE
            }, 'google_translate_element');
        }
    </script>
    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <style>
        /* Hide Google Translate default UI */
        #google_translate_element,
        .goog-te-banner-frame,
        .goog-te-gadget,
        .skiptranslate { display: none !important; }
        body { top: 0 !important; }
        .VIpgJd-ZVi9od-xl07Ob-lTBxed,
        .VIpgJd-ZVi9od-aZ2wEe { display: none !important; }

    </style>
</head>
<body>

<!-- Hidden GT element -->
<div id="google_translate_element" style="display:none;visibility:hidden;"></div>

<!-- ===== NAVBAR ===== -->
<nav class="navbar" role="navigation" aria-label="Main navigation">
    <div class="nav-inner container">

        <!-- Logo -->
        <a href="<?= isset($base_path) ? $base_path : '' ?>index.php" class="nav-logo" aria-label="Tjap Daoen Beranda">
            <img src="<?= isset($base_path) ? $base_path : '' ?>assets/images/logo.png"
                 alt="Tjap Daoen Logo"
                 onerror="this.style.display='none'">
            <span class="nav-logo-text">Tjap<br>Daoen</span>
        </a>

        <!-- Nav Links -->
        <ul class="nav-links" id="nav-links" role="list">
            <li><a href="<?= isset($base_path) ? $base_path : '' ?>index.php"
                   class="<?= $active_nav === 'beranda' ? 'active' : '' ?>">Beranda</a></li>
            <li><a href="<?= isset($base_path) ? $base_path : '' ?>tentang.php"
                   class="<?= $active_nav === 'tentang' ? 'active' : '' ?>">Tentang Kami</a></li>
            <li><a href="<?= isset($base_path) ? $base_path : '' ?>produk.php"
                   class="<?= $active_nav === 'menu' ? 'active' : '' ?>">Menu</a></li>
            <li><a href="<?= isset($base_path) ? $base_path : '' ?>kontak.php"
                   class="<?= $active_nav === 'kontak' ? 'active' : '' ?>">Kontak</a></li>
        </ul>

        <!-- Right: hamburger only (lang moved to bottom-left) -->
        <div class="nav-right">
            <button class="theme-toggle" id="theme-toggle" type="button" aria-label="Aktifkan mode gelap" aria-pressed="false">
                <svg class="theme-icon theme-icon-sun" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42"/></svg>
                <svg class="theme-icon theme-icon-moon" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.9 13A9 9 0 0 1 11 3.1 9 9 0 1 0 20.9 13Z"/></svg>
            </button>
            <button class="hamburger" id="hamburger" aria-label="Buka menu" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </div>

    </div>
</nav>

<!-- Offset for fixed navbar -->
<div class="page-wrap">

<?php
// ============================================================
// PLACEHOLDER IMAGE — assets/images/placeholder.php
// Generates a dark placeholder image on-the-fly
// Usage: <img src="assets/images/placeholder.php?w=400&h=300&t=Tjap+Daoen">
// ============================================================
$w    = min(max((int)($_GET['w'] ?? 800), 100), 1200);
$h    = min(max((int)($_GET['h'] ?? 600), 100), 900);
$text = $_GET['t'] ?? 'Tjap Daoen';

header('Content-Type: image/svg+xml');
header('Cache-Control: public, max-age=86400');

echo <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$w}" height="{$h}" viewBox="0 0 {$w} {$h}">
  <rect width="{$w}" height="{$h}" fill="#272727"/>
  <rect width="{$w}" height="{$h}" fill="none" stroke="#333333" stroke-width="1"/>
  <text x="50%" y="46%" font-family="sans-serif" font-size="16" fill="#666" text-anchor="middle" dominant-baseline="middle">{$text}</text>
  <text x="50%" y="56%" font-family="sans-serif" font-size="13" fill="#555" text-anchor="middle" dominant-baseline="middle">No Image</text>
</svg>
SVG;

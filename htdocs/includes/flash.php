<?php
// ============================================================
// includes/flash.php — Tampilkan pesan sukses/error dari session
// ============================================================
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success" style="margin-bottom:24px;">'
        . htmlspecialchars($_SESSION['success'])
        . '</div>';
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger" style="margin-bottom:24px;">'
        . htmlspecialchars($_SESSION['error'])
        . '</div>';
    unset($_SESSION['error']);
}
<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    header("Location: ../admin/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin Login — Tjap Daoen</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="../assets/style.css?v=<?= filemtime(__DIR__ . '/../assets/style.css') ?>">
    <style>
        /* Login page uses the public navbar + centered box */
        .login-page-body {
            min-height: calc(100vh - 64px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 24px;
        }

        .login-card {
            width: 100%;
            max-width: 360px;
            background: #111;
            border: 1px solid #333;
            border-radius: 4px;
            padding: 40px 40px 48px;
        }

        .login-heading {
            font-size: 13px;
            font-weight: 600;
            color: var(--muted);
            text-align: center;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 28px;
        }

        .login-field {
            position: relative;
            margin-bottom: 14px;
        }

        .login-field svg {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: #555;
            pointer-events: none;
        }

        .login-input {
            width: 100%;
            padding: 13px 14px 13px 42px;
            background: #f5f5f5;
            border: none;
            border-radius: 3px;
            font-family: var(--font);
            font-size: 13px;
            font-weight: 600;
            color: #333;
            letter-spacing: 1px;
            outline: none;
        }

        .login-input::placeholder {
            color: #888;
            font-weight: 600;
            letter-spacing: 1.5px;
        }

        .login-btn {
            display: block;
            width: auto;
            margin: 24px auto 0;
            padding: 11px 48px;
            background: var(--green);
            color: #fff;
            border: none;
            border-radius: 3px;
            font-family: var(--font);
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.2s;
        }

        .login-btn:hover {
            background: var(--green-hover);
        }

        .login-forgot {
            display: block;
            text-align: center;
            margin-top: 16px;
            font-size: 13px;
            color: #5b8dee;
            text-decoration: none;
            transition: color 0.2s;
        }

        .login-forgot:hover {
            color: #4a7de0;
        }

        .login-error {
            background: rgba(255,107,107,0.1);
            border: 1px solid rgba(255,107,107,0.3);
            color: #ff6b6b;
            padding: 10px 14px;
            border-radius: 4px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<!-- Public navbar (same as website) -->
<nav class="navbar" role="navigation">
    <div class="nav-inner container">
        <a href="../index.php" class="nav-logo" aria-label="Tjap Daoen Beranda">
            <img src="../assets/images/logo.png" alt="Tjap Daoen Logo"
                 onerror="this.style.display='none'">
            <span class="nav-logo-text">TJAP<br>DAOEN</span>
        </a>
        <ul class="nav-links">
            <li><a href="../index.php" class="active">Beranda</a></li>
            <li><a href="../tentang.php">Tentang Kami</a></li>
            <li><a href="../produk.php">Menu</a></li>
            <li><a href="../kontak.php">Kontak</a></li>
        </ul>
    </div>
</nav>

<div class="page-wrap">
<div class="login-page-body">

    <div class="login-card">

        <p class="login-heading">Admin Login</p>

        <?php if (isset($_SESSION['error'])): ?>
        <div class="login-error">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); endif; ?>

        <form action="proses_login.php" method="POST">

            <!-- Username -->
            <div class="login-field">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                <input class="login-input" type="text" name="username"
                       placeholder="USERNAME" required autocomplete="username">
            </div>

            <!-- Password -->
            <div class="login-field">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0110 0v4"/>
                </svg>
                <input class="login-input" type="password" name="password"
                       placeholder="PASSWORD" required autocomplete="current-password">
            </div>

            <button type="submit" class="login-btn">Login</button>

        </form>

        

    </div>

</div>
</div>

</body>
</html>
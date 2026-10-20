<?php
declare(strict_types=1);
require_once __DIR__ . '/config/bootstrap.php';
if (!empty($_SESSION['user'])) redirect('dashboard.php');
$error = flash('error'); $success = flash('success');
?>
<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Login | PengliCraft</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body class="auth-page"><main class="auth-card"><div class="brand-mark">✦</div><p class="eyebrow">PENGLIPURAN RUMAH41</p><h1>Selamat datang di PengliCraft</h1><p class="muted">Kelola koleksi kerajinan dengan mudah.</p><?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><?php if ($success): ?><div class="alert success"><?= e($success) ?></div><?php endif; ?><form action="auth/process_login.php" method="post" class="stack"><label>Username atau email<input type="text" name="identity" autocomplete="username" required autofocus></label><label>Password<div class="password-field"><input id="password" type="password" name="password" autocomplete="current-password" required><button type="button" class="show-password" data-password-toggle aria-label="Tampilkan password">Lihat</button></div></label><button class="button primary" type="submit">Masuk ke dashboard</button></form></main></body></html>

<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/bootstrap.php';

$isLocal = in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1', 'localhost'], true);
$lockFile = __DIR__ . '/../storage/admin_setup.lock';
$setupError = null;

if (!$isLocal) {
    http_response_code(403);
    exit('Setup admin hanya dapat dijalankan dari server lokal.');
}
if (is_file($lockFile)) {
    http_response_code(403);
    exit('Setup admin sudah dinonaktifkan. Hapus file lock hanya jika melakukan instalasi ulang secara lokal.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim((string)($_POST['name'] ?? ''));
    $username = trim((string)($_POST['username'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    if ($name === '' || $username === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
        $setupError = 'Isi semua data dengan benar. Password minimal 8 karakter.';
    } else {
        $exists = db()->prepare('SELECT id FROM users WHERE username = :username OR email = :email');
        $exists->execute(['username' => $username, 'email' => $email]);
        if ($exists->fetch()) {
            $setupError = 'Username atau email sudah digunakan.';
        } else {
            $insert = db()->prepare('INSERT INTO users (name, username, email, password, role) VALUES (:name, :username, :email, :password, :role)');
            $insert->execute([
                'name' => $name, 'username' => $username, 'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT), 'role' => 'admin',
            ]);
            file_put_contents($lockFile, 'Admin setup completed at ' . date('c'));
            flash('success', 'Akun admin berhasil dibuat. Silakan login.');
            redirect('../login.php');
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title Setup Admin | PengliCraft</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body class="auth-page"><main class="auth-card"><div class="brand-mark">✦</div><p class="eyebrow">INSTALASI LOKAL</p><h1>Buat Admin Pertama</h1><p class="muted">Halaman ini otomatis dinonaktifkan setelah akun berhasil dibuat.</p>
<?php if ($setupError): ?><div class="alert error"><?= e($setupError) ?></div><?php endif; ?>
<form method="post" class="stack"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label>Nama lengkap<input name="name" required value="<?= e($_POST['name'] ?? '') ?>"></label><label>Username<input name="username" required value="<?= e($_POST['username'] ?? '') ?>"></label><label>Email<input type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>"></label><label>Password<input type="password" name="password" minlength="8" required></label><button class="button primary" type="submit">Buat akun admin</button></form></main></body></html>

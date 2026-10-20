<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax',
    ]);
    session_start();
}

require_once __DIR__ . '/database.php';

set_exception_handler(function (Throwable $exception): void {
    error_log($exception->getMessage());
    http_response_code(500);
    $message = 'Aplikasi tidak dapat memproses permintaan. Pastikan MySQL aktif dan database penglicraft sudah diimpor.';
    if (PHP_SAPI === 'cli' || (($_SERVER['REMOTE_ADDR'] ?? '') === '127.0.0.1')) {
        $message .= ' Detail: ' . $exception->getMessage();
    }
    echo '<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>PengliCraft - Kesalahan</title><link rel="stylesheet" href="' . (str_contains($_SERVER['REQUEST_URI'] ?? '', '/auth/') ? '../' : '') . 'assets/css/style.css"></head><body class="auth-page"><main class="auth-card"><div class="brand-mark">!</div><p class="eyebrow">PENGLICRAFT</p><h1>Terjadi kendala</h1><div class="alert error">' . e($message) . '</div><a class="button primary" href="' . (str_contains($_SERVER['REQUEST_URI'] ?? '', '/auth/') ? '../' : '') . 'login.php">Kembali ke login</a></main></body></html>';
    exit;
});

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(419);
        exit('Permintaan tidak valid. Silakan muat ulang halaman.');
    }
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return null;
    }
    $value = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $value;
}

<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/bootstrap.php';

function require_login(): void
{
    if (empty($_SESSION['user'])) {
        flash('error', 'Silakan login terlebih dahulu.');
        redirect('login.php');
    }
}

function require_role(string ...$roles): void
{
    require_login();
    if (!in_array($_SESSION['user']['role'] ?? '', $roles, true)) {
        http_response_code(403);
        exit('Anda tidak memiliki izin untuk mengakses halaman ini.');
    }
}

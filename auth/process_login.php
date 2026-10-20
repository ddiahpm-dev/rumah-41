<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../login.php');
}

$identity = trim((string)($_POST['identity'] ?? ''));
$password = (string)($_POST['password'] ?? '');

if ($identity === '' || $password === '') {
    flash('error', 'Username/email dan password wajib diisi.');
    redirect('../login.php');
}

$statement = db()->prepare(
    'SELECT id, name, username, email, password, role FROM users WHERE username = :username OR email = :email LIMIT 1'
);
$statement->execute(['username' => $identity, 'email' => $identity]);
$user = $statement->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    flash('error', 'Username/email atau password salah.');
    redirect('../login.php');
}

session_regenerate_id(true);
unset($user['password']);
$_SESSION['user'] = $user;
redirect('../dashboard.php');

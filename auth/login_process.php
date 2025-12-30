<?php
session_start();
include __DIR__ . '/../api/database/config.php';

// Hanya terima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$email = trim($_POST['email'] ?? '');
$pass  = trim($_POST['pass'] ?? '');

if ($email === '' || $pass === '') {
    $_SESSION['error'] = 'Email dan password wajib diisi';
    header("Location: login.php");
    exit;
}

// Ambil user
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Validasi
if ($user && $pass === $user['pass']) {

    $_SESSION['user'] = [
        'id'    => $user['id'],
        'name'  => $user['name'],
        'email' => $user['email'],
        'role'  => $user['role']
    ];

    header("Location: ../index.php");
    exit;
}

// Gagal login
$_SESSION['error'] = 'Email atau password salah';
header("Location: login.php");
exit;
?>
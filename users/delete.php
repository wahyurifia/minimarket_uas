<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

include __DIR__ . '/../api/database/config.php';

// Validasi id
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: index.php");
    exit;
}

// Optional: proteksi role (misalnya hanya admin)
if ($_SESSION['user']['role'] === 'pelanggan') {
    // pelanggan tidak boleh delete
    header("Location: index.php");
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['success'] = 'Users berhasil dihapus';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Gagal menghapus order';
}

// Redirect kembali
header("Location: index.php");
exit;

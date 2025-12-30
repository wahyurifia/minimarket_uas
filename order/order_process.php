<?php
session_start();

// cek login
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

include __DIR__ . '/../api/database/config.php';

// ambil data dari form
$productId = (int) ($_POST['id'] ?? 0);
$qty = (int) ($_POST['qty'] ?? 0);

if ($productId <= 0 || $qty <= 0) {
    header("Location: index.php");
    exit;
}

// ambil nama pembeli dari session
$pembeli = $_SESSION['user']['name'];

// ambil data produk
$stmt = $pdo->prepare("SELECT name, price, stock FROM products WHERE id = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: index.php");
    exit;
}

// validasi stok
if ($qty > $product['stock']) {
    die("Stok tidak mencukupi");
}

// hitung total
$total = $qty * $product['price'];

// =====================
// GENERATE ORDER ID
// =====================
$stmt = $pdo->query("SELECT order_id FROM orders ORDER BY id DESC LIMIT 1");
$lastOrderId = $stmt->fetchColumn();

if ($lastOrderId) {
    $number = (int) substr($lastOrderId, 3) + 1;
} else {
    $number = 1;
}

$orderId = 'ORD' . str_pad($number, 3, '0', STR_PAD_LEFT);

// =====================
// INSERT KE ORDERS
// =====================
$stmt = $pdo->prepare("
    INSERT INTO orders (order_id, pembeli, product_name, qty, total)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->execute([
    $orderId,
    $pembeli,
    $product['name'],
    $qty,
    $total
]);

// =====================
// UPDATE STOK & SOLD
// =====================
$stmt = $pdo->prepare("
    UPDATE products 
    SET stock = stock - ?, sold = sold + ?
    WHERE id = ?
");
$stmt->execute([$qty, $qty, $productId]);

// =====================
// REDIRECT
// =====================
header("Location: index.php");
exit;

<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

include __DIR__ . '/../api/database/config.php';

$userRole = $_SESSION['user']['role'];
$userName = $_SESSION['user']['name'];

if ($userRole === 'pelanggan') {
    // Pelanggan hanya lihat order milik sendiri
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE pembeli = ? ORDER BY id ASC");
    $stmt->execute([$userName]);
} else {
    // Admin / kasir / staff lihat semua
    $stmt = $pdo->query("SELECT * FROM orders ORDER BY id ASC");
}

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include "../partials/header.php"; ?>
<?php include "../partials/sidebar.php"; ?>

<div class="content_page">

<div class="section-card">
    <h2>Order History</h2>
    <br>

    <table class="table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Pembeli</th>
                <th>Tanggal</th>
                <th>Produk</th>
                <th>Total Qty</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>

            <?php
                $totalQty = 0;
                $totalHarga = 0;
            ?>

            <tr>
                <td><?= $order['order_id'] ?></td>
                <td><?= $order['pembeli'] ?></td>
                <td><?= $order['tanggal'] ?></td>
                <td><?= $order['product_name'] ?></td>
                <td><?= $order['qty'] ?></td>
                <td>
                    <strong>
                        Rp <?= number_format($order['total'] ,0,',','.') ?>
                    </strong>
                </td>
            </tr>

            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="checkout-action">
        <a href="../index.php" class="btn-checkout">
            Checkout Again
        </a>
    </div>

</div>



</div>
<script>
// JS hanya untuk UI
const toggleBtn = document.getElementById('toggleSidebar');
const sidebar = document.getElementById('sidebar');

toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
});
</script>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit;
}
// include koneksi database
include __DIR__ . '/api/database/config.php';

// Ambil category dari query string
$activeCategory = $_GET['category'] ?? 'all';

// Query produk sesuai category
if ($activeCategory === 'all') {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
} else {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? ORDER BY created_at DESC");
    $stmt->execute([$activeCategory]);
}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil produk yang dipilih berdasarkan id
$selectedProduct = null;
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $selectedProduct = $stmt->fetch(PDO::FETCH_ASSOC);
}


$qty = (int)($_POST['qty'] ?? 1);
$total = 0;

if ($selectedProduct) {
    $qty = max(1, min($qty, $selectedProduct['stock']));
    $total = $qty * $selectedProduct['price'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CMS Dashboard</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include "partials/header.php"; ?>
<?php include "partials/sidebar.php"; ?>

<div class="content">

    <!-- Filter -->
 <div class="filter-bar">
    <div class="filter">
        <a class="filter-btn <?= $activeCategory=='all'?'active':'' ?>" href="?category=all">All</a>
        <a class="filter-btn <?= $activeCategory=='makanan'?'active':'' ?>" href="?category=makanan">Makanan</a>
        <a class="filter-btn <?= $activeCategory=='minuman'?'active':'' ?>" href="?category=minuman">Minuman</a>
        <a class="filter-btn <?= $activeCategory=='lainnya'?'active':'' ?>" href="?category=lainnya">Lainnya</a>
    </div>

</div>


    <!-- Products -->
    <div class="products">
     <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <a href="?id=<?= $product['id'] ?>" class="product-item" data-category="<?= $product['category'] ?>">
                <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
                <h3><?= $product['name'] ?></h3>
                <p class="price">Rp <?= number_format($product['price'],0,',','.') ?></p>
                <p class="condition"><?= ucfirst($product['category']) ?></p>
                <div class="meta">
                    <span class="shop"><?= $product['sold'] ?> Terjual</span>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
    
    <div class="hr-margin"> 
        <hr>
        <hr>
    </div>

    <?php if ($selectedProduct): ?>
        <div class="product-detail">
            <h2>Detail Produk</h2>

            <div class="detail-card">
                <img src="<?= $selectedProduct['image'] ?>" alt="<?= $selectedProduct['name'] ?>">

                <div class="detail-info">
                <h3><?= $selectedProduct['name'] ?></h3>

                <p>Harga: <strong>Rp <?= number_format($selectedProduct['price'],0,',','.') ?></strong></p>
                <p>Stok tersedia: <?= $selectedProduct['stock'] ?></p>

                <!-- FORM HITUNG TOTAL -->
                <form method="post">
                    <label>Jumlah Pembelian</label>
                    <input 
                    type="number" 
                    name="qty" 
                    min="1" 
                    max="<?= $selectedProduct['stock'] ?>" 
                    value="<?= $qty ?>" 
                    required
                    >

                    <div class="total-box">
                        <span>Total Harga</span>
                        <strong>Rp <?= number_format($total,0,',','.') ?></strong>
                    </div>

                    <button type="submit" class="btn-secondary">
                    Hitung Total
                    </button>
                </form>

                <!-- CHECKOUT -->
                <form method="post" action="order/order_process.php" style="margin-top:10px">
                    <input type="hidden" name="id" value="<?= $selectedProduct['id'] ?>">
                    <input type="hidden" name="qty" value="<?= $qty ?>">

                    <button type="submit" class="btn-primary">
                    Checkout
                    </button>
                </form>

                </div>
            </div>
        </div>
    <?php endif; ?>

    
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

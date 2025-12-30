<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}
include __DIR__ . '/../api/database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_create'])) {

    $name     = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price    = (int) ($_POST['price'] ?? 0);
    $stock    = (int) ($_POST['stock'] ?? 0);
    $sold     = (int) ($_POST['sold'] ?? 0);
    $image    = trim($_POST['image'] ?? '');

    // Validasi minimal
    if ($name === '' || $category === '' || $price <= 0) {
        die("<script>
            alert('Data tidak valid');
            history.back();
        </script>");
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO products
                (name, category, price, stock, sold, image)
            VALUES
                (:name, :category, :price, :stock, :sold, :image)
        ");

        $stmt->execute([
            ':name'     => $name,
            ':category' => $category,
            ':price'    => $price,
            ':stock'    => $stock,
            ':sold'     => $sold,
            ':image'    => $image
        ]);

        echo "<script>
            alert('Produk berhasil ditambahkan');
            window.location.href = 'index.php';
        </script>";
        exit;

    } catch (PDOException $e) {
        // log error (server-side)
        error_log($e->getMessage());

        echo "<script>
            alert('Gagal menambahkan produk');
            history.back();
        </script>";
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include "../partials/header.php"; ?>
<?php include "../partials/sidebar.php"; ?>

<div class="page-container">

    <div class="edit-card">
        <h2>Tambah Produk</h2>

        <form method="post" action="create.php">

            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="category" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="makanan">Makanan</option>
                    <option value="minuman">Minuman</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>

            <div class="form-group">
                <label>Harga</label>
                <input type="number" name="price" required>
            </div>

            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stock" required>
            </div>

            <div class="form-group">
                <label>Terjual</label>
                <input type="number" name="sold" value="0">
            </div>

            <div class="form-group">
                <label>Image URL</label>
                <input type="text" name="image">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary" name="submit_create">
                    Simpan Produk
                </button>
                <a href="index.php" class="btn-secondary">
                    Batal
                </a>
            </div>

        </form>
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

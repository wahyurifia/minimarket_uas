<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}
include __DIR__ . '/../api/database/config.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_update'])) {

    $id = (int) ($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = (int) ($_POST['price'] ?? 0);
    $stock = (int) ($_POST['stock'] ?? 0);
    $sold = (int) ($_POST['sold'] ?? 0);
    $image = trim($_POST['image'] ?? '');

    if ($id <= 0 || $name === '' || $category === '') {
        echo "<script>
            alert('Data tidak valid');
            history.back();
        </script>";
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE products SET
                name = :name,
                category = :category,
                price = :price,
                stock = :stock,
                sold = :sold,
                image = :image
            WHERE id = :id
            ");

        $stmt->execute([
            ':id'   => $id,
            ':name'   => $name,
            ':category'   => $category,
            ':price'   => $price,
            ':stock'   => $stock,
            ':sold'   => $sold,
            ':image'   => $image,
        ]);

        die("<script>
                alert('Produk berhasil diperbarui');
                window.location.href = 'index.php';
            </script>");

    } catch (PDOException $e) {
            echo "<script>
                alert('Gagal update produk');
                history.back();
            </script>"; 
    }
}

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Produk tidak ditemukan");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include "../partials/header.php"; ?>
<?php include "../partials/sidebar.php"; ?>

    <div class="page-container">
        <div class="edit-card">
            <h2>Edit Produk</h2>

            <form method="post" action="update.php">

                <input type="hidden" name="id" value="<?= $product['id'] ?>">

                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="category">
                        <?php foreach (['makanan','minuman','lainnya'] as $cat): ?>
                            <option value="<?= $cat ?>" <?= $product['category']===$cat?'selected':'' ?>>
                                <?= ucfirst($cat) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Harga</label>
                    <input type="number" name="price" value="<?= $product['price'] ?>" required>
                </div>

                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stock" value="<?= $product['stock'] ?>" required>
                </div>

                <div class="form-group">
                    <label>Terjual</label>
                    <input type="number" name="sold" value="<?= $product['sold'] ?>">
                </div>

                <div class="form-group">
                    <label>Image URL</label>
                    <input type="text" name="image" value="<?= $product['image'] ?>">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary" name="submit_update">Simpan Perubahan</button>
                    <a href="index.php" class="btn-secondary">Batal</a>
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

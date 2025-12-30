<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}
include __DIR__ . '/../api/database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_create'])) {

    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $pass    = trim($_POST['pass'] ?? '');
    $role     = trim($_POST['role'] ?? '');

    // Validasi minimal
    if ($name === '' || $email === '' || $role === '') {
        die("<script>
            alert('Data tidak valid');
            history.back();
        </script>");
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO users
                (name, email, role, pass)
            VALUES
                (:name, :email, :role, :pass)
        ");

        $stmt->execute([
            ':name'     => $name,
            ':email' => $email,
            ':role'    => $role,
            ':pass'    => $pass
        ]);

        echo "<script>
            alert('User berhasil ditambahkan');
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
    <title>Add User</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include "../partials/header.php"; ?>
<?php include "../partials/sidebar.php"; ?>

<div class="page-container">
    <div class="edit-card">
        <h2>Tambah User</h2>

        <form method="post" action="create.php">

            <input type="hidden" name="id" >

            <div class="form-group">
                <label>Nama User</label>
                <input type="text" name="name"  required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email"  required>
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="admin">Admin</option>
                    <option value="kasir">Kasir</option>
                    <option value="pelanggan">Pelanggan</option>

                </select>
            </div>

             <div class="form-group">
                <label>Password</label>
                <input type="password" name="pass"  required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary" name="submit_create">Simpan Perubahan</button>
                <a href="index.php" class="btn-secondary">Batal</a>
            </div>

        </form>
    </div>
</div>

<script>
// JS hanya untuk UI sidebar toggle
const toggleBtn = document.getElementById('toggleSidebar');
const sidebar = document.getElementById('sidebar');
toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
});
</script>
</body>
</html>

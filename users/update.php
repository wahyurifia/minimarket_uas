<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}
include __DIR__ . '/../api/database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_update'])) {

    $id    = (int) ($_POST['id'] ?? 0);
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role  = trim($_POST['role'] ?? '');

    if ($id <= 0 || $name === '' || $email === '') {
        die("<script>alert('Data tidak valid');history.back();</script>");
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE users SET
                name = :name,
                email = :email,
                role = :role
            WHERE id = :id
            ");

        $stmt->execute([
            ':id'    => $id,
            ':name'  => $name,
            ':email' => $email,
            ':role'  => $role,
        ]);

        die("<script>
            alert('User berhasil diperbarui');
            window.location.href = 'index.php';
        </script>");

    } catch (PDOException $e) {
        error_log($e->getMessage());
        die("<script>alert('Gagal update user');history.back();</script>");
    }
}

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User tidak ditemukan");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include "../partials/header.php"; ?>
<?php include "../partials/sidebar.php"; ?>

<div class="page-container">
    <div class="edit-card">
        <h2>Edit User</h2>

        <form method="post" action="update.php">

            <input type="hidden" name="id" value="<?= $user['id'] ?>">

            <div class="form-group">
                <label>Nama User</label>
                <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" required>
                    <?php foreach (['admin','kasir','pelanggan'] as $role): ?>
                        <option value="<?= $role ?>" <?= $user['role'] === $role ? 'selected' : '' ?>>
                            <?= ucfirst($role) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary" name="submit_update">Simpan Perubahan</button>
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

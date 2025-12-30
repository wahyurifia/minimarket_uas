<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}
// include koneksi database
include __DIR__ . '/../api/database/config.php';

$stmt = $pdo->query("SELECT * FROM users ORDER BY id ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User List</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include "../partials/header.php"; ?>
<?php include "../partials/sidebar.php"; ?>

<div class="content_page">
    <div class="filter-bar">
        <h2>User List</h2>
        <a href="create.php" class="btn-add">+ Add User</a>
    </div>

    <div class="table-card">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <span class="badge role-<?= $user['role'] ?>">
                            <?= ucfirst($user['role']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="update.php?id=<?= $user['id'] ?>" class=" btn-secondary link-edit">Edit</a>
                        <a href="delete.php?id=<?= $user['id'] ?>" class="btn-danger"onclick="return confirm('Yakin ingin menghapus user ini?')">🗑️</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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

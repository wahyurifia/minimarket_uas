<?php
$role = $_SESSION['user']['role'] ?? '';
?>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <button id="toggleSidebar">☰</button>
    </div>

    <ul class="menu">
        <!-- Dashboard (admin only) -->
        <li>
            <a href="/minimarket_wahyurifiarizki/">
                <span>🏠</span><span class="text">Dashboard</span>
            </a>
        </li>

        <!-- Users (admin only) -->
        <?php if ($role === 'admin'): ?>
            <li>
                <a href="/minimarket_wahyurifiarizki/users">
                    <span>👤</span><span class="text">Users</span>
                </a>
            </li>
        <?php endif; ?>

        <!-- Products (admin, kasir) -->
        <?php if (in_array($role, ['admin', 'kasir'])): ?>
            <li>
                <a href="/minimarket_wahyurifiarizki/products">
                    <span>📝</span><span class="text">Products</span>
                </a>
            </li>
        <?php endif; ?>

        <!-- Order (admin full, kasir view) -->
        <?php if (in_array($role, ['admin', 'kasir', 'pelanggan'])): ?>
            <li>
                <a href="/minimarket_wahyurifiarizki/order">
                    <span>🗂️</span><span class="text">Order</span>
                </a>
            </li>
        <?php endif; ?>

        <!-- Logout (semua role) -->
        <li>
            <a href="/minimarket_wahyurifiarizki/auth/logout.php">
                <span>➜]</span><span class="text">Logout</span>
            </a>
        </li>
    </ul>
</div>

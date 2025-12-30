<?php
$username = $_SESSION['user']['name'];
?>

<div class="header">
    <div class="header-left">
        <h1>Minimarket _ Wahyu Rifia Rizki</h1>
    </div>

    <div class="header-right">
        <span class="user"><?= htmlspecialchars($username) ?></span>
        </div>
</div>

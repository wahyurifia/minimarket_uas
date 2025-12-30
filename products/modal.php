<?php if ($selectedProduct): ?>
<div class="modal-overlay active">
  <div class="modal">

    <img src="<?= $selectedProduct['image'] ?>" alt="<?= $selectedProduct['name'] ?>">

    <h3><?= $selectedProduct['name'] ?></h3>

    <p>Kategori: <?= ucfirst($selectedProduct['category']) ?></p>
    <p>Harga: Rp <?= number_format($selectedProduct['price'],0,',','.') ?></p>
    <p>Stok: <?= $selectedProduct['stock'] ?></p>
    <p>Terjual: <?= $selectedProduct['sold'] ?></p>
 
   <form method="get" action="update.php" style="display:flex; gap:10px; align-items:center;">
    
    <a href="index.php" class="btn-secondary">
        Back
    </a>

    <input type="hidden" name="id" value="<?= $selectedProduct['id'] ?>">

    <!-- UPDATE -->
    <button type="submit" class="btn-primary">
        Update Produk
    </button>

    <!-- DELETE -->
    <a 
        href="delete.php?id=<?= $selectedProduct['id'] ?>"
        class="btn-danger"
        title="Hapus Produk"
        onclick="return confirm('Yakin ingin menghapus produk ini?')"
    >
        🗑️
    </a>

</form>

  </div>
</div>
<?php endif; ?>

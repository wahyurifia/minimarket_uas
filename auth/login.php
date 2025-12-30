<?php
session_start();

// Kalau sudah login, jangan balik ke login
if (isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Admin Minimarket</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
<div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-md">

  <!-- Logo -->
  <div class="text-center mb-6">
    <div class="w-20 h-20 mx-auto bg-gray-950 rounded-full flex items-center justify-center text-white text-3xl font-bold">
      M
    </div>
    <h2 class="mt-4 text-xl font-semibold text-gray-700">Minimarket Admin Panel</h2>
    <p class="text-sm text-gray-500">Silakan login untuk melanjutkan</p>
  </div>

  <!-- Error -->
  <?php if (isset($_SESSION['error'])): ?>
    <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
  <?php endif; ?>

  <!-- Form -->
  <form action="login_process.php" method="POST">
    <label class="block mb-3">
      <span class="text-gray-700 font-medium">Email</span>
      <input type="email" name="email" required
        class="mt-1 block w-full border rounded-lg px-3 py-2">
    </label>

    <label class="block mb-5">
      <span class="text-gray-700 font-medium">Password</span>
      <input type="password" name="pass" required
        class="mt-1 block w-full border rounded-lg px-3 py-2">
    </label>

    <button type="submit"
      class="w-full bg-gray-950 hover:bg-gray-600 text-white py-2 rounded-lg font-semibold">
      Login
    </button>
  </form>

</div>
</body>
</html>

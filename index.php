<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Studi Kasus CRUD & Enkripsi</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>

  <div class="container">
    <?php if (isset($_SESSION['user'])): ?>
      <!-- Halaman Pengguna (Sudah Login) -->
      <div id="dashboard-box">
        <h2>Selamat Datang!</h2>
        <p><strong>Nama:</strong> <span id="user-nama"><?= htmlspecialchars($_SESSION['user']['nama']); ?></span></p>
        <p><strong>Username:</strong> <span id="user-username"><?= htmlspecialchars($_SESSION['user']['username']); ?></span></p>

        <div class="action-buttons">
          <button id="logout-btn" class="btn-warning">Keluar</button>
          <button id="delete-btn" class="btn-danger">Hapus Akun</button>
        </div>
      </div>
    <?php else: ?>
      <!-- Halaman Auth (Form Login / Register) -->
      <div id="auth-box">
        <h2>Form Pengguna</h2>
        <div id="alert-message" class="alert hidden"></div>

        <form id="auth-form">
          <div class="form-group">
            <label for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" placeholder="Masukkan nama">
          </div>

          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Masukkan username" required>
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Masukkan password" required>
          </div>

          <div class="button-group">
            <button type="button" id="login-btn" class="btn-primary">Masuk</button>
            <button type="button" id="register-btn" class="btn-secondary">Daftar</button>
          </div>
        </form>
      </div>
    <?php endif; ?>
  </div>

  <script src="script.js"></script>
</body>

</html>
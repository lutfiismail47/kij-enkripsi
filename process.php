<?php
session_start();
header('Content-Type: application/json');
require_once 'db.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// 1. DAFTAR (CREATE)
if ($action === 'register') {
  $nama = trim($_POST['nama'] ?? '');
  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');

  if (empty($nama) || empty($username) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Harap isi semua kolom (Nama, Username, Password) untuk mendaftar."]);
    exit;
  }

  // Cek apakah username sudah dipakai
  $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Username ini sudah digunakan oleh orang lain!"]);
    exit;
  }

  // Hash password sebelum disimpan ke database
  $hashed_password = password_hash($password, PASSWORD_BCRYPT);

  $stmt = $conn->prepare("INSERT INTO users (nama, username, password) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $nama, $username, $hashed_password);

  if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Pendaftaran berhasil! Silakan klik Masuk."]);
  } else {
    echo json_encode(["status" => "error", "message" => "Gagal mendaftar."]);
  }
}

// 2. MASUK (READ / AUTH)
elseif ($action === 'login') {
  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');

  if (empty($username) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Masukkan username dan password."]);
    exit;
  }

  // Cari user berdasarkan username
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Username tidak ditemukan. Anda belum mendaftar, silakan mendaftar terlebih dahulu."]);
    exit;
  }

  $user = $result->fetch_assoc();

  // Verifikasi hash password
  if (password_verify($password, $user['password'])) {
    $_SESSION['user'] = [
      'id' => $user['id'],
      'nama' => $user['nama'],
      'username' => $user['username']
    ];
    echo json_encode(["status" => "success", "message" => "Login berhasil!"]);
  } else {
    echo json_encode(["status" => "error", "message" => "Password salah!"]);
  }
}

// 3. KELUAR (LOGOUT)
elseif ($action === 'logout') {
  session_destroy();
  echo json_encode(["status" => "success", "message" => "Berhasil keluar."]);
}

// 4. HAPUS DATA (DELETE)
elseif ($action === 'delete') {
  if (!isset($_SESSION['user'])) {
    echo json_encode(["status" => "error", "message" => "Sesi tidak valid."]);
    exit;
  }

  $userId = $_SESSION['user']['id'];
  $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
  $stmt->bind_param("i", $userId);

  if ($stmt->execute()) {
    session_destroy();
    echo json_encode(["status" => "success", "message" => "Akun berhasil dihapus."]);
  } else {
    echo json_encode(["status" => "error", "message" => "Gagal menghapus akun."]);
  }
}

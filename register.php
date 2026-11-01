<?php
require_once "db_connect.php";

// Check if the form is submited
if (isset($_POST["submit"])) {

  // Filter user input
  function sanitize_input($data)
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, "UTF-8");

    return $data;
  }

  // Store user input
  $name = sanitize_input($_POST["name"]);
  $username = sanitize_input($_POST["username"]);
  $password = sanitize_input($_POST["password"]);
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  $sql = "SELECT id FROM users WHERE username = ?";

  // Prepare SQL statement
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      echo "<script>alert('Username sudah digunakan! Silakan pilih username lain.'); window.location='register.html';</script>";
    } else {
      // Username tersedia, lanjutkan proses INSERT ke database
      $insert_sql = "INSERT INTO users (name, username, password) VALUES (?, ?, ?)";
      $insert_stmt = $conn->prepare($insert_sql);

      if ($insert_stmt) {
        $insert_stmt->bind_param("sss", $name, $username, $hashedPassword);

        // Eksekusi query insert
        if ($insert_stmt->execute()) {
          echo "Pendaftaran berhasil! Akun dengan username <b>$username</b> telah dibuat.";
        } else {
          echo "Gagal mendaftarkan akun: " . $insert_stmt->error;
        }

        // Tutup insert statement
        $insert_stmt->close();
      }
    }

    $stmt->close();
  } else {
    echo "Terjadi kesalahan pada sistem: " . $conn->error;
  }
}

$conn->close();

document.addEventListener("DOMContentLoaded", () => {
  const authForm = document.getElementById("auth-form");
  const alertBox = document.getElementById("alert-message");
  const loginBtn = document.getElementById("login-btn");
  const registerBtn = document.getElementById("register-btn");
  const logoutBtn = document.getElementById("logout-btn");
  const deleteBtn = document.getElementById("delete-btn");

  function showAlert(msg, type = "error") {
    if (!alertBox) return;
    alertBox.textContent = msg;
    alertBox.className = `alert alert-${type}`;
    alertBox.classList.remove("hidden");
  }

  // Handler Tombol Masuk (Login)
  if (loginBtn) {
    loginBtn.addEventListener("click", () => {
      const formData = new FormData(authForm);
      formData.append("action", "login");

      fetch("process.php", { method: "POST", body: formData })
        .then((res) => res.json())
        .then((data) => {
          if (data.status === "success") {
            window.location.reload();
          } else {
            showAlert(data.message, "error");
          }
        });
    });
  }

  // Handler Tombol Daftar (Register)
  if (registerBtn) {
    registerBtn.addEventListener("click", () => {
      const formData = new FormData(authForm);
      formData.append("action", "register");

      fetch("process.php", { method: "POST", body: formData })
        .then((res) => res.json())
        .then((data) => {
          if (data.status === "success") {
            showAlert(data.message, "success");
          } else {
            showAlert(data.message, "error");
          }
        });
    });
  }

  // Handler Tombol Keluar (Logout)
  if (logoutBtn) {
    logoutBtn.addEventListener("click", () => {
      const formData = new FormData();
      formData.append("action", "logout");

      fetch("process.php", { method: "POST", body: formData })
        .then((res) => res.json())
        .then(() => window.location.reload());
    });
  }

  // Handler Tombol Hapus Data (Delete)
  if (deleteBtn) {
    deleteBtn.addEventListener("click", () => {
      if (confirm("Apakah Anda yakin ingin menghapus akun Anda?")) {
        const formData = new FormData();
        formData.append("action", "delete");

        fetch("process.php", { method: "POST", body: formData })
          .then((res) => res.json())
          .then((data) => {
            alert(data.message);
            window.location.reload();
          });
      }
    });
  }
});

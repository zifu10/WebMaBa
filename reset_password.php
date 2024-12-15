<?php
session_start();
include 'config.php';

// Variabel untuk pesan sukses/gagal
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_id = $_SESSION['user_id'];

    // Ambil password saat ini dari database
    $sql = "SELECT password FROM users WHERE id = '$user_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password saat ini
        if (password_verify($current_password, $user['password'])) {
            // Cek kesesuaian password baru dan konfirmasi
            if ($new_password === $confirm_password) {
                $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update password di database
                $update_sql = "UPDATE users SET password='$hashed_new_password' WHERE id='$user_id'";
                if ($conn->query($update_sql) === TRUE) {
                    $message = "<div class='message success'>Password berhasil diubah!</div>";
                } else {
                    $message = "<div class='message error'>Terjadi kesalahan saat mengubah password.</div>";
                }
            } else {
                $message = "<div class='message error'>Password baru dan konfirmasi tidak cocok!</div>";
            }
        } else {
            $message = "<div class='message error'>Password saat ini salah!</div>";
        }
    } else {
        $message = "<div class='message error'>Terjadi kesalahan. Pengguna tidak ditemukan.</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <link rel="stylesheet" href="form.css">
</head>
<body>
  <section class="form-container">
    <div class="form-section">
      <h2>Reset Password</h2>
      <!-- Tampilkan pesan notifikasi -->
      <?php echo $message; ?>
      <form method="POST">
        <label for="current_password">Password Saat Ini:</label>
        <input type="password" id="current_password" name="current_password" required>

        <label for="new_password">Password Baru:</label>
        <input type="password" id="new_password" name="new_password" required>

        <label for="confirm_password">Konfirmasi Password Baru:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <button type="submit" class="btn">Reset Password</button>
      </form>
      <div class="navigation-links">
        <a href="profile.php" class="btn">Kembali ke Profil</a>
        <a href="Home.html" class="btn">Home</a>
      </div>
    </div>
  </section>
</body>
</html>
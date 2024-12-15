<?php
session_start();
include 'config.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Periksa apakah form konfirmasi telah dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        // Ambil user_id dari sesi
        $user_id = $_SESSION['user_id'];

        // Hapus data pengguna dari database
        $sql = "DELETE FROM users WHERE id = '$user_id'";
        if ($conn->query($sql) === TRUE) {
            // Hapus sesi dan arahkan ke halaman index
            session_destroy();
            header("Location: index.html");
            exit();
        } else {
            echo "Terjadi kesalahan saat menghapus akun: " . $conn->error;
        }
        $conn->close();
    } else {
        // Jika memilih "Tidak", kembali ke halaman profil
        header("Location: profile.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Hapus Akun</title>
    <link rel="stylesheet" href="delete_account.css">
</head>
<body>
    <div class="confirmation-container">
        <div class="confirmation-box">
            <h2>Apakah Anda yakin ingin menghapus akun?</h2>
            <p>Klik “Ya” jika ingin menghapus akun</p>
            <form method="POST">
                <button type="submit" name="confirm" value="yes" class="confirm-btn">Ya</button>
                <button type="submit" name="confirm" value="no" class="cancel-btn">Tidak</button>
            </form>
        </div>
    </div>
</body>
</html>
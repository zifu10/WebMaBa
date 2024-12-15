<?php
session_start();
include 'config.php';

// Periksa apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Ambil data dari sesi
$user_id = $_SESSION['user_id'];

// Validasi POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategori_donasi = trim($_POST['kategori_donasi']);
    $jenis_bantuan = trim($_POST['jenis_bantuan']);
    $deskripsi = trim($_POST['deskripsi']);
    $nominal = isset($_POST['nominal']) && $_POST['nominal'] !== '' ? trim($_POST['nominal']) : null;
    $nama_barang = isset($_POST['nama_barang']) && $_POST['nama_barang'] !== '' ? trim($_POST['nama_barang']) : null;

    // Validasi apakah kategori, jenis bantuan, dan deskripsi diisi
    if (!empty($kategori_donasi) && !empty($jenis_bantuan) && !empty($deskripsi)) {
        // Masukkan data ke database
        $stmt = $conn->prepare("
            INSERT INTO bantuan (user_id, kategori_donasi, jenis_bantuan, deskripsi, nominal, nama_barang) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("isssss", $user_id, $kategori_donasi, $jenis_bantuan, $deskripsi, $nominal, $nama_barang);

        if ($stmt->execute()) {
            // Menampilkan pesan berhasil
            echo '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Pengajuan Berhasil</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        margin: 0;
                        background-color: #f0f8ff;
                    }
                    .success-box {
                        text-align: center;
                        padding: 20px 40px;
                        background-color: #e7f5e1;
                        border: 2px solid #4caf50;
                        border-radius: 10px;
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                    }
                    .success-box h2 {
                        color: #4caf50;
                        margin-bottom: 10px;
                    }
                    .success-box p {
                        color: #333;
                        margin-bottom: 20px;
                    }
                    .success-box a {
                        text-decoration: none;
                        background-color: #4caf50;
                        color: white;
                        padding: 10px 20px;
                        border-radius: 5px;
                    }
                    .success-box a:hover {
                        background-color: #45a049;
                    }
                </style>
            </head>
            <body>
                <div class="success-box">
                    <h2>Pengajuan Berhasil!</h2>
                    <p>Pengajuan bantuan Anda berhasil terkirim. Terima kasih atas kontribusi Anda!</p>
                    <a href="PengajuanBantuan.php">Kembali ke Halaman Pengajuan</a>
                </div>
            </body>
            </html>
            ';
        } else {
            echo "Terjadi kesalahan saat menyimpan data: " . $stmt->error;
        }
    } else {
        echo '
        <script>
            alert("Harap isi semua data wajib!");
            window.location.href = "submit_form_bantuan.php";
        </script>
        ';
    }
}

$conn->close();
?>
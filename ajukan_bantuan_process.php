<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $jenis_bantuan = $_POST['jenis_bantuan'];
    $deskripsi = $_POST['deskripsi'];

    // Jika jenis bantuan "Lainnya", ambil nilai dari input tambahan
    if ($jenis_bantuan === "Lainnya" && !empty($_POST['jenis_bantuan_lainnya'])) {
        $jenis_bantuan = $_POST['jenis_bantuan_lainnya'];
    }

    // Menyimpan data ke database
    $sql = "INSERT INTO bantuan (name, email, phone, jenis_bantuan, deskripsi) 
            VALUES ('$name', '$email', '$phone', '$jenis_bantuan', '$deskripsi')";

    if ($conn->query($sql) === TRUE) {
        // Menampilkan pesan konfirmasi
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Pengajuan Tersimpan</title>
            <style>
                body { font-family: Arial, sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; background-color: #f0f4f8; }
                .confirmation-box { text-align: center; padding: 30px; border-radius: 8px; background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
                .confirmation-box h2 { margin-bottom: 10px; }
                .confirmation-box p { margin-bottom: 20px; }
            </style>
        </head>
        <body>
            <div class="confirmation-box">
                <h2>Pengajuan Bantuan Berhasil!</h2>
                <p>Pengajuan bantuan Anda telah tersimpan. Anda akan segera diarahkan kembali ke halaman pengajuan.</p>
            </div>
        </body>
        </html>
        ';
        header("refresh:3;url=PengajuanBantuan.html"); // Redirect setelah 3 detik
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
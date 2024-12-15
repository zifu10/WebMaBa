<?php
session_start();
include 'config.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Periksa apakah ID bantuan diberikan melalui URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: PengajuanBantuan.php");
    exit();
}

// Ambil ID bantuan dari URL
$bantuan_id = intval($_GET['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Hapus Bantuan</title>
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styling */
        body {
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f9f9f9;
            overflow: hidden;
            position: relative;
        }

        /* Overlay Background Blur */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(8px);
            background: rgba(0, 0, 0, 0.4);
            z-index: 10;
        }

        /* Confirmation Box Styling */
        .confirmation-box {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #6e7e8d;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            z-index: 20;
            width: 400px;
            color: white;
        }

        .confirmation-box h2 {
            font-size: 20px;
            margin-bottom: 15px;
        }

        .confirmation-box p {
            font-size: 14px;
            margin-bottom: 20px;
            color: #e0e0e0;
        }

        /* Buttons Styling */
        .buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .confirm-btn {
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #4caf50;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .cancel-btn {
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #f44336;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
        }

        .confirm-btn:hover {
            background-color: #388e3c;
        }

        .cancel-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="confirmation-box">
        <h2>Apakah Anda yakin ingin menghapus?</h2>
        <p>Klik "Ya" jika ingin menghapus</p>
        <form method="POST" action="delete_bantuan.php">
            <!-- Kirim ID bantuan melalui input hidden -->
            <input type="hidden" name="bantuan_id" value="<?php echo htmlspecialchars($bantuan_id); ?>">
            <div class="buttons">
                <button type="submit" class="confirm-btn">Ya</button>
                <a href="PengajuanBantuan.php" class="cancel-btn">Tidak</a>
            </div>
        </form>
    </div>
</body>
</html>
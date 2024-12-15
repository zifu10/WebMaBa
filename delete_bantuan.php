<?php
session_start();
include 'config.php';

// Periksa apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Validasi parameter ID dari metode POST
if (isset($_POST['bantuan_id']) && is_numeric($_POST['bantuan_id'])) {
    $id = intval($_POST['bantuan_id']); // Konversi ke integer untuk keamanan

    // Periksa apakah bantuan dengan ID tersebut ada dan dimiliki oleh user yang login
    $user_id = $_SESSION['user_id'];
    $check_sql = "SELECT id FROM bantuan WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Jika bantuan ditemukan, hapus bantuan
        $delete_sql = "DELETE FROM bantuan WHERE id = ? AND user_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("ii", $id, $user_id);

        if ($delete_stmt->execute()) {
            // Berhasil menghapus, arahkan kembali ke halaman PengajuanBantuan
            header("Location: PengajuanBantuan.php");
            exit();
        } else {
            echo "Terjadi kesalahan saat menghapus data: " . $conn->error;
        }
    } else {
        echo "Data tidak ditemukan atau Anda tidak memiliki akses.";
    }

    $stmt->close();
} else {
    echo "ID tidak valid.";
}

$conn->close();
?>
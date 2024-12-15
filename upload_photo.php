<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_photo'])) {
    $user_id = $_SESSION['user_id'];
    $photo = $_FILES['profile_photo'];

    // Validasi file
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($photo['type'], $allowed_types)) {
        echo "File harus berupa gambar (JPEG, PNG, atau GIF).";
        exit();
    }

    // Validasi ukuran file (maks 2MB)
    if ($photo['size'] > 2 * 1024 * 1024) {
        echo "Ukuran file tidak boleh lebih dari 2MB.";
        exit();
    }

    // Tentukan lokasi penyimpanan
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $new_filename = uniqid() . "-" . basename($photo['name']);
    $upload_path = $upload_dir . $new_filename;

    if (move_uploaded_file($photo['tmp_name'], $upload_path)) {
        // Hapus foto lama jika ada
        $sql = "SELECT profile_photo FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['profile_photo'] != 'default.png') {
            unlink($upload_dir . $row['profile_photo']);
        }

        // Update database
        $sql = "UPDATE users SET profile_photo = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_filename, $user_id);
        if ($stmt->execute()) {
            header("Location: profile.php");
        } else {
            echo "Gagal mengunggah foto.";
        }
    } else {
        echo "Gagal memindahkan file.";
    }
}
?>
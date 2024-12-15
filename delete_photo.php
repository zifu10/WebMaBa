<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $default_photo = 'default.png';

    // Ambil nama foto lama
    $sql = "SELECT profile_photo FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['profile_photo'] != $default_photo) {
        unlink("uploads/" . $row['profile_photo']);
    }

    // Update foto profil ke default
    $sql = "UPDATE users SET profile_photo = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $default_photo, $user_id);
    if ($stmt->execute()) {
        header("Location: profile.php");
    } else {
        echo "Gagal menghapus foto.";
    }
}
?>
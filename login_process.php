<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $password = $_POST['password'];

    // Query database
    $sql = "SELECT * FROM users WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Simpan informasi pengguna dalam sesi
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_phone'] = $user['phone'];
            
            header("Location: home.html");
            exit();
        } else {
            showLoginError("Password salah. Silakan coba lagi.");
        }
    } else {
        showLoginError("Akun dengan nama tersebut tidak ditemukan.");
    }
}

function showLoginError($message) {
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Failed</title>
        <link rel="stylesheet" href="login_fail.css">
    </head>
    <body>
        <div class="failed-container">
            <div class="failed-box">
                <div style="font-size: 50px; color: #dc3545;">&#x2716;</div>
                <h2>Login Failed</h2>
                <p>' . $message . '</p>
                <a href="login.html" class="failed-button">Back</a>
            </div>
        </div>
    </body>
    </html>
    ';
    exit();
}

$conn->close();
?>
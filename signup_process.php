<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $role = $_POST['role'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi jika password dan confirm password tidak cocok
    if ($password !== $confirm_password) {
        displayError("Passwords do not match. Please try again.");
        exit();
    }

    // Validasi jika username/email sudah ada
    $check_sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($check_sql);
    if ($result->num_rows > 0) {
        displayError("The email is already registered. Please use another email.");
        exit();
    }

    // Hashing password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Menyimpan data ke database
    $sql = "INSERT INTO users (name, email, phone, address, role, password) 
            VALUES ('$name', '$email', '$phone', '$address', '$role', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        // Jika berhasil
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Registration Successful</title>
            <link rel="stylesheet" href="signup_success.css">
        </head>
        <body>
            <div class="success-container">
                <div class="success-box">
                    <div class="success-icon">✔</div>
                    <h2>Register Success!</h2>
                    <p>Register successfully! We will proceed it to the Authorized Party.<br>
                    Please Kindly Login Again.
                    </p>
                    <a href="login.html" class="success-button">Login</a>
                </div>
            </div>
        </body>
        </html>
        ';
    } else {
        displayError("An error occurred while processing your registration. Please try again.");
    }

    $conn->close();
}

// Fungsi untuk menampilkan UI gagal
function displayError($errorMessage) {
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register Failed</title>
        <link rel="stylesheet" href="signup_fail.css">
    </head>
    <body>
        <div class="failed-container">
            <div class="failed-box">
                <img src="images/failed-icon.png" alt="Failed Icon" class="failed-icon">
                <h2>Register Failed!</h2>
                <p>' . $errorMessage . '</p>
                <a href="signup.html" class="failed-button">Back</a>
            </div>
        </div>
    </body>
    </html>
    ';
}
?>
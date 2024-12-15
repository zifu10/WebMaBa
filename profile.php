<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Fetch user data from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT name, email, phone, address, role, profile_photo FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Pengguna</title>
  <link rel="stylesheet" href="profile.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
  <!-- Header -->
  <header>
    <div class="logo">
      <h1>MABA</h1>
      <p>Mari Bangkit</p>
    </div>
    <nav>
      <ul>
        <li><a href="https://wa.me/6282389074739?text=Halo%20MABA%20Mari%20Bangkit">Contact</a></li>
        <li><a href="https://facebook.com" target="_blank"><img src="images/Facebook.png" alt="Facebook"></a></li>
        <li><a href="https://instagram.com" target="_blank"><img src="images/Instagram.png" alt="Instagram"></a></li>
        <li><a href="https://linkedin.com" target="_blank"><img src="images/LinkedIn.png" alt="LinkedIn"></a></li>
      </ul>
    </nav>
  </header>

  <section class="profile-section">
    <h2>Profil Pengguna</h2>
    <div class="profile-photo">
        <?php if (empty($user['profile_photo']) || $user['profile_photo'] === 'default.png'): ?>
            <i class="bi bi-person-circle profile-icon"></i>
        <?php else: ?>
            <img src="uploads/<?php echo htmlspecialchars($user['profile_photo']); ?>" alt="Foto Profil" class="profile-image">
        <?php endif; ?>
    </div>
    <p><strong>Nama:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p><strong>Nomor Telepon:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
    <p><strong>Alamat:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
    <p><strong>Peran:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
    <div class="profile-buttons">
      <a href="update_profile.php" class="btn">Update Profil</a>
      <a href="reset_password.php" class="btn">Reset Password</a>
      <form action="delete_account.php" method="POST" style="display: inline;">
        <button type="submit" class="btn delete-account">Hapus Akun</button>
      </form>
      <a href="index.html" class="btn logout">Logout</a>
      <a href="Home.html" class="btn back-home">Home</a>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <p>© 2024 Mari Bangkit. Semua Hak Dilindungi. Kebijakan Privasi.</p>
    <div class="social-media">
      <a href="https://facebook.com" target="_blank"><img src="images/Facebook.png" alt="Facebook"></a>
      <a href="https://instagram.com" target="_blank"><img src="images/Instagram.png" alt="Instagram"></a>
      <a href="https://linkedin.com" target="_blank"><img src="images/LinkedIn.png" alt="LinkedIn"></a>
    </div>
  </footer>
</body>
</html>

<?php
$conn->close();
?>
<?php
session_start();
include 'config.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Ambil user_id dari sesi
$user_id = $_SESSION['user_id'];

// Ambil data pengguna dari database
$sql = "SELECT name, email, phone, address, role, profile_photo FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Pengguna tidak ditemukan.";
    exit();
}

// Proses form
$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update data umum
    if (isset($_POST['update_profile'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $role = $_POST['role'];

        $sql = "UPDATE users SET name='$name', email='$email', phone='$phone', address='$address', role='$role' WHERE id='$user_id'";
        if ($conn->query($sql) === TRUE) {
            $message = "Profil berhasil diperbarui!";
        } else {
            $message = "Gagal memperbarui profil: " . $conn->error;
        }
    }

    // Hapus foto profil
    if (isset($_POST['delete_photo'])) {
        $sql = "UPDATE users SET profile_photo='default.png' WHERE id='$user_id'";
        if ($conn->query($sql) === TRUE) {
            $message = "Foto profil berhasil dihapus.";
        } else {
            $message = "Gagal menghapus foto: " . $conn->error;
        }
    }

    // Unggah dan crop foto profil
    if (!empty($_FILES['profile_photo']['tmp_name']) && isset($_POST['crop_data'])) {
        $cropData = json_decode($_POST['crop_data'], true);
        $srcImage = imagecreatefromstring(file_get_contents($_FILES['profile_photo']['tmp_name']));
        if ($srcImage === false) {
            $message = "File gambar tidak valid.";
        } else {
            $croppedImage = imagecrop($srcImage, [
                'x' => $cropData['x'],
                'y' => $cropData['y'],
                'width' => $cropData['width'],
                'height' => $cropData['height']
            ]);

            if ($croppedImage !== false) {
                $fileName = 'cropped_' . time() . '.png';
                $filePath = 'uploads/' . $fileName;
                imagepng($croppedImage, $filePath);
                imagedestroy($croppedImage);

                $sql = "UPDATE users SET profile_photo='$fileName' WHERE id='$user_id'";
                if ($conn->query($sql) === TRUE) {
                    $message = "Foto profil berhasil diperbarui!";
                } else {
                    $message = "Gagal memperbarui foto: " . $conn->error;
                }
            } else {
                $message = "Gagal memotong foto.";
            }
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Profil</title>
  <link rel="stylesheet" href="form.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
</head>
<body>
<header>
    <div class="logo">
        <h1>MABA</h1>
        <p>Mari Bangkit</p>
    </div>
    <nav>
        <ul>
            <li><a href="https://wa.me/6282389074739">Contact</a></li>
            <li><a href="https://facebook.com" target="_blank"><img src="images/Facebook.png" alt="Facebook"></a></li>
            <li><a href="https://instagram.com" target="_blank"><img src="images/Instagram.png" alt="Instagram"></a></li>
            <li><a href="https://linkedin.com" target="_blank"><img src="images/LinkedIn.png" alt="LinkedIn"></a></li>
        </ul>
    </nav>
</header>

  <section class="form-section">
    <h2>Update Profil</h2>
    <?php if (!empty($message)): ?>
      <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <!-- Tampilkan Foto Profil -->
    <div class="profile-photo">
        <?php if ($user['profile_photo'] == 'default.png' || empty($user['profile_photo'])): ?>
            <i class="bi bi-person-circle profile-icon"></i>
        <?php else: ?>
            <img id="profile-image" src="uploads/<?php echo htmlspecialchars($user['profile_photo']); ?>" alt="Foto Profil" class="profile-image">
        <?php endif; ?>
    </div>

    <!-- Form Update Foto Profil -->
    <div class="photo-actions">
      <form id="profileForm" method="POST" enctype="multipart/form-data">
          <label for="profile_photo" class="btn btn-upload">Unggah Foto Profil</label>
          <input type="file" id="profile_photo" name="profile_photo" accept="image/*">
          <input type="hidden" name="crop_data" id="crop_data">
          <div id="crop-container" style="display: none;">
              <img id="crop-image" style="max-width: 100%;">
              <button type="button" id="crop-button" class="btn btn-upload">Crop & Unggah</button>
          </div>
      </form>
      <form method="POST">
          <button type="submit" name="delete_photo" class="btn btn-delete">Hapus Foto Profil</button>
      </form>
    </div>

    <!-- Form Update Data Umum -->
    <form method="POST">
      <label for="name">Nama Lengkap:</label>
      <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

      <label for="phone">Nomor Telepon:</label>
      <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>

      <label for="address">Alamat:</label>
      <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>

      <label for="role">Peran:</label>
      <select id="role" name="role" required>
        <option value="donatur" <?php if ($user['role'] == 'donatur') echo 'selected'; ?>>Donatur</option>
        <option value="penerima" <?php if ($user['role'] == 'penerima') echo 'selected'; ?>>Penerima</option>
      </select>
      <button type="submit" name="update_profile" class="btn">Update Profil</button>
    </form>

    <!-- Navigasi -->
    <div class="navigation-links">
      <a href="profile.php" class="btn">Kembali ke Profil</a>
      <a href="Home.html" class="btn">Home</a>
    </div>
  </section>
  
  <!-- Footer -->
  <footer>
    <div class="footer-container">
      <p>© 2024 Mari Bangkit. Semua Hak Dilindungi. Kebijakan Privasi.</p>
      <div class="social-media">
        <a href="https://facebook.com" target="_blank"><img src="images/Facebook.png" alt="Facebook"></a>
        <a href="https://instagram.com" target="_blank"><img src="images/Instagram.png" alt="Instagram"></a>
        <a href="https://linkedin.com" target="_blank"><img src="images/LinkedIn.png" alt="LinkedIn"></a>
      </div>
    </div>
  </footer>

  <!-- JavaScript untuk Cropper -->
  <script>
    const fileInput = document.getElementById('profile_photo');
    const cropContainer = document.getElementById('crop-container');
    const cropImage = document.getElementById('crop-image');
    const cropButton = document.getElementById('crop-button');
    const cropDataInput = document.getElementById('crop_data');
    let cropper;

    fileInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = () => {
                cropImage.src = reader.result;
                cropContainer.style.display = 'block';

                if (cropper) cropper.destroy();

                cropper = new Cropper(cropImage, {
                    aspectRatio: 1,
                    viewMode: 1,
                });
            };
            reader.readAsDataURL(file);
        }
    });

    cropButton.addEventListener('click', () => {
        if (cropper) {
            const cropData = cropper.getData(true);
            cropDataInput.value = JSON.stringify({
                x: cropData.x,
                y: cropData.y,
                width: cropData.width,
                height: cropData.height
            });
            document.getElementById('profileForm').submit();
        }
    });
  </script>
</body>
</html>
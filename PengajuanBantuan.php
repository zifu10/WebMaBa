<?php
session_start();
include 'config.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Ambil ID pengguna dari sesi
$user_id = $_SESSION['user_id'];

// Ambil data pengajuan bantuan dari database
$sql = "SELECT id, jenis_bantuan, deskripsi, DATE_FORMAT(created_at, '%e %M %Y') as tanggal FROM bantuan WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Bantuan - MABA</title>
    <link rel="stylesheet" href="main.css">
    <link href="https://fonts.googleapis.com/css2?family=Arapey&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo">
            <div class="icons">
                <a href="Notifikasi.html">
                    <img src="images/Mail.png" alt="Mail Icon" class="icon">
                </a>
                <a href="profile.php">
                    <img src="images/User.png" alt="User Icon" class="icon">
                </a>
            </div>
            <div class="logo-text">
                <h1>MABA</h1>
                <p>Mari Bangkit</p>
            </div>
        </div>
        <nav>
            <ul>
                <li><a href="KategoriDonasi.html">Donasi</a></li>
                <li><a href="Transparansi.html">Transparansi</a></li>
                <li><a href="PengajuanBantuan.php">Pengajuan Bantuan</a></li>
                <li><a href="LaporanProyek.html">Laporan Proyek</a></li>
                <li><a href="InformasiEdukatif.html">Informasi Edukatif</a></li>
                <li><a href="Komunitas.html">Komunitas</a></li>
            </ul>
        </nav>
    </header>

    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <a href="Home.html">Home</a> > <a href="PengajuanBantuan.php">Pengajuan Bantuan</a>
    </div>

    <!-- Section Pengajuan Bantuan -->
    <section class="pengajuan">
        <h2>PENGAJUAN BANTUAN</h2>

        <div class="action-buttons">
            <!-- Tombol Ajukan Bantuan -->
            <a href="submit_form_bantuan.php" class="ajukan-btn">Ajukan Bantuan</a>
        </div>

        <!-- Daftar Pengajuan Bantuan -->
        <?php if ($result->num_rows > 0): ?>
            <div class="bantuan-list">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="bantuan-card">
                        <p class="date"><?php echo htmlspecialchars($row['tanggal']); ?></p>
                        <h3>Jenis Bantuan: <?php echo htmlspecialchars($row['jenis_bantuan']); ?></h3>
                        <p>Deskripsi: <?php echo htmlspecialchars($row['deskripsi']); ?></p>
                        <div class="action-buttons">
                            <a href="update_bantuan.php?id=<?php echo htmlspecialchars($row['id']); ?>">
                                <img src="images/update.png" alt="Update" class="btn-icon">
                            </a>
                            <a href="konfirmasi_hapus.php?id=<?php echo htmlspecialchars($row['id']); ?>">
                                <img src="images/delete.png" alt="Delete" class="btn-icon">
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="no-data">Tidak Ada Pengajuan Bantuan</p>
        <?php endif; ?>
    </section>

    <!-- Footer -->
    <footer>
        <p>© 2024 Mari Bangkit. Semua Hak Dilindungi. Kebijakan Privasi.</p>
        <div class="social-media">
            <a href="#"><img src="images/Facebook.png" alt="Facebook"></a>
            <a href="#"><img src="images/Instagram.png" alt="Instagram"></a>
            <a href="#"><img src="images/LinkedIn.png" alt="LinkedIn"></a>
        </div>
    </footer>

    <!-- Script untuk Update dan Delete -->
    <script>
        function deleteBantuan(id) {
            if (confirm("Apakah Anda yakin ingin menghapus pengajuan ini?")) {
                window.location.href = `delete_bantuan.php?id=${id}`;
            }
        }

        function updateBantuan(id) {
            window.location.href = `update_bantuan.php?id=${id}`;
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>
<?php
session_start();
include 'config.php';

// Periksa apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Ambil data user dari sesi
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
$user_phone = $_SESSION['user_phone'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Bantuan - MABA</title>
    <link rel="stylesheet" href="FormBantuan.css">
    <script>
        function toggleInputs() {
            const jenisBantuan = document.getElementById("jenis_bantuan").value;
            const nominalInput = document.getElementById("nominal_group");
            const barangInput = document.getElementById("barang_group");

            // Reset visibility
            nominalInput.style.display = "none";
            barangInput.style.display = "none";

            if (jenisBantuan === "Dana") {
                nominalInput.style.display = "block";
            } else if (jenisBantuan === "Barang") {
                barangInput.style.display = "block";
            } else if (jenisBantuan === "Lainnya") {
                nominalInput.style.display = "block";
                barangInput.style.display = "block";
            }
        }
    </script>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo">
            <div class="icons">
                <a href="mailto:example@example.com">
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
        <a href="Home.html">Home</a> > <a href="PengajuanBantuan.php">Pengajuan Bantuan</a> > Ajukan Bantuan
    </div>

    <!-- Form -->
    <section class="form-section">
        <h2>FORM PENGAJUAN BANTUAN</h2>
        <form action="submit_bantuan.php" method="POST" class="form-ajukan">
            <!-- Nama -->
            <div class="form-group">
                <label for="user_name">Nama Lengkap:</label>
                <input type="text" id="user_name" name="user_name" value="<?php echo htmlspecialchars($user_name); ?>" readonly>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="user_email">Email:</label>
                <input type="email" id="user_email" name="user_email" value="<?php echo htmlspecialchars($user_email); ?>" readonly>
            </div>

            <!-- Nomor Telepon -->
            <div class="form-group">
                <label for="user_phone">Nomor Telepon:</label>
                <input type="text" id="user_phone" name="user_phone" value="<?php echo htmlspecialchars($user_phone); ?>" readonly>
            </div>

            <!-- Kategori Donasi -->
            <div class="form-group">
                <label for="kategori_donasi">Kategori Donasi:</label>
                <select id="kategori_donasi" name="kategori_donasi" required>
                    <option value="" disabled selected>Pilih Kategori Donasi</option>
                    <option value="Pembangunan Infrastruktur">Pembangunan Infrastruktur</option>
                    <option value="Pendidikan">Pendidikan</option>
                    <option value="Pelatihan Kerja">Pelatihan Kerja</option>
                </select>
            </div>

            <!-- Jenis Bantuan -->
            <div class="form-group">
                <label for="jenis_bantuan">Jenis Bantuan:</label>
                <select id="jenis_bantuan" name="jenis_bantuan" onchange="toggleInputs()" required>
                    <option value="" disabled selected>Pilih Jenis Bantuan</option>
                    <option value="Dana">Dana</option>
                    <option value="Barang">Barang</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <!-- Nominal (opsional) -->
            <div class="form-group" id="nominal_group" style="display: none;">
                <label for="nominal">Nominal Uang:</label>
                <input type="number" id="nominal" name="nominal" placeholder="Masukkan nominal uang">
            </div>

            <!-- Nama Barang (opsional) -->
            <div class="form-group" id="barang_group" style="display: none;">
                <label for="nama_barang">Nama Barang:</label>
                <input type="text" id="nama_barang" name="nama_barang" placeholder="Masukkan nama barang">
            </div>

            <!-- Deskripsi -->
            <div class="form-group">
                <label for="deskripsi">Deskripsi Bantuan:</label>
                <textarea id="deskripsi" name="deskripsi" placeholder="Jelaskan bantuan yang diajukan" rows="5" required></textarea>
            </div>

            <button type="submit" class="submit-btn">Kirim Pengajuan</button>
        </form>
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
</body>
</html>
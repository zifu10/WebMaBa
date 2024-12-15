<?php
session_start();
include 'config.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Ambil ID bantuan dari parameter URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    header("Location: PengajuanBantuan.php");
    exit();
}

// Ambil data bantuan yang akan diperbarui
$sql = "SELECT * FROM bantuan WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    header("Location: PengajuanBantuan.php");
    exit();
}

// Perbarui data jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategori_donasi = trim($_POST['kategori_donasi']);
    $jenis_bantuan = trim($_POST['jenis_bantuan']);
    $deskripsi = trim($_POST['deskripsi']);
    $nominal = isset($_POST['nominal']) ? trim($_POST['nominal']) : null;
    $nama_barang = isset($_POST['nama_barang']) ? trim($_POST['nama_barang']) : null;

    // Validasi input wajib
    if (!empty($kategori_donasi) && !empty($jenis_bantuan) && !empty($deskripsi)) {
        $update_sql = "
            UPDATE bantuan 
            SET kategori_donasi = ?, jenis_bantuan = ?, deskripsi = ?, nominal = ?, nama_barang = ? 
            WHERE id = ? AND user_id = ?
        ";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param(
            "sssssii",
            $kategori_donasi,
            $jenis_bantuan,
            $deskripsi,
            $nominal,
            $nama_barang,
            $id,
            $_SESSION['user_id']
        );

        if ($update_stmt->execute()) {
            header("Location: PengajuanBantuan.php");
            exit();
        } else {
            $error = "Terjadi kesalahan saat memperbarui data: " . $conn->error;
        }
    } else {
        $error = "Harap isi semua data wajib!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Pengajuan Bantuan</title>
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
        <a href="Home.html">Home</a> > <a href="PengajuanBantuan.php">Pengajuan Bantuan</a> > Update Bantuan
    </div>

    <!-- Section Form -->
    <section class="form-section">
        <h2>FORM UPDATE PENGAJUAN BANTUAN</h2>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form class="form-ajukan" action="" method="POST">
            <!-- Kategori Donasi -->
            <div class="form-group">
                <label for="kategori_donasi">Kategori Donasi:</label>
                <select id="kategori_donasi" name="kategori_donasi" required>
                    <option value="Pembangunan Infrastruktur" <?php if ($data['kategori_donasi'] === 'Pembangunan Infrastruktur') echo 'selected'; ?>>Pembangunan Infrastruktur</option>
                    <option value="Pendidikan" <?php if ($data['kategori_donasi'] === 'Pendidikan') echo 'selected'; ?>>Pendidikan</option>
                    <option value="Pelatihan Kerja" <?php if ($data['kategori_donasi'] === 'Pelatihan Kerja') echo 'selected'; ?>>Pelatihan Kerja</option>
                </select>
            </div>

            <!-- Jenis Bantuan -->
            <div class="form-group">
                <label for="jenis_bantuan">Jenis Bantuan:</label>
                <select id="jenis_bantuan" name="jenis_bantuan" onchange="toggleInputs()" required>
                    <option value="Dana" <?php if ($data['jenis_bantuan'] === 'Dana') echo 'selected'; ?>>Dana</option>
                    <option value="Barang" <?php if ($data['jenis_bantuan'] === 'Barang') echo 'selected'; ?>>Barang</option>
                    <option value="Lainnya" <?php if ($data['jenis_bantuan'] === 'Lainnya') echo 'selected'; ?>>Lainnya</option>
                </select>
            </div>

            <!-- Nominal -->
            <div class="form-group" id="nominal_group" style="<?php echo $data['jenis_bantuan'] === 'Dana' || $data['jenis_bantuan'] === 'Lainnya' ? 'display: block;' : 'display: none;'; ?>">
                <label for="nominal">Nominal Uang:</label>
                <input type="number" id="nominal" name="nominal" value="<?php echo htmlspecialchars($data['nominal']); ?>" placeholder="Masukkan nominal uang">
            </div>

            <!-- Nama Barang -->
            <div class="form-group" id="barang_group" style="<?php echo $data['jenis_bantuan'] === 'Barang' || $data['jenis_bantuan'] === 'Lainnya' ? 'display: block;' : 'display: none;'; ?>">
                <label for="nama_barang">Nama Barang:</label>
                <input type="text" id="nama_barang" name="nama_barang" value="<?php echo htmlspecialchars($data['nama_barang']); ?>" placeholder="Masukkan nama barang">
            </div>

            <!-- Deskripsi -->
            <div class="form-group">
                <label for="deskripsi">Deskripsi Bantuan:</label>
                <textarea id="deskripsi" name="deskripsi" rows="5" required><?php echo htmlspecialchars($data['deskripsi']); ?></textarea>
            </div>

            <button type="submit" class="submit-btn">Update Pengajuan</button>
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
<?php
// Mengimpor file DatabaseConnection.php untuk koneksi ke database
// Menggunakan Encapsulation: Detail koneksi disembunyikan di dalam kelas DatabaseConnection
require_once 'DatabaseConnection.php';

// Membuat instance dari koneksi database
$db = new DatabaseConnection();
$conn = $db->getConnection(); // Mendapatkan koneksi melalui metode getConnection()

// Mengecek apakah permintaan berasal dari metode GET untuk mendapatkan data berdasarkan ID
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id']; // Mengambil ID kamar dari parameter URL

    // Query untuk mendapatkan data kamar berdasarkan ID
    $query = "SELECT * FROM kamar WHERE id = ?";
    $stmt = $conn->prepare($query); // Menyiapkan query
    $stmt->bind_param("i", $id); // Mengikat parameter (Encapsulation untuk keamanan query)
    $stmt->execute(); // Menjalankan query
    $result = $stmt->get_result(); // Mendapatkan hasil query
    $kamar = $result->fetch_assoc(); // Mengambil data kamar dalam bentuk array asosiatif

    if (!$kamar) {
        die("Data kamar tidak ditemukan!"); // Menampilkan pesan jika data tidak ditemukan
    }
}
// Mengecek apakah permintaan berasal dari metode POST untuk mengupdate data
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id']; // Mengambil ID kamar dari input form
    $nomorKamar = $_POST['nomorKamar']; // Mengambil nomor kamar
    $tipeKamar = $_POST['tipeKamar']; // Mengambil tipe kamar
    $ukuranKamar = $tipeKamar === 'standar' ? '20m²' : '30m²'; // Menentukan ukuran kamar berdasarkan tipe
    $hargaDasar = str_replace('.', '', $_POST['hargaDasar']); // Membersihkan format Rupiah untuk menyimpan angka murni
    $durasiMenginap = $_POST['durasiMenginap']; // Mengambil durasi menginap
    $status = $_POST['status']; // Mengambil status kamar

    // Polymorphism: Logika berbeda untuk tipe kamar standar dan deluxe
    if ($tipeKamar === 'standar') {
        // Query untuk update data kamar standar
        $query = "UPDATE kamar SET nomorKamar = ?, tipe = ?, ukuranKamar = ?, hargaDasar = ?, durasiMenginap = ?, status = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssdssi", $nomorKamar, $tipeKamar, $ukuranKamar, $hargaDasar, $durasiMenginap, $status, $id);
    } else {
        // Mengambil data tambahan untuk tipe kamar deluxe
        $fasilitasTambahan = $_POST['fasilitasTambahan'];
        $tambahanHarga = str_replace('.', '', $_POST['tambahanHarga']);

        // Query untuk update data kamar deluxe
        $query = "UPDATE kamar SET nomorKamar = ?, tipe = ?, ukuranKamar = ?, hargaDasar = ?, durasiMenginap = ?, status = ?, fasilitasTambahan = ?, tambahanHarga = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssdsssii", $nomorKamar, $tipeKamar, $ukuranKamar, $hargaDasar, $durasiMenginap, $status, $fasilitasTambahan, $tambahanHarga, $id);
    }

    // Menjalankan query update
    if ($stmt->execute()) {
        // Redirect ke halaman index dengan pesan sukses
        header("Location: index.php?message=Data kamar berhasil diubah.");
        exit;
    } else {
        // Menampilkan pesan error jika query gagal
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kamar</title>
    <!-- Mengimpor Bootstrap untuk styling antarmuka -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <!-- Judul Halaman -->
        <h1 class="text-center text-warning mb-4">Edit Kamar</h1>
        <!-- Form untuk mengedit data kamar -->
        <form action="edit.php" method="POST" class="shadow-lg p-4 bg-white rounded">
            <!-- Input Hidden: ID Kamar (tidak terlihat oleh pengguna) -->
            <input type="hidden" name="id" value="<?= $kamar['id']; ?>">
            
            <!-- Input: Nomor Kamar -->
            <div class="mb-3">
                <label for="nomorKamar" class="form-label">Nomor Kamar</label>
                <input type="text" name="nomorKamar" class="form-control" value="<?= $kamar['nomorKamar']; ?>" required>
            </div>

            <!-- Input: Tipe Kamar -->
            <div class="mb-3">
                <label for="tipeKamar" class="form-label">Tipe Kamar</label>
                <select name="tipeKamar" class="form-select" id="tipeKamar" required>
                    <!-- Opsi untuk tipe kamar -->
                    <option value="standar" <?= $kamar['tipe'] === 'standar' ? 'selected' : ''; ?>>Standar</option>
                    <option value="deluxe" <?= $kamar['tipe'] === 'deluxe' ? 'selected' : ''; ?>>Deluxe</option>
                </select>
            </div>

            <!-- Input: Ukuran Kamar -->
            <div class="mb-3">
                <label for="ukuranKamar" class="form-label">Ukuran Kamar</label>
                <input type="text" name="ukuranKamar" id="ukuranKamar" class="form-control" value="<?= $kamar['ukuranKamar']; ?>" readonly>
            </div>

            <!-- Input: Harga Dasar -->
            <div class="mb-3">
                <label for="hargaDasar" class="form-label">Harga Dasar</label>
                <input type="text" name="hargaDasar" class="form-control format-rupiah" value="<?= number_format($kamar['hargaDasar'], 0, ',', '.'); ?>" required>
            </div>

            <!-- Input: Durasi Menginap -->
            <div class="mb-3">
                <label for="durasiMenginap" class="form-label">Durasi Menginap</label>
                <input type="number" name="durasiMenginap" class="form-control" value="<?= $kamar['durasiMenginap']; ?>" required>
            </div>

            <!-- Input: Fasilitas Tambahan untuk Kamar Deluxe -->
            <div id="deluxeFields" style="display: <?= $kamar['tipe'] === 'deluxe' ? 'block' : 'none'; ?>;">
                <div class="mb-3">
                    <label for="fasilitasTambahan" class="form-label">Fasilitas Tambahan</label>
                    <select name="fasilitasTambahan" class="form-select">
                        <option value="Jacuzzi" <?= $kamar['fasilitasTambahan'] === 'Jacuzzi' ? 'selected' : ''; ?>>Jacuzzi</option>
                        <option value="Akses Lounge" <?= $kamar['fasilitasTambahan'] === 'Akses Lounge' ? 'selected' : ''; ?>>Akses Lounge</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="tambahanHarga" class="form-label">Tambahan Harga</label>
                    <input type="text" name="tambahanHarga" class="form-control format-rupiah" value="<?= number_format($kamar['tambahanHarga'], 0, ',', '.'); ?>">
                </div>
            </div>

            <!-- Input: Status Kamar -->
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="Tersedia" <?= $kamar['status'] === 'Tersedia' ? 'selected' : ''; ?>>Tersedia</option>
                    <option value="Terisi" <?= $kamar['status'] === 'Terisi' ? 'selected' : ''; ?>>Terisi</option>
                </select>
            </div>

            <!-- Tombol Simpan dan Kembali -->
            <div class="d-flex justify-content-between">
                <a href="index.php" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <!-- Script untuk menangani interaksi dinamis -->
    <script>
        // Menampilkan atau menyembunyikan field berdasarkan tipe kamar
        const tipeKamarSelect = document.getElementById('tipeKamar');
        const deluxeFields = document.getElementById('deluxeFields');
        const ukuranKamarInput = document.getElementById('ukuranKamar');

        tipeKamarSelect.addEventListener('change', function () {
            if (this.value === 'standar') {
                deluxeFields.style.display = 'none';
                ukuranKamarInput.value = '20m²';
            } else if (this.value === 'deluxe') {
                deluxeFields.style.display = 'block';
                ukuranKamarInput.value = '30m²';
            }
        });

        // Memformat input harga menjadi format Rupiah
        document.querySelectorAll('.format-rupiah').forEach(input => {
            input.addEventListener('input', function () {
                let value = this.value.replace(/\D/g, '');
                this.value = new Intl.NumberFormat('id-ID').format(value);
            });
        });
    </script>
</body>
</html>

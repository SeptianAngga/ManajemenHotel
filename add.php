<?php
// Mengimpor file DatabaseConnection.php untuk koneksi ke database
// Menggunakan konsep Encapsulation karena detail koneksi disimpan dalam atribut private di DatabaseConnection
require_once 'DatabaseConnection.php';

// Memeriksa apakah permintaan berasal dari metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Membuat instance dari DatabaseConnection
    // Menggunakan konsep Encapsulation karena akses ke koneksi database dilakukan melalui metode getConnection()
    $db = new DatabaseConnection();
    $conn = $db->getConnection(); // Mendapatkan koneksi ke database

    // Mengambil data dari form input
    // Tidak ada akses langsung ke database, semua dilakukan melalui metode koneksi (Encapsulation)
    $nomorKamar = $_POST['nomorKamar']; // Nomor kamar
    $tipeKamar = $_POST['tipeKamar']; // Tipe kamar (standar/deluxe)
    $ukuranKamar = $tipeKamar === 'standar' ? '20m²' : '30m²'; // Menentukan ukuran kamar berdasarkan tipe
    $hargaDasar = str_replace('.', '', $_POST['hargaDasar']); // Menghapus format angka untuk menyimpan sebagai integer
    $durasiMenginap = $_POST['durasiMenginap']; // Durasi menginap
    $status = $_POST['status']; // Status kamar (Tersedia/Terisi)

    // Menentukan query berdasarkan tipe kamar
    // Polymorphism: Menangani data secara berbeda berdasarkan tipe kamar (standar atau deluxe)
    if ($tipeKamar === 'standar') {
        // Query untuk kamar standar
        $query = "INSERT INTO kamar (nomorKamar, tipe, ukuranKamar, hargaDasar, durasiMenginap, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query); // Menyiapkan query
        $stmt->bind_param("sssdss", $nomorKamar, $tipeKamar, $ukuranKamar, $hargaDasar, $durasiMenginap, $status); // Mengikat parameter
    } else {
        // Mengambil data tambahan untuk tipe kamar deluxe
        $fasilitasTambahan = $_POST['fasilitasTambahan']; // Fasilitas tambahan
        $tambahanHarga = str_replace('.', '', $_POST['tambahanHarga']); // Harga tambahan

        // Query untuk kamar deluxe
        $query = "INSERT INTO kamar (nomorKamar, tipe, ukuranKamar, hargaDasar, durasiMenginap, status, fasilitasTambahan, tambahanHarga) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query); // Menyiapkan query
        $stmt->bind_param("sssdssss", $nomorKamar, $tipeKamar, $ukuranKamar, $hargaDasar, $durasiMenginap, $status, $fasilitasTambahan, $tambahanHarga); // Mengikat parameter
    }

    // Menjalankan query dan memeriksa hasilnya
    if ($stmt->execute()) {
        // Redirect ke halaman index dengan pesan sukses
        header("Location: index.php?message=Data kamar berhasil ditambahkan.");
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
    <title>Tambah Kamar</title>
    <!-- Mengimpor CSS Bootstrap untuk styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <!-- Judul halaman -->
        <h1 class="text-center text-success mb-4">Tambah Kamar Baru</h1>
        <!-- Form untuk input data kamar -->
        <form action="add.php" method="POST" class="shadow-lg p-4 bg-white rounded">
            <!-- Input: Nomor Kamar -->
            <div class="mb-3">
                <label for="nomorKamar" class="form-label">Nomor Kamar</label>
                <input type="text" name="nomorKamar" class="form-control" required>
            </div>
            <!-- Input: Tipe Kamar -->
            <div class="mb-3">
                <label for="tipeKamar" class="form-label">Tipe Kamar</label>
                <select name="tipeKamar" class="form-select" id="tipeKamar" required>
                    <!-- Opsi untuk tipe kamar -->
                    <option value="standar">Standar</option>
                    <option value="deluxe">Deluxe</option>
                </select>
            </div>
            <!-- Input: Ukuran Kamar -->
            <div class="mb-3">
                <label for="ukuranKamar" class="form-label">Ukuran Kamar</label>
                <input type="text" name="ukuranKamar" id="ukuranKamar" class="form-control" value="20m²" readonly>
            </div>
            <!-- Input: Harga Dasar -->
            <div class="mb-3">
                <label for="hargaDasar" class="form-label">Harga Dasar</label>
                <input type="text" name="hargaDasar" class="form-control format-rupiah" required>
            </div>
            <!-- Input: Durasi Menginap -->
            <div class="mb-3">
                <label for="durasiMenginap" class="form-label">Durasi Menginap</label>
                <input type="number" name="durasiMenginap" class="form-control" required>
            </div>
            <!-- Bagian Fasilitas Tambahan untuk tipe Deluxe -->
            <div id="deluxeFields" style="display: none;">
                <div class="mb-3">
                    <label for="fasilitasTambahan" class="form-label">Fasilitas Tambahan</label>
                    <select name="fasilitasTambahan" class="form-select">
                        <!-- Opsi fasilitas tambahan -->
                        <option value="Jacuzzi">Jacuzzi</option>
                        <option value="Akses Lounge">Akses Lounge</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="tambahanHarga" class="form-label">Tambahan Harga</label>
                    <input type="text" name="tambahanHarga" class="form-control format-rupiah">
                </div>
            </div>
            <!-- Input: Status Kamar -->
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <!-- Opsi status kamar -->
                    <option value="Tersedia">Tersedia</option>
                    <option value="Terisi">Terisi</option>
                </select>
            </div>
            <!-- Tombol untuk menyimpan data -->
            <div class="d-flex justify-content-between">
                <a href="index.php" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
    <!-- Mengimpor script Bootstrap dan JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Event listener untuk mengatur tampilan field berdasarkan tipe kamar
        // Polymorphism: Logika berbeda untuk tipe kamar yang berbeda
        document.querySelector('#tipeKamar').addEventListener('change', function () {
            const deluxeFields = document.getElementById('deluxeFields');
            const ukuranKamarInput = document.getElementById('ukuranKamar');

            if (this.value === 'standar') {
                deluxeFields.style.display = 'none'; // Sembunyikan field tambahan
                ukuranKamarInput.value = '20m²'; // Ukuran untuk tipe standar
            } else if (this.value === 'deluxe') {
                deluxeFields.style.display = 'block'; // Tampilkan field tambahan
                ukuranKamarInput.value = '30m²'; // Ukuran untuk tipe deluxe
            }
        });

        // Event listener untuk memformat input harga ke dalam format Rupiah
        document.querySelectorAll('.format-rupiah').forEach(input => {
            input.addEventListener('input', function () {
                let value = this.value.replace(/\D/g, ''); // Hanya angka
                this.value = new Intl.NumberFormat('id-ID').format(value); // Format menjadi Rupiah
            });
        });
    </script>
</body>
</html>

<?php
// Mengimpor file DatabaseConnection.php untuk koneksi database
// Mengimpor file Kamar.php yang berisi kelas abstrak dan kelas turunannya
require_once 'DatabaseConnection.php';
require_once 'Kamar.php';

/**
 * Fungsi untuk mendapatkan semua data kamar dari database.
 * Menggunakan Polymorphism untuk menciptakan objek KamarStandar atau KamarDeluxe berdasarkan tipe kamar.
 * Menggunakan Encapsulation untuk mengakses data kamar melalui metode getter pada kelas Kamar.
 */
function getAllKamar() {
    // Membuat koneksi ke database menggunakan Encapsulation (metode getConnection)
    $db = new DatabaseConnection();
    $conn = $db->getConnection();

    // Query untuk mendapatkan semua data kamar
    $query = "SELECT * FROM kamar";
    $result = $conn->query($query); // Menjalankan query

    $listKamar = []; // Array untuk menyimpan objek kamar
    while ($row = $result->fetch_assoc()) {
        // Polymorphism: Membuat objek berbeda berdasarkan tipe kamar
        if ($row['tipe'] === 'standar') {
            // Membuat objek KamarStandar
            $listKamar[] = new KamarStandar(
                $row['id'],
                $row['nomorKamar'],
                $row['hargaDasar'],
                $row['status'],
                $row['durasiMenginap'],
                $row['ukuranKamar']
            );
        } else {
            // Membuat objek KamarDeluxe
            $listKamar[] = new KamarDeluxe(
                $row['id'],
                $row['nomorKamar'],
                $row['hargaDasar'],
                $row['status'],
                $row['durasiMenginap'],
                $row['fasilitasTambahan'],
                $row['tambahanHarga'],
                $row['ukuranKamar']
            );
        }
    }

    return $listKamar; // Mengembalikan array objek kamar
}

// Memanggil fungsi untuk mendapatkan semua data kamar
$listKamar = getAllKamar();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Hotel</title>
    <!-- Mengimpor CSS Bootstrap untuk styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <!-- Judul Halaman -->
        <h1 class="text-center text-primary mb-4">Sistem Manajemen Hotel</h1>

        <!-- Tombol Tambah dan Refresh -->
        <div class="d-flex justify-content-between mb-4">
            <a href="add.php" class="btn btn-success">Tambah Kamar</a> <!-- Tombol untuk menambah data kamar -->
            <a href="index.php" class="btn btn-info text-white">Refresh</a> <!-- Tombol untuk memuat ulang halaman -->
        </div>

        <!-- Tabel Data Kamar -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <!-- Header Tabel -->
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nomor Kamar</th>
                        <th>Tipe</th>
                        <th>Ukuran Kamar</th>
                        <th>Harga Dasar</th>
                        <th>Durasi Menginap</th>
                        <th>Fasilitas Tambahan</th>
                        <th>Tambahan Harga</th>
                        <th>Status</th>
                        <th>Total Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Iterasi data kamar menggunakan PHP -->
                    <?php foreach ($listKamar as $kamar): ?>
                        <tr>
                            <td><?= $kamar->getId(); ?></td> <!-- Menampilkan ID kamar -->
                            <td><?= $kamar->getNomorKamar(); ?></td> <!-- Menampilkan nomor kamar -->
                            <td><?= $kamar instanceof KamarStandar ? 'Standar' : 'Deluxe'; ?></td> <!-- Menentukan tipe kamar (Polymorphism) -->
                            <td><?= $kamar->getUkuranKamar(); ?></td> <!-- Menampilkan ukuran kamar -->
                            <td><?= number_format($kamar->getHargaDasar(), 0, ',', '.'); ?></td> <!-- Format harga dasar ke format Rupiah -->
                            <td><?= $kamar->getDurasiMenginap(); ?> malam</td> <!-- Menampilkan durasi menginap -->
                            <td><?= $kamar instanceof KamarDeluxe ? $kamar->getFasilitasTambahan() : '-'; ?></td> <!-- Fasilitas tambahan untuk tipe deluxe -->
                            <td><?= $kamar instanceof KamarDeluxe ? number_format($kamar->getTambahanHarga(), 0, ',', '.') : '-'; ?></td> <!-- Harga tambahan untuk tipe deluxe -->
                            <td><?= $kamar->getStatus(); ?></td> <!-- Menampilkan status kamar -->
                            <td><?= number_format($kamar->hitungHarga(), 0, ',', '.'); ?></td> <!-- Menghitung total harga (Polymorphism) -->
                            <td>
                                <!-- Tombol Edit -->
                                <a href="edit.php?id=<?= $kamar->getId(); ?>" class="btn btn-warning btn-sm">Edit</a>
                                <!-- Tombol Hapus -->
                                <form action="delete.php" method="post" class="d-inline-block">
                                    <input type="hidden" name="id" value="<?= $kamar->getId(); ?>"> <!-- ID kamar untuk dihapus -->
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kamar ini?');">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mengimpor JavaScript Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

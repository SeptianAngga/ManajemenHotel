<?php
// Mengimpor file DatabaseConnection.php untuk koneksi ke database
// Menggunakan Encapsulation: Detail koneksi disembunyikan di dalam kelas DatabaseConnection
require_once 'DatabaseConnection.php';

// Memeriksa apakah permintaan berasal dari metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Membuat instance dari koneksi database
    // Menggunakan Encapsulation: Koneksi hanya dapat diakses melalui metode getConnection()
    $db = new DatabaseConnection();
    $conn = $db->getConnection(); // Mendapatkan koneksi ke database

    // Mengambil ID dari data yang dikirimkan melalui POST
    $id = $_POST['id'];

    // Query untuk menghapus data kamar berdasarkan ID
    // Menggunakan Polymorphism: Query ini menangani semua tipe kamar (standar atau deluxe) dengan logika yang sama
    $query = "DELETE FROM kamar WHERE id = ?";
    $stmt = $conn->prepare($query); // Menyiapkan query SQL
    $stmt->bind_param("i", $id); // Mengikat parameter untuk memastikan keamanan (mencegah SQL injection)

    // Menjalankan query penghapusan
    if ($stmt->execute()) {
        // Redirect ke halaman index dengan pesan sukses jika data berhasil dihapus
        header("Location: index.php?message=Data kamar berhasil dihapus.");
    } else {
        // Menampilkan pesan error jika penghapusan gagal
        echo "Error: " . $stmt->error;
    }
}
?>

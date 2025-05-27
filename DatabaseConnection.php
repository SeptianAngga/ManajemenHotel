<?php
// DatabaseConnection.php: File ini bertanggung jawab untuk menyediakan koneksi ke database.
// Menggunakan enkapsulasi dengan atribut private untuk menyimpan detail koneksi database.
// Menggunakan modularitas untuk membuat koneksi yang dapat digunakan di seluruh aplikasi.

class DatabaseConnection {
    // Atribut private untuk menyimpan detail koneksi database (Encapsulation)
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "db_manajemen_hotel";
    private $connection; // Atribut untuk menyimpan koneksi

    // Constructor: Membuat koneksi ke database saat objek diinisialisasi
    public function __construct() {
        try {
            // Membuat koneksi menggunakan mysqli
            $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);
            if ($this->connection->connect_error) {
                throw new Exception("Koneksi gagal: " . $this->connection->connect_error); // Menangani error koneksi
            }
        } catch (Exception $e) {
            die("Error: " . $e->getMessage()); // Menampilkan pesan error jika koneksi gagal
        }
    }

    // Metode getter untuk mendapatkan koneksi (Encapsulation)
    public function getConnection() {
        return $this->connection;
    }
}
?>

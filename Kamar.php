<?php
// Kamar.php: File ini mendefinisikan kelas abstrak `Kamar` dan dua kelas turunan `KamarStandar` dan `KamarDeluxe`.
// Menggunakan inheritance untuk pewarisan dari `Kamar` ke `KamarStandar` dan `KamarDeluxe`.
// Menggunakan encapsulation dengan atribut protected/private.
// Menggunakan polymorphism melalui metode abstrak `hitungHarga()` yang di-override di kelas turunan.

abstract class Kamar {
    // Atribut protected: Mengimplementasikan encapsulation dengan membatasi akses hanya untuk kelas ini dan turunannya.
    protected $id, $nomorKamar, $hargaDasar, $status, $durasiMenginap, $ukuranKamar;

    // Constructor: Menginisialisasi data umum untuk kelas `Kamar`.
    public function __construct($id, $nomorKamar, $hargaDasar, $status, $durasiMenginap, $ukuranKamar) {
        $this->id = $id;
        $this->nomorKamar = $nomorKamar;
        $this->hargaDasar = $hargaDasar;
        $this->status = $status;
        $this->durasiMenginap = $durasiMenginap;
        $this->ukuranKamar = $ukuranKamar;
    }

    // Getter: Menggunakan encapsulation untuk menyediakan akses aman ke atribut.
    public function getId() {
        return $this->id;
    }

    public function getNomorKamar() {
        return $this->nomorKamar;
    }

    public function getHargaDasar() {
        return $this->hargaDasar;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getDurasiMenginap() {
        return $this->durasiMenginap;
    }

    public function getUkuranKamar() {
        return $this->ukuranKamar;
    }

    // Metode abstrak: Harus diimplementasikan oleh kelas turunan (Polymorphism - Overriding).
    abstract public function hitungHarga();
}

// KamarStandar: Kelas turunan dari Kamar yang merepresentasikan tipe kamar standar.
class KamarStandar extends Kamar {
    // Constructor: Menggunakan inheritance untuk memanggil constructor dari kelas induk (parent).
    public function __construct($id, $nomorKamar, $hargaDasar, $status, $durasiMenginap, $ukuranKamar = "20m²") {
        parent::__construct($id, $nomorKamar, $hargaDasar, $status, $durasiMenginap, $ukuranKamar);
    }

    // Implementasi metode abstrak dari kelas induk (Polymorphism - Overriding).
    public function hitungHarga() {
        // Menghitung total harga berdasarkan harga dasar dan durasi menginap.
        return $this->hargaDasar * $this->durasiMenginap;
    }
}

// KamarDeluxe: Kelas turunan dari Kamar yang merepresentasikan tipe kamar deluxe.
class KamarDeluxe extends Kamar {
    // Atribut tambahan: Dideklarasikan sebagai private untuk menjaga encapsulation.
    private $fasilitasTambahan, $tambahanHarga;

    // Constructor: Menggunakan inheritance untuk memanggil constructor dari kelas induk, serta menginisialisasi atribut tambahan.
    public function __construct($id, $nomorKamar, $hargaDasar, $status, $durasiMenginap, $fasilitasTambahan, $tambahanHarga, $ukuranKamar = "30m²") {
        parent::__construct($id, $nomorKamar, $hargaDasar, $status, $durasiMenginap, $ukuranKamar);
        $this->fasilitasTambahan = $fasilitasTambahan;
        $this->tambahanHarga = $tambahanHarga;
    }

    // Getter untuk atribut tambahan (Encapsulation).
    public function getFasilitasTambahan() {
        return $this->fasilitasTambahan;
    }

    public function getTambahanHarga() {
        return $this->tambahanHarga;
    }

    // Implementasi metode abstrak dari kelas induk (Polymorphism - Overriding).
    public function hitungHarga() {
        // Menghitung total harga berdasarkan harga dasar, tambahan harga, dan durasi menginap.
        return ($this->hargaDasar + $this->tambahanHarga) * $this->durasiMenginap;
    }
}
?>

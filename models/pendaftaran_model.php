<?php
class PendaftaranModel {
    private $conn;

    // Konstruktor menerima koneksi database dari db.php
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // Fungsi untuk menambah data pendaftar
    public function tambahPendaftar($data) {
        $query = "INSERT INTO pendaftaran_user (nama, nim, email, jurusan, angkatan, portofolio, status) 
                  VALUES ($1, $2, $3, $4, $5, $6, 'Pending')";
        
        $params = [
            $data['nama'],
            $data['nim'],
            $data['email'],
            $data['jurusan'],
            $data['angkatan'],
            $data['portofolio']
        ];

        // Jalankan query
        $result = pg_query_params($this->conn, $query, $params);

        return $result; // Mengembalikan true jika sukses, false jika gagal
    }
}
?>
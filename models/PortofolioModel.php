<?php
class PortofolioModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // ... (Fungsi hitungData biarkan tetap sama) ...
    public function hitungData($kategori = null) {
        if ($kategori) {
            $query = "SELECT COUNT(*) as total FROM portofolio WHERE kategori = '$kategori'";
        } else {
            $query = "SELECT COUNT(*) as total FROM portofolio";
        }
        $result = pg_query($this->conn, $query);
        $row = pg_fetch_assoc($result);
        return $row['total'];
    }

    // MODIFIKASI DI SINI: Tambahkan parameter $keyword
    public function getAllPortfolios($keyword = null) {
        $query = "SELECT * FROM portofolio";
        
        // Cek jika ada keyword pencarian
        if ($keyword) {
            // Bersihkan input untuk mencegah SQL Injection sederhana
            $safe_keyword = pg_escape_string($this->conn, $keyword);
            
            // Gunakan ILIKE untuk pencarian case-insensitive (PostgreSQL)
            // Mencari di Judul, Penulis, atau Deskripsi
            $query .= " WHERE judul ILIKE '%$safe_keyword%' 
                        OR penulis ILIKE '%$safe_keyword%' 
                        OR penulis_anggota ILIKE '%$safe_keyword%'
                        OR deskripsi ILIKE '%$safe_keyword%'";
        }

        $query .= " ORDER BY tahun DESC, id_portofolio DESC";

        // if ($limit !== null && $offset !== null) {
        //     $query .= " LIMIT $limit OFFSET $offset";
        // }
        
        $result = pg_query($this->conn, $query);
        return pg_fetch_all($result);
    }

    public function countAllPortfolios($keyword = null) {
    $query = "SELECT COUNT(*) as total FROM portofolio";
    
    if ($keyword) {
        $safe_keyword = pg_escape_string($this->conn, $keyword);
        $query .= " WHERE judul ILIKE '%$safe_keyword%' 
                    OR penulis ILIKE '%$safe_keyword%' 
                    OR penulis_anggota ILIKE '%$safe_keyword%' 
                    OR deskripsi ILIKE '%$safe_keyword%'";
    }
    
    $result = pg_query($this->conn, $query);
    $row = pg_fetch_assoc($result);
    return $row['total'];
}
}
?>
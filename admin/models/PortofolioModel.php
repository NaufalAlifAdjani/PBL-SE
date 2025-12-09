<?php
class PortofolioModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // Ambil data dengan filter (Search, Kategori, Tahun)
    public function getFiltered($filters = []) {
        $query = "SELECT * FROM portofolio";
        $conditions = [];

        if (!empty($filters['kategori'])) {
            $kat = pg_escape_string($this->conn, $filters['kategori']);
            $conditions[] = "kategori = '$kat'";
        }

        if (!empty($filters['tahun'])) {
            $thn = pg_escape_string($this->conn, $filters['tahun']);
            $conditions[] = "tahun = '$thn'";
        }

        if (!empty($filters['search'])) {
            $search = pg_escape_string($this->conn, $filters['search']);
            // PostgreSQL ILIKE agar case-insensitive (huruf besar/kecil dianggap sama)
            $conditions[] = "(judul ILIKE '%$search%' OR penulis ILIKE '%$search%')";
        }

        if (count($conditions) > 0) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }

        $query .= " ORDER BY id_portofolio DESC";
        
        $result = pg_query($this->conn, $query);
        // pg_fetch_all mengembalikan array atau false jika kosong
        return pg_fetch_all($result) ?: []; 
    }

    // Ambil list kategori unik untuk dropdown
    public function getDistinctKategori() {
        $query = "SELECT DISTINCT kategori FROM portofolio ORDER BY kategori ASC";
        $result = pg_query($this->conn, $query);
        return pg_fetch_all($result) ?: [];
    }

    // Ambil list tahun unik untuk dropdown
    public function getDistinctTahun() {
        $query = "SELECT DISTINCT tahun FROM portofolio ORDER BY tahun DESC";
        $result = pg_query($this->conn, $query);
        return pg_fetch_all($result) ?: [];
    }

    // --- FUNGSI LAMA TETAP ADA DI BAWAH ---
    public function getAll() {
        $query = "SELECT * FROM portofolio ORDER BY id_portofolio DESC";
        $result = pg_query($this->conn, $query);
        return pg_fetch_all($result);
    }

    public function getById($id) {
        $query = "SELECT * FROM portofolio WHERE id_portofolio = '$id'";
        $result = pg_query($this->conn, $query);
        return pg_fetch_assoc($result);
    }

    public function insert($data) {
        $judul = pg_escape_string($this->conn, $data['judul']);
        $penulis = pg_escape_string($this->conn, $data['penulis']);
        $deskripsi = pg_escape_string($this->conn, $data['deskripsi']);
        $link = pg_escape_string($this->conn, $data['link_eksternal']);
        
        $query = "INSERT INTO portofolio (judul, kategori, tahun, penulis, deskripsi, link_eksternal, gambar) 
                  VALUES ('$judul', '{$data['kategori']}', '{$data['tahun']}', '$penulis', '$deskripsi', '$link', '{$data['gambar']}')";
        return pg_query($this->conn, $query);
    }

    public function update($data) {
        $id = $data['id_portofolio'];
        $judul = pg_escape_string($this->conn, $data['judul']);
        $penulis = pg_escape_string($this->conn, $data['penulis']);
        $deskripsi = pg_escape_string($this->conn, $data['deskripsi']);
        $link = pg_escape_string($this->conn, $data['link_eksternal']);

        $query = "UPDATE portofolio SET 
                  judul='$judul', kategori='{$data['kategori']}', tahun='{$data['tahun']}', 
                  penulis='$penulis', deskripsi='$deskripsi', link_eksternal='$link'";

        if (!empty($data['gambar'])) {
            $query .= ", gambar='{$data['gambar']}'";
        }

        $query .= " WHERE id_portofolio='$id'";
        return pg_query($this->conn, $query);
    }

    public function delete($id) {
        $query = "DELETE FROM portofolio WHERE id_portofolio = '$id'";
        return pg_query($this->conn, $query);
    }
}
?>
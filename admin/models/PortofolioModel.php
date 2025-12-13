<?php
class PortofolioModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // [MODIFIKASI] Tambahkan parameter $limit dan $offset
    public function getFiltered($filters = [], $limit = 10, $offset = 0) {
        $query = "SELECT * FROM portofolio";
        $conditions = $this->buildConditions($filters); // Pindahkan logika WHERE ke fungsi helper

        if (count($conditions) > 0) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }

        $query .= " ORDER BY id_portofolio DESC";
        
        // Tambahkan LIMIT dan OFFSET untuk pagination
        $query .= " LIMIT $limit OFFSET $offset";
        
        $result = pg_query($this->conn, $query);
        return pg_fetch_all($result) ?: []; 
    }

    // [BARU] Hitung total data berdasarkan filter (untuk menentukan jumlah halaman)
    public function countFiltered($filters = []) {
        $query = "SELECT COUNT(*) as total FROM portofolio";
        $conditions = $this->buildConditions($filters);

        if (count($conditions) > 0) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }

        $result = pg_query($this->conn, $query);
        $row = pg_fetch_assoc($result);
        return $row['total'];
    }

    // [HELPER] Memisahkan logika kondisi agar bisa dipakai di getFiltered & countFiltered
    private function buildConditions($filters) {
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
            $conditions[] = "(judul ILIKE '%$search%' OR penulis ILIKE '%$search%' OR penulis_anggota ILIKE '%$search%')";
        }
        return $conditions;
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
        $anggota = pg_escape_string($this->conn, $data['penulis_anggota']);
        $deskripsi = pg_escape_string($this->conn, $data['deskripsi']);
        $link = pg_escape_string($this->conn, $data['link_eksternal']);
        
        $query = "INSERT INTO portofolio (judul, kategori, tahun, penulis, penulis_anggota, deskripsi, link_eksternal, gambar) 
                  VALUES ('$judul', '{$data['kategori']}', '{$data['tahun']}', '$penulis', '$anggota', '$deskripsi', '$link', '{$data['gambar']}')";
        return pg_query($this->conn, $query);
    }

    public function update($data) {
        $id = $data['id_portofolio'];
        $judul = pg_escape_string($this->conn, $data['judul']);
        $penulis = pg_escape_string($this->conn, $data['penulis']);
        $anggota = pg_escape_string($this->conn, $data['penulis_anggota']);
        $deskripsi = pg_escape_string($this->conn, $data['deskripsi']);
        $link = pg_escape_string($this->conn, $data['link_eksternal']);

        $query = "UPDATE portofolio SET 
                  judul='$judul', kategori='{$data['kategori']}', tahun='{$data['tahun']}', 
                  penulis='$penulis', penulis_anggota='$anggota', deskripsi='$deskripsi', link_eksternal='$link'";

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
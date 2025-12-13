<?php
class DashboardModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Fungsi umum untuk menghitung jumlah data di tabel apa saja
    // $table = nama tabel
    // $condition = kondisi tambahan (misal: WHERE status='Aktif')
    public function getCount($table, $condition = "") {
        $query = "SELECT COUNT(*) as total FROM $table $condition";
        $result = pg_query($this->db, $query);
        
        if ($result) {
            $row = pg_fetch_assoc($result);
            return $row['total'];
        }
        return 0;
    }

    // Khusus menghitung Personil (Dosen)
    public function getPersonilCount() {
        return $this->getCount('dosen'); 
    }

    // Khusus menghitung Artikel/Blog
    public function getBlogCount() {
        // Pastikan nama tabel artikel kamu benar (misal: 'artikel' atau 'blog')
        return $this->getCount('artikel'); 
    }

    // Khusus menghitung Member SE Geeks yang DITERIMA
    public function getGeeksCount() {
        return $this->getCount('pendaftaran_user', "WHERE status = 'Diterima'");
    }
    
}
?>
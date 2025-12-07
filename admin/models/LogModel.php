<?php
class LogModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function catat($aksi, $tabel, $idTarget, $keterangan) {
        // === PERBAIKAN DI SINI ===
        // Cek apakah session sudah aktif? Kalau belum, kita start paksa.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // 1. Ambil ID Admin dari Session
        $idAdmin = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        
        // 2. Ambil Username (Jika tidak ada session, anggap 'System')
        $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'System';

        // 3. Siapkan Query Insert
        // Kita simpan username juga di tabel log agar kalau admin dihapus, log tetap terbaca namanya
        $sql = "INSERT INTO activity_logs (id_admin, username, aksi, tabel_target, id_target, keterangan) 
                VALUES ($1, $2, $3, $4, $5, $6)";

        $params = [
            $idAdmin,
            $username,
            $aksi,        
            $tabel,       
            $idTarget,    
            $keterangan   
        ];

        // 4. Eksekusi (Silent mode: pakai @ agar error log tidak menghentikan website)
        @pg_query_params($this->db, $sql, $params);
    }
}
?>
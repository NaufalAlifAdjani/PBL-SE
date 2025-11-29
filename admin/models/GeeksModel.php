<?php
class GeeksModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // 1. Ambil Semua Data (Tetap pakai Query Biasa karena hanya SELECT)
    public function getAllPendaftar() {
        $query = "SELECT * FROM pendaftaran_user 
                  ORDER BY CASE WHEN status = 'Pending' THEN 1 ELSE 2 END, id_pendaftaran_user DESC";
        return pg_query($this->db, $query);
    }

    // 2. Ambil Satu Data User (Tetap pakai Query Biasa untuk ambil email)
    public function getUserById($id) {
        $id = pg_escape_string($this->db, $id);
        $query = "SELECT * FROM pendaftaran_user WHERE id_pendaftaran_user = '$id'";
        $result = pg_query($this->db, $query);
        return pg_fetch_assoc($result);
    }

    // 3. Update Status (Terima/Tolak) -> MENGGUNAKAN STORED PROCEDURE
    public function updateStatus($id, $status) {
        $id = pg_escape_string($this->db, $id);
        $status = pg_escape_string($this->db, $status);
        
        // Memanggil Procedure 'update_status_pendaftaran' di database
        $query = "CALL update_status_pendaftaran($id, '$status')";
        
        return pg_query($this->db, $query);
    }

    // 4. Hapus User -> MENGGUNAKAN STORED PROCEDURE
    public function deleteUser($id) {
        $id = pg_escape_string($this->db, $id);
        
        // Memanggil Procedure 'hapus_user_pendaftaran' di database
        $query = "CALL hapus_user_pendaftaran($id)";
        
        return pg_query($this->db, $query);
    }
    // 5. Catat Log Email (Memanggil Stored Procedure)
    // public function catatLogEmail($idUser, $emailTujuan, $status) {
    //     // Amankan input
    //     $idUser = (int)$idUser;
    //     $emailClean = pg_escape_string($this->db, $emailTujuan);
    //     $statusClean = pg_escape_string($this->db, $status);
        
    //     // Panggil Procedure di Database
    //     $query = "CALL catat_log_email($idUser, '$emailClean', '$statusClean')";
        
    //     return pg_query($this->db, $query);
    // }
    // // Ganti fungsi catatLogEmail yang lama dengan yang ini:
    public function catatLogEmail($idUser, $emailTujuan, $status) {
        $idUser = (int)$idUser; 
        $emailClean = pg_escape_string($this->db, $emailTujuan);
        $statusClean = pg_escape_string($this->db, $status);
        
        // Query memanggil procedure
        $query = "CALL catat_log_email($idUser, '$emailClean', '$statusClean')";
        
        // EKSEKUSI DAN CEK ERROR
        $result = pg_query($this->db, $query);

        // --- JEBAKAN ERROR (DEBUGGING) ---
        if (!$result) {
            echo "<h1>GAGAL MENCATAT LOG!</h1>";
            echo "Query: " . $query . "<br>";
            echo "Error Database: " . pg_last_error($this->db); // Ini akan membocorkan alasan errornya
            die(); // Matikan proses biar Anda bisa baca errornya
        }
        
        return $result;
    }
}
?>
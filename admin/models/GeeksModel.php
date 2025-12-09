<?php
require_once __DIR__ . '/LogModel.php';

class GeeksModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // --- BARU: AMBIL LIST BATCH UNTUK DROPDOWN ---
    public function getListBatch() {
        // Ambil tahun-tahun yang ada di DB, urutkan dari yang terbaru
        $query = "SELECT DISTINCT batch FROM pendaftaran_user ORDER BY batch DESC";
        return pg_query($this->db, $query);
    }

    // --- UPDATE: BISA FILTER BERDASARKAN BATCH ---
    public function getAllPendaftar($batchFilter = null) {
        // Jika batch tidak dipilih, ambil batch paling baru secara otomatis
        if ($batchFilter === null) {
            $qBatch = "SELECT DISTINCT batch FROM pendaftaran_user ORDER BY batch DESC LIMIT 1";
            $resBatch = pg_query($this->db, $qBatch);
            $rowBatch = pg_fetch_assoc($resBatch);
            $batchFilter = $rowBatch['batch'] ?? date('Y'); // Fallback tahun ini
        }

        $batchClean = pg_escape_string($this->db, $batchFilter);

        // Query Ambil Data Sesuai Batch
        $query = "SELECT * FROM pendaftaran_user 
                  WHERE batch = '$batchClean'
                  ORDER BY CASE WHEN status = 'Pending' THEN 1 ELSE 2 END, id_pendaftaran_user DESC";
        
        return pg_query($this->db, $query);
    }

    public function getUserById($id) {
        $id = pg_escape_string($this->db, $id);
        $query = "SELECT * FROM pendaftaran_user WHERE id_pendaftaran_user = '$id'";
        $result = pg_query($this->db, $query);
        return pg_fetch_assoc($result);
    }

    public function updateStatus($id, $status) {
        $logger = new LogModel($this->db);
        $id = pg_escape_string($this->db, $id);
        $statusClean = pg_escape_string($this->db, $status);
        
        $query = "CALL update_status_pendaftaran($id, '$statusClean')";
        $result = pg_query($this->db, $query);

        if ($result) {
            $user = $this->getUserById($id);
            $nama = $user['nama'] ?? 'Unknown';
            $logger->catat('UPDATE', 'pendaftaran_user', $id, "Mengubah status $nama menjadi: $status");
        }
        return $result;
    }

    public function deleteUser($id) {
        $logger = new LogModel($this->db);
        $user = $this->getUserById($id);
        $nama = $user['nama'] ?? 'Unknown';
        $id = pg_escape_string($this->db, $id);
        
        $query = "CALL hapus_user_pendaftaran($id)";
        $result = pg_query($this->db, $query);

        if ($result) {
            $logger->catat('DELETE', 'pendaftaran_user', $id, "Menghapus pendaftar: $nama");
        }
        return $result;
    }

    public function catatLogEmail($idUser, $emailTujuan, $status) {
        $idUser = (int)$idUser; 
        $emailClean = pg_escape_string($this->db, $emailTujuan);
        $statusClean = pg_escape_string($this->db, $status);
        $query = "CALL catat_log_email($idUser, '$emailClean', '$statusClean')";
        return pg_query($this->db, $query);
    }
}
?>
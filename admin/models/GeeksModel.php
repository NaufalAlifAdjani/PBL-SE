<?php
// === WAJIB: Panggil LogModel ===
require_once __DIR__ . '/LogModel.php';

class GeeksModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getAllPendaftar() {
        $query = "SELECT * FROM pendaftaran_user 
                  ORDER BY CASE WHEN status = 'Pending' THEN 1 ELSE 2 END, id_pendaftaran_user DESC";
        return pg_query($this->db, $query);
    }

    public function getUserById($id) {
        $id = pg_escape_string($this->db, $id);
        $query = "SELECT * FROM pendaftaran_user WHERE id_pendaftaran_user = '$id'";
        $result = pg_query($this->db, $query);
        return pg_fetch_assoc($result);
    }

    // === MODIFIKASI UPDATE STATUS ===
    public function updateStatus($id, $status) {
        $logger = new LogModel($this->db); // Init Logger

        $id = pg_escape_string($this->db, $id);
        $statusClean = pg_escape_string($this->db, $status);
        
        $query = "CALL update_status_pendaftaran($id, '$statusClean')";
        $result = pg_query($this->db, $query);

        if (!$result) {
            die("Error Update Status: " . pg_last_error($this->db));
        } else {
            // LOG ACTIVITY
            // Ambil nama user dulu biar lognya bagus
            $user = $this->getUserById($id);
            $nama = $user['nama'] ?? 'Unknown';
            
            $logger->catat('UPDATE', 'pendaftaran_user', $id, "Mengubah status pendaftaran $nama menjadi: $status");
        }
        return $result;
    }

    // === MODIFIKASI DELETE ===
    public function deleteUser($id) {
        $logger = new LogModel($this->db); // Init Logger

        $user = $this->getUserById($id); // Ambil data sebelum hapus
        $nama = $user['nama'] ?? 'Unknown';

        $id = pg_escape_string($this->db, $id);
        $query = "CALL hapus_user_pendaftaran($id)";
        $result = pg_query($this->db, $query);

        if (!$result) {
            die("Error Delete User: " . pg_last_error($this->db));
        } else {
            // LOG ACTIVITY
            $logger->catat('DELETE', 'pendaftaran_user', $id, "Menghapus pendaftar: $nama");
        }
        return $result;
    }

    public function catatLogEmail($idUser, $emailTujuan, $status) {
        // Ini log internal sistem untuk email, mungkin tidak perlu masuk activity_logs admin
        // kecuali kamu mau mencatatnya juga. Biarkan default dulu.
        $idUser = (int)$idUser; 
        $emailClean = pg_escape_string($this->db, $emailTujuan);
        $statusClean = pg_escape_string($this->db, $status);
        
        $query = "CALL catat_log_email($idUser, '$emailClean', '$statusClean')";
        $result = pg_query($this->db, $query);

        if (!$result) {
            die("Error Catat Log Email"); 
        }
        return $result;
    }
}
?>
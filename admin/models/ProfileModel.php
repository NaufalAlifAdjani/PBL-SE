<?php
// === WAJIB: Panggil ActivityLogModel ===
require_once __DIR__ . '/ActivityLogModel.php';

class ProfileModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllProfiles()
    {
        $sql = "SELECT id, title, slug, menu_group, is_published, updated_at
                FROM profile ORDER BY display_order ASC";
        return pg_query($this->conn, $sql);
    }

    public function getProfileById($id)
    {
        $sql = "SELECT * FROM profile WHERE id = $1";
        $result = pg_query_params($this->conn, $sql, [$id]);
        return pg_fetch_assoc($result);
    }

    // === MODIFIKASI CREATE ===
    public function createProfile($data)
    {
        $logger = new ActivityLogModel($this->conn); // Init Logger

        $sql = "CALL sp_create_profile($1, $2, $3, $4, $5, $6)";
        $params = [
            $data['title'], $data['slug'], $data['content'],
            $data['menu_group'], (int)$data['display_order'], $data['is_published']
        ];

        $res = pg_query_params($this->conn, $sql, $params);

        if ($res) {
            // Karena SP tidak return ID, kita isi ID Target 0, tapi keterangan jelas
            $logger->catat('CREATE', 'profile', 0, "Menambah halaman profile: " . $data['title']);
        }
        return $res;
    }

    // === MODIFIKASI UPDATE ===
    public function updateProfile($id, $data)
    {
        $logger = new ActivityLogModel($this->conn); // Init Logger

        $sql = "CALL sp_update_profile($1, $2, $3, $4, $5, $6, $7)";
        $params = [
            $id, $data['title'], $data['slug'], $data['content'],
            $data['menu_group'], (int)$data['display_order'], $data['is_published']
        ];

        $res = pg_query_params($this->conn, $sql, $params);

        if ($res) {
             $logger->catat('UPDATE', 'profile', $id, "Mengupdate halaman profile: " . $data['title']);
        }
        return $res;
    }

    // === MODIFIKASI DELETE ===
    public function deleteProfile($id)
    {
        $logger = new ActivityLogModel($this->conn); // Init Logger
        // Ambil judul dulu sebelum dihapus (opsional, biar log bagus)
        $cek = pg_fetch_assoc(pg_query_params($this->conn, "SELECT title FROM profile WHERE id=$1", [$id]));
        $judul_lama = $cek['title'] ?? 'Unknown';

        $sql = "CALL sp_delete_profile($1)";
        $res = pg_query_params($this->conn, $sql, [$id]);

        if ($res) {
            $logger->catat('DELETE', 'profile', $id, "Menghapus halaman profile : " . $judul_lama);
        }
        return $res;
    }
}
?>
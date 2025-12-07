<?php
require_once __DIR__ . '/LogModel.php';
class BlogModel {
    private $db;

    public function __construct($conn) {
        $this->db = $conn;
    }

    // Mendapatkan semua artikel
    public function getAllArticles($keyword = null, $status = null) {
        // Query dasar
        $query = "SELECT a.*, ad.username
                FROM artikel a
                LEFT JOIN admin ad ON a.id_admin = ad.id_admin
                WHERE 1=1";

        $params = [];
        $idx = 1; // Counter untuk parameter $1, $2, dst

        // Filter Search (Judul / Konten)
        if (!empty($keyword)) {
            $query .= " AND (a.judul ILIKE $" . $idx . " OR a.isi_konten ILIKE $" . $idx . ")";
            $params[] = "%" . $keyword . "%";
            $idx++;
        }

        // Filter Status
        if (!empty($status)) {
            $query .= " AND a.status_artikel = $" . $idx;
            $params[] = $status;
            $idx++;
        }

        $query .= " ORDER BY a.tgl_diperbarui DESC";

        // Eksekusi query PostgreSQL
        $result = pg_query_params($this->db, $query, $params);
        
        return pg_fetch_all($result) ?: [];
    }

    // Ambil 1 artikel berdasarkan ID
    public function getArticleById($id) {
        $query = "SELECT * FROM artikel WHERE id_artikel = $1";
        $result = pg_query_params($this->db, $query, array($id));
        return pg_fetch_assoc($result);
    }

    // Create Artikel
    public function createArticle($data) {
        $logger = new LogModel($this->db);
        $query = "SELECT sp_tambah_artikel(
                    $1::INT,
                    $2::VARCHAR,
                    $3::VARCHAR,
                    $4::TEXT,
                    $5::VARCHAR,
                    $6::VARCHAR
                  ) as new_id";

        $params = array(
            $data['id_admin'],
            $data['judul'],
            $data['slug'],
            $data['isi_konten'],
            $data['gambar_artikel'],
            $data['status_artikel']
        );

        $result = pg_query_params($this->db, $query, $params);
        
        // Ambil ID baru untuk Log
        if ($result) {
            $row = pg_fetch_assoc($result);
            $new_id = $row['new_id'];

            // LOG ACTIVITY
            $logger->catat('CREATE', 'artikel', $new_id, "Menulis artikel baru: " . $data['judul']);
        }

        return $result;
    }

    // Update Artikel
    public function updateArticle($id, $data) {
        $logger = new LogModel($this->db);

        $query = "UPDATE artikel
                  SET id_admin = $1, judul = $2, slug = $3, isi_konten = $4,
                      gambar_artikel = $5, status_artikel = $6, tgl_diperbarui = CURRENT_TIMESTAMP
                  WHERE id_artikel = $7";

        $params = array(
            $data['id_admin'],
            $data['judul'],
            $data['slug'],
            $data['isi_konten'],
            $data['gambar_artikel'],
            $data['status_artikel'],
            $id
        );

        $result = pg_query_params($this->db, $query, $params);

        if ($result) {
            // LOG ACTIVITY
            $logger->catat('UPDATE', 'artikel', $id, "Mengedit artikel: " . $data['judul']);
        }

        return $result;
    }

    // Hapus Artikel
    public function deleteArticle($id) {
        $logger = new LogModel($this->db); // Init Logger

        // Ambil judul dulu sebelum dihapus (opsional, biar log bagus)
        $cek = pg_fetch_assoc(pg_query_params($this->db, "SELECT judul FROM artikel WHERE id_artikel=$1", [$id]));
        $judul_lama = $cek['judul'] ?? 'Unknown';

        $query = "DELETE FROM artikel WHERE id_artikel = $1";
        $result = pg_query_params($this->db, $query, array($id));

        if ($result) {
            // LOG ACTIVITY
            $logger->catat('DELETE', 'artikel', $id, "Menghapus artikel: " . $judul_lama);
        }

        return $result;
    }

    // Total Artikel (Dashboard)
    public function getArticleCount() {
        $query = "SELECT COUNT(*) as total FROM artikel";
        $result = pg_query($this->db, $query);
        $row = pg_fetch_assoc($result);
        return $row['total'];
    }
}
?>

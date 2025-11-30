<?php
class BlogModel {
    private $db;

    public function __construct($conn) {
        $this->db = $conn;
    }

    // Mendapatkan semua artikel
    public function getAllArticles() {
        $query = "SELECT a.*, ad.username
                  FROM artikel a
                  LEFT JOIN admin ad ON a.id_admin = ad.id_admin
                  ORDER BY a.tgl_diperbarui DESC";

        $result = pg_query($this->db, $query);
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

        return pg_query_params($this->db, $query, $params);
    }

    // Update Artikel
    public function updateArticle($id, $data) {
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

        return pg_query_params($this->db, $query, $params);
    }

    // Hapus Artikel
    public function deleteArticle($id) {
        $query = "DELETE FROM artikel WHERE id_artikel = $1";
        return pg_query_params($this->db, $query, array($id));
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

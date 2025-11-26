<?php
class BlogModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // mendapatkan semua artikel dari db
    public function getAllArticles() {
        $query = "SELECT a.*, ad.username
                  FROM artikel a
                  LEFT JOIN admin ad ON a.id_admin = ad.id_admin
                  ORDER BY a.tgl_dibuat DESC";
        $result = pg_query($this->conn, $query);
        return pg_fetch_all($result) ?: [];
    }

    // ambil 1 artikel berdasarkan ID
    public function getArticleById($id) {
        $query = "SELECT * FROM artikel WHERE id_artikel = $1";
        $result = pg_query_params($this->conn, $query, array($id));
        return pg_fetch_assoc($result);
    }

    // bikin artikel
    public function createArticle($data) {
        $query = "INSERT INTO artikel (id_admin, judul, slug, isi_konten, gambar_artikel, status_artikel, tgl_dibuat, tgl_diperbarui)
                  VALUES ($1, $2, $3, $4, $5, $6, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";

        $params = array(
            $data['id_admin'],
            $data['judul'],
            $data['slug'],
            $data['isi_konten'],
            $data['gambar_artikel'],
            $data['status_artikel']
        );

        return pg_query_params($this->conn, $query, $params);
    }

    // edit srtikel
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

        return pg_query_params($this->conn, $query, $params);
    }
}
?>

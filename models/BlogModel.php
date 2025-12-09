<?php
class BlogModel {
    private $db;

    public function __construct($conn) {
        $this->db = $conn;
    }

    // Helper untuk reuse
    private function fetchAll($query, $params = []) {
        $result = empty($params)
            ? pg_query($this->db, $query)
            : pg_query_params($this->db, $query, $params);
        return ($result && pg_num_rows($result) > 0) ? pg_fetch_all($result) : [];
    }

    private function fetchOne($query, $params = []) {
        $result = pg_query_params($this->db, $query, $params);
        return ($result && pg_num_rows($result) > 0) ? pg_fetch_assoc($result) : null;
    }

    public function getAllArticles($keyword = null) {
        $query = "SELECT a.*, ad.username
                  FROM artikel a
                  LEFT JOIN admin ad ON a.id_admin = ad.id_admin
                  WHERE a.status_artikel = 'Published'";
        
        $params = [];

        // Logika tambahan jika ada keyword pencarian
        if (!empty($keyword)) {
            // Gunakan ILIKE untuk PostgreSQL (Case Insensitive)
            // $1 akan mereferensikan parameter pertama di array $params
            $query .= " AND (a.judul ILIKE $1 OR a.isi_konten ILIKE $1)"; 
            $params[] = '%' . $keyword . '%';
        }

        $query .= " ORDER BY COALESCE(a.tgl_diperbarui, a.tgl_dibuat) DESC";

        return $this->fetchAll($query, $params);
    }

    public function getArticleBySlug($slug) {
        $query = "SELECT a.*, adm.username
                  FROM artikel a
                  JOIN admin adm ON a.id_admin = adm.id_admin
                  WHERE a.slug = $1 AND a.status_artikel = 'Published'";
        return $this->fetchOne($query, [$slug]);
    }
}
?>

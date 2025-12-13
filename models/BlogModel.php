<?php
class BlogModel {
    private $db;

    public function __construct($conn) {
        $this->db = $conn;
    }

    // ... method helper fetchAll dan fetchOne TETAP SAMA ...
    private function fetchAll($query, $params = []) {
        $result = empty($params)
            ? pg_query($this->db, $query)
            : pg_query_params($this->db, $query, $params);
        return ($result && pg_num_rows($result) > 0) ? pg_fetch_all($result) : [];
    }

    // --- LOGIC QUERY BUILDER (Biar gak nulis ulang query where) ---
    private function buildFilterQuery($keyword, $category, $tag) {
        $whereClause = " WHERE a.status_artikel = 'Published'";
        $params = [];
        $paramIndex = 1;

        if (!empty($category)) {
            $whereClause .= " AND a.kategori = $" . $paramIndex; 
            $params[] = $category;
            $paramIndex++;
        }

        if (!empty($keyword)) {
            $whereClause .= " AND (a.judul ILIKE $" . $paramIndex . " OR a.isi_konten ILIKE $" . $paramIndex . ")"; 
            $params[] = '%' . $keyword . '%';
            $paramIndex++;
        }

        if (!empty($tag)) {
            $whereClause .= " AND a.tags ILIKE $" . $paramIndex;
            $params[] = '%' . $tag . '%'; 
            $paramIndex++;
        }

        return ['where' => $whereClause, 'params' => $params, 'nextIndex' => $paramIndex];
    }

    // 1. HITUNG TOTAL DATA (Untuk Pagination)
    public function countArticles($keyword = null, $category = null, $tag = null) {
        $filter = $this->buildFilterQuery($keyword, $category, $tag);
        
        $query = "SELECT COUNT(*) as total FROM artikel a" . $filter['where'];
        
        $result = pg_query_params($this->db, $query, $filter['params']);
        $row = pg_fetch_assoc($result);
        
        return $row ? (int)$row['total'] : 0;
    }

    // 2. AMBIL DATA DENGAN LIMIT & OFFSET
    public function getAllArticles($keyword = null, $category = null, $tag = null, $limit = 6, $offset = 0) {
        $filter = $this->buildFilterQuery($keyword, $category, $tag);
        $params = $filter['params'];
        $idx = $filter['nextIndex'];

        $query = "SELECT a.*, ad.username
                  FROM artikel a
                  LEFT JOIN admin ad ON a.id_admin = ad.id_admin" 
                  . $filter['where'] . 
                  " ORDER BY COALESCE(a.tgl_diperbarui, a.tgl_dibuat) DESC";

        // Tambahkan Limit dan Offset untuk PostgreSQL
        $query .= " LIMIT $" . $idx . " OFFSET $" . ($idx + 1);
        
        // Masukkan nilai limit dan offset ke params
        $params[] = $limit;
        $params[] = $offset;

        return $this->fetchAll($query, $params);
    }

    // ... method getArticleBySlug TETAP SAMA ...
    public function getArticleBySlug($slug) {
        $query = "SELECT a.*, adm.username
                  FROM artikel a
                  JOIN admin adm ON a.id_admin = adm.id_admin
                  WHERE a.slug = $1 AND a.status_artikel = 'Published'";
        $result = pg_query_params($this->db, $query, [$slug]);
        return ($result && pg_num_rows($result) > 0) ? pg_fetch_assoc($result) : null;
    }
}
?>
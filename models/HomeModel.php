<?php
class HomeModel {
    private $db;

    public function __construct($conn) {
        $this->db = $conn;
    }

    // Helper private untuk eksekusi query agar tidak repetitif
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

    public function getVisiMisi() {
        return $this->fetchOne(
            "SELECT title, content FROM Profile WHERE slug = 'visi-misi'"
        );
    }

    public function getProfileDropdown() {
        $query = "SELECT title, slug FROM Profile
                  WHERE menu_group = 'profile_dropdown' AND is_published = TRUE
                  ORDER BY display_order ASC";
        $result = pg_query($this->db, $query);

        $data = [];
        if ($result && pg_num_rows($result) > 0) {
            while ($row = pg_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getPersonilDropdown() {
        return $this->fetchAll(
            "SELECT id_dosen, nama_dosen FROM dosen ORDER BY id_dosen ASC LIMIT 3"
        );
    }



    public function getAllDosen() {
        $query = "SELECT * FROM v_dosen_list
                  ORDER BY
                    CASE WHEN jabatan ILIKE '%kepala%' THEN 0 ELSE 1 END,
                    nama_dosen ASC";
        return $this->fetchAll($query);
    }

    public function getLatestArticles($limit = 3) {
        $query = "SELECT * FROM v_artikel_published
              ORDER BY COALESCE(tgl_diperbarui, tgl_dibuat) DESC
              LIMIT $1";
        return $this->fetchAll($query, [$limit]);
    }
}
?>

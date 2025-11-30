<?php
class PageModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Ambil konten halaman berdasarkan slug
    public function getPageBySlug($slug) {
        $query = "SELECT title, content FROM Profile WHERE slug = $1 AND is_published = TRUE";
        $result = pg_query_params($this->conn, $query, [$slug]);
        return ($result && pg_num_rows($result) > 0) ? pg_fetch_assoc($result) : null;
    }

    // Ambil menu sidebar
    public function getSidebarItems() {


        $query = "SELECT title, slug FROM Profile
                  WHERE menu_group = 'profile_dropdown' AND is_published = TRUE
                  ORDER BY display_order ASC";
        $result = pg_query($this->conn, $query);

        $items = [];
        if ($result) {
            while ($row = pg_fetch_assoc($result)) {
                $items[] = $row;
            }
        }
        return $items;
    }
}

?>


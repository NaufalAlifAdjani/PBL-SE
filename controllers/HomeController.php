<?php
require_once 'models/HomeModel.php';

class HomeController {
    private $conn;
    private $db_raw;

    public function __construct($dbConnection) {
        $this->db_raw = $dbConnection;
        $this->conn = new HomeModel($dbConnection);
    }

    private function getLayoutData() {
        return [
            'menu_profile'  => $this->conn->getProfileDropdown(),
            'menu_personil' => $this->conn->getPersonilDropdown()
        ];
    }

    private function prepareArticleViewData($article) {
        // Handle Gambar
        $path_gambar = 'uploads/' . ($article['gambar_artikel'] ?? '');
        $article['image_path'] = (!empty($article['gambar_artikel']) && file_exists($path_gambar))
                                 ? $path_gambar : "uploads/dummy.png";

        // Handle Snippet Teks
        $clean_text = strip_tags($article['isi_konten']);
        $article['snippet'] = (strlen($clean_text) > 100)
                              ? substr($clean_text, 0, 100) . '...'
                              : $clean_text;

        // tgl edited
        // Jika tgl_diperbarui ada isinya, berarti sudah pernah diedit
        if (!empty($article['tgl_diperbarui'])) {
            $article['is_edited'] = true;
            $article['display_date'] = date('d M Y', strtotime($article['tgl_diperbarui']));
            // $article['date_label'] = 'Updated';
        } else {
            $article['is_edited'] = false;
            $article['display_date'] = date('d M Y', strtotime($article['tgl_dibuat']));
            // $article['date_label'] = 'Published';
        }

        return $article;
    }

    // --- Main Method ---
    public function index() {
        // Ambil Data Raw
        $aboutData = $this->conn->getAboutInfo('tentang-lab');
        $rawArticles = $this->conn->getLatestArticles();

        // Proses Data Artikel (Bersihkan logika dari View)
        $processedArticles = array_map([$this, 'prepareArticleViewData'], $rawArticles);

        // Gabungkan Data
        $data = array_merge($this->getLayoutData(), [
            'about'    => $aboutData,
            'articles' => $processedArticles
        ]);

        // Prioritaskan jabatan yang mengandung kata 'kepala', lalu urutkan nama
        $sql_dosen = "SELECT * FROM v_dosen_list
                      ORDER BY
                        CASE WHEN jabatan ILIKE '%kepala%' THEN 0 ELSE 1 END, nama_dosen ASC";
        $dosen_home = pg_query($this->db_raw, $sql_dosen);

        if (!$dosen_home) {
            // Error handling simple, biar gak crash fatal
            error_log("Gagal load dosen home: " . pg_last_error($this->conn));
        }

        // Load View
        require 'home_view.php';
    }
}
?>

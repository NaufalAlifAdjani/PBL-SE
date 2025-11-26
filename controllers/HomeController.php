<?php
require_once 'models/HomeModel.php';

class HomeController {
    private $model;

    public function __construct($dbConnection) {
        $this->model = new HomeModel($dbConnection);
    }

    private function getLayoutData() {
        return [
            'menu_profile'  => $this->model->getProfileDropdown(),
            'menu_personil' => $this->model->getPersonilDropdown()
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

        return $article;
    }

    // --- Main Method ---
    public function index() {
        // Ambil Data Raw
        $aboutData = $this->model->getAboutInfo('tentang-lab');
        $rawArticles = $this->model->getLatestArticles();

        // Proses Data Artikel (Bersihkan logika dari View)
        $processedArticles = array_map([$this, 'prepareArticleViewData'], $rawArticles);

        // Gabungkan Data
        $data = array_merge($this->getLayoutData(), [
            'about'    => $aboutData,
            'articles' => $processedArticles
        ]);

        // Load View
        require 'views/home_view.php';
    }
}
?>

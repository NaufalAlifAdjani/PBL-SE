<?php
require_once 'models/BlogModel.php';
require_once 'models/HomeModel.php';

class BlogController {
    private $blogModel;
    private $homeModel;

    public function __construct($dbConnection) {
        $this->blogModel = new BlogModel($dbConnection);
        $this->homeModel = new HomeModel($dbConnection);
    }

    // Mengambil data Header
    private function getLayoutData() {
        return [
            'menu_profile'  => $this->homeModel->getProfileDropdown(),
            'menu_personil' => $this->homeModel->getPersonilDropdown()
        ];
    }

    // Menyiapkan data artikel
    private function prepareArticleViewData($article) {
        $path_gambar = 'uploads/' . ($article['gambar_artikel'] ?? '');
        $article['image_path'] = (!empty($article['gambar_artikel']) && file_exists($path_gambar))
                                 ? $path_gambar : "uploads/dummy.png";

        // Buat snippet bersih
        $clean_text = strip_tags($article['isi_konten']);
        $article['snippet'] = (strlen($clean_text) > 100)
                              ? substr($clean_text, 0, 100) . '...'
                              : $clean_text;

        return $article;
    }

    // --- MAIN METHODS ---
    public function index() {
        $rawArticles = $this->blogModel->getAllArticles();

        // Proses setiap artikel untuk view
        $processedArticles = array_map([$this, 'prepareArticleViewData'], $rawArticles);

        // Gabungkan data Layout + Data Halaman
        $data = array_merge($this->getLayoutData(), [
            'articles' => $processedArticles
        ]);

        require 'views/blog_view.php';
    }

    public function detail($slug) {
        $articleRaw = $this->blogModel->getArticleBySlug($slug);

        // Default State 
        $viewData = [
            'exists'  => false,
            'judul'   => "Artikel Tidak Ditemukan",
            'konten'  => "<div class='alert alert-warning'>Maaf, artikel yang Anda cari tidak ada.</div>",
            'gambar'  => "https://placehold.co/1200x400/dc3545/white?text=404+Not+Found",
            'pembuat' => "System",
            'tgl'     => date('d F Y')
        ];

        if ($articleRaw) {
            $processed = $this->prepareArticleViewData($articleRaw);
            $viewData = [
                'exists'  => true,
                'judul'   => htmlspecialchars($processed['judul']),
                'konten'  => $processed['isi_konten'],
                'gambar'  => $processed['image_path'],
                'pembuat' => htmlspecialchars($processed['username']),
                'tgl'     => date('d F Y', strtotime($processed['tgl_dibuat']))
            ];
        }

        $data = array_merge($this->getLayoutData(), [
            'article' => $viewData
        ]);

        require 'views/blog_detail_view.php';
    }
}
?>

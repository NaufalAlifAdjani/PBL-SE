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
        $path_gambar = 'uploads/artikel/' . ($article['gambar_artikel'] ?? '');
        $article['image_path'] = (!empty($article['gambar_artikel']) && file_exists($path_gambar))
                                 ? $path_gambar : "uploads/dummy.png";


        $clean_text = strip_tags($article['isi_konten']);
        $article['snippet'] = (strlen($clean_text) > 100)
                              ? substr($clean_text, 0, 100) . '...'
                              : $clean_text;
        //buat snippet bersih
        $article['display_date'] = date('d M Y', strtotime($article['tgl_dibuat']));

        // --- TAMBAHAN BARU ---
        $article['kategori'] = $article['kategori'] ?? 'Artikel';
        
        // Ubah string "IoT, Web, AI" menjadi Array ["IoT", "Web", "AI"]
        if (!empty($article['tags'])) {
            $article['tags_array'] = array_map('trim', explode(',', $article['tags']));
        } else {
            $article['tags_array'] = [];
        }

        // Dianggap diedit HANYA JIKA: tgl_diperbarui tidak kosong dan tgl_diperbarui TIDAK SAMA dengan tgl_dibuat
        $tgl_buat = $article['tgl_dibuat'];
        $tgl_edit = $article['tgl_diperbarui'];

        $article['is_edited'] = (!empty($tgl_edit) && $tgl_edit !== $tgl_buat);

        return $article;
    }

    // BlogController.php

    public function index() {
        // 1. Tangkap Filter
        $searchKeyword = isset($_GET['q']) ? trim($_GET['q']) : null;
        $categoryFilter = isset($_GET['cat']) ? trim($_GET['cat']) : null;
        $tagFilter      = isset($_GET['tag']) ? trim($_GET['tag']) : null;

        // --- SETUP PAGINATION ---
        $limit = 6; // Jumlah artikel per halaman (bisa diganti misal 9 atau 12)
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        
        $offset = ($page - 1) * $limit;

        // 2. Panggil Model
        // Hitung total dulu
        $totalArticles = $this->blogModel->countArticles($searchKeyword, $categoryFilter, $tagFilter);
        
        // Hitung total halaman
        $totalPages = ceil($totalArticles / $limit);

        // Ambil data sesuai halaman (kirim limit & offset)
        $rawArticles = $this->blogModel->getAllArticles($searchKeyword, $categoryFilter, $tagFilter, $limit, $offset);

        // Proses view data
        $processedArticles = array_map([$this, 'prepareArticleViewData'], $rawArticles);

        // Gabungkan data
        $data = array_merge($this->getLayoutData(), [
            'articles' => $processedArticles,
            'search_keyword' => $searchKeyword,
            'current_category' => $categoryFilter,
            'current_tag'      => $tagFilter,
            // Data Pagination untuk View
            'pagination' => [
                'current_page' => $page,
                'total_pages'  => $totalPages,
                'has_previous' => ($page > 1),
                'has_next'     => ($page < $totalPages)
            ]
        ]);

        require 'views/blog_view.php';
    }

    public function detail($slug) {
        $articleRaw = $this->blogModel->getArticleBySlug($slug);

        // Default State
        $viewData = [
            'exists'    => false,
            'judul'     => "Artikel Tidak Ditemukan",
            'konten'    => "<div class='alert alert-warning'>Maaf, artikel yang Anda cari tidak ada.</div>",
            'gambar'    => "https://placehold.co/1200x400/dc3545/white?text=404+Not+Found",
            'pembuat'   => "System",
            'tgl'       => date('d F Y'),
            'is_edited' => false // Default false
        ];

        if ($articleRaw) {
            $processed = $this->prepareArticleViewData($articleRaw);
            $viewData = [
                'exists'    => true,
                'judul'     => htmlspecialchars($processed['judul']),
                'konten'    => $processed['isi_konten'],
                'gambar'    => $processed['image_path'],
                'pembuat'   => htmlspecialchars($processed['username']),
                // Gunakan display_date yang sudah kita set ke tgl_dibuat
                'tgl'       => $processed['display_date'],
                // Pass status edited ke view detail
                'is_edited' => $processed['is_edited'],
                'kategori'  => $processed['kategori'], // Baru
                'tags'      => $processed['tags_array'] // Baru
            ];
        }

        $data = array_merge($this->getLayoutData(), [
            'article' => $viewData
        ]);

        require 'views/blog_detail_view.php';
    }
}
?>

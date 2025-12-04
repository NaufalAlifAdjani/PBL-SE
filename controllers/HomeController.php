<?php
require_once 'models/HomeModel.php';

class HomeController {
    private $conn;   // Ini menyimpan instance Model

    public function __construct($dbConnection) {
        $this->conn = new HomeModel($dbConnection);
    }

    // helper

    private function getLayoutData() {
        return [
            'menu_profile'  => $this->conn->getProfileDropdown(),
            'menu_personil' => $this->conn->getPersonilDropdown()
        ];
    }

    private function prepareArticleViewData($article) {
        // Handle Gambar
        $path_gambar = 'uploads/artikel/' . ($article['gambar_artikel'] ?? '');
        $article['image_path'] = (!empty($article['gambar_artikel']) && file_exists($path_gambar))
                                 ? $path_gambar : "uploads/dummy.png";

        // Handle Snippet Teks
        $clean_text = strip_tags($article['isi_konten']);
        $article['snippet'] = (strlen($clean_text) > 100)
                              ? substr($clean_text, 0, 100) . '...'
                              : $clean_text;


        // Handle Tanggal
        if (!empty($article['tgl_diperbarui'])) {
            $article['is_edited'] = true;
            $article['display_date'] = date('d M Y', strtotime($article['tgl_diperbarui']));
        } else {
            $article['is_edited'] = false;
            $article['display_date'] = date('d M Y', strtotime($article['tgl_dibuat']));
        }

        return $article;
    }

    // [BARU] Helper untuk merapikan data Dosen
    private function prepareDosenViewData($dosen) {
        $fotoRaw = $dosen['foto_profil'] ?? '';

        // Cek apakah file foto benar-benar ada di folder
        $pathCek = 'uploads/personil/' . $fotoRaw;
        $fotoAda = (!empty($fotoRaw) && file_exists($pathCek));

        // Jika foto tidak ada, pakai Avatar Generator (UI Avatars)
        $dosen['foto'] = $fotoAda
            ? $pathCek
            : "https://ui-avatars.com/api/?name=" . urlencode($dosen['nama_dosen']) . "&background=random";

        // Pastikan key konsisten untuk View
        $dosen['nama'] = $dosen['nama_dosen'];

        return $dosen;
    }

    public function index() {
        // 1. Ambil Data Mentah

        $visiMisiRaw  = $this->conn->getVisiMisi(); // <-- Ambil data Visi Misi
        $rawArticles  = $this->conn->getLatestArticles();
        $rawDosen     = $this->conn->getAllDosen();

        // 3. PROSES LOGIC: Visi Misi (Baru)
        $data['visi_misi'] = [
            'title'   => !empty($visiMisiRaw['title']) ? $visiMisiRaw['title'] : 'Visi & Misi',
            'content' => !empty($visiMisiRaw['content'])
                ? $visiMisiRaw['content']
                : '<p class="text-muted">Visi dan Misi belum tersedia di database.</p>'
        ];

        // 4. PROSES LOGIC: Artikel
        $data['dosen_list'] = array_map([$this, 'prepareDosenViewData'], $rawDosen);

        // 4. PROSES LOGIC: Artikel
        $data['articles'] = array_map([$this, 'prepareArticleViewData'], $rawArticles);

        // 6. Gabungkan dengan data Layout (Menu)
        $finalData = array_merge($this->getLayoutData(), $data);

        // Extract agar key array menjadi variabel ($visi_misi, $dosen_list, dll)
        extract($finalData);

        // 7. Load View
        require 'views/home_view.php';
    }
}
?>

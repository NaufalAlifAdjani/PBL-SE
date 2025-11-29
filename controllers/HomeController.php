<?php
require_once 'models/HomeModel.php';

class HomeController {
    private $conn;   // Ini menyimpan instance Model
    private $db_raw; // Ini menyimpan koneksi DB asli

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

    public function index() {
        // 1. Ambil Data Mentah
        $aboutDataRaw = $this->conn->getAboutInfo('tentang-lab');
        $visiMisiRaw  = $this->conn->getVisiMisi(); // <-- Ambil data Visi Misi
        $rawArticles  = $this->conn->getLatestArticles();

        // 2. PROSES LOGIC: About Us
        $data['about'] = [
            'content' => !empty($aboutDataRaw['content'])
                ? $aboutDataRaw['content']
                : "Website ini dibuat sebagai ruang untuk berbagi informasi dan menghadirkan konten yang bermanfaat bagi pengunjung."
        ];

        // 3. PROSES LOGIC: Visi Misi (Baru)
        $data['visi_misi'] = [
            'title'   => !empty($visiMisiRaw['title']) ? $visiMisiRaw['title'] : 'Visi & Misi',
            'content' => !empty($visiMisiRaw['content'])
                ? $visiMisiRaw['content']
                : '<p class="text-muted">Visi dan Misi belum tersedia di database.</p>'
        ];

        // 4. PROSES LOGIC: Artikel
        $data['articles'] = array_map([$this, 'prepareArticleViewData'], $rawArticles);

        // 5. PROSES LOGIC: Dosen/Tim Pengajar
        $sql_dosen = "SELECT * FROM v_dosen_list
                      ORDER BY
                        CASE WHEN jabatan ILIKE '%kepala%' THEN 0 ELSE 1 END, nama_dosen ASC";
        $dosen_res = pg_query($this->db_raw, $sql_dosen);

        $dosenList = [];
        if ($dosen_res && pg_num_rows($dosen_res) > 0) {
            while ($d = pg_fetch_assoc($dosen_res)) {
                $nama = $d['nama_dosen'] ?? $d['nama'] ?? 'Tanpa Nama';
                $fotoRaw = $d['foto_profil'] ?? '';

                $fotoFinal = $fotoRaw
                    ? "uploads/" . htmlspecialchars($fotoRaw)
                    : "https://ui-avatars.com/api/?name=" . urlencode($nama) . "&background=random";

                $dosenList[] = [
                    'nama'    => $nama,
                    'jabatan' => $d['jabatan'] ?? $d['posisi'] ?? 'Dosen',
                    'slug'    => $d['slug'] ?? '#',
                    'foto'    => $fotoFinal
                ];
            }
        }
        $data['dosen_list'] = $dosenList;

        // 6. Gabungkan dengan data Layout (Menu)
        $finalData = array_merge($this->getLayoutData(), $data);

        // Extract agar key array menjadi variabel ($visi_misi, $dosen_list, dll)
        extract($finalData);

        // 7. Load View
        require 'views/home_view.php';
    }
}
?>

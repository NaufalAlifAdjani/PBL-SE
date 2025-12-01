<?php
require_once __DIR__ . '/../models/peruser_model.php';
class peruser_controller
{
    /** @var resource PostgreSQL connection **/
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // INDEX – LIST PERSONIL (Dosen + Member) UNTUK USER
    public function index()
    {
        global $extra_css;
        $extra_css = ['personil.css'];

        // 1. DATA DOSEN (dari VIEW v_dosen_list)
        //    -> sekarang lewat model (Peruser_model::getDosenList)
        $dosen = Peruser_model::getDosenList($this->conn);
        if (!$dosen) {
            die('Query dosen gagal: ' . pg_last_error($this->conn));
        }

        // 2. DATA MEMBER (pendaftaran_user)
        $member = Peruser_model::getMemberList($this->conn);
        if (!$member) {
            die('Query member gagal: ' . pg_last_error($this->conn));
        }

        $page_title = 'Personil Lab';

        // 3. LOAD VIEW LIST
        $viewPath = __DIR__ . '/../views/personil_view.php';
        if (!file_exists($viewPath)) {
            die('View tidak ditemukan: ' . $viewPath);
        }

        include $viewPath;
    }

    // DETAIL – PROFIL DOSEN BERDASARKAN SLUG
    public function detail()
    {
        $slug = $_GET['slug'] ?? null;

        if (!$slug) {
            echo "<div class='container py-5'><h3>Slug dosen tidak diberikan.</h3></div>";
            return;
        }

        // 1) Data dosen utama (lengkap) dari model
        $dosen = peruser_model::getDosenDetailBySlug($this->conn, $slug);
        if (!$dosen) {
            echo "<div class='container py-5'>
                    <h3>Data dosen tidak ditemukan.</h3>
                  </div>";
            return;
        }

        $nip      = $dosen['nip'] ?? null;
        $id_dosen = $dosen['id_dosen'] ?? null;

        // 2) Riwayat pendidikan (tabel riwayat_pendidikan)
        $pendidikan = false;
        if ($nip) {
            $pendidikan = peruser_model::getPendidikanByNip($this->conn, $nip);
            if (!$pendidikan) {
                $pendidikan = false;
            }
        }

        // 3) Publikasi (tabel publikasi)
        $publikasi = false;
        if ($nip) {
            $publikasi = peruser_model::getPublikasiByNip($this->conn, $nip);
            if (!$publikasi) {
                $publikasi = false;
            }
        }

        // 4) KBM (JOIN kbm + mata_kuliah)
        $kbm = false;
        if ($id_dosen) {
            $kbm = peruser_model::getKbmByDosenId($this->conn, $id_dosen);
            if (!$kbm) {
                $kbm = false;
            }
        }

        $page_title = 'Detail Dosen';

        $viewPath = __DIR__ . '/../views/peruser_detail.php';
        if (!file_exists($viewPath)) {
            die('View detail tidak ditemukan: ' . $viewPath);
        }

        include $viewPath;
    }
}







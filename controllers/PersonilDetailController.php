<?php
require_once __DIR__ . '/../models/PersonilDetailModel.php';
class PersonilDetailController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

       //INDEX – LIST PERSONIL (Dosen + Member) UNTUK USER

    public function index()
    {

         //  1. DATA DOSEN


        $sql_dosen = "SELECT * FROM v_dosen_list
            ORDER BY
                CASE
                    WHEN jabatan ILIKE '%kepala%' THEN 1
                    ELSE 2
                END,
                nama_dosen ASC
        ";

        $dosen = pg_query($this->conn, $sql_dosen);

        if (!$dosen) {
            die('Query dosen gagal: ' . pg_last_error($this->conn));
        }

        $page_title = 'Personil Lab';


           //3. LOAD VIEW LIST

        $viewPath = __DIR__ . '/../views/personil_view.php';

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

          // 1) Data dosen utama

        $sql = "SELECT
                id_dosen,
                nip,
                nidn,
                nama_dosen,
                jabatan,
                email_dosen,
                foto_profil,
                slug
            FROM dosen
            WHERE slug = $1
            LIMIT 1
        ";

        $result = pg_query_params($this->conn, $sql, [$slug]);
        if (!$result) {
            echo "<div class='container py-5'><h3>Query gagal: " . htmlspecialchars(pg_last_error($this->conn)) . "</h3></div>";
            return;
        }

        $dosen = pg_fetch_assoc($result);
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


        //    2) Riwayat pendidikan (tabel riwayat_pendidikan)

        $pendidikan = false;
        if ($nip) {
            $sql_pend = "SELECT
                    program_studi,
                    nama_kampus,
                    thn_lulus,
                    jenjang
                FROM riwayat_pendidikan
                WHERE nip = $1
                ORDER BY thn_lulus DESC
            ";
            $pendidikan = pg_query_params($this->conn, $sql_pend, [$nip]);

        // 2) Riwayat pendidikan (tabel riwayat_pendidikan)
        $pendidikan = false;
        if ($nip) {
            $pendidikan = peruser_model::getPendidikanByNip($this->conn, $nip);
            if (!$pendidikan) {
                $pendidikan = false;
            }
        }


           //3) Publikasi (tabel publikasi)

        $publikasi = false;
        if ($nip) {
            $sql_pub = "SELECT
                    judul,
                    thn_terbit,
                    link_publikasi
                FROM publikasi
                WHERE nip = $1
                ORDER BY thn_terbit DESC
            ";
            $publikasi = pg_query_params($this->conn, $sql_pub, [$nip]);
        // 3) Publikasi (tabel publikasi)
        $publikasi = false;
        if ($nip) {
            $publikasi = peruser_model::getPublikasiByNip($this->conn, $nip);
            if (!$publikasi) {
                $publikasi = false;
            }
        }

           //4) KBM (JOIN kbm + mata_kuliah)

        $kbm = false;
        if ($id_dosen) {
            $sql_kbm = "SELECT
                    kbm.id_matkul,
                    mk.nama_matkul
                FROM kbm
                JOIN mata_kuliah mk ON mk.id_matkul = kbm.id_matkul
                WHERE kbm.id_dosen = $1
                ORDER BY mk.nama_matkul ASC
            ";
            $kbm = pg_query_params($this->conn, $sql_kbm, [$id_dosen]);

            if (!$kbm) {
                $kbm = false;
            }
        }

        $page_title = 'Detail Dosen';



        $viewPath = __DIR__ . '/../views/personil_detail_view.php';
        if (!file_exists($viewPath)) {
            die('View detail tidak ditemukan: ' . $viewPath);
        }

        include $viewPath;
        }
    }
    }}



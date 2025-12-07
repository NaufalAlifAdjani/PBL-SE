<?php

require_once __DIR__ . '/../models/personil_model.php';

class personil_controller
{
    /** @var resource PostgreSQL connection */
    private $conn;

    /** @var PersonilModel */
    private $model;

    public function __construct($conn)
    {
        $this->conn  = $conn;
        $this->model = new PersonilModel($conn);
    }

    // ============================
    // HALAMAN LIST PERSONIL (ADMIN)
    // ============================
    // Ubah method index() menjadi seperti ini:

    public function index()
    {
        // 1. Tangkap Keyword & Angkatan
        $keyword  = isset($_GET['q']) ? trim($_GET['q']) : null;
        $angkatan = isset($_GET['angkatan']) && $_GET['angkatan'] !== '' ? (int)$_GET['angkatan'] : null;

        // 2. Kirim ke Model
        // DOSEN (Parameter ke-3 null karena dosen tidak difilter angkatan)
        $dosen = $this->model->getList('dosen', $keyword, null);
        if (!$dosen) die('Query dosen gagal: ' . pg_last_error($this->conn));

        // MEMBER (Kirim parameter angkatan)
        $member = $this->model->getList('member', $keyword, $angkatan);
        if (!$member) die('Query member gagal: ' . pg_last_error($this->conn));

        // 3. Ambil List Tahun untuk Dropdown Filter
        $list_angkatan_db = $this->model->getListAngkatan();
        $opsi_angkatan = [];
        while ($row = pg_fetch_assoc($list_angkatan_db)) {
            $opsi_angkatan[] = $row['angkatan'];
        }

        $page_title = 'Personil Lab';

        $viewPath = __DIR__ . '/../views/manage_personil_view.php';
        if (!file_exists($viewPath)) die('View tidak ditemukan: ' . $viewPath);

        include $viewPath;
    }

    // FORM TAMBAH / EDIT DOSEN
    public function form()
    {
        $id_dosen = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        // default data dosen
        $dosen = [
            'id_dosen'      => null,
            'nip'           => '',
            'nidn'          => '',
            'nama_dosen'    => '',
            'jabatan'       => '',
            'email_dosen'   => '',
            'slug'          => '',
            'foto_profil'   => '',
        ];

        $riwayat_list   = [];
        $publikasi_list = [];
        $kbm_list       = [];
        $all_matkul     = [];

        if ($id_dosen > 0) {
            $row = $this->model->getDosenById($id_dosen);
            if ($row) {
                $dosen = $row;
            }

            $nip = $dosen['nip'] ?? null;

            if (!empty($nip)) {
                $riwayat_list   = $this->model->getRiwayatByNip($nip);
                $publikasi_list = $this->model->getPublikasiByNip($nip);
            }

            $kbm_list = $this->model->getKbmByDosenId($id_dosen);
        }

        $all_matkul = $this->model->getAllMatkul();

        $riwayat_list   = is_array($riwayat_list)   ? $riwayat_list   : [];
        $publikasi_list = is_array($publikasi_list) ? $publikasi_list : [];
        $kbm_list       = is_array($kbm_list)       ? $kbm_list       : [];
        $all_matkul     = is_array($all_matkul)     ? $all_matkul     : [];

        $page_title = $id_dosen > 0 ? 'Edit Dosen' : 'Tambah Dosen';

        $viewPath = __DIR__ . '/../views/manage_personil_view.php';
        if (!file_exists($viewPath)) {
            die('View tidak ditemukan: ' . $viewPath);
        }

        include $viewPath;
    }

    // SIMPAN DOSEN + RELASI

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: manage_personil.php');
            exit;
        }

        $id_dosen      = !empty($_POST['id_dosen']) ? (int)$_POST['id_dosen'] : 0;
        $nip           = $_POST['nip']           ?? '';
        $nidn          = $_POST['nidn']          ?? '';
        $nama_dosen    = $_POST['nama_dosen']    ?? '';
        $jabatan       = $_POST['jabatan']       ?? '';
        $email_dosen   = $_POST['email_dosen']   ?? '';

            // slug otomatis dari nama
            $slug = $_POST['slug'] ?? '';
            if ($slug === '' && $nama_dosen !== '') {
                $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $nama_dosen), '-'));
            }

        // 3. PENYELAMAT: Jika hasil generate di atas MASIH kosong (misal nama dosen dihapus/kosong)
        // Kita beri nilai default random supaya tidak error "Duplicate Key" di database
        if (empty($slug)) {
            $slug = 'dosen-' . uniqid(); 
        }

        // simpan dosen (insert/update)
        $id_dosen = $this->model->saveDosen(
            $id_dosen,
            $nip,
            $nidn,
            $nama_dosen,
            $jabatan,
            $email_dosen,
            $slug
        );

        // RIWAYAT PENDIDIKAN
        if ($nip !== '') {
            $this->model->deleteRiwayatByNip($nip);
        }

        $jenjang       = $_POST['jenjang']        ?? [];
        $program_studi = $_POST['program_studi']  ?? [];
        $nama_kampus   = $_POST['nama_kampus']    ?? [];
        $thn_lulus     = $_POST['thn_lulus']      ?? [];

        $count_rw = count($jenjang);
        for ($i = 0; $i < $count_rw; $i++) {
            $j  = trim($jenjang[$i]        ?? '');
            $ps = trim($program_studi[$i]  ?? '');
            $nk = trim($nama_kampus[$i]    ?? '');
            $tl = trim($thn_lulus[$i]      ?? '');

            if ($j === '' && $ps === '' && $nk === '' && $tl === '') {
                continue;
            }

            $this->model->insertRiwayat($nip, $j, $ps, $nk, $tl);
        }

        // PUBLIKASI
        if ($nip !== '') {
            $this->model->deletePublikasiByNip($nip);
        }

        $judul_pub = $_POST['judul_pub'] ?? [];
        $tahun_pub = $_POST['tahun_pub'] ?? [];
        $link_pub  = $_POST['link_pub']  ?? [];

        $count_pub = count($judul_pub);
        for ($i = 0; $i < $count_pub; $i++) {
            $judul = trim($judul_pub[$i] ?? '');
            $thn   = trim($tahun_pub[$i] ?? '');
            $link  = trim($link_pub[$i]  ?? '');

            if ($judul === '' && $thn === '' && $link === '') {
                continue;
            }

            $this->model->insertPublikasi($nip, $judul, $thn, $link);
        }

        // KBM
        $this->model->deleteKbmByDosen($id_dosen);

        $id_matkul = $_POST['id_matkul'] ?? [];
        foreach ($id_matkul as $id_mk) {
            $id_mk = (int)$id_mk;
            if ($id_mk <= 0) {
                continue;
            }
            $this->model->insertKbm($id_dosen, $id_mk);
        }

        if ($id_dosen > 0) { 
        $status = 'updated';
        } else {
            $status = 'created';
        }

        header('Location: manage_personil.php?status=' . $status);
        exit;
    }

    // DETAIL DOSEN

    public function detail_dosen()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo "<div class='container p-4'><h4>ID dosen tidak diberikan.</h4></div>";
            return;
        }

        $dosen = $this->model->getDosenDetailById((int)$id);
        if (!$dosen) {
            echo "<div class='container p-4'><h4>Data dosen tidak ditemukan.</h4></div>";
            return;
        }

        echo "<pre>";
        print_r($dosen);
        echo "</pre>";
    }


    // DELETE DOSEN

    public function delete_dosen()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            header('Location: manage_personil.php?status=error_param');
            exit;
        }

        $this->model->deleteDosenCascade($id);

        header('Location: manage_personil.php?status=deleted');
        exit;
    }

 
    // DELETE MEMBER (Stored Procedure)
   
    public function delete_member()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            header('Location: manage_personil.php?status=error_param');
            exit;
        }

        $this->model->callDeleteMemberSP($id);

        header('Location: manage_personil.php?status=deleted');
        exit;
    }


    // FORM EDIT MEMBER

    public function form_member()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            echo "<div class='container p-4'><h4>ID member tidak valid.</h4></div>";
            return;
        }

        $row = $this->model->getMemberById($id);
        if (!$row) {
            echo "<div class='container p-4'><h4>Data member tidak ditemukan.</h4></div>";
            return;
        }

        $member_detail = [
            'id_member'       => $row['id_pendaftaran_user'],
            'nama'            => $row['nama'],
            'nim'             => $row['nim'],
            'link_portofolio' => $row['portofolio'],
        ];

        $page_title = 'Edit Member';

        $viewPath = __DIR__ . '/../views/manage_personil_view.php';
        if (!file_exists($viewPath)) {
            die('View tidak ditemukan: ' . $viewPath);
        }

        include $viewPath;
    }

    // UPDATE MEMBER
   
    public function update_member()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: manage_personil.php');
            exit;
        }

        $id   = !empty($_POST['id_member']) ? (int)$_POST['id_member'] : 0;
        $nama = $_POST['nama'] ?? '';
        $nim  = $_POST['nim'] ?? '';
        $link = $_POST['link_portofolio'] ?? '';

        if ($id <= 0) {
            header('Location: manage_personil.php?status=error_param');
            exit;
        }

        $this->model->updateMember($id, $nama, $nim, $link);

        header('Location: manage_personil.php?status=updated');
        exit;
    }
}








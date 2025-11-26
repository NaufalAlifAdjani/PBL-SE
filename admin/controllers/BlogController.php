<?php
include_once 'models/BlogModel.php';

class BlogController {
    private $model;

    public function __construct($conn) {
        $this->model = new BlogModel($conn);
    }

    public function index() {
        // minta data ke Model
        $artikel = $this->model->getAllArticles();

        include 'views/manage_blog_view.php';
    }

    public function form() {
        // inisialisasi var default agar view tidak error
        $judul = '';
        $isi_konten = '';
        $status_artikel = 'Draft';
        $gambar_sekarang = '';
        $mode = 'create';
        $id_artikel = 0;

        // 2. cek apakah mode edit?
        if (isset($_GET['id'])) {
            $mode = 'update';
            $id_artikel = $_GET['id'];
            $artikel = $this->model->getArticleById($id_artikel);

            if ($artikel) {
                $judul = $artikel['judul'];
                $isi_konten = $artikel['isi_konten'];
                $status_artikel = $artikel['status_artikel'];
                $gambar_sekarang = $artikel['gambar_artikel'];
            } else {
                // ID tidak ketemu, balik ke index
                header("Location: manage_blog.php");
                exit;
            }
        }

        // Proses Form Submission (POST)
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->processSave($mode, $id_artikel);
            return;
        }

        include 'views/blog_form_view.php';
    }

    private function processSave($mode, $id_artikel) {
        $judul_post = $_POST['judul'];
        $isi_konten_post = $_POST['isi_konten'];
        $status_artikel_post = $_POST['status_artikel'];
        $id_admin_post = 1;

        // buat slug
        $slug_post = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul_post)));

        // handle file upload
        $nama_file_gambar = $_POST['gambar_sekarang_hidden'] ?? '';
        $upload_dir = '../uploads/';

        if (isset($_FILES['gambar_artikel']) && $_FILES['gambar_artikel']['error'] == 0) {
            $nama_file_asli = basename($_FILES['gambar_artikel']['name']);
            $nama_file_unik = uniqid() . '-' . $nama_file_asli;
            $target_file = $upload_dir . $nama_file_unik;

            if (move_uploaded_file($_FILES['gambar_artikel']['tmp_name'], $target_file)) {
                $nama_file_gambar = $nama_file_unik;

                // Hapus gambar lama jika ada gambar baru
                if ($mode == 'update' && !empty($_POST['gambar_sekarang_hidden'])) {
                    $gambar_lama = $upload_dir . $_POST['gambar_sekarang_hidden'];
                    if (file_exists($gambar_lama)) unlink($gambar_lama);
                }
            }
        }

        // Siapkan Data Array
        $data = [
            'id_admin' => $id_admin_post,
            'judul' => $judul_post,
            'slug' => $slug_post,
            'isi_konten' => $isi_konten_post,
            'gambar_artikel' => $nama_file_gambar,
            'status_artikel' => $status_artikel_post
        ];

        // Panggil Model untuk Simpan/Update
        if ($mode == 'update') {
            $hasil = $this->model->updateArticle($id_artikel, $data);
        } else {
            $hasil = $this->model->createArticle($data);
        }

        if ($hasil) {
            echo "<script>alert('Berhasil disimpan!'); window.location='manage_blog.php';</script>";
            exit;
        } else {
            die("Error Saving Data.");
        }
    }
}
?>

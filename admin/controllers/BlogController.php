<?php
include_once 'models/BlogModel.php'; //manggil mode db

class BlogController {
    private $model;

    public function __construct($conn) {
        $this->model = new BlogModel($conn); // Inisialisasi model dgn koneksi db
    }

    // Di dalam class BlogController method index()

    public function index() {
        // 1. Tangkap input dari URL (Search & Filter)
        $keyword = isset($_GET['q']) ? $_GET['q'] : null;
        $status  = isset($_GET['status']) ? $_GET['status'] : null;

        // 2. Minta data ke Model (Model yang akan query ke DB)
        $artikel = $this->model->getAllArticles($keyword, $status); 

        // 3. Panggil View (View hanya menampilkan $artikel)
        include 'views/manage_blog_view.php'; 
    }

    // Inisialisasi default form
    public function form() {
        $judul = '';
        $isi_konten = '';
        $status_artikel = 'Draft';
        $gambar_sekarang = '';
        $mode = 'create';
        $id_artikel = 0;

        if (isset($_GET['id'])) { // Jika ada id, berarti edit
            $mode = 'update';
            $id_artikel = $_GET['id'];
            $artikel = $this->model->getArticleById($id_artikel);

            if ($artikel) {
                // Isi form dengan data artikel
                $judul = $artikel['judul'];
                $isi_konten = $artikel['isi_konten'];
                $status_artikel = $artikel['status_artikel'];
                $gambar_sekarang = $artikel['gambar_artikel'];
            } else {
                header("Location: manage_blog.php"); // Kalau data tidak ada, balik ke list
                exit;
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // simpan/update
            $this->processSave($mode, $id_artikel);
            return;
        }

        include 'views/blog_form_view.php'; //tampil form
    }

    public function delete($id) {
        $artikel = $this->model->getArticleById($id);

        if ($artikel && !empty($artikel['gambar_artikel'])) {
            $file = '../uploads/artikel/' . $artikel['gambar_artikel'];
            if (file_exists($file)) {
                unlink($file); // Hapus gambar lama
            }
        }

        $hasil = $this->model->deleteArticle($id); // Hapus data dari db

        if ($hasil) {
            header("Location: manage_blog.php?status=deleted"); // Redirect ke list
            exit;
        } else {
            // Error handling sederhana
            header("Location: manage_blog.php?status=error");
            exit;
        }
    }

    private function processSave($mode, $id_artikel) {
        // Ambil data dari form
        $judul_post = $_POST['judul'];
        $isi_konten_post = $_POST['isi_konten'];
        $status_artikel_post = $_POST['status_artikel'];
        $id_admin_post = 1;

        // Buat slug dari judul
        $slug_post = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul_post)));

        $nama_file_gambar = $_POST['gambar_sekarang_hidden'] ?? '';
        $upload_dir = '../uploads/artikel/';

        if (isset($_FILES['gambar_artikel']) && $_FILES['gambar_artikel']['error'] == 0) {
            $nama_file_asli = basename($_FILES['gambar_artikel']['name']);
            $nama_file_unik = uniqid() . '-' . $nama_file_asli;
            $target_file = $upload_dir . $nama_file_unik;

            if (move_uploaded_file($_FILES['gambar_artikel']['tmp_name'], $target_file)) {
                $nama_file_gambar = $nama_file_unik;

                // Hapus gambar lama jika update
                if ($mode == 'update' && !empty($_POST['gambar_sekarang_hidden'])) {
                    $gambar_lama = $upload_dir . $_POST['gambar_sekarang_hidden'];
                    if (file_exists($gambar_lama)) unlink($gambar_lama);
                }
            }
        }

        // Siapkan data utk db
        $data = [
            'id_admin' => $id_admin_post,
            'judul' => $judul_post,
            'slug' => $slug_post,
            'isi_konten' => $isi_konten_post,
            'gambar_artikel' => $nama_file_gambar,
            'status_artikel' => $status_artikel_post
        ];

        // Simpan/update
        if ($mode == 'update') {
            $hasil = $this->model->updateArticle($id_artikel, $data);
        } else {
            $hasil = $this->model->createArticle($data);
        }

        // popup info hadil
        if ($hasil) {

            if ($mode == 'update') {
                $status = 'updated';    
            } else {
                $status = 'created';
            }

            header("Location: manage_blog.php?status=" . $status);
            exit;
        } else {
            header("Location: manage_blog.php?status=error");
            exit;
        }
    }
}
?>

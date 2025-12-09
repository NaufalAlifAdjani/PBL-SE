<?php
require_once 'models/PortofolioModel.php';

class PortofolioController {
    private $model;

    public function __construct($conn) {
        $this->model = new PortofolioModel($conn);
    }

    // Menampilkan halaman List (Tabel) dengan Filter
    public function index() {
        $page_title = "Kelola Portofolio";

        // 1. Tangkap Filter dari URL
        $filters = [
            'kategori' => $_GET['kategori'] ?? '',
            'tahun'    => $_GET['tahun'] ?? '',
            'search'   => $_GET['search'] ?? ''
        ];

        // 2. Ambil Data Utama (sudah difilter)
        $portofolios = $this->model->getFiltered($filters);

        // 3. Ambil Data untuk Isi Dropdown
        $list_kategori = $this->model->getDistinctKategori();
        $list_tahun = $this->model->getDistinctTahun();

        // 4. Kirim semua ke View
        include 'views/portofolio_list_view.php';
    }

    // ... (Fungsi form, save, delete biarkan sama seperti kodemu sebelumnya) ...
    
    public function form($id = null) {
        $data = [];
        $page_title = "Tambah Portofolio Baru";

        if ($id) {
            $data = $this->model->getById($id);
            $page_title = "Edit Portofolio";
            if (!$data) {
                echo "<script>alert('Data tidak ditemukan!'); window.location='manage_portofolio.php';</script>";
                exit;
            }
        }
        include 'views/portofolio_form_view.php';
    }

    public function save() {
        $id = $_POST['id'] ?? '';
        $data = [
            'id_portofolio' => $id,
            'judul' => $_POST['judul'],
            'kategori' => $_POST['kategori'],
            'tahun' => $_POST['tahun'],
            'penulis' => $_POST['penulis'],
            'deskripsi' => $_POST['deskripsi'],
            'link_eksternal' => $_POST['link_eksternal'],
            'gambar' => ''
        ];

        // Upload Logic (Keep existing logic if working, ensuring $data['gambar'] isn't overwritten if no upload)
        // Note: Logic upload kamu sebelumnya menimpa $data['gambar'] jadi string kosong jika tidak upload saat edit.
        // Sebaiknya ditangani di Model atau di sini dicek jika edit & kosong jangan di-set.
        // Tapi untuk fokus perbaikan error, saya gunakan logic kamu:
        
        if (!empty($_FILES['gambar']['name'])) {
            $nama_file = time() . '_' . $_FILES['gambar']['name'];
            $tmp_file = $_FILES['gambar']['tmp_name'];
            $path = "../uploads/portofolio/" . $nama_file;
            if (move_uploaded_file($tmp_file, $path)) {
                $data['gambar'] = $nama_file;
            }
        }

        if (empty($id)) {
            $result = $this->model->insert($data);
            $status = "created";
        } else {
            $result = $this->model->update($data);
            $status = "updated";
        }

        if ($result) {
            header("Location: manage_portofolio.php?status=" . $status);
            exit;
        } else {
            header("Location: manage_portofolio.php?status=error&msg=Gagal menyimpan data");
            exit;
        }
    }

    public function delete($id) {
        $data = $this->model->getById($id);
        if ($data && $data['gambar'] && file_exists("../uploads/portofolio/" . $data['gambar'])) {
            unlink("../uploads/portofolio/" . $data['gambar']);
        }

        if ($this->model->delete($id)) {
            header("Location: manage_portofolio.php?status=deleted");
            exit;
        } else {
            header("Location: manage_portofolio.php?status=error&msg=Gagal menghapus data");
            exit;
        }
    }
}
?>
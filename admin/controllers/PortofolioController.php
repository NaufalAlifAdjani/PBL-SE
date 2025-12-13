<?php
require_once 'models/PortofolioModel.php';
require_once 'models/ActivityLogModel.php';

class PortofolioController {
    private $model;
    private $logger;

    public function __construct($conn) {
        $this->model = new PortofolioModel($conn);
        $this->logger = new ActivityLogModel($conn);
    }

    // Menampilkan halaman List (Tabel) dengan Filter
    public function index() {
        $page_title = "Kelola Portofolio";

        // 1. Tangkap Filter
        $filters = [
            'kategori' => $_GET['kategori'] ?? '',
            'tahun'    => $_GET['tahun'] ?? '',
            'search'   => $_GET['search'] ?? ''
        ];

        // [BARU] Konfigurasi Pagination
        $limit = 5; // Jumlah data per halaman (sesuaikan keinginan)
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page); // Pastikan halaman minimal 1
        $offset = ($page - 1) * $limit;

        // [BARU] Hitung Total Data & Total Halaman
        $total_data = $this->model->countFiltered($filters);
        $total_pages = ceil($total_data / $limit);

        // 2. Ambil Data Utama dengan Limit & Offset
        $portofolios = $this->model->getFiltered($filters, $limit, $offset);

        // 3. Ambil Data Dropdown
        $list_kategori = $this->model->getDistinctKategori();
        $list_tahun = $this->model->getDistinctTahun();

        // 4. Kirim data ke View (termasuk variabel pagination)
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
        $judul = $_POST['judul']; // Simpan judul ke variabel untuk keterangan log
        
        $data = [
            'id_portofolio' => $id,
            'judul' => $judul,
            'kategori' => $_POST['kategori'],
            'tahun' => $_POST['tahun'],
            'penulis' => $_POST['penulis'],
            'penulis_anggota' => $_POST['penulis_anggota'],
            'deskripsi' => $_POST['deskripsi'],
            'link_eksternal' => $_POST['link_eksternal'],
            'gambar' => ''
        ];

        // ... (Logika upload gambar biarkan sama seperti sebelumnya) ...
        if (!empty($_FILES['gambar']['name'])) {
            $nama_file = time() . '_' . $_FILES['gambar']['name'];
            $tmp_file = $_FILES['gambar']['tmp_name'];
            $path = "../uploads/portofolio/" . $nama_file;
            if (move_uploaded_file($tmp_file, $path)) {
                $data['gambar'] = $nama_file;
            }
        }

        // Logic Insert / Update
        if (empty($id)) {
            $result = $this->model->insert($data);
            $action_type = "CREATE";
            // Catatan: Jika model insert kamu tidak mengembalikan ID baru, 
            // kita set ID target sementara jadi 0 atau perlu modifikasi model agar return ID.
            $target_id = 0; 
            $status = "created";
        } else {
            $result = $this->model->update($data);
            $action_type = "UPDATE";
            $target_id = $id;
            $status = "updated";
        }

        if ($result) {
            // [BARU] Catat ke Activity Log jika sukses
            // Parameter: ($aksi, $tabel, $idTarget, $keterangan)
            $keterangan = "Berhasil " . ($action_type == 'CREATE' ? "menambahkan" : "mengubah") . " portofolio: " . $judul;
            
            $this->logger->catat($action_type, 'portofolio', $target_id, $keterangan);

            header("Location: manage_portofolio.php?msg_status=" . $status);
            exit;
        } else {
            header("Location: manage_portofolio.php?msg_status=error&msg=Gagal menyimpan data");
            exit;
        }
    }

    public function delete($id) {
        // Ambil data dulu untuk keperluan hapus gambar & log nama judulnya
        $data = $this->model->getById($id);
        
        // Hapus file fisik gambar jika ada
        if ($data && $data['gambar'] && file_exists("../uploads/portofolio/" . $data['gambar'])) {
            unlink("../uploads/portofolio/" . $data['gambar']);
        }

        // Eksekusi delete di database
        if ($this->model->delete($id)) {
            // [BARU] Catat ke Log setelah sukses delete
            $judul_hapus = isset($data['judul']) ? $data['judul'] : 'ID '.$id;
            $this->logger->catat(
                "DELETE", 
                "portofolio", 
                $id, 
                "Menghapus portofolio: " . $judul_hapus
            );

            header("Location: manage_portofolio.php?msg_status=deleted");
            exit;
        } else {
            header("Location: manage_portofolio.php?msg_status=error&msg=Gagal menghapus data");
            exit;
        }
    }
}
?>
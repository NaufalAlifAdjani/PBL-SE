<?php
require_once 'models/PortofolioModel.php';

class PortofolioController {
    private $model;

    public function __construct($dbConnection) {
        $this->model = new PortofolioModel($dbConnection);
    }

    // Buka PortofolioController.php dan ubah method index()

    public function index() {
        // 1. Sidebar Counts (Tetap sama)
        $total_all          = $this->model->hitungData();
        $total_publikasi    = $this->model->hitungData('publikasi');
        $total_produk       = $this->model->hitungData('produk');
        $total_penelitian   = $this->model->hitungData('penelitian');
        $total_pengabdian   = $this->model->hitungData('pengabdian');

        // 2. Logika Search & Pagination [BARU]
        $keyword = isset($_GET['search']) ? $_GET['search'] : null;
        
        // Tentukan jumlah data per halaman (Misal: 9 item per halaman)
        $limit = 9; 
        
        // Cek halaman saat ini (Default halaman 1)
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = ($page < 1) ? 1 : $page; // Mencegah halaman 0 atau minus
        
        // Hitung Offset (Mulai data dari mana)
        $offset = ($page - 1) * $limit;

        // Hitung Total Data (Sesuai pencarian)
        $total_items = $this->model->countAllPortfolios($keyword);
        
        // Hitung Total Halaman (Total item dibagi limit, dibulatkan ke atas)
        $total_pages = ceil($total_items / $limit);

        // Ambil Data Portofolio sesuai halaman
        $portfolios = $this->model->getAllPortfolios($keyword, $limit, $offset);

        // 3. Panggil View (Kirimkan variabel page & total_pages ke view)
        require_once 'views/portofolio_view.php';
    }
}
?>
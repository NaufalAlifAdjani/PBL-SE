<?php
require_once 'models/PortofolioModel.php';

class PortofolioController {
    private $model;

    public function __construct($dbConnection) {
        $this->model = new PortofolioModel($dbConnection);
    }

    public function index() {
        // 1. Ambil Data Hitungan (Sidebar) - Tetap sama
        $total_all          = $this->model->hitungData();
        $total_publikasi    = $this->model->hitungData('publikasi');
        $total_produk       = $this->model->hitungData('produk');
        $total_penelitian   = $this->model->hitungData('penelitian');
        $total_pengabdian   = $this->model->hitungData('pengabdian');

        // 2. MODIFIKASI: Cek apakah ada pencarian
        $keyword = null;
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $keyword = $_GET['search'];
        }

        // Kirim keyword ke model (jika null, model akan ambil semua data)
        $portfolios = $this->model->getAllPortfolios($keyword);

        // 3. Panggil View
        require_once 'views/portofolio_view.php';
    }
}
?>
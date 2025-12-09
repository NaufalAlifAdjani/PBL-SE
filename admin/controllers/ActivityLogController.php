<?php
require_once 'models/ActivityLogModel.php';

class ActivityLogController {
    private $model;

    public function __construct($dbConnection) {
        $this->model = new ActivityLogModel($dbConnection);
    }

    public function handleRequest() {
        // 1. Ambil Parameter Filter & Pagination
        $filter_aksi = isset($_GET['filter_aksi']) ? $_GET['filter_aksi'] : '';
        $filter_tgl  = isset($_GET['filter_tgl']) ? $_GET['filter_tgl'] : '';
        
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        // 2. Panggil Model
        $totalData  = $this->model->countTotalLogs($filter_aksi, $filter_tgl);
        $logsResult = $this->model->getLogs($filter_aksi, $filter_tgl, $limit, $offset);

        // 3. Hitung Pagination
        $totalPages = ceil($totalData / $limit);

        // 4. Siapkan Data untuk View (Kemas dalam array)
        $data = [
            'logs'        => $logsResult,
            'totalData'   => $totalData,
            'totalPages'  => $totalPages,
            'currentPage' => $page,
            'filter_aksi' => $filter_aksi,
            'filter_tgl'  => $filter_tgl,
            'limit'       => $limit
        ];

        // 5. Load View
        // Variabel $data akan di-extract di view menjadi variabel biasa ($logs, $totalPages, dll)
        $this->loadView('views/activity_log_view.php', $data);
    }

    private function loadView($viewPath, $data) {
        if (file_exists($viewPath)) {
            extract($data); // Ubah array key menjadi variabel
            include $viewPath;
        } else {
            echo "Error: View not found.";
        }
    }
}
?>
<?php
include_once 'models/GeeksModel.php';

class GeeksController {
    private $model;

    public function __construct($db) {
        $this->model = new GeeksModel($db);
    }

    public function index() {
        // 1. Ambil Filter & Data Mentah
        $selected_batch = $_GET['filter_batch'] ?? null;
        $raw_result = $this->model->getAllPendaftar($selected_batch);
        $list_batch = $this->model->getListBatch();

        // 2. Tentukan Current Batch (Logika UI)
        if (!$selected_batch && pg_num_rows($raw_result) > 0) {
            $first_row = pg_fetch_assoc($raw_result, 0); 
            $current_batch = $first_row['batch'];
        } else {
            $current_batch = $selected_batch ?? date('Y');
        }

        // 3. LOGIKA UTAMA: Pisahkan Data ke Array (MVC Separation)
        // Kita siapkan keranjang kosong biar View tinggal Loop
        $data_pending = [];
        $data_diterima = [];
        $data_ditolak = [];

        if (pg_num_rows($raw_result) > 0) {
            // Reset pointer karena tadi sempat diintip (pg_fetch_assoc baris 18)
            pg_result_seek($raw_result, 0);

            while ($row = pg_fetch_assoc($raw_result)) {
                if ($row['status'] == 'Pending') {
                    $data_pending[] = $row;
                } elseif ($row['status'] == 'Diterima') {
                    $data_diterima[] = $row;
                } else {
                    $data_ditolak[] = $row; // Ditolak
                }
            }
        }

        // 4. Load View (Kirim variabel yang sudah matang)
        // Variabel $data_pending, $data_diterima, dll otomatis bisa diakses di view
        include 'views/manage_geeks_views.php';
    }
}
?>
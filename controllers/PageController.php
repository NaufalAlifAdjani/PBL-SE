<?php
require_once __DIR__ . '/../models/PageModel.php';

class PageController {
    private $model;
    private $db_connection; // 1. Properti baru untuk menyimpan koneksi

    public function __construct($conn) {
        $this->db_connection = $conn; // 2. Simpan koneksi ke properti class
        $this->model = new PageModel($conn);
    }

    public function show($slug) {
        // 1. Ambil data halaman
        $data = null;
        if ($slug) {
            $data = $this->model->getPageBySlug($slug);
        }

        // 2. Ambil data sidebar
        $sidebar_items = $this->model->getSidebarItems();


        // 3. PENYELAMAT: Definisikan variabel $conn di sini
        // Karena 'header.php' butuh variabel bernama $conn, kita buat lokal di sini.
        // Saat view di-include, dia akan mewarisi variabel lokal ini.
        $conn = $this->db_connection; 

        // dibutuhkan untuk header
        $menu_profile = $sidebar_items;



        // 4. Panggil View
        include __DIR__ . '/../views/page_view.php';
    }
}
?>


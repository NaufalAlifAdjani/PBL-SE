<?php
include_once 'models/GeeksModel.php';

class GeeksController {
    private $model;

    public function __construct($db) {
        // Inisialisasi Model dengan koneksi database
        $this->model = new GeeksModel($db);
    }

    public function index() {
        // 1. Ambil data dari Model
        $result = $this->model->getAllPendaftar();

        // 2. Load View (Header, Konten Utama, Footer)
        // Path relative terhadap file manage_geeks.php (entry point)
        include 'includes/header_admin.php'; 
        include 'views/manage_geeks_views.php';
        include 'includes/footer_admin.php';
    }
}
?>
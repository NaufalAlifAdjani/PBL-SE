<?php
// Kita perlu meload file modelnya dulu
require_once 'models/RecruitmentModel.php';

class RecruitmentController {
    private $model;

    public function __construct($dbConnection) {
        // Inisialisasi Model saat Controller dibuat
        $this->model = new RecruitmentModel($dbConnection);
    }

    public function index() {
        // Minta data ke Model
        $result = $this->model->getMemberDiterima();
        
        // Kembalikan data untuk dipakai di View
        return $result;
    }
}
?>
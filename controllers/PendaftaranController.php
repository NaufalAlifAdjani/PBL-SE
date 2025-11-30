<?php
require_once __DIR__ . '/../models/pendaftaran_model.php';

class PendaftaranController {
    private $conn;
    private $model;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
        $this->model = new PendaftaranModel($this->conn);
    }

    // Fungsi utama untuk menangani request (mirip show di PageController)
    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->prosesSimpan();
        } else {
            $this->tampilkanForm();
        }
    }

    // Logika menampilkan view
    private function tampilkanForm() {
        // Kita include view dari folder views
        $conn = $this->conn;
        include __DIR__ . '/../views/pendaftaran_view.php';
    }

    // Logika memproses data (pindahan dari proses_pendaftaran.php)
    private function prosesSimpan() {
        $dataForm = [
            'nama'       => $_POST['nama'],
            'nim'        => $_POST['nim'],
            'email'      => $_POST['email'],
            'jurusan'    => $_POST['jurusan'],
            'angkatan'   => $_POST['angkatan'],
            'portofolio' => $_POST['portofolio']
        ];

        $hasil = $this->model->tambahPendaftar($dataForm);

        if ($hasil) {
            // Redirect kembali ke pendaftaran.php (bukan ke view langsung)
            header("Location: pendaftaran.php?status=success");
            exit;
        } else {
            $error = pg_last_error($this->conn);
            header("Location: pendaftaran.php?status=error&msg=" . urlencode("Gagal database"));
            exit;
        }
    }
}
?>
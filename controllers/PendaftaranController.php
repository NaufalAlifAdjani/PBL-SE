<?php
// Pastikan path ini benar sesuai struktur folder kamu
require_once __DIR__ . '/../models/pendaftaran_model.php';

class PendaftaranController {
    private $conn;
    private $model;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
        $this->model = new PendaftaranModel($this->conn);
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->prosesSimpan();
        } else {
            $this->tampilkanForm();
        }
    }

    private function tampilkanForm() {
        // Variable $conn mungkin dibutuhkan di view untuk dropdown dsb
        $conn = $this->conn;
        include __DIR__ . '/../views/pendaftaran_view.php';
    }

    private function prosesSimpan() {
        $dataForm = [
            'nama'       => $_POST['nama'],
            'nim'        => $_POST['nim'],
            'email'      => $_POST['email'],
            'jurusan'    => $_POST['jurusan'],
            'angkatan'   => $_POST['angkatan'],
            'portofolio' => $_POST['portofolio']
        ];

        // Panggil fungsi di Model (yang baru kamu kirim)
        $hasil = $this->model->tambahPendaftar($dataForm);

        // PERUBAHAN PENTING DI SINI:
        // Karena model mengembalikan array ['status' => ..., 'msg' => ...],
        // Kita harus cek index ['status']-nya.
        if ($hasil['status'] === true) {
            // Jika SUKSES
            header("Location: pendaftaran.php?status=success");
            exit;
        } else {
            // Jika GAGAL (Duplikat atau Error Lain)
            // Ambil pesan error dari Model ($hasil['msg']) dan kirim ke URL
            $pesanError = urlencode($hasil['msg']);
            header("Location: pendaftaran.php?status=error&msg=" . $pesanError);
            exit;
        }
    }
}
?>
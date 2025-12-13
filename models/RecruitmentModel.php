<?php
class RecruitmentModel {
    private $conn;

    // Konstruktor menerima koneksi database dari db.php
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function tambahPendaftar($data) {
        // === VALIDASI 0: CEK RANGE ANGKATAN ===
        $tahunSekarang = (int)date('Y');
        $batasBawah = $tahunSekarang - 3; // Contoh: 2025 - 3 = 2022
        $angkatanInput = (int)$data['angkatan'];

        // Jika angkatan lebih besar dari tahun ini ATAU lebih kecil dari batas bawah
        if ($angkatanInput > $tahunSekarang || $angkatanInput < $batasBawah) {
            return [
                'status' => false, 
                'msg' => "Pendaftaran hanya dibuka untuk angkatan $batasBawah sampai $tahunSekarang."
            ];
        }

        // === VALIDASI 1: CEK MEMBER RESMI (Kode sebelumnya) ===
        $checkQuery = "SELECT id_pendaftaran_member FROM pendaftaran_member 
                    WHERE (email = $1 OR nim = $2) 
                    AND status = 'Diterima' LIMIT 1";
        // ... (lanjutkan kode validasi member resmi seperti sebelumnya) ...
        $checkParams = [$data['email'], $data['nim']];
        $checkResult = pg_query_params($this->conn, $checkQuery, $checkParams);

        if (pg_num_rows($checkResult) > 0) {
            return ['status' => false, 'msg' => 'Anda sudah terdaftar sebagai Member Resmi.'];
        }

        // === VALIDASI 2: INSERT (Kode sebelumnya) ===
        $query = "INSERT INTO pendaftaran_member (nama, nim, email, jurusan, angkatan, portofolio, status) 
                VALUES ($1, $2, $3, $4, $5, $6, 'Pending')";
        
        // ... (lanjutkan sisa kodenya sama persis) ...
        $params = [
            $data['nama'],
            $data['nim'],
            $data['email'],
            $data['jurusan'],
            $data['angkatan'], // Pastikan ini masuk ke DB
            $data['portofolio']
        ];

        $result = @pg_query_params($this->conn, $query, $params);
        // ... (return result logic sama seperti sebelumnya) ...
        
        if ($result) {
            return ['status' => true, 'msg' => 'Berhasil mendaftar!'];
        } else {
            $error = pg_last_error($this->conn);
            if (strpos($error, 'duplicate key value') !== false) {
                return ['status' => false, 'msg' => 'NIM atau Email ini sudah melakukan pendaftaran di periode tahun ini.'];
            }
            return ['status' => false, 'msg' => 'Gagal mendaftar, terjadi kesalahan sistem.'];
        }
    }
}
?>
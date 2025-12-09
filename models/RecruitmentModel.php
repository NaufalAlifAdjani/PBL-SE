<?php
class RecruitmentModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getMemberDiterima() {
        // PERUBAHAN DI SINI:
        // Urutkan berdasarkan 'batch' (Tahun Masuk) dulu secara menurun (DESC), 
        // baru urutkan nama sesuai abjad.
        $query = "SELECT * FROM pendaftaran_user 
                  WHERE status = 'Diterima' 
                  ORDER BY batch DESC, nama ASC";
        
        return pg_query($this->conn, $query);
    }
}
?>
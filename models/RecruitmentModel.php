<?php
class RecruitmentModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getMemberDiterima() {
        // Query SQL pindah ke sini
        $query = "SELECT * FROM pendaftaran_user WHERE status = 'Diterima' ORDER BY angkatan DESC, nama ASC";
        
        // Eksekusi query
        return pg_query($this->conn, $query);
    }
}
?>
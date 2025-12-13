<?php
class ActivityLogModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // =========================================================
    // BAGIAN 1: FUNGSI PENCATATAN (DARI LogModel)
    // =========================================================
    
    public function catat($aksi, $tabel, $idTarget, $keterangan) {
        // Cek session
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // 1. Ambil ID Admin & Username dari Session
        $idAdmin  = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'System';

        // 2. Query Insert
        $sql = "INSERT INTO activity_logs (id_admin, username, aksi, tabel_target, id_target, keterangan) 
                VALUES ($1, $2, $3, $4, $5, $6)";

        $params = [
            $idAdmin,
            $username,
            $aksi,        
            $tabel,       
            $idTarget,    
            $keterangan   
        ];

        // 3. Eksekusi (Silent mode)
        @pg_query_params($this->conn, $sql, $params);
    }

    // =========================================================
    // BAGIAN 2: FUNGSI MENAMPILKAN DATA (DARI ActivityLogModel)
    // =========================================================

    // Helper private untuk filter
    private function buildWhereClause($filterAksi, $filterTgl) {
        $where = "WHERE 1=1";
        $params = [];
        $idx = 1; // PostgreSQL param index

        if (!empty($filterAksi)) {
            $where .= " AND l.aksi = $" . $idx++;
            $params[] = $filterAksi;
        }

        if (!empty($filterTgl)) {
            $where .= " AND DATE(l.created_at) = $" . $idx++;
            $params[] = $filterTgl;
        }

        return ['where' => $where, 'params' => $params, 'nextIdx' => $idx];
    }

    public function countTotalLogs($filterAksi, $filterTgl) {
        $build = $this->buildWhereClause($filterAksi, $filterTgl);
        
        $sql = "SELECT COUNT(*) as total FROM activity_logs l " . $build['where'];
        $result = pg_query_params($this->conn, $sql, $build['params']);
        $row = pg_fetch_assoc($result);
        
        return (int)$row['total'];
    }

    public function getLogs($filterAksi, $filterTgl, $limit, $offset) {
        $build = $this->buildWhereClause($filterAksi, $filterTgl);
        $params = $build['params'];
        $idx = $build['nextIdx'];

        // Query dengan JOIN ke admin untuk memastikan data user terbaru (opsional, karena username sudah disimpan di log)
        $sql = "SELECT l.*, a.username as admin_name 
                FROM activity_logs l 
                LEFT JOIN admin a ON l.id_admin = a.id_admin 
                " . $build['where'] . " 
                ORDER BY l.created_at DESC 
                LIMIT $" . $idx++ . " OFFSET $" . $idx++;
        
        $params[] = $limit;
        $params[] = $offset;

        $result = pg_query_params($this->conn, $sql, $params);
        return $result; 
    }
}
?>
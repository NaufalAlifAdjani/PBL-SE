<?php
class ActivityLogModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // Helper private untuk membangun query filter agar tidak duplikasi kode
    private function buildWhereClause($filterAksi, $filterTgl) {
        $where = "WHERE 1=1";
        $params = [];
        $idx = 1; // PostgreSQL param index ($1, $2...)

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

        // Tambahkan Limit dan Offset
        $sql = "SELECT l.*, a.username 
                FROM activity_logs l 
                LEFT JOIN admin a ON l.id_admin = a.id_admin 
                " . $build['where'] . " 
                ORDER BY l.created_at DESC 
                LIMIT $" . $idx++ . " OFFSET $" . $idx++;
        
        $params[] = $limit;
        $params[] = $offset;

        $result = pg_query_params($this->conn, $sql, $params);
        return $result; // Mengembalikan resource result pg
    }
}
?>
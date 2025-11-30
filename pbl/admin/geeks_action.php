<?php
include '../includes/db.php';

// Ambil parameter
$action = $_GET['action'] ?? null;
$id = $_GET['id'] ?? null;

if ($action && $id) {
    
    if ($action == 'approve') {
        // Ubah status jadi Diterima
        $query = "UPDATE pendaftaran_user SET status = 'Diterima' WHERE id_pendaftaran_user = $1";
        $msg = "Mahasiswa berhasil diterima!";
        
    } elseif ($action == 'reject') {
        // Ubah status jadi Ditolak
        $query = "UPDATE pendaftaran_user SET status = 'Ditolak' WHERE id_pendaftaran_user = $1";
        $msg = "Pendaftaran ditolak.";

    } elseif ($action == 'delete') {
        // Hapus data permanen
        $query = "DELETE FROM pendaftaran_user WHERE id_pendaftaran_user = $1";
        $msg = "Data berhasil dihapus.";
    }

    // Eksekusi Query
    if (isset($query)) {
        $result = pg_query_params($conn, $query, [$id]);
        
        if ($result) {
            header("Location: manage_geeks.php?msg=" . urlencode($msg));
            exit;
        } else {
            echo "Error: " . pg_last_error($conn);
        }
    }

} else {
    header("Location: manage_geeks.php");
    exit;
}
?>
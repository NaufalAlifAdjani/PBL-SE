<?php
include '../includes/db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $query = "DELETE FROM Profile WHERE id = $1";
    $result = pg_query_params($conn, $query, [$id]);

    if ($result) {
        // Sukses
        header("Location: manage_profile.php?status=deleted");
        exit;
    } else {
        // Gagal
        header("Location: manage_profile.php?status=error");
        exit;
    }
} else {
    // Tidak ada ID
    header("Location: manage_profile.php?status=no_id");
    exit;
}
?>  
<?php
include '../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // ambil info gambar untuk dihapus
    $query_select = "SELECT gambar_artikel FROM artikel WHERE id_artikel = $1";
    $res_select = pg_query_params($conn, $query_select, array($id));

    if ($res_select) {
        $row = pg_fetch_assoc($res_select);
        if ($row && !empty($row['gambar_artikel'])) {
            $file = '../uploads/' . $row['gambar_artikel'];
            if (file_exists($file)) unlink($file);
        }
    }

    // hapus data
    $query_delete = "DELETE FROM artikel WHERE id_artikel = $1";
    $res_delete = pg_query_params($conn, $query_delete, array($id));

    if ($res_delete) {
        header("Location: manage_blog.php");
        exit;
    } else {
        die("Gagal menghapus: " . pg_last_error($conn));
    }

} else {
    header("Location: manage_blog.php");
    exit;
}
?>

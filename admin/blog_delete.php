<?php
include '../includes/db.php';

if (!isset($_GET['id'])) {
    header('Location: manage_blog.php');
    exit;
}

$id_artikel = $_GET['id'];

try {
    // ambil nama file gambar sebelum dihapus dari DB
    $sql_select = "SELECT gambar_artikel FROM artikel WHERE id_artikel = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->execute([$id_artikel]);
    $row = $stmt_select->fetch(PDO::FETCH_ASSOC);

    if ($row && !empty($row['gambar_artikel'])) {
        $gambar_path = '../uploads/' . $row['gambar_artikel'];

        // hapus file gambar dari server
        if (file_exists($gambar_path)) {
            unlink($gambar_path);
        }
    }

    // hapus record dari database
    $sql_delete = "DELETE FROM artikel WHERE id_artikel = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->execute([$id_artikel]);

    // Set pesan sukses
    // $_SESSION['success_message'] = "Artikel berhasil dihapus.";

    // redirect kembali ke halaman manage blog
    header('Location: manage_blog.php');
    exit;

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
    // redirect pesan error
    // $_SESSION['error_message'] = "Gagal menghapus artikel: " . $e->getMessage();
    // header('Location: manage_blog.php');
    // exit;
}
?>

<?php
include '../includes/db.php';

$id = $_GET['id'] ?? null;
$type = $_GET['type'] ?? null; // Ambil tipe (dosen/mahasiswa)

if ($id && $type) {
    
    if ($type == 'dosen') {
        // Hapus Dosen
        // (Opsional: Hapus foto dulu)
        $q_foto = pg_query_params($conn, "SELECT foto_profil FROM dosen WHERE id_dosen=$1", [$id]);
        $d_foto = pg_fetch_assoc($q_foto);
        if ($d_foto['foto_profil']) {
            @unlink('../uploads/personil/' . $d_foto['foto_profil']);
        }

        $query = "DELETE FROM dosen WHERE id_dosen = $1";
    } else {
        // Hapus Mahasiswa (Dari tabel pendaftaran_user)
        $query = "DELETE FROM pendaftaran_user WHERE id_pendaftaran_user = $1";
    }

    $result = pg_query_params($conn, $query, [$id]);

    if ($result) {
        header("Location: manage_personil.php?status=deleted");
        exit;
    }
}

header("Location: manage_personil.php?status=error");
exit;
?>
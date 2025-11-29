<?php
session_start();
include '../includes/db.php';            // Koneksi Database
include '../models/pendaftaran_model.php'; // Panggil Modelnya

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. Siapkan Data
    $dataForm = [
        'nama'       => $_POST['nama'],
        'nim'        => $_POST['nim'],
        'email'      => $_POST['email'],
        'jurusan'    => $_POST['jurusan'],
        'angkatan'   => $_POST['angkatan'],
        'portofolio' => $_POST['portofolio']
    ];

    // 2. Panggil Model
    // Buat object model dan kirim koneksi $conn (dari db.php)
    $pendaftaran = new PendaftaranModel($conn);
    
    // Suruh model menyimpan data
    $hasil = $pendaftaran->tambahPendaftar($dataForm);

    // 3. Cek Hasil & Redirect
    if ($hasil) {
        header("Location: ../views/pendaftaran.php?status=success");
        exit;
    } else {
        $error = pg_last_error($conn);
        header("Location: ../views/pendaftaran.php?status=error&msg=" . urlencode("Gagal database"));
        exit;
    }

} else {
    header("Location: ../views/pendaftaran.php");
    exit;
}
?>
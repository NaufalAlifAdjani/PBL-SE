<?php
// === 1. KONEKSI & HEADER ===
// Header ini diasumsikan sudah melakukan include '../includes/db.php'
// sehingga variabel global $conn tersedia.
include 'includes/header_admin.php'; 

// Jika header_admin.php TIDAK membuka koneksi database secara otomatis, 
// un-comment baris di bawah ini:
// include '../includes/db.php'; 

// === 2. INISIALISASI MVC ===
require_once 'controllers/ActivityLogController.php';

// Pastikan $conn tersedia (dari header atau db.php)
if (isset($conn)) {
    $controller = new ActivityLogController($conn);
    $controller->handleRequest();
} else {
    echo "<div class='alert alert-danger'>Koneksi database tidak ditemukan. Cek includes/header_admin.php</div>";
}

// === 3. FOOTER ===
include 'includes/footer_admin.php'; 
?>
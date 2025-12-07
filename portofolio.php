<?php
// 1. Include Koneksi Database
// Path ini 'includes/' karena file ini berada di root (sejajar folder includes)
require_once 'includes/db.php';
include 'includes/header.php'; 

// 2. Include Controller
require_once 'controllers/PortofolioController.php';

// 3. Jalankan MVC
// Buat instance Controller dengan membawa koneksi $conn dari db.php
$controller = new PortofolioController($conn);

// Panggil method utama controller untuk menampilkan halaman
$controller->index();

include 'includes/footer.php';
?>
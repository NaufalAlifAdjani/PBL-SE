<?php
// 1. Koneksi Database
include '../includes/db.php'; 

// 2. Panggil Controller
include 'controllers/GeeksController.php';

// 3. Eksekusi
// Kita passing $conn (dari db.php) ke Controller
$controller = new GeeksController($conn);
$controller->index();
?>
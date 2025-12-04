<?php

// 1. Koneksi DB
include_once 'includes/db.php';

// 2. Panggil Controller
require_once 'controllers/PageController.php';

// 3. Jalankan
$slug = $_GET['slug'] ?? null;
$controller = new PageController($conn);
$controller->show($slug);

// // 1. Ambil slug dari URL
// $slug = $_GET['slug'] ?? null;
// $data = null;

// if ($slug) {
//     // 2. Ambil data berdasarkan slug
//     $data = getProfileSection($conn, $slug);
// }
?>


<?php
// 1. Koneksi DB
include_once 'includes/db.php';

// 2. Panggil Controller Baru
require_once 'controllers/RecruitmentController.php';

// 3. Jalankan Controller
$controller = new RecruitmentController($conn);
$controller->handleRequest();
?>
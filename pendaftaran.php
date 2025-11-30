<?php
// 1. Koneksi DB
include_once 'includes/db.php';

// 2. Panggil Controller Baru
require_once 'controllers/PendaftaranController.php';

// 3. Jalankan Controller
$controller = new PendaftaranController($conn);
$controller->handleRequest();
?>
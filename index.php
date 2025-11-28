<?php
include 'includes/db.php';
require_once 'controllers/HomeController.php';

// Inisialisasi Controller
$controller = new HomeController($conn);
$controller->index();
?>

<?php
include 'includes/header_admin.php';
include '../includes/db.php';
include 'controllers/BlogController.php'; //include controller

// inisialisasi Controller
$controller = new BlogController($conn);
$controller->index(); //run method

include 'includes/footer_admin.php';
?>

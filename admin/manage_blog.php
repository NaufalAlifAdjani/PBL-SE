<?php
include 'includes/header_admin.php';
include '../includes/db.php';
include 'controllers/BlogController.php';

$controller = new BlogController($conn);
$controller->index();

include 'includes/footer_admin.php';
?>

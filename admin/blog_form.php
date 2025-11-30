<?php
include 'includes/header_admin.php';
include '../includes/db.php';
include 'controllers/BlogController.php';

$controller = new BlogController($conn);
$controller->form();

include 'includes/footer_admin.php';
?>


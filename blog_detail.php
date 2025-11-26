<?php
include 'includes/db.php';
require_once 'controllers/BlogController.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

$controller = new BlogController($conn);
$controller->detail($slug);
?>

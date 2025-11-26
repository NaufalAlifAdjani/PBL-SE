<?php
include 'includes/db.php';
require_once 'controllers/BlogController.php';

$controller = new BlogController($conn);
$controller->index();
?>

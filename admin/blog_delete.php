<?php
include '../includes/db.php';
include 'controllers/BlogController.php';

if (isset($_GET['id'])) { // Cek apakah ada parameter id
    $id = $_GET['id'];
    $controller = new BlogController($conn); // Buat instance controller
    $controller->delete($id); // Panggil delete
} else {
    header("Location: manage_blog.php");
    exit;
}
?>

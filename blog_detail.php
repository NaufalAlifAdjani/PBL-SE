<?php
include 'includes/db.php';
require_once 'controllers/BlogController.php';

$controller = new BlogController($conn);

// Ambil slug dari URL (contoh: blog_detail.php?slug=judul-artikel)
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

if ($slug) {
    // Panggil method detail dengan parameter slug
    $controller->detail($slug);
} else {
    // Jika tidak ada slug, kembalikan ke halaman blog utama
    header("Location: blog.php");
    exit;
}
?>
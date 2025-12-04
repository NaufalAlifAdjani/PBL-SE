<?php
include 'includes/header.php';

// Panggil Controller
require_once 'controllers/RecruitmentController.php';

// Jalankan Controller
$controller = new RecruitmentController($conn);
$result = $controller->index();

// Tampilkan View
include 'views/recruitment_view.php';

include 'includes/footer.php';
?>
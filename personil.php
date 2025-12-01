<?php
include 'includes/header.php';
include 'includes/db.php';
require_once __DIR__ . '/models/peruser_model.php';
require_once __DIR__ . '/controllers/peruser_controller.php';

$controller = new peruser_controller($conn);
$controller->index();

include 'includes/footer.php';






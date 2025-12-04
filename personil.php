<?php
include 'includes/header.php';
include 'includes/db.php';

require_once __DIR__ . '/models/PersonilDetailModel.php';

require_once __DIR__ . '/controllers/PersonilDetailController.php';

$controller = new PersonilDetailController($conn);
$controller->index();

include 'includes/footer.php';
?>


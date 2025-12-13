<?php
include 'includes/header.php';
include 'includes/db.php';

require_once __DIR__ . '/models/PersonilModel.php';

require_once __DIR__ . '/controllers/PersonilController.php';

$controller = new PersonilController($conn);
$controller->index();

include 'includes/footer.php';
?>


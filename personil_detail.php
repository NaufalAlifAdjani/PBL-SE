
<?php
include 'includes/header.php';
include 'includes/db.php';
require_once __DIR__ . '/controllers/PersonilDetailController.php';

$controller = new PersonilDetailController($conn);
$controller->detail();

include 'includes/footer.php';
?>


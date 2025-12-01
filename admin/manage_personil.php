<?php
include 'includes/header_admin.php';
include '../includes/db.php';
require_once __DIR__ . '/models/personil_model.php';
require_once __DIR__ . '/controllers/personil_controller.php';

$action = $_GET['action'] ?? 'index';

$controller = new personil_controller($conn);

switch ($action) {
    case 'form':
        $controller->form();
        break;

    case 'save':
        $controller->save();
        break;

    case 'delete_dosen':
        $controller->delete_dosen();
        break;

    case 'delete_member':
        $controller->delete_member();
        break;
        
    case 'form_member':
    $controller->form_member();
    break;

    case 'update_member':
        $controller->update_member();
        break;

    default:
        $controller->index();
        break;
}

include 'includes/footer_admin.php';










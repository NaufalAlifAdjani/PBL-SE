<?php
// admin/manage_profile.php

include '../includes/db.php'; 
require_once 'controllers/ProfileController.php';

$controller = new ProfileController($conn);

// Ambil action dari URL, default-nya 'index' (tampil list)
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'add':
    case 'edit':
        // Tampilkan Form (View yang sama untuk tambah/edit)
        $controller->form($id);
        break;

    case 'save':
        // Proses Simpan Data (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->save();
        }
        break;

    case 'delete':
        // Proses Hapus
        $controller->delete($id);
        break;

    default:
        // Tampilkan List Tabel (Default)
        $controller->index();
        break;
}
?>
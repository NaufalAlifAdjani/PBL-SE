<?php
// admin/manage_portofolio.php

include '../includes/db.php'; 
require_once 'controllers/PortofolioController.php';

// Inisialisasi Controller
$controller = new PortofolioController($conn);

// Ambil action dari URL
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'add':
    case 'edit':
        // Satu method 'form' menangani logika ambil data (jika edit) atau kosong (jika add)
        $controller->form($id);
        break;

    case 'save':
        // Proses Simpan Data (Create / Update)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->save();
        }
        break;

    case 'delete':
        $controller->delete($id);
        break;

    default:
        // Tampilkan Tabel
        $controller->index();
        break;
}
?>
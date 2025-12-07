<?php
// --- 1. INISIALISASI (Tanpa Output HTML) ---
include '../includes/db.php'; // Koneksi DB dulu
require_once __DIR__ . '/models/LogModel.php';
require_once __DIR__ . '/models/personil_model.php';
require_once __DIR__ . '/controllers/personil_controller.php';

$action = $_GET['action'] ?? 'index';
$controller = new personil_controller($conn);

// --- 2. LOGIKA PROSES (Database Only - Redirects) ---
// Bagian ini TIDAK BOLEH ada HTML header/footer
switch ($action) {
    case 'save':
        $controller->save();
        exit; // Stop script setelah redirect di controller

    case 'delete_dosen':
        $controller->delete_dosen();
        exit;

    case 'delete_member':
        $controller->delete_member();
        exit;

    case 'update_member':
        $controller->update_member();
        exit;
}

// --- 3. LOGIKA TAMPILAN (Views) ---
// Baru di sini kita load Header karena kita mau menampilkan halaman HTML
include 'includes/header_admin.php';

switch ($action) {
    case 'form':
        $controller->form(); // Memanggil view_personil_form_dosen.php
        break;
        
    case 'form_member':
        $controller->form_member(); // Memanggil view_personil_form_member.php
        break;

    default: // case 'index'
        $controller->index(); // Memanggil view_personil_list.php
        break;
}

include 'includes/footer_admin.php';
?>
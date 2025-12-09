<?php
// --- BAGIAN 1: KONEKSI & FILE PENDUKUNG ---
include '../includes/db.php';
include 'models/GeeksModel.php';
require_once 'services/EmailServices.php'; 
include 'controllers/GeeksController.php';

// --- BAGIAN 2: LOGIKA AKSI (MENGGUNAKAN SWITCH CASE) ---
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? 0;

// Kita cek id ada dulu, baru masuk switch
if ($id) {
    // Inisialisasi Model
    $modelAction = new GeeksModel($conn);
    $mailer = new EmailServices($modelAction); 
    $user = $modelAction->getUserById($id);

    if ($user) {
        $email = $user['email'];
        $nama  = $user['nama'];
        $msg   = ""; 

        // Menggunakan SWITCH CASE agar lebih rapi
        switch ($action) {
            case 'approve':
                if ($modelAction->updateStatus($id, 'Diterima')) {
                    $mailer->sendApprovalEmail($id, $email, $nama);
                    $msg = "User diterima (Data tersimpan).";
                } else {
                    $msg = "Gagal update database.";
                }
                break; // Jangan lupa break!

        case 'reject':
            // 1. Kirim email penolakan
            $mailer->sendRejectionEmail($id, $email, $nama);
            
            // 2. SEKARANG: Update status jadi 'Ditolak' (Jangan dihapus)
            if ($modelAction->updateStatus($id, 'Ditolak')) {
                $msg = "User berhasil ditolak. Status diperbarui.";
            } else {
                $msg = "Gagal mengupdate status database.";
            }
            break;

            case 'delete':
                $modelAction->deleteUser($id);
                $msg = "Data dihapus permanen.";
                break;
            
            default:
                // Jika action tidak dikenali, redirect tanpa pesan atau pesan error
                header("Location: manage_recruitment.php");
                exit();
        }

        // --- REDIRECT SETELAH SWITCH SELESAI ---
        // Cek jika pesannya mengandung kata "Gagal", maka statusnya error, selain itu success
        $status_type = (strpos($msg, 'Gagal') !== false) ? 'error' : 'success';

        // Sertakan parameter 'status' DAN 'msg'
        header("Location: manage_recruitment.php?status=" . $status_type . "&msg=" . urlencode($msg));
        exit();

    } else {
        header("Location: manage_recruitment.php?msg=User tidak ditemukan.");
        exit();
    }
}

// Cek safety untuk bulk action (opsional)
if (isset($_GET['action']) && !isset($_POST['bulk_action']) && !$id) {
    header("Location: manage_recruitment.php");
    exit();
}

// --- BAGIAN 3: TAMPILAN DATA ---
$controller = new GeeksController($conn);
$controller->index();
?>
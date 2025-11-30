<?php
// Lokasi: admin/geeks_action.php

include '../includes/db.php';
include 'models/GeeksModel.php';
require_once 'services/EmailServices.php'; 

// 1. Inisialisasi
$model = new GeeksModel($conn);
$mailer = new EmailServices($model); 

// ==========================================
// A. LOGIKA BULK ACTION (AKSI MASSAL)
// ==========================================
if (isset($_POST['bulk_action']) && isset($_POST['pilih_id'])) {
    
    $action_type = $_POST['bulk_action']; 
    $ids = $_POST['pilih_id']; 
    $count_success = 0;
    
    set_time_limit(300); 

    foreach ($ids as $id) {
        $user = $model->getUserById($id);

        if ($user) {
            $email = $user['email'];
            $nama  = $user['nama'];

            if ($action_type == 'approve_selected') {
                // UPDATE STATUS (Tetap di DB)
                if ($model->updateStatus($id, 'Diterima')) {
                    $mailer->sendApprovalEmail($id, $email, $nama);
                    $count_success++;
                }

            } elseif ($action_type == 'reject_selected') {
                // LOGIKA BARU: Kirim Email Dulu, Baru HAPUS
                // 1. Kirim Email Penolakan
                $mailer->sendRejectionEmail($id, $email, $nama);
                
                // 2. Hapus User dari DB
                if ($model->deleteUser($id)) {
                    $count_success++;
                }

            } elseif ($action_type == 'delete_selected') {
                // Hapus tanpa kirim email
                if ($model->deleteUser($id)) {
                    $count_success++;
                }
            }
        }
    }
    header("Location: manage_geeks.php?msg=Berhasil memproses $count_success data terpilih.");
    exit();
}

// ==========================================
// B. LOGIKA SINGLE ACTION (SATUAN)
// ==========================================
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? 0;

if ($id) {
    $user = $model->getUserById($id);

    if ($user) {
        $email = $user['email'];
        $nama  = $user['nama'];

        // --- TERIMA (APPROVE) ---
        if ($action == 'approve') {
            // Update jadi Diterima (Masuk DB / Tetap di DB)
            if ($model->updateStatus($id, 'Diterima')) {
                $mailer->sendApprovalEmail($id, $email, $nama);
                header("Location: manage_geeks.php?msg=User diterima (Data tersimpan).");
            } else {
                header("Location: manage_geeks.php?msg=Gagal update database.");
            }

        // --- TOLAK (REJECT) ---
        } elseif ($action == 'reject') {
            // LOGIKA BARU:
            // 1. Kirim Email dulu (Mumpung datanya masih ada)
            $mailer->sendRejectionEmail($id, $email, $nama);
            
            // 2. Langsung Hapus dari DB
            if ($model->deleteUser($id)) {
                header("Location: manage_geeks.php?msg=User ditolak, email terkirim, dan data dihapus.");
            } else {
                header("Location: manage_geeks.php?msg=Gagal menghapus data.");
            }

        // --- HAPUS (DELETE) ---
        } elseif ($action == 'delete') {
            $model->deleteUser($id);
            header("Location: manage_geeks.php?msg=Data dihapus permanen.");
        }
    } else {
        header("Location: manage_geeks.php?msg=User tidak ditemukan.");
    }
} else {
    if (!isset($_POST['bulk_action'])) {
        header("Location: manage_geeks.php");
    }
}
?>  
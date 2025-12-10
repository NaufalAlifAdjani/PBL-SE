<?php
session_start();
// PERHATIKAN PATH INI:
// Jika file ini ada di folder 'admin/', dan db.php ada di 'includes/' (di luar admin), gunakan '../includes/db.php'
// Jika db.php ada di 'admin/includes/', gunakan 'includes/db.php'
include '../includes/db.php'; 

// Cek login admin
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

// Ambil data JSON
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['status'])) {
    $newStatus = $input['status'] ? '1' : '0'; 

    // --- PERBAIKAN DISINI (Gunakan pg_query_params) ---
    $query = "UPDATE settings SET value = $1 WHERE key_name = 'recruitment_status'";
    $result = pg_query_params($conn, $query, array($newStatus));

    if ($result) {
        echo json_encode(['status' => 'success', 'new_value' => $newStatus]);
    } else {
        // Tampilkan error asli postgres jika gagal
        echo json_encode(['status' => 'error', 'message' => 'Database Error: ' . pg_last_error($conn)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}
?>
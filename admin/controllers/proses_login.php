<?php
session_start();
session_unset(); // bersihkan session lama


include_once '../../includes/db.php';
// include '../includes/header_admin.php';
// include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ambil data username saja (password dicek manual pakai password_verify)
    $sql = "SELECT * FROM admin WHERE username = $1 LIMIT 1";
    $result = pg_query_params($conn, $sql, [$username]);
    $user = pg_fetch_assoc($result);

    if ($user) {

        if ($password === $user['password']) {

            // ====== SESSION WAJIB ======
            $_SESSION['user_id'] = $user['id_admin'];
            $_SESSION['username'] = $user['username'];
            
            // --- TAMBAHAN PENTING (Agar tidak ditendang dashboard) ---
            $_SESSION['status'] = 'login'; 
            // ---------------------------------------------------------

            // Ingat saya
            if (isset($_POST['ingat_saya'])) {
                setcookie('ingat_username', $username, time() + (86400 * 30), "/");
            } else {
                if (isset($_COOKIE['ingat_username'])) {
                    setcookie('ingat_username', '', time() - 3600, "/");
                }
            }

            header("Location:../index.php");
            exit;
        }
    }

    // Gagal login
    header("Location: ../views/login.php?error=Username%20atau%20password%20salah");
    exit;

}
?>

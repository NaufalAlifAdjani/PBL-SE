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

        // Jika password di DB tidak di-hash, gunakan perbandingan biasa:
        if ($password === $user['password']) {

            // ====== SESSION WAJIB (cocok dengan header_admin.php) ======
            $_SESSION['user_id'] = $user['id_admin'];
            $_SESSION['username'] = $user['username'];

            // Ingat saya
            if (isset($_POST['ingat_saya'])) {
                setcookie("ingat_username", $username, time() + (86400 * 30), "/");
            } else {
                setcookie("ingat_username", "", time() - 3600, "/");
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

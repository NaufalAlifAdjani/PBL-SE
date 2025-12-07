<?php
session_start();
// 1. Hapus semua session login user
session_unset();
session_destroy();

// 2. Mulai session BARU (kosong) khusus untuk menampung pesan flash
session_start();
$_SESSION['success_msg'] = "Berhasil Logout";

// 3. Redirect tanpa membawa parameter di URL
header("Location: login.php");
exit;
?>
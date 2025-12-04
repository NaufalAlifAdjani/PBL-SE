<?php
session_start();
session_unset();
session_destroy();

// Arahkan user kembali ke halaman LOGIN, bukan index.php
header("Location: login.php?msg=Berhasil Logout");
exit;
?>
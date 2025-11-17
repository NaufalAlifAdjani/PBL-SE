<?php
// Mulai session untuk cek login
session_start();

// Cek jika user belum login, lempar ke halaman login
// (Kita asumsikan ada 'login.php' di folder 'admin/')
// if (!isset($_SESSION['user_id'])) {
//     header('Location: login.php');
//     exit;
// }

// Ambil nama file saat ini untuk 'active' state
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="admin-body">

<div class="d-flex">
    <nav class="sidebar vh-100">
        <div class="sidebar-header">
            <h3 class="text-white fw-bold">Lab SE</h3>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'manage_blog.php') ? 'active' : ''; ?>" href="manage_blog.php">
                    <i class="bi bi-file-earmark-text-fill"></i> Manage Blog
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'manage_personil.php') ? 'active' : ''; ?>" href="manage_personil.php">
                    <i class="bi bi-people-fill"></i> Manage Personil
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'manage_geeks.php') ? 'active' : ''; ?>" href="manage_geeks.php">
                    <i class="bi bi-person-badge-fill"></i> Manage SE Geeks
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'manage_profile.php') ? 'active' : ''; ?>" href="manage_profile.php">
                    <i class="bi bi-person-vcard-fill"></i> Manage Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>" href="#">
                    <i class="bi bi-gear-fill"></i> Settings
                </a>
            </li>
        </ul>
        <div class="sidebar-footer mt-auto">
            <a href="#" class="btn btn-logout">
                <i class="bi bi-box-arrow-left"></i> Logout
            </a>
        </div>
    </nav>

    <main class="main-content flex-grow-1 p-4">
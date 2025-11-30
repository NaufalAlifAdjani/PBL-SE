<?php
session_start();

// Gunakan __DIR__ agar path ke db.php selalu benar, tidak peduli dipanggil dari mana
include_once __DIR__ . '/../../includes/db.php'; 

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

    <link rel="stylesheet" href="../assets/css/style_admin.css">
    <!-- <link rel="stylesheet" href="admin.css"> -->

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="admin-body">

<nav class="navbar navbar-dark d-md-none sticky-top px-3 shadow-sm" style="background-color: #111827; z-index: 1050;">
    <span class="navbar-brand fw-bold">Lab SE</span>
    <button class="btn btn-outline-light border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
        <i class="bi bi-list fs-1"></i>
    </button>
</nav>

<div class="admin-wrapper">
    
    <div class="sidebar offcanvas-md offcanvas-start" tabindex="-1" id="sidebarMenu" style="background-color: #111827 !important; color: #fff !important;">
        
        <div class="sidebar-header">
            <h3 class="text-white fw-bold mb-0">Lab SE</h3>
            <button type="button" class="btn-close btn-close-white sidebar-close-btn" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body d-flex flex-column p-0 h-100">
            <ul class="nav flex-column mt-2">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">
                        <i class="bi bi-grid-fill me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'manage_blog.php') ? 'active' : ''; ?>" href="manage_blog.php">
                        <i class="bi bi-file-earmark-text-fill me-2"></i> Manage Blog
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'manage_personil.php') ? 'active' : ''; ?>" href="manage_personil.php">
                        <i class="bi bi-people-fill me-2"></i> Manage Personil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'geeks_action.php') ? 'active' : ''; ?>" href="geeks_action.php">
                        <i class="bi bi-person-badge-fill me-2"></i> Manage SE Geeks
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'manage_profile.php') ? 'active' : ''; ?>" href="manage_profile.php">
                        <i class="bi bi-person-vcard-fill me-2"></i> Manage Profile
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer mt-auto">
                <a href="../index.php" class="btn btn-logout">
                    <i class="bi bi-box-arrow-left me-2"></i> Logout
                </a>
            </div>
        </div>
    </div>

    <main class="main-content flex-grow-1 p-4">
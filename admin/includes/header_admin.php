<?php
// Cek apakah session sudah dimulai, jika belum, start session.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
global $conn;
// Gunakan __DIR__ agar path ke db.php selalu benar
include_once __DIR__ . '/../../includes/db.php'; 

// Default status
$is_recruitment_open = false; 

// Gunakan pg_query, bukan $conn->prepare
$query = "SELECT value FROM settings WHERE key_name = 'recruitment_status'";
$result = pg_query($conn, $query);

if ($result) {
    $row_status = pg_fetch_assoc($result);
    // Jika value '1' maka ON
    if ($row_status && $row_status['value'] == '1') {
        $is_recruitment_open = true;
    }
}


// === LOGIKA KEAMANAN ===
// Jika status bukan 'login', tendang ke halaman login
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    // Sesuaikan path ini dengan struktur foldermu. 
    // Jika header_admin.php di-include oleh index.php, path ini relatif terhadap index.php
    header("Location: views/login.php?msg=Harap Login Terlebih Dahulu");
    exit(); 
}
// =======================

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

    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body class="admin-body">

<nav class="navbar navbar-dark d-md-none sticky-top px-3 shadow-sm">
    <span class="navbar-brand fw-bold">Lab SE</span>
    <button class="btn btn-outline-light border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
        <i class="bi bi-list fs-1"></i>
    </button>
</nav>

<div class="admin-wrapper">
    
    <div class="sidebar offcanvas-md offcanvas-start" tabindex="-1" id="sidebarMenu" style="background-color: #ffffffff !important; color: #fff !important;">
        
        <div class="sidebar-header">
            <h3 class="text-white fw-bold mb-0">Lab SE</h3>
            <button type="button" class="btn-close btn-close-white sidebar-close-btn" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'manage_blog.php') ? 'active' : ''; ?>" href="manage_blog.php">
                    <i class="bi bi-file-earmark-text-fill"></i> Manage Article
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'manage_personil.php') ? 'active' : ''; ?>" href="manage_personil.php">
                    <i class="bi bi-people-fill"></i> Manage Personil
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'manage_recruitment.php') ? 'active' : ''; ?>" href="manage_recruitment.php">
                    <i class="bi bi-person-badge-fill"></i> Manage recruitment
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'manage_profile.php') ? 'active' : ''; ?>" href="manage_profile.php">
                    <i class="bi bi-person-vcard-fill"></i> Manage Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'activity_logs.php') ? 'active' : ''; ?>" href="activity_logs.php">
                    <i class="bi bi-activity"></i> Activity Logs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'manage_portofolio.php') ? 'active' : ''; ?>" href="manage_portofolio.php">
                <i class="bi bi-briefcase-fill"></i> Manage Portofolio
                </a>
            </li>
            <li class="sidebar-footer mt-auto">
                <a href="views/logout.php" class="btn btn-logout">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </a>
            </li>
        </ul>   
    </div>

    <main class="main-content flex-grow-1 p-4">
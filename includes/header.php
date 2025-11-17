<?php
// (Mencari 'db.php' di folder yang sama, yaitu 'includes/')
include_once 'db.php'; 

function getProfileSection($conn, $slug) {
    $query = "SELECT title, content FROM Profile WHERE slug = $1 AND is_published = TRUE";
    $result = pg_query_params($conn, $query, [$slug]);
    if ($result && pg_num_rows($result) > 0) {
        return pg_fetch_assoc($result);
    }
    return null;
}

$query_dropdown = "SELECT title, slug FROM Profile 
                   WHERE menu_group = 'profile_dropdown' AND is_published = TRUE 
                   ORDER BY display_order ASC";
$dropdown_items = pg_query($conn, $query_dropdown);

$query_personil = "SELECT id_dosen, nama_dosen FROM dosen ORDER BY id_dosen ASC LIMIT 3";
$personil_items = pg_query($conn, $query_personil);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Lab SE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="/pbl/assets/css/style.css"> 
    
    <style>
        /* (Style kustom kamu) */
        .navbar { background-color: #fff; border-bottom: 1px solid #e9ecef; }
        .navbar-brand { font-weight: 700; }
        .btn-login { background-color: #111827; color: #fff; font-weight: 600; border-radius: 8px; }
        .btn-login:hover { background-color: #374151; color: #fff; }
        .profile-container {
            max-width: 1040px;
            margin: 40px auto;
            padding: 40px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        
        <a class="navbar-brand" href="/pbl/index.php">Lab SE</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="/pbl/index.php">Landing Page</a></li> 
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownProfile" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Profile
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownProfile">
                        <?php
                        if ($dropdown_items && pg_num_rows($dropdown_items) > 0) {
                            while($item = pg_fetch_assoc($dropdown_items)) {
                                echo '<li><a class="dropdown-item" href="/pbl/page.php?slug=' . htmlspecialchars($item['slug']) . '">' . htmlspecialchars($item['title']) . '</a></li>';
                            }
                        } else {
                            echo '<li><a class="dropdown-item" href="#">(Belum ada data)</a></li>';
                        }
                        ?>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPersonil" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Personil
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownPersonil">
                        <li><a class="dropdown-item" href="/pbl/personil.php">Lihat Semua Personil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <?php
                        if ($personil_items && pg_num_rows($personil_items) > 0) {
                            while($p_item = pg_fetch_assoc($personil_items)) {
                                // echo '<li><a class="dropdown-item" href="/pbl/personil.php#personil-' . $p_item['id_dosen'] . '">' . htmlspecialchars($p_item['nama_dosen']) . '</a></li>';
                                echo '<li><a class="dropdown-item" href="/pbl/personil.php#personil-dosen-' . $p_item['id_dosen'] . '">' . htmlspecialchars($p_item['nama_dosen']) . '</a></li>';
                            
                            }
                        }
                        ?>
                    </ul>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownGeeks" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        SE Geeks
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownGeeks">
                        <li><a class="dropdown-item" href="/pbl/se_geeks.php">List Anggota</a></li>
                        <li><a class="dropdown-item" href="/pbl/pendaftaran.php">Pendaftaran Baru</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="#">Blog Artikel</a></li>
            </ul>
        </div>
        
        <a href="/pbl/admin/index.php" class="btn btn-login px-3 py-2">Login</a>
    </div>
</nav>

<main class="container">
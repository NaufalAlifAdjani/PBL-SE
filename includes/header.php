<?php
// (Mencari 'db.php' di folder yang sama, yaitu 'includes/')
global $conn;
if (!$conn) {
    include_once 'db.php';
}

function getProfileSection($conn, $slug) {
    $query = "SELECT title, content FROM Profile WHERE slug = $1 AND is_published = TRUE";
    $result = pg_query_params($conn, $query, [$slug]);
    if ($result && pg_num_rows($result) > 0) {
        return pg_fetch_assoc($result);
    }
    return null;
}

// query dropdown profile
$query_dropdown = "SELECT title, slug FROM Profile
                   WHERE menu_group = 'profile_dropdown' AND is_published = TRUE
                   ORDER BY display_order ASC";
$dropdown_items = pg_query($conn, $query_dropdown);

// query dropdown personil
$query_personil = "SELECT id_dosen, nama_dosen FROM dosen ORDER BY id_dosen ASC LIMIT 3";
$personil_items = pg_query($conn, $query_personil);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Lab SE</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="assets/css/userStyle.css">
    <link rel="stylesheet" href="assets/css/homeStyle.css">
        <link rel="stylesheet" href="assets/css/swiper-bundle.min.css">

</head>
<body>

<!-- navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">

        <a class="navbar-brand" href="index.php">Lab SE</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>

                <!-- profile navbar -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="/PBL-SE/page.php" id="navbarDropdownProfile" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Profile
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownProfile">
                        <li><a class="dropdown-item" href="/PBL-SE/page.php?slug=tentang-lab">Tentang Lab SE</a></li>
                        <li><a class="dropdown-item" href="/PBL-SE/page.php?slug=visi-misi">Visi & Misi</a></li>
                        <li><a class="dropdown-item" href="/PBL-SE/page.php?slug=roadmap">Roadmap Penelitian</a></li>
                        <li><a class="dropdown-item" href="/PBL-SE/page.php?slug=focus-scope">Focus & scope</a></li>
                        <li><hr class="dropdown-divider"></li>
                    </ul>
                </li>

                <!-- personil navbar -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="/PBL-SE/personil.php" id="navbarDropdownPersonil" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Personil
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownPersonil">
                        <li><a class="dropdown-item" href="/PBL-SE/personil.php">Lihat Semua Personil</a></li>
                        <li><hr class="dropdown-divider"></li>
                    </ul>
                </li>

                <!-- recruit navbar -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownGeeks" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Recruitment
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownGeeks">
                        <li><a class="dropdown-item" href="/PBL-SE/se_geeks.php">List Anggota</a></li>
                        <li><a class="dropdown-item" href="/PBL-SE/pendaftaran.php">Pendaftaran Baru</a></li>
                        <li><hr class="dropdown-divider"></li> <!-- minor change  -->
                    </ul>
                </li>

                <li class="nav-item"><a class="nav-link" href="/PBL-SE/blog.php">Blog</a></li>
            </ul>
        </div>

    </div>
</nav>

<main class="w-100">

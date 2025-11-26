<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Lab SE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/userStyle.css">
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">

        <a class="navbar-brand" href="/PBL-SE/index.php">Lab SE</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="/PBL-SE/index.php">Home</a></li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownProfile" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Profile
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownProfile">
                        <?php
                        if (!empty($data['menu_profile'])) {
                            foreach($data['menu_profile'] as $item) {
                                echo '<li><a class="dropdown-item" href="/PBL-SE/page.php?slug=' . htmlspecialchars($item['slug']) . '">' . htmlspecialchars($item['title']) . '</a></li>';
                            }
                        } else {
                            echo '<li><a class="dropdown-item" href="#">(Belum ada data)</a></li>';
                        }
                        ?>
                        <li><hr class="dropdown-divider"></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="/PBL-SE/personil.php" id="navbarDropdownPersonil" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Personil
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownPersonil">
                        <li><a class="dropdown-item" href="/PBL-SE/personil.php">Lihat Semua Personil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <?php
                        if (!empty($data['menu_personil'])) {
                            foreach($data['menu_personil'] as $p_item) {
                                echo '<li><a class="dropdown-item" href="/PBL-SE/personil.php#personil-dosen-' . $p_item['id_dosen'] . '">' . htmlspecialchars($p_item['nama_dosen']) . '</a></li>';
                            }
                        }
                        ?>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownGeeks" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Recruitment
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownGeeks">
                        <li><a class="dropdown-item" href="/PBL-SE/se_geeks.php">List Anggota</a></li>
                        <li><a class="dropdown-item" href="/PBL-SE/pendaftaran.php">Pendaftaran Baru</a></li>
                        <li><hr class="dropdown-divider"></li>
                    </ul>
                </li>

                <li class="nav-item"><a class="nav-link" href="/PBL-SE/blog.php">Blog Artikel</a></li>
            </ul>
        </div>

    </div>
</nav>

<main class="w-100">

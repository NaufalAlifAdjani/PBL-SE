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

    <!-- css -->
    <link rel="stylesheet" href="assets/css/swiper-bundle.min.css">
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

                <!-- profile navbar -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="/PBL-SE/page.php" id="navbarDropdownProfile" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Profile
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownProfile">
                        <?php
                        // DATA SOURCE: Controller ($menu_profile)
                        // Kita cek apakah $menu_profile ada dan berupa array
                        if (!empty($menu_profile) && is_array($menu_profile)) {
                            foreach ($menu_profile as $item) {
                                echo '<li><a class="dropdown-item" href="/PBL-SE/page.php?slug=' . htmlspecialchars($item['slug']) . '">' . htmlspecialchars($item['title']) . '</a></li>';
                            }
                        } else {
                            echo '<li><a class="dropdown-item" href="#">(Belum ada data)</a></li>';
                        }
                        ?>
                        <li><hr class="dropdown-divider"></li>
                    </ul>
                </li>

                <!-- personil navbar -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="/PBL-SE/personil.php" id="navbarDropdownPersonil" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Personil
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownPersonil">
                        <li><a class="dropdown-item" href="/PBL-SE/personil.php">See All Personil</a></li>
                        <li><hr class="dropdown-divider"></li>
                    </ul>
                </li>

                <!-- recruit navbar -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownGeeks" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Recruitment
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownGeeks">
                        <li><a class="dropdown-item" href="/PBL-SE/se_geeks.php">List Member</a></li>
                        <li><a class="dropdown-item" href="/PBL-SE/pendaftaran.php">New Reccruit</a></li>
                        <li><hr class="dropdown-divider"></li>
                    </ul>
                </li>

                <li class="nav-item"><a class="nav-link" href="/PBL-SE/blog.php">Blog</a></li>
            </ul>
        </div>

    </div>
</nav>

<main class="w-100">

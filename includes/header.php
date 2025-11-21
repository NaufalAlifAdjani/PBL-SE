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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="assets/css/userStyle.css">

    <style>
        /* (Style kustom kamu) */
        :root {
            --bg-dark: #0f0c1b;
            --bg-surface: #1a1626;
            --accent-purple: #8b5cf6;
            --accent-glow: #a78bfa;
            --text-primary: #ffffff;
            --text-secondary: #9ca3af;
        }

        body {
            background-color: #f3f4f6;
        }

        /* Navbar  */
        .navbar {
            background-color: rgba(15, 12, 27, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(139, 92, 246, 0.1);
            position: sticky;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0,0,0,0.4);
        }

        /* Logo */
        .navbar-brand {
            font-weight: 700;
            color: var(--text-primary) !important;
        }

        /* Nav link */
        .navbar-nav .nav-link {
            color: var(--text-secondary);
            font-weight: 400;
            padding: 8px 18px !important;
            position: relative;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.show {
            color: var(--text-primary);
            text-shadow: 0 0 10px rgba(139, 92, 246, 0.5);
        }

        .navbar-nav .nav-item .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            bottom: 5px;
            left: 50%;
            background: linear-gradient(90deg, var(--accent-purple), var(--accent-glow));
            border-radius: 2px;
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.27, 1.55);
            transform: translateX(-50%);
        }

        .navbar-nav .nav-item .nav-link:hover::after {
            width: 30px;
        }

        /* Dropdown */
        .dropdown-menu {
            background-color: var(--bg-surface);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 8px;
            margin-top: 15px;
            box-shadow: 0 10px 10px rgba(0,0,0,0.3);
            animation: slideUp 0.3s ease forwards;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-item {
            color: var(--text-secondary);
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background-color: rgba(139, 92, 246, 0.1);
            color: var(--accent-glow);
            transform: translateX(5px);
        }

        .dropdown-divider {
            border-color: rgba(255,255,255,0.1);
        }

        /* Button Login  */
        .btn-login {
            background: linear-gradient(135deg, var(--accent-purple) 0%, #d946ef 100%);
            color: #fff;
            font-weight: 600;
            border-radius: 50px;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(139, 92, 246, 0.4);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(139, 92, 246, 0.6);
            color: #fff;
        }

        /* Toggler */
        .navbar-toggler {
            border: none;
        }
        .navbar-toggler-icon {
            filter: invert(1);
        }
        .navbar-toggler:focus {
            box-shadow: none;
        }
    </style>
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
                        if ($dropdown_items && pg_num_rows($dropdown_items) > 0) {
                            while($item = pg_fetch_assoc($dropdown_items)) {
                                echo '<li><a class="dropdown-item" href="/PBL-SE/page.php?slug=' . htmlspecialchars($item['slug']) . '">' . htmlspecialchars($item['title']) . '</a></li>';
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
                        <li><a class="dropdown-item" href="/PBL-SE/personil.php">Lihat Semua Personil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <?php
                        if ($personil_items && pg_num_rows($personil_items) > 0) {
                            while($p_item = pg_fetch_assoc($personil_items)) {
                                // echo '<li><a class="dropdown-item" href="/PBL-SE/personil.php#personil-' . $p_item['id_dosen'] . '">' . htmlspecialchars($p_item['nama_dosen']) . '</a></li>';
                                echo '<li><a class="dropdown-item" href="/PBL-SE/personil.php#personil-dosen-' . $p_item['id_dosen'] . '">' . htmlspecialchars($p_item['nama_dosen']) . '</a></li>';

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
                        <li><a class="dropdown-item" href="/PBL-SE/se_geeks.php">List Anggota</a></li>
                        <li><a class="dropdown-item" href="/PBL-SE/pendaftaran.php">Pendaftaran Baru</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="/PBL-SE/blog.php">Blog Artikel</a></li>
            </ul>
        </div>
        <a href="/PBL-SE/admin/index.php" class="btn btn-login px-3 py-2">Login</a>
    </div>
</nav>

<main class="w-100">

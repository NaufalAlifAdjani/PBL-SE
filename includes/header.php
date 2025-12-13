<?php
// (Mencari 'db.php' di folder yang sama, yaitu 'includes/')
global $conn;
if (!$conn) {
    include_once 'db.php';
}

$current_page = basename($_SERVER['PHP_SELF']);

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
// $query_personil = "SELECT id_dosen, nama_dosen FROM dosen ORDER BY id_dosen ASC LIMIT 3";
// $personil_items = pg_query($conn, $query_personil);

// [BARU] Cek Status Recruitment dari Database
$is_recruitment_open = false; // Default tertutup
$query_status = "SELECT value FROM settings WHERE key_name = 'recruitment_status'";
$result_status = pg_query($conn, $query_status);

if ($result_status && pg_num_rows($result_status) > 0) {
    $row_status = pg_fetch_assoc($result_status);
    // Jika value '1', berarti BUKA
    if ($row_status['value'] == '1') {
        $is_recruitment_open = true;
    }
}
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

    <link rel="stylesheet" href="assets/css/main.css">
    
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/userPersonil.css">

</head>
<body>

<!-- navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            Lab SE
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'index.php' || $current_page == '') ? 'active' : ''; ?>" href="index.php">Home</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo ($current_page == 'page.php') ? 'active' : ''; ?>" href="#" id="navbarDropdownProfile" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Profile
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownProfile">
                        <?php 
                        if ($dropdown_items && pg_num_rows($dropdown_items) > 0) {
                            while ($row = pg_fetch_assoc($dropdown_items)) {
                                $title = htmlspecialchars($row['title']);
                                $slug = htmlspecialchars($row['slug']);
                                // Cek slug URL untuk active state di dropdown item (opsional)
                                $isActiveItem = (isset($_GET['slug']) && $_GET['slug'] == $slug) ? 'active-item' : '';
                                echo "<li><a class='dropdown-item $isActiveItem' href='https://localhost/PBL-TRY/PBL-SE/page.php?slug={$slug}'>{$title}</a></li>";
                            }
                        } else {
                            echo "<li><a class='dropdown-item' href='#'>Belum ada menu</a></li>";
                        }
                        ?>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'portofolio.php') ? 'active' : ''; ?>" href="https://localhost/PBL-TRY/PBL-SE/portofolio.php">Portofolio</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'personil.php') ? 'active' : ''; ?>" href="https://localhost/PBL-TRY/PBL-SE/personil.php">Personil</a>
                </li>

                <?php if ($is_recruitment_open): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'pendaftaran.php' || $current_page == 'recruitment.php') ? 'active' : ''; ?>" href="https://localhost/PBL-TRY/PBL-SE/pendaftaran.php">Recruitment</a>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'blog.php') ? 'active' : ''; ?>" href="https://localhost/PBL-TRY/PBL-SE/blog.php">Blog</a>
                </li>

            </ul>
        </div>
        
        <a class="navbar-brand d-none d-lg-block me-0" href="#">
            <img src="uploads/logo/putih.png" alt="Logo Lab SE" style="height: 40px; width: auto;" class="me-2"> 
        </a>

    </div>
</nav>

<main class="w-100">

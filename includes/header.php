<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- navbar  -->
    <nav class="navbar navbar-expand-lg navbar-dark main-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand lab-logo" href="index.php">Logo lab</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- homepage -->
                    <li class="nav-item"><a class="nav-link <?php if(isset($activePage) && $activePage == 'home') echo 'active'; ?>" href="index.php">Home</a></li>

                    <!-- profiel -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">Profile</a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#.php"><!-- ganti --> contoh</a></li>
                            <li><a class="dropdown-item" href="#.php"><!-- ganti --> contoh</a></li>
                            <li><a class="dropdown-item" href="#.php"><!-- ganti --> contoh</a></li>
                        </ul>
                    </li>

                    <!-- personil -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">Personil</a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#.php"><!-- ganti --> contoh</a></li>
                            <li><a class="dropdown-item" href="#.php"><!-- ganti --> contoh</a></li>
                            <li><a class="dropdown-item" href="#.php"><!-- ganti --> contoh</a></li>
                        </ul>
                    </li>

                    <!-- blog -->
                    <li class="nav-item">
                        <a class="nav-link <?php if(isset($activePage) && $activePage == 'blog') echo 'active'; ?>" href="blog.php">Blog</a>
                    </li>

                    <li class="nav-item">
                        <!-- masih langsung direct ke manage blog  -->
                        <!-- belum ke login  -->
                        <a class="nav-link" href="admin/manage_blog.php">Login Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- <main id="page-content"> -->

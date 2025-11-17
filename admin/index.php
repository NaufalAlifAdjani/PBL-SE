<?php
include 'includes/header_admin.php';
// (Di sini kamu bisa query ke DB untuk mendapat angka 24, 12, 45)
// $personil_count = pg_num_rows(pg_query($conn, "SELECT 1 FROM tbl_personnel"));
// $blog_count = pg_num_rows(pg_query($conn, "SELECT 1 FROM tbl_articles"));
// $geeks_count = pg_num_rows(pg_query($conn, "SELECT 1 FROM tbl_geeks"));
?>

<h1 class="fw-bold">Dashboard Admin</h1>
<p class="text-muted">Selamat datang kembali, Admin Lab SE</p>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card-stat card-stat-blue d-flex justify-content-between align-items-center">
            <div>
                <h5>Total Personil</h5>
                <span class="stat-number">24</span>
            </div>
            <i class="bi bi-people-fill stat-icon"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-stat card-stat-green d-flex justify-content-between align-items-center">
            <div>
                <h5>Blog Articles</h5>
                <span class="stat-number">12</span>
            </div>
            <i class="bi bi-file-earmark-text-fill stat-icon"></i>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-stat card-stat-purple d-flex justify-content-between align-items-center">
            <div>
                <h5>SE Geeks Members</h5>
                <span class="stat-number">45</span>
            </div>
            <i class="bi bi-person-badge-fill stat-icon"></i>
        </div>
    </div>
</div>

<div class="card card-admin">
    <div class="card-body p-4">
        <h4 class="fw-semibold mb-3">Akses Cepat</h4>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card-admin p-3">
                    <h5 class="fw-semibold">Tambah Blog</h5>
                    <p class="text-muted mb-0">Buat artikel blog baru</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-admin p-3">
                    <h5 class="fw-semibold">Kelola Personil</h5>
                    <p class="text-muted mb-0">Edit data personil lab</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer_admin.php'; ?>
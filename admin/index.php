<?php
session_start(); // Tetap butuh start session untuk ambil data user/model

// 1. Include Koneksi & Model
include '../includes/db.php'; 
include 'models/DashboardsModel.php';

// 2. Load Header (Pengecekan Login sekarang otomatis terjadi di sini)
include 'includes/header_admin.php';

// 3. Inisialisasi Model & Ambil Data
// (Pindahkan logika pengambilan data SETELAH include header agar aman)
$dashboard = new DashboardModel($conn);
$jml_personil = $dashboard->getPersonilCount();
$jml_blog     = $dashboard->getBlogCount();
$jml_geeks    = $dashboard->getGeeksCount();
?>

<div class="mb-4">
    <h1 class="fw-bold text-dark">Dashboard Admin</h1>
    <p class="text-muted">Selamat datang kembali, Admin Lab SE</p>
</div>

<div class="row g-4">
    
    <div class="col-12 col-md-4">
        <div class="dashboard-card bg-primary text-white p-3 rounded-3 position-relative shadow-sm">
            <div class="card-content">
                <h5 class="card-title mb-0">Total Personil</h5>
                <div class="stat-number fs-1 fw-bold my-2"><?php echo $jml_personil; ?></div>
                <p class="card-desc mb-0 opacity-75">Dosen & Anggota Aktif</p>
            </div>
            <i class="bi bi-people-fill stat-icon position-absolute top-50 end-0 translate-middle-y me-3 fs-1 opacity-25"></i>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-4">
        <div class="dashboard-card bg-success text-white p-3 rounded-3 position-relative shadow-sm">
            <div class="card-content">
                <h5 class="card-title mb-0">Blog Articles</h5>
                <span class="stat-number fs-1 fw-bold my-2 d-block"><?php echo $jml_blog; ?></span>
                <p class="card-desc mb-0 opacity-75">Artikel Terpublikasi</p>
            </div>
            <i class="bi bi-file-text-fill stat-icon position-absolute top-50 end-0 translate-middle-y me-3 fs-1 opacity-25"></i>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-4">
        <div class="dashboard-card bg-purple text-white p-3 rounded-3 position-relative shadow-sm" style="background-color: #6f42c1;">
            <div class="card-content">
                <h5 class="card-title mb-0">SE Geeks Members</h5>
                <span class="stat-number fs-1 fw-bold my-2 d-block"><?php echo $jml_geeks; ?></span>
                <p class="card-desc mb-0 opacity-75">Anggota Komunitas</p>
            </div>
            <i class="bi bi-person-badge-fill stat-icon position-absolute top-50 end-0 translate-middle-y me-3 fs-1 opacity-25"></i>
        </div>
    </div>

<?php include 'includes/footer_admin.php'; ?>
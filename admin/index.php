<?php
include 'includes/header_admin.php';

// Data Dummy (Ganti query jika sudah siap)
$personil_count = 24;
$blog_count = 12;
$geeks_count = 45;
?>

<div class="mb-4">
    <h1 class="fw-bold text-dark">Dashboard Admin</h1>
    <p class="text-muted">Selamat datang kembali, Admin Lab SE</p>
</div>

<div class="row g-4">
    
    <div class="col-12 col-md-4">
        <div class="dashboard-card bg-primary">
            <div class="card-content">
                <h5 class="card-title">Total Personil</h5>
                <div class="stat-number"><?php echo $personil_count; ?></div>
                <p class="card-desc">Dosen & Anggota Aktif</p>
            </div>
            <i class="bi bi-people-fill stat-icon"></i>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="dashboard-card bg-success">
            <div class="card-content">
                <h5 class="card-title">Blog Articles</h5>
                <div class="stat-number"><?php echo $blog_count; ?></div>
                <p class="card-desc">Artikel Terpublikasi</p>
            </div>
            <i class="bi bi-file-text-fill stat-icon"></i>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="dashboard-card bg-purple">
            <div class="card-content">
                <h5 class="card-title">SE Geeks Members</h5>
                <div class="stat-number"><?php echo $geeks_count; ?></div>
                <p class="card-desc">Mahasiswa Terdaftar</p>
            </div>
            <i class="bi bi-person-badge-fill stat-icon"></i>
        </div>
    </div>

</div>

<?php include 'includes/footer_admin.php'; ?>
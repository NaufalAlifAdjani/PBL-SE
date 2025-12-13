<?php
session_start();

// 1. Include Koneksi & Model
include '../includes/db.php'; 
include 'models/DashboardsModel.php';

// 2. Load Header
include 'includes/header_admin.php';

// 3. Ambil Data
$dashboard = new DashboardModel($conn);
$jml_personil = $dashboard->getPersonilCount();
$jml_blog     = $dashboard->getBlogCount();
$jml_geeks    = $dashboard->getGeeksCount();

// Logika sapaan berdasarkan waktu
$jam = date('H');
if ($jam < 12) { $sapaan = "Selamat Pagi"; } 
elseif ($jam < 15) { $sapaan = "Selamat Siang"; }
elseif ($jam < 18) { $sapaan = "Selamat Sore"; } 
else { $sapaan = "Selamat Malam"; }
?>

<style>
    .dashboard-header {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .dashboard-header::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    
    .stat-card {
        border: none;
        border-radius: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        color: white;
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }
    .stat-card .card-body {
        position: relative;
        z-index: 2;
    }
    .stat-card .icon-bg {
        position: absolute;
        right: -20px;
        bottom: -20px;
        font-size: 8rem;
        opacity: 0.15;
        transform: rotate(-15deg);
        transition: all 0.3s ease;
        z-index: 1;
    }
    .stat-card:hover .icon-bg {
        transform: rotate(0deg) scale(1.1);
        opacity: 0.2;
    }

    /* Gradients */
    .bg-gradient-primary-custom { background: linear-gradient(45deg, #4e73df, #224abe); }
    .bg-gradient-success-custom { background: linear-gradient(45deg, #1cc88a, #13855c); }
    .bg-gradient-purple-custom  { background: linear-gradient(45deg, #6f42c1, #4e2a8e); }

    .quick-action-btn {
        border-radius: 12px;
        padding: 15px;
        border: 1px solid #e3e6f0;
        background: white;
        color: #5a5c69;
        font-weight: 600;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
    }
    .quick-action-btn:hover {
        background: #f8f9fc;
        border-color: #d1d3e2;
        color: #4e73df;
        transform: translateY(-2px);
    }
</style>

<div class="dashboard-header bg-gradient-purple-custom shadow-sm">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold mb-1"><?php echo $sapaan; ?>, Admin! 👋</h2>
            <p class="mb-0 opacity-75">Ini adalah ringkasan aktivitas di Lab Software Engineering hari ini.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <span class="badge bg-light bg-purple fs-6 px-3 py-2 rounded-pill">
                <i class="bi bi-calendar-event me-2"></i> <?php echo date('d F Y'); ?>
            </span>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-12 col-md-4">
        <div class="card stat-card bg-gradient-purple-custom shadow-sm">
            <div class="card-body p-4">
                <h6 class="text-uppercase mb-1 opacity-75 fw-bold" style="font-size: 0.85rem;">Total Personil</h6>
                <div class="d-flex align-items-center">
                    <h2 class="display-4 fw-bold mb-0 me-3"><?php echo $jml_personil; ?></h2>
                </div>
                <p class="mt-3 mb-0 small opacity-75">
                    <i class="bi bi-arrow-up-circle-fill"></i> Dosen & Anggota Aktif
                </p>
            </div>
            <i class="bi bi-people-fill icon-bg"></i>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card stat-card bg-gradient-purple-custom shadow-sm">
            <div class="card-body p-4">
                <h6 class="text-uppercase mb-1 opacity-75 fw-bold" style="font-size: 0.85rem;">Blog Articles</h6>
                <div class="d-flex align-items-center">
                    <h2 class="display-4 fw-bold mb-0 me-3"><?php echo $jml_blog; ?></h2>
                </div>
                <p class="mt-3 mb-0 small opacity-75">
                    <i class="bi bi-check-circle-fill"></i> Artikel Terpublikasi
                </p>
            </div>
            <i class="bi bi-file-earmark-richtext-fill icon-bg"></i>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card stat-card bg-gradient-purple-custom shadow-sm">
            <div class="card-body p-4">
                <h6 class="text-uppercase mb-1 opacity-75 fw-bold" style="font-size: 0.85rem;">Member</h6>
                <div class="d-flex align-items-center">
                    <h2 class="display-4 fw-bold mb-0 me-3"><?php echo $jml_geeks; ?></h2>
                </div>
                <p class="mt-3 mb-0 small opacity-75">
                    <i class="bi bi-stars"></i> Anggota Komunitas
                </p>
            </div>
            <i class="bi bi-joystick icon-bg"></i>
        </div>
    </div>

</div>

<?php include 'includes/footer_admin.php'; ?>
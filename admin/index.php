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

<!-- <div class="row g-4">
    
    <div class="col-lg-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-dark">
                    <i class="bi bi-bell-fill me-2 text-warning"></i>Butuh Tindakan (Pending)
                </h6>
                <span class="badge bg-danger rounded-pill">3 Baru</span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    
                    <div class="list-group-item border-0 p-3 d-flex align-items-start">
                        <div class="bg-primary bg-opacity-10 text-primary rounded p-2 me-3">
                            <i class="bi bi-file-earmark-text fs-5"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1 fw-bold text-dark">Review Artikel Baru</h6>
                                <small class="text-muted">10 menit lalu</small>
                            </div>
                            <p class="mb-2 text-muted small">Agata mengirimkan artikel "Dasar UX Design". Perlu persetujuan untuk publish.</p>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-success py-0 px-3" style="font-size: 0.8rem;">Publish</button>
                                <button class="btn btn-sm btn-outline-secondary py-0 px-3" style="font-size: 0.8rem;">Lihat</button>
                            </div>
                        </div>
                    </div>

                    <div class="list-group-item border-0 p-3 d-flex align-items-start bg-light bg-opacity-50">
                        <div class="bg-success bg-opacity-10 text-success rounded p-2 me-3">
                            <i class="bi bi-person-plus fs-5"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1 fw-bold text-dark">Verifikasi Anggota</h6>
                                <small class="text-muted">1 jam lalu</small>
                            </div>
                            <p class="mb-2 text-muted small">Mahasiswa baru mendaftar sebagai anggota SE Geeks.</p>
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name=Budi+S&background=random" class="rounded-circle" width="24" height="24">
                                <span class="small fw-bold">Budi Santoso (20241010)</span>
                                <button class="btn btn-sm btn-link text-primary p-0 ms-auto" style="font-size: 0.8rem; text-decoration: none;">Verifikasi</button>
                            </div>
                        </div>
                    </div>

                    <div class="list-group-item border-0 p-3 d-flex align-items-start">
                        <div class="bg-warning bg-opacity-10 text-warning rounded p-2 me-3">
                            <i class="bi bi-chat-dots fs-5"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1 fw-bold text-dark">Komentar Masuk</h6>
                                <small class="text-muted">3 jam lalu</small>
                            </div>
                            <p class="mb-1 text-muted small fst-italic">"Tutorialnya sangat membantu kak, terima kasih..."</p>
                            <span class="badge bg-light text-secondary border">di Artikel: Intro Golang</span>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card-footer bg-white border-0 text-center py-3">
                <a href="#" class="text-decoration-none small fw-bold text-muted">Lihat Semua Notifikasi</a>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-dark">
                    <i class="bi bi-trophy-fill me-2 text-primary"></i>Konten Terpopuler
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4">Judul</th>
                                <th class="text-end pe-4">Views</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-warning text-dark me-2 rounded-pill">1</span>
                                        <span class="fw-bold text-dark small">Roadmap Frontend 2025</span>
                                    </div>
                                </td>
                                <td class="text-end pe-4 text-primary fw-bold">1,204</td>
                            </tr>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-secondary bg-opacity-25 text-dark me-2 rounded-pill">2</span>
                                        <span class="fw-bold text-dark small">Cara Install Laravel</span>
                                    </div>
                                </td>
                                <td class="text-end pe-4 text-muted small fw-bold">845</td>
                            </tr>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-secondary bg-opacity-25 text-dark me-2 rounded-pill">3</span>
                                        <span class="fw-bold text-dark small">Tips UI/UX untuk Pemula</span>
                                    </div>
                                </td>
                                <td class="text-end pe-4 text-muted small fw-bold">602</td>
                            </tr>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-secondary bg-opacity-25 text-dark me-2 rounded-pill">4</span>
                                        <span class="fw-bold text-dark small">Kenapa Memilih Golang?</span>
                                    </div>
                                </td>
                                <td class="text-end pe-4 text-muted small fw-bold">410</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="p-3 bg-light border-top mt-auto">
                <h6 class="small fw-bold text-muted mb-2"><i class="bi bi-sticky me-1"></i>Catatan Pribadi</h6>
                <div class="bg-white p-2 rounded border shadow-sm">
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="checkbox" id="note1">
                        <label class="form-check-label small text-muted" for="note1">Update foto profil Lab</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="note2" checked>
                        <label class="form-check-label small text-muted text-decoration-line-through" for="note2">Balas email himpunan</label>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div> -->
</div>

<?php include 'includes/footer_admin.php'; ?>
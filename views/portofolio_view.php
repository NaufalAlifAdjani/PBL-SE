<div class="container py-5">
    <div class="row mb-5 text-center">
        <div class="col-lg-8 mx-auto">
            <h2 class="fw-bold" style="color: #3b0a70;">Portofolio & Publikasi</h2>
            <p class="text-muted">Kumpulan hasil riset, inovasi produk, dan pengabdian masyarakat Laboratorium Software Engineering.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 100px; z-index: 1;">
                <div class="card-header bg-white p-3 border-bottom-0">
                    <h6 class="mb-0 fw-bold text-uppercase text-muted small">Kategori</h6>
                </div>
                <div class="list-group list-group-flush custom-sidebar p-2">
                    <a href="#" class="list-group-item d-flex justify-content-between align-items-center active-item" onclick="filterPortofolio('all', this)">
                        Semua Data
                        <span class="badge bg-secondary rounded-pill"><?= $total_all ?></span>
                    </a>
                    <a href="#" class="list-group-item d-flex justify-content-between align-items-center" onclick="filterPortofolio('publikasi', this)">
                        Publikasi
                        <span class="badge bg-light text-dark border rounded-pill"><?= $total_publikasi ?></span>
                    </a>
                    <a href="#" class="list-group-item d-flex justify-content-between align-items-center" onclick="filterPortofolio('produk', this)">
                        Produk Inovasi
                        <span class="badge bg-light text-dark border rounded-pill"><?= $total_produk ?></span>
                    </a>
                    <a href="#" class="list-group-item d-flex justify-content-between align-items-center" onclick="filterPortofolio('penelitian', this)">
                        Penelitian
                        <span class="badge bg-light text-dark border rounded-pill"><?= $total_penelitian ?></span>
                    </a>
                    <a href="#" class="list-group-item d-flex justify-content-between align-items-center" onclick="filterPortofolio('pengabdian', this)">
                        Pengabdian
                        <span class="badge bg-light text-dark border rounded-pill"><?= $total_pengabdian ?></span>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <h4 id="judul-kategori" class="fw-bold m-0" style="color: #3b0a70;">
                    <?php if(isset($_GET['search']) && !empty($_GET['search'])): ?>
                        Hasil Pencarian: "<?= htmlspecialchars($_GET['search']) ?>"
                    <?php else: ?>
                        Semua Data
                    <?php endif; ?>
                </h4>

                <form action="" method="GET" class="d-flex" style="flex: 1; max-width: 400px;">
                    <div class="input-group shadow-sm">
                        <input type="text" 
                            name="search" 
                            class="form-control border-end-0 rounded-start-pill ps-4" 
                            placeholder="Cari judul atau penulis..." 
                            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                            
                        <button class="btn btn-white bg-white border border-start-0 text-muted" type="submit">
                            <i class="bi bi-search" style="color: #3b0a70;"></i>
                        </button>
                            
                        <?php if(isset($_GET['search'])): ?>
                            <a href="portofolio.php" class="btn btn-danger rounded-end-pill ms-1 px-3 d-flex align-items-center">
                            <i class="bi bi-x-lg"></i>
                        </a>
                        <?php endif; ?>
                     </div>
                </form>
            </div>
            
            <div class="row g-4" id="portfolio-container">
                <?php if ($portfolios): ?>
                    <?php foreach ($portfolios as $row): ?>
                        <div class="col-md-6 portfolio-item" data-category="<?= $row['kategori'] ?>">
                            <div class="card h-100 portfolio-card shadow-sm">
                                <span class="badge-category"><?= $row['kategori'] ?></span>

                                <div style="height: 200px; overflow: hidden; position: relative;">
                                    <?php if($row['gambar']): ?>
                                        <img src="uploads/portofolio/<?= $row['gambar'] ?>" class="card-img-top h-100 w-100" style="object-fit: cover;" alt="<?= $row['judul'] ?>">
                                    <?php else: ?>
                                        <div class="h-100 w-100 d-flex align-items-center justify-content-center card-gradient">
                                            <i class="bi bi-journal-bookmark fs-1 text-white-50"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="card-body d-flex flex-column text-start">
                                    <small class="text-muted mb-2">
                                        <i class="bi bi-calendar-event me-1"></i> Tahun <?= $row['tahun'] ?>
                                    </small>
                                    <h5 class="card-title fw-bold text-dark mb-3"><?= $row['judul'] ?></h5>
                                    <p class="card-text text-muted small mb-4"><?= substr($row['deskripsi'], 0, 100) ?>...</p>

                                    <div class="mt-auto pt-3 border-top">
                                        <small class="text-secondary d-block mb-3">
                                            <i class="bi bi-people-fill me-1"></i> <?= $row['penulis'] ?>
                                        </small>
                                        <?php if($row['link_eksternal']): ?>
                                            <a href="<?= $row['link_eksternal'] ?>" target="_blank" class="btn btn-outline-primary btn-sm w-100 rounded-pill">
                                                Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-light btn-sm w-100 rounded-pill text-muted" disabled>Tidak ada link</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <img src="assets/images/empty-box.png" alt="Empty" style="width: 100px; opacity: 0.5;">
                        <p class="text-muted mt-3">Belum ada data portofolio.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/portofolio.js"></script>

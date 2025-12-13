<link rel="stylesheet" href="assets/css/portofolio.css">

<div class="container py-4 py-lg-5">
    <div class="row mb-3 mb-lg-5 text-center">
        <div class="col-lg-8 mx-auto">
            <h2 class="fw-bold fs-3 fs-lg-2" style="color: #3b0a70;">Portofolio & Publikasi</h2>
            <p class="text-muted small">Kumpulan hasil riset dan inovasi Lab Software Engineering.</p>
        </div>
    </div>

    <div class="d-lg-none mb-4">
        <div class="d-flex gap-2 overflow-auto pb-2" style="white-space: nowrap;">
            <button class="btn btn-outline-primary rounded-pill btn-sm active-mobile-filter px-3" onclick="filterPortofolio('all', this)">
                Semua
            </button>
            <button class="btn btn-outline-secondary rounded-pill btn-sm px-3" onclick="filterPortofolio('publikasi', this)">
                Publikasi
            </button>
            <button class="btn btn-outline-secondary rounded-pill btn-sm px-3" onclick="filterPortofolio('produk', this)">
                Produk
            </button>
            <button class="btn btn-outline-secondary rounded-pill btn-sm px-3" onclick="filterPortofolio('penelitian', this)">
                Penelitian
            </button>
            <button class="btn btn-outline-secondary rounded-pill btn-sm px-3" onclick="filterPortofolio('pengabdian', this)">
                Pengabdian
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 mb-4 align-self-start d-none d-lg-block">
            <div class="card shadow-sm border-0 sticky-top" style="top: 90px; z-index: 1;">
                <div class="card-header bg-white p-3 border-bottom-0">
                    <h6 class="mb-0 fw-bold text-uppercase text-muted small">Kategori</h6>
                </div>
                <div class="list-group list-group-flush custom-sidebar p-2">
                    <a href="#" class="list-group-item d-flex justify-content-between align-items-center active-item" onclick="filterPortofolio('all', this)">
                        Semua Data <span class="badge bg-light text-dark border rounded-pill"><?= $total_all ?></span>
                    </a>
                    <a href="#" class="list-group-item d-flex justify-content-between align-items-center" onclick="filterPortofolio('publikasi', this)">
                        Publikasi <span class="badge bg-secondary text-white border rounded-pill"><?= $total_publikasi ?></span>
                    </a>
                    <a href="#" class="list-group-item d-flex justify-content-between align-items-center" onclick="filterPortofolio('produk', this)">
                        Produk Inovasi <span class="badge bg-secondary text-white border rounded-pill"><?= $total_produk ?></span>
                    </a>
                    <a href="#" class="list-group-item d-flex justify-content-between align-items-center" onclick="filterPortofolio('penelitian', this)">
                        Penelitian <span class="badge bg-secondary text-white border rounded-pill"><?= $total_penelitian ?></span>
                    </a>
                    <a href="#" class="list-group-item d-flex justify-content-between align-items-center" onclick="filterPortofolio('pengabdian', this)">
                        Pengabdian <span class="badge bg-secondary text-white border rounded-pill"><?= $total_pengabdian ?></span>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
                <h4 id="judul-kategori" class="fw-bold m-0 fs-6 fs-md-4" style="color: #3b0a70;">
                    <?php if(isset($_GET['search']) && !empty($_GET['search'])): ?>
                        Hasil: "<?= htmlspecialchars($_GET['search']) ?>"
                    <?php else: ?>
                        Semua Data
                    <?php endif; ?>
                </h4>

                <form action="" method="GET" class="d-flex w-100 w-md-auto" style="flex: 1; max-width: 400px;">
                    <div class="input-group shadow-sm input-group-sm input-group-lg-md">
                        <input type="text" name="search" class="form-control border-end-0 rounded-start-pill ps-3" 
                            placeholder="Cari..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                        <button class="btn btn-white bg-white border border-start-0 text-muted" type="submit">
                            <i class="bi bi-search" style="color: #3b0a70;"></i>
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="row g-2 g-md-3" id="portfolio-container">
                <?php if ($portfolios): ?>
                    <?php foreach ($portfolios as $row): ?>
                        
                        <div class="col-4 col-md-4 col-xl-3 portfolio-item" data-category="<?= $row['kategori'] ?>">
                            <div class="card h-100 portfolio-card shadow-sm border-0">
                                
                                <div class="portfolio-img-container" style="overflow: hidden; position: relative;">
                                    <?php if($row['gambar']): ?>
                                        <img src="uploads/portofolio/<?= $row['gambar'] ?>" class="card-img-top h-100 w-100" style="object-fit: cover;" alt="<?= $row['judul'] ?>">
                                    <?php else: ?>
                                        <div class="h-100 w-100 d-flex align-items-center justify-content-center bg-light">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="card-body card-body-mobile d-flex flex-column text-start p-3">
                                    <small class="text-muted portfolio-meta mb-1 d-block">
                                        <?= $row['tahun'] ?>
                                    </small>
                                    
                                    <h6 class="card-title portfolio-title fw-bold text-dark text-truncate-2-lines mb-2" 
                                        style="font-size: 0.95rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        <?= $row['judul'] ?>
                                    </h6>
                                    
                                    <p class="card-text text-muted small mb-3 d-none d-md-block" 
                                       style="font-size: 0.85rem; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                        <?php 
                                            $deskripsi = $row['deskripsi'];
                                            echo htmlspecialchars(strlen($deskripsi) > 40 ? substr($deskripsi, 0, 40) . '...' : $deskripsi); 
                                        ?>
                                    </p>

                                    <div class="mt-auto pt-2 border-top-0 border-md-top">
                                        <div class="d-flex align-items-center mb-1" title="Penulis Utama: <?= htmlspecialchars($row['penulis']) ?>" data-bs-toggle="tooltip">
                                            <i class="bi bi-person-fill text-black me-2"></i>
                                            <span class="text-dark fw-bold text-truncate" style="font-size: 0.85rem;">
                                                <?= $row['penulis'] ?>
                                            </span>
                                        </div>

                                        <?php if(!empty($row['penulis_anggota'])): ?>
                                            <div class="d-flex align-items-start text-muted mb-3" 
                                                title="Anggota: <?= htmlspecialchars($row['penulis_anggota']) ?>" 
                                                data-bs-toggle="tooltip">
                                                <i class="bi bi-people me-2 mt-1" style="font-size: 0.8rem;"></i>
                                                
                                                <span class="small" style="font-size: 0.8rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.3;">
                                                    <?= $row['penulis_anggota'] ?>
                                                </span>
                                            </div>
                                        <?php else: ?>
                                            <div class="mb-3"></div>
                                        <?php endif; ?>

                                        <?php if($row['link_eksternal']): ?>
                                            <a href="<?= $row['link_eksternal'] ?>" target="_blank" class="btn btn-outline-primary btn-sm btn-mobile-sm w-100 rounded-pill">
                                                <span class="d-none d-md-inline">Lihat Detail</span> 
                                                <span class="d-md-none">Detail</span> 
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Data tidak ditemukan.</p>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($total_pages > 1): ?>
            <div class="d-flex justify-content-center mt-5">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page - 1 ?><?= $keyword ? '&search='.$keyword : '' ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo; Previous</span>
                            </a>
                        </li>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?><?= $keyword ? '&search='.$keyword : '' ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page + 1 ?><?= $keyword ? '&search='.$keyword : '' ?>" aria-label="Next">
                                <span aria-hidden="true">Next &raquo;</span>
                            </a>
                        </li>
                        
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="assets/js/portofolio.js"></script>
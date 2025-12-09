<?php include 'includes/header.php'; ?>

<section class="py-5">
    <div class="container">
        
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <h1 class="fw-bold">Blog & Artikel Terbaru</h1>
                <p class="text-muted mb-4">Update terkini dari tim kami</p>

                <form action="" method="GET" class="d-flex justify-content-center">
                    <div class="input-group mb-3 shadow-sm" style="max-width: 500px;">
                        <input type="text" 
                               name="q" 
                               class="form-control" 
                               placeholder="Cari artikel..." 
                               value="<?php echo htmlspecialchars($data['search_keyword'] ?? ''); ?>"
                               aria-label="Cari artikel">
                        <button class="btn btn-primary px-4" type="submit">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                </form>
                
                <?php if (!empty($data['search_keyword'])): ?>
                    <div class="mt-2">
                        <span class="text-muted small">Menampilkan hasil untuk: <strong>"<?php echo htmlspecialchars($data['search_keyword']); ?>"</strong></span>
                        <a href="blog.php" class="text-decoration-none ms-2 small text-danger"><i class="bi bi-x-circle"></i> Reset Filter</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row g-2 g-md-4">
            <?php if (!empty($data['articles'])): ?>
                <?php foreach ($data['articles'] as $row): ?>
                    <div class="col-6 col-md-4">
                        <div class="card h-100 shadow border-0 hover-shadow">

                            <img src="<?php echo htmlspecialchars($row['image_path']); ?>"
                                 class="card-img-top"
                                 alt="<?php echo htmlspecialchars($row['judul']); ?>"
                                 style="height: 200px; object-fit: cover;">

                            <div class="card-body d-flex flex-column">
                                <div class="mb-2 small text-muted text-start">
                                    <i class="bi bi-calendar3"></i>
                                    <?php echo $row['display_date']; ?>
                                    <?php if ($row['is_edited']): ?>
                                        <span class="badge bg-warning text-dark ms-1" style="font-size: 0.7em;">Edited</span>
                                    <?php endif; ?>
                                    &nbsp;|&nbsp;
                                    <i class="bi bi-person-fill text-start"></i> <?php echo htmlspecialchars($row['username'] ?? 'Admin'); ?>
                                </div>

                                <h5 class="card-title fw-bold text-start">
                                    <a href="blog_detail.php?slug=<?php echo $row['slug']; ?>" class="text-decoration-none text-dark">
                                        <?php 
                                            $judul = $row['judul'];
                                            // Jika lebih dari 50 karakter, potong dan tambah '...'
                                            echo htmlspecialchars(strlen($judul) > 35 ? substr($judul, 0, 35) . '...' : $judul); 
                                        ?>
                                    </a>
                                </h5>

                                <p class="card-text text-muted flex-grow-1 text-start mb-3">
                                    <?php echo htmlspecialchars($row['snippet']); ?>
                                </p>

                                <a href="blog_detail.php?slug=<?php echo $row['slug']; ?>" class="btn mb-3 ">
                                    Read More</i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <img src="/PBL-SE/uploads/empty-box.png" alt="icon" style="width: 150px; opacity: 0.6;">
                    
                    <?php if (!empty($data['search_keyword'])): ?>
                        <h4 class="text-muted mt-4">Artikel "<?php echo htmlspecialchars($data['search_keyword']); ?>" tidak ditemukan.</h4>
                        <a href="blog.php" class="btn btn-outline-primary mt-3">Lihat Semua Artikel</a>
                    <?php else: ?>
                        <h4 class="text-muted mt-4">Belum ada artikel yang dipublish.</h4>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
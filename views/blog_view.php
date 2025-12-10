<?php include 'includes/header.php'; ?>

<section class="py-5" style="background-color: #f3f4f6; min-height: 100vh;">
    <div class="container">
        
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <h2 class="fw-bold mb-3 text-dark">Blog & Artikel</h2>
                <form action="" method="GET" class="d-flex justify-content-center blog-search-container">
                    <div class="input-group mb-3 shadow-sm" style="max-width: 600px; width: 100%;">
                        <input type="text" name="q" class="form-control" placeholder="Cari artikel..." value="<?php echo htmlspecialchars($data['search_keyword'] ?? ''); ?>">
                        <button class="btn btn-search" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-3 g-md-4">
            <?php if (!empty($data['articles'])): ?>
                <?php foreach ($data['articles'] as $row): ?>
                    
                    <div class="col-6 col-md-4 d-flex align-items-stretch">
                        
                        <div class="card w-100 shadow-sm blog-card">

                            <div class="blog-card-img-wrapper">
                                <img src="<?php echo htmlspecialchars($row['image_path']); ?>"
                                     class="card-img-top blog-card-img"
                                     alt="<?php echo htmlspecialchars($row['judul']); ?>">
                            </div>

                            <div class="card-body blog-card-body">
                                
                                <div class="blog-card-meta">
                                    <i class="bi bi-calendar3"></i> <?php echo $row['display_date']; ?>
                                </div>

                                <h5 class="card-title blog-card-title">
                                    <a href="blog_detail.php?slug=<?php echo $row['slug']; ?>">
                                        <?php 
                                            $judul = $row['judul'];
                                            // Potong judul agak pendek di mobile biar gak kepanjangan
                                            echo htmlspecialchars(strlen($judul) > 40 ? substr($judul, 0, 40) . '...' : $judul); 
                                        ?>
                                    </a>
                                </h5>

                                <p class="card-text blog-card-text">
                                    <?php echo htmlspecialchars($row['snippet']); ?>
                                </p>

                                <a href="blog_detail.php?slug=<?php echo $row['slug']; ?>" class="btn">
                                    Read More
                                </a>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                 <div class="col-12 text-center py-5">
                    <h4 class="text-muted">Tidak ada artikel.</h4>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
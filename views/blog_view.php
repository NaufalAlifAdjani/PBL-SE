<?php include 'includes/header.php'; ?>

<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="fw-bold">Blog & Artikel Terbaru</h1>
            <p class="text-muted">Software Engineering and Technology</p>
        </div>

        <div class="row g-4">
            <?php if (!empty($data['articles'])): ?>
                <?php foreach ($data['articles'] as $row): ?>
                    <div class="col-md-4">
                        <div class="card h-100 shadow border-0 hover-shadow">

                            <img src="<?php echo htmlspecialchars($row['image_path']); ?>"
                                 class="card-img-top"
                                 alt="<?php echo htmlspecialchars($row['judul']); ?>"
                                 style="height: 200px; object-fit: cover;">

                            <div class="card-body d-flex flex-column">
                                <div class="mb-2 small text-muted">
                                    <i class="bi bi-calendar3"></i> <?php echo date('d M Y', strtotime($row['tgl_dibuat'])); ?>
                                    &nbsp;|&nbsp;
                                    <i class="bi bi-person-fill"></i> <?php echo htmlspecialchars($row['username'] ?? 'Admin'); ?>
                                </div>

                                <h5 class="card-title fw-bold">
                                    <a href="blog_detail.php?slug=<?php echo $row['slug']; ?>" class="text-decoration-none text-dark">
                                        <?php echo htmlspecialchars($row['judul']); ?>
                                    </a>
                                </h5>

                                <p class="card-text text-muted flex-grow-1">
                                    <?php echo htmlspecialchars($row['snippet']); ?>
                                </p>

                                <a href="blog_detail.php?slug=<?php echo $row['slug']; ?>" class="btn mb-3">
                                    Read More <i class="bi bi-caret-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <img src="/PBL-SE/uploads/empty-box.png" alt="icon" style="width: 180px; height: 180px;">
                    <h4 class="text-muted py-5">Tidak ada artikel yang dipublish</h4>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

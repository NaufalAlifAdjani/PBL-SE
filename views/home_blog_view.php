<section id="blog" class="bg-light-subtle py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Our Information</h2>
            <p class="lead text-muted">Follow the latest developments and research from our lab.</p>
        </div>

        <div class="row g-4">
            <?php if (!empty($data['articles'])): ?>
                <?php foreach ($data['articles'] as $row): ?>
                    <div class="col-md-4 d-flex align-items-stretch">
                        <div class="card shadow rounded-4 border-0 h-100 w-100">
                            <img src="<?php echo htmlspecialchars($row['image_path']); ?>"
                                 class="card-img-top"
                                 alt="<?php echo htmlspecialchars($row['judul']); ?>"
                                 style="height: 200px; object-fit: cover;">

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold h2">
                                    <?php echo htmlspecialchars($row['judul']); ?>
                                </h5>

                                <p class="card-text text-muted small flex-grow-1">
                                    <?php echo htmlspecialchars($row['snippet']); ?>
                                </p>

                                <a href="blog_detail.php?slug=<?php echo htmlspecialchars($row['slug']); ?>"
                                   class="btn btn-sm rounded-pill align-self-start">
                                   Read More
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class='col-12'>
                    <h4 class="text-center fw-semibold text-secondary">Belum ada informasi terbaru.</h4>
                    <p class="text-center text-muted">Mohon maaf, saat ini artikel tidak tersedia.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-end mt-5">
            <a href="blog.php" class="btn btn-dark fw-semibold">
                All Information <i class="bi bi-caret-right-fill"></i>
            </a>
        </div>
    </div>
</section>

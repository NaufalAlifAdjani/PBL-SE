<section id="blog" class="py-4 bg-light">
    <div class="container px-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Informasi Kami</h2>
            <p class="lead text-muted">Ikuti perkembangan terbaru dan riset yang sedang kami kembangkan di laboratorium kami.</p>
        </div>
        <div class="row g-2">
            <?php if (!empty($data['articles'])): ?>
                <?php foreach ($data['articles'] as $row): ?>
                    <div class="col-6 col-md-4 d-flex align-items-stretch">
                        <div class="card shadow rounded-2 border-0 h-100 w-100">
                            <img src="<?php echo htmlspecialchars($row['image_path']); ?>"
                                 class="card-img-top"
                                 alt="<?php echo htmlspecialchars($row['judul']); ?>"
                                 style="height: 200px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold">
                                    <?php 
                                        $judul = $row['judul'];
                                        // Jika lebih dari 50 karakter, potong dan tambah '...'
                                        echo htmlspecialchars(strlen($judul) > 35 ? substr($judul, 0, 35) . '...' : $judul); 
                                    ?>
                                </h5>
                                <p class="card-text text-muted small flex-grow-1">
                                    <?php echo htmlspecialchars($row['snippet']); ?>
                                </p>
                                <a href="blog_detail.php?slug=<?php echo htmlspecialchars($row['slug']); ?>"
                                class="btn btn-sm rounded-pill align-self-center"
                                style="white-space: nowrap; font-size: 0.8rem; padding-left: 12px; padding-right: 12px;">
                                Read More
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <div class="text-secondary opacity-25">
                        <i class="bi bi-inbox-fill" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="fw-semibold text-secondary">Belum ada informasi terbaru.</h4>
                    <p class="text-muted">Mohon maaf, saat ini artikel tidak tersedia.</p>
                </div>
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

<?php include 'includes/header.php'; ?>

<section class="banner">
    <div class="container">
        <h1 class="display-6 fw-bold">Welcome to</h1>
        <h1 class="display-4 fw-bold">Software Engineering Lab</h1>
        <p class="lead my-3">We build, test, and refine software solutions that shape the future of technology.</p>
    </div>
</section>

<section id="about-us" class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <img src="uploads/dummy.png" class="img-fluid rounded-3 shadow" alt="About Us">
            </div>
            <div class="col-md-6 text-justify">
                <h2 class="fw-bold mb-3">About Us</h2>
                <p class="lead text-muted">
                    <?php
                        // Fallback sederhana jika data database kosong
                        echo !empty($data['about']['content'])
                             ? $data['about']['content']
                             : "Website ini dibuat sebagai ruang untuk berbagi informasi dan menghadirkan konten yang bermanfaat bagi pengunjung. Kami berkomitmen untuk terus mengembangkan layanan.";
                    ?>
                </p>
            </div>
        </div>
    </div>
</section>

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
                    <p class='text-center text-muted'>Belum ada informasi terbaru.</p>
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

<?php include 'includes/footer.php'; ?>

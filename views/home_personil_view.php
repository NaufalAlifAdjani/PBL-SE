<section class="py-4 bg-light">
    <div class="container">

        <div class="text-center">
            <h2 class="fw-bold">Tim Pengajar Kami</h2>
            <p class="lead text-muted">Dosen dan Tenaga Ahli Laboratorium</p>
        </div>

        <?php if (!empty($dosen_list)): ?>
            <div class="slide-container">
                <div class="slide-content swiper">
                    <div class="card-wrapper swiper-wrapper pb-5"> <?php foreach ($dosen_list as $dosen): ?>
                            <div class="swiper-slide">
                                <div class="card shadow-sm rounded-4">
                                    <div class="image-content">
                                        <span class="overlay"></span>
                                        <div class="card-image shadow-sm">
                                            <img src="<?= $dosen['foto'] ?>"
                                                 alt="<?= htmlspecialchars($dosen['nama']) ?>"
                                                 class="card-img">
                                        </div>
                                    </div>

                                    <div class="card-body text-center">
                                        <div class="name-box mb-2">
                                            <h5 class="fw-semibold"><?= htmlspecialchars($dosen['nama']) ?></h5>
                                        </div>

                                        <p class="text-secondary small mb-4">
                                            <?= htmlspecialchars($dosen['jabatan']) ?>
                                        </p>

                                        <a href="personil_detail.php?slug=<?= urlencode($dosen['slug']) ?>" class="btn btn-sm rounded-pill">
                                            Lihat Profil
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="swiper-pagination"></div>
                </div>

                <div class="swiper-button-next swiper-navBtn">
                    <i class="bi bi-caret-right-fill fs-4"></i>
                </div>
                <div class="swiper-button-prev swiper-navBtn">
                    <i class="bi bi-caret-left-fill fs-4"></i>
                </div>

                <div class="swiper-button-next swiper-navBtn d-none d-md-flex"><i class="bi bi-caret-right-fill"></i></div>
                <div class="swiper-button-prev swiper-navBtn d-none d-md-flex"><i class="bi bi-caret-left-fill"></i></div>
                <div class="swiper-pagination"></div>
            </div>

        <?php else: ?>

            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <div class="text-secondary opacity-25">
                        <i class="bi bi-inbox-fill" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="fw-semibold text-secondary">Data Belum Tersedia</h4>
                    <p class="text-muted">Mohon maaf, saat ini data pengajar belum dapat ditampilkan.</p>
                </div>
            </div>

        <?php endif; ?>

    </div>
</section>

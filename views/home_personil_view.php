<style>
    /* Khusus Tampilan Mobile */
    @media (max-width: 768px) {
        /* Membatasi lebar kartu personil agar tidak full layar */
        .profile-card {
            max-width: 85% !important; /* Kartu hanya mengambil 85% lebar layar */
            margin: 0 auto; /* Posisi tengah */
            transform: scale(0.95); /* Sedikit diperkecil agar elegan */
        }
        
        /* Jika ingin menampilkan 2 orang sekaligus dalam slider, 
           Kamu harus mengedit file Javascript (main.js/script.js) 
           pada bagian 'breakpoints' Swiper menjadi 'slidesPerView: 2' */
    }
</style>

<section class="py-5 bg-light"> <div class="container">

        <div class="text-center mb-5">
            <h2 class="fw-bold">Tim Pengajar Kami</h2>
            <p class="lead text-muted">Dosen dan Tenaga Ahli Laboratorium</p>
        </div>

        <?php if (!empty($dosen_list)): ?>
            
            <div class="slide-container">
                
                <div class="slide-content swiper">
                    <div class="card-wrapper swiper-wrapper pb-5"> <?php foreach ($dosen_list as $dosen): ?>
                            <div class="swiper-slide">
                                <div class="card profile-card">
                                    <div class="image-content">
                                        <span class="overlay"></span>
                                        <div class="card-image">
                                            <img src="<?= !empty($dosen['foto']) ? htmlspecialchars($dosen['foto']) : 'assets/img/default.png' ?>"
                                                 alt="<?= htmlspecialchars($dosen['nama']) ?>"
                                                 class="card-img">
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="name-box">
                                            <h5 class="fw-bold"><?= htmlspecialchars($dosen['nama']) ?></h5>
                                        </div>
                                        <p class="small text-muted mb-3">
                                            <?= htmlspecialchars($dosen['jabatan']) ?>
                                        </p>

                                        <a href="personil_detail.php?slug=<?= urlencode($dosen['slug'] ?? '') ?>" class="btn btn-primary rounded-pill text-white btn-sm">
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

            </div>

        <?php else: ?>
            <div class="text-center text-muted">Belum ada data.</div>
        <?php endif; ?>

    </div>
</section>
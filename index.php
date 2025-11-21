<?php
include 'includes/header.php';

// Ambil data 'Tentang Lab' dari DB
$slug = 'tentang-lab'; // Slug dari data dummy
$data = getProfileSection($conn, $slug);
?>

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
                    <img src="assets/images/dummy1.png" class="img-fluid rounded-3 shadow" alt="">
                </div>
                <div class="col-md-6" style="text-align: justify;">
                    <h2 class="fw-bold mb-3">About Us</h2>
                    <p class="lead text-muted">
                        Website ini dibuat sebagai ruang untuk berbagi informasi dan menghadirkan konten yang bermanfaat bagi pengunjung. Kami berkomitmen untuk terus mengembangkan layanan, memperbaiki kualitas, dan memberikan pengalaman terbaik bagi setiap pengguna. Terima kasih telah mengunjungi situs kami.
                    </p>
                        <!-- connect ke visi misi? -->
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

            <div class="row g-4 mx-3">
                <?php
                $sql = "SELECT id_artikel, judul, slug, isi_konten, gambar_artikel, tgl_dibuat
                        FROM artikel
                        WHERE status_artikel = 'Published'
                        ORDER BY tgl_dibuat DESC
                        LIMIT 3";

                $hasil = pg_query($conn, $sql);

                if (!$hasil) {
                    echo "<div class='col-12 alert alert-danger'>Error Query: " . pg_last_error($conn) . "</div>";
                } else {
                    if (pg_num_rows($hasil) == 0) {
                        echo "<div class='col-12'><p class='text-center text-muted'>Belum ada informasi terbaru.</p></div>";
                    }

                    while ($row = pg_fetch_assoc($hasil)) {

                        $snippet = strip_tags($row['isi_konten']);
                        if (strlen($snippet) > 100) {
                            $snippet = substr($snippet, 0, 100) . '...';
                        }

                        $gambar_path = 'uploads/' . htmlspecialchars($row['gambar_artikel'] ?? '');
                        if (empty($row['gambar_artikel']) || !file_exists($gambar_path)) {
                            $gambar_path = "uploads/dummy.png";
                        }
                ?>

                <div class="col-md-4 d-flex align-items-stretch">
                    <div class="card shadow rounded-4 border-0 h-100 w-100">
                        <img src="<?php echo $gambar_path; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['judul']); ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold h2"><?php echo htmlspecialchars($row['judul']); ?></h5>
                            <p class="card-text text-muted small flex-grow-1"><?php echo htmlspecialchars($snippet); ?></p>

                            <a href="blog_detail.php?slug=<?php echo htmlspecialchars($row['slug']); ?>" class="btn btn-outline-dark btn-sm rounded-pill align-self-start"> Read More
                            </a>
                        </div>
                    </div>
                </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>

        <div class="text-end me-5 mt-5">
            <a href="blog.php" class="btn btn-dark fw-semibold">All Information <i class="bi bi-caret-right-fill"></i></a>
        </div>
    </section>

<?php
include 'includes/footer.php';
?>

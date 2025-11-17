<?php
    $activePage = 'home';

    // memanggil header
    include 'includes/header.php';
    include 'includes/db.php';
?>
<head>
    <title>Beranda - Lab SE</title>
</head>
    <section class="banner text-white">
        <div class="container">
            <h1 class="display-6 fw-bold">Welcome to</h1>
            <h1 class="display-4 fw-bold">Software Engineering Lab</h1>
            <p class="lead my-3">We build, test, and refine software solutions that shape the future of technology.</p>
            <!-- direct ke recruit  -->
            <a href="recruitment.php" class="btn btn-light btn-lg rounded-pill fw-bold px-4">Join Us</a>
        </div>
    </section>

    <!-- tentang lab se  -->
    <section id="about-us" class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <!-- gambar -->
                <div class="col-md-6 mb-4 mb-md-0">
                    <img src="assets/images/dummy1.png"
                         class="img-fluid rounded-3 shadow"
                         alt="">
                </div>

                <!-- teks -->
                <div class="col-md-6" style="text-align: justify;">
                    <h2 class="fw-bold mb-3">About Us</h2>
                    <p class="lead text-muted">
                        Laboratorium Software Engineering adalah pusat penelitian dan pengembangan yang didedikasikan untuk eksplorasi...
                    </p>
                    <p>
                        Misi kami adalah menjembatani kesenjangan antara teori akademis dan tantangan industri di dunia nyata. Kami menyediakan lingkungan kolaboratif bagi mahasiswa dan peneliti untuk berinovasi, membangun, dan menguji solusi perangkat lunak yang tangguh dan skalabel.
                    </p>

                    <ul class="list-unstyled mt-4">
                        <li class="d-flex align-items-start mb-2">
                            <i class="bi bi-check-circle-fill text-primary me-3 mt-1"></i>
                            <span>----------.</span>
                        </li>

                        <li class="d-flex align-items-start">
                            <i class="bi bi-check-circle-fill text-primary me-3 mt-1"></i>
                            <span>----------.</span>
                        </li>

                        <li class="d-flex align-items-start">
                            <i class="bi bi-check-circle-fill text-primary me-3 mt-1"></i>
                            <span>----------.</span>
                        </li>
                    </ul>
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

            <!-- blog artikel  -->
            <div class="row g-5"> <?php
                try {
                    $sql = "SELECT id_artikel, judul, slug, isi_konten, gambar_artikel, tgl_dibuat
                            FROM artikel
                            WHERE status_artikel = 'Published'
                            ORDER BY tgl_dibuat DESC
                            LIMIT 4";
                    $stmt = $conn->query($sql);

                    if ($stmt->rowCount() == 0) {
                        echo "<div class='col-12'><p class='text-center text-muted'>Belum ada informasi terbaru.</p></div>";
                    }

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        // deskripsi singkat
                        $snippet = strip_tags($row['isi_konten']);
                        if (strlen($snippet) > 100) {
                            $snippet = substr($snippet, 0, 100) . '...';
                        }

                        // path gambar
                        $gambar_path = 'uploads/' . htmlspecialchars($row['gambar_artikel'] ?? '');
                        if (empty($row['gambar_artikel']) || !file_exists($gambar_path)) {
                            $gambar_path = "assets/images/dummy1.png";
                        }
                ?>

                <div class="col-md-3 d-flex align-items-stretch">
                    <div class="card shadow-sm rounded-3 border-0 h-100 w-100">
                        <img src="<?php echo $gambar_path; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['judul']); ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold h6"><?php echo htmlspecialchars($row['judul']); ?></h5>
                            <p class="card-text text-muted small flex-grow-1"><?php echo htmlspecialchars($snippet); ?></p>

                            <a href="blog_detail.php?slug=<?php echo htmlspecialchars($row['slug']); ?>" class="btn btn-outline-primary btn-sm rounded-pill align-self-start mt-2">
                                Read More
                            </a>
                        </div>
                    </div>
                </div>

                <?php
                    }
                } catch (PDOException $e) {
                    echo "<div class='col-12 alert alert-danger'>Error: " . $e->getMessage() . "</div>";
                }
                ?>
            </div>
        </div>

        <!-- lainnya button -->
        <div class="text-end me-5">
            <a href="blog.php" class="btn btn-dark fw-semibold">All Information <i class="bi bi-caret-right-fill"></i></a>
        </div>
    </section>

<?php
    //memanggil footer
    include 'includes/footer.php';
?>

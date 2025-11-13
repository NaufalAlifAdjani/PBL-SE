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

                    <!-- Poin-poin Kunci (Opsional, tapi bagus) -->
                    <ul class="list-unstyled mt-4">
                        <li class="d-flex align-items-start mb-2">
                            <i class="bi bi-check-circle-fill text-primary me-3 mt-1"></i>
                            <span>Fokus pada metodologi pengembangan modern dan praktik terbaik.</span>
                        </li>
                        <li class="d-flex align-items-start mb-2">
                            <i class="bi bi-check-circle-fill text-primary me-3 mt-1"></i>
                            <span>Penelitian aktif di bidang AI, Keamanan Perangkat Lunak, dan Big Data.</span>
                        </li>
                        <li class="d-flex align-items-start">
                            <i class="bi bi-check-circle-fill text-primary me-3 mt-1"></i>
                            <span>Kolaborasi erat dengan mitra industri untuk proyek-proyek dunia nyata.</span>
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

            <div class="row">
                <?php
                    // semua card yang sudah dibuat
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

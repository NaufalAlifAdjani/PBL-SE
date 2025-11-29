<?php
    include 'includes/header.php';

    // ambil slug URL
    $artikel_slug = isset($_GET['slug']) ? $_GET['slug'] : '';

    // default
    $artikel_ada = false;
    $artikel_judul = '';
    $artikel_pembuat = '';
    $artikel_tgl = '';
    $artikel_konten = '';
    $artikel_gambar = '';

    if (!empty($artikel_slug)) {
        // query
        $sql = "SELECT a.*, adm.username FROM artikel a JOIN admin adm ON a.id_admin = adm.id_admin WHERE a.slug = $1 AND a.status_artikel = 'Published'";

        // pg_query_params untuk keamanan
        $hasil = pg_query_params($conn, $sql, array($artikel_slug));

        // cek apakah query berhasil dan ada datanya
        if ($hasil && pg_num_rows($hasil) > 0) {
            $artikel = pg_fetch_assoc($hasil);
            $artikel_ada = true;

            // Set data ke variabel
            $artikel_judul = htmlspecialchars($artikel['judul']);

            //Gambar
            $gambar_db = $artikel['gambar_artikel'];
            $path_gambar = 'uploads/' . $gambar_db;

            if (!empty($gambar_db) && file_exists($path_gambar)) {
                $artikel_gambar = $path_gambar;
            } else {
                // Gambar dummy
                $artikel_gambar = "uploads\dummy.png";
            }

            $artikel_pembuat = htmlspecialchars($artikel['username']);
            $artikel_tgl = date('d F Y', strtotime($artikel['tgl_dibuat']));

            $artikel_konten = $artikel['isi_konten'];
        }
    }

    // jika artikel tidak ditemukan
    if (!$artikel_ada) {
        $artikel_judul = "Artikel Tidak Ditemukan";
        $artikel_konten = "<div class='alert alert-warning'>Maaf, artikel yang Anda cari tidak ada.</div>";
        $artikel_gambar = "https://placehold.co/1200x400/dc3545/white?text=404+Not+Found";
        $artikel_pembuat = "System";
        $artikel_tgl = date('d F Y');
    }
?>

    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">

                    <h1 class="display-5 fw-bold mb-3"><?php echo $artikel_judul; ?></h1>

                    <p class="text-muted border-bottom pb-3">
                        <i class="bi bi-person-circle me-1"></i> <strong><?php echo $artikel_pembuat; ?></strong>
                        &nbsp;|&nbsp;
                        <i class="bi bi-calendar3"></i> <?php echo $artikel_tgl; ?>
                    </p>

                    <?php if ($artikel_ada): ?>
                        <div class="mb-4">
                            <img src="<?php echo $artikel_gambar; ?>"
                                 class="rounded-3 shadow-sm w-100"
                                 alt="Gambar <?php echo $artikel_judul; ?>"
                                 style="object-fit: cover; max-height: 500px;">
                        </div>
                    <?php endif; ?>

                    <div class="konten lh-lg">
                        <?php echo $artikel_konten; ?>
                    </div>

                    <div class="mt-5 pt-4 border-top">
                        <a href="blog.php" class="btn rounded-pill px-4">
                            <i class="bi bi-caret-left-fill"></i>Kembali ke Daftar Artikel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
    include 'includes/footer.php';
?>

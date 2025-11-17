<?php
    $activePage = 'blog';
    include 'includes/header.php';
    include 'includes/db.php';
?>

    <title>Blog Artikel</title>
    <section class="py-5 bg-light-subtle">
        <div class="container">
            <div class="text-center">
                <h1 class="display-5 fw-bold">Blog & Information</h1>
                <p class="lead text-muted">See all the latest news, research, and developments from our lab.</p>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-4">

                <?php
                try {
                    // ambil semua artikel yang statusnya 'Published'
                    $sql = "SELECT id_artikel, judul, slug, isi_konten, gambar_artikel, tgl_dibuat
                            FROM artikel
                            WHERE status_artikel = 'Published'
                            ORDER BY tgl_dibuat DESC";
                    $stmt = $conn->query($sql);

                    if ($stmt->rowCount() == 0) {
                        echo "<p class='text-center'>Belum ada artikel yang dipublikasikan.</p>";
                    }

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        // deskripsi singkat
                        $snippet = strip_tags($row['isi_konten']);
                        if (strlen($snippet) > 150) {
                            $snippet = substr($snippet, 0, 150) . '...';
                        }

                        $gambar_path = 'uploads/' . htmlspecialchars($row['gambar_artikel'] ?? '');
                        if (empty($row['gambar_artikel']) || !file_exists($gambar_path)) {
                            $gambar_path = "https://placehold.co/600x400/55595c/white?text=Gambar+Tidak+Tersedia";
                        }
                ?>

                <div class="col-md-3 d-flex align-items-stretch">
                    <div class="card shadow-sm rounded-3 border-0 h-100">
                        <img src="<?php echo $gambar_path; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['judul']); ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold"><?php echo htmlspecialchars($row['judul']); ?></h5>
                            <p class="card-text text-muted flex-grow-1"><?php echo htmlspecialchars($snippet); ?></p>

                            <a href="blog_detail.php?slug=<?php echo htmlspecialchars($row['slug']); ?>" class="btn btn-dark rounded-pill px-3 align-self-start">
                                Read More
                            </a>
                        </div>
                    </div>
                </div>
                <?php
                    }
                } catch (PDOException $e) {
                    echo "<div class='alert alert-danger'>Gagal mengambil data: " . $e->getMessage() . "</div>";
                }
                ?>

            </div> </div> </section>

<?php
    include 'includes/footer.php';
?>

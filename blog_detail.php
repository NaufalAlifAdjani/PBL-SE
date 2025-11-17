<?php
    $activePage = 'blog';
    include 'includes/header.php';
    include 'includes/db.php';

    // ambil slug dari URL
    $article_slug = isset($_GET['slug']) ? htmlspecialchars($_GET['slug']) : '';

    $article_found = false;

    if (!empty($article_slug)) {
        try {
            // ambil data artikel dan nama admin menggunakan
            $sql = "SELECT a.*, adm.username
                    FROM artikel a
                    JOIN admin adm ON a.id_admin = adm.id_admin
                    WHERE a.slug = ? AND a.status_artikel = 'Published'";

            $stmt = $conn->prepare($sql);
            $stmt->execute([$article_slug]);
            $article = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($article) {
                $article_found = true;
                $article_title = htmlspecialchars($article['judul']);

                $article_image_path = 'uploads/' . htmlspecialchars($article['gambar_artikel'] ?? '');
                if (empty($article['gambar_artikel']) || !file_exists($article_image_path)) {
                    $article_image = "assets/images/dummy1.png";
                } else {
                    $article_image = $article_image_path;
                }

                $article_author = htmlspecialchars($article['username']);
                $article_date = date('d F Y', strtotime($article['tgl_dibuat']));

                $article_content = $article['isi_konten'];

            }

        } catch (PDOException $e) {
            $article_title = "Error Database";
            $article_content = "<p>Terjadi kesalahan saat mengambil data: " . $e->getMessage() . "</p>";
            $article_image = "https://placehold.co/1200x400/dc3545/white?text=Database+Error";
            $article_author = "System";
            $article_date = date('d F Y');
        }
    }

    // jika artikel tidak ditemukan
    if (!$article_found) {
        $article_title = "Artikel Tidak Ditemukan";
        $article_content = "<p>Maaf, artikel yang Anda cari tidak ada atau belum dipublikasikan.</p>";
        $article_image = "https://placehold.co/1200x400/dc3545/white?text=Error+404+Not+Found";
        $article_author = "System";
        $article_date = date('d F Y');
    }
?>

    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">

                    <h1 class="display-5 fw-bold mb-3"><?php echo $article_title; ?></h1>

                    <p class="text-muted mb-4">
                        Oleh <strong><?php echo $article_author; ?></strong> | Diposting pada <?php echo $article_date; ?>
                    </p>

                    <?php if ($article_found): // hanya tampilkan gambar jika artikel valid ?>
                    <img src="<?php echo $article_image; ?>"
                         class="img-fluid rounded-3 shadow-sm mb-4"
                         alt="Gambar utama untuk artikel '<?php echo $article_title; ?>'">
                    <?php endif; ?>

                    <div class="article-content">
                        <?php echo $article_content;?>
                    </div>

                    <hr class="my-5">

                    <a href="blog.php" class="btn btn-outline-dark rounded-pill">
                        <i class="bi bi-caret-left-fill"></i> Kembali ke Semua Artikel
                    </a>

                </div>
            </div>
        </div>
    </section>

<?php
    include 'includes/footer.php';
?>

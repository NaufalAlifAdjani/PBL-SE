<?php
include 'includes/header.php';
include 'includes/db.php';

// ambil artikel yg publish
// urutkan berdasarkan tanggal terbaru
$query = "SELECT a.*, ad.username FROM artikel a LEFT JOIN admin ad ON a.id_admin = ad.id_admin WHERE a.status_artikel = 'Published' ORDER BY a.tgl_dibuat DESC";

$hasil = pg_query($conn, $query);
$artikel = [];

if ($hasil) {
    $artikel = pg_fetch_all($hasil);
    // jika pg_fetch_all return kosong, set array kosong
    if (!$artikel) $artikel = [];
}
?>

<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="fw-bold">Blog & Artikel Terbaru</h1>
            <p class="text-muted">Software Engineering and Technology</p>
        </div>

        <div class="row">
            <?php if (count($artikel) > 0): ?>
                <?php foreach ($artikel as $row): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm border-0 hover-shadow">
                            <?php
                                // cek apakah ada gambar, jika tidak pakai dummy
                                $path_gambar = 'uploads/' . $row['gambar_artikel'];
                                if (!empty($row['gambar_artikel']) && file_exists($path_gambar)) {
                                    $img_src = $path_gambar;
                                } else {
                                    // gambar default jika file tidak ditemukan
                                    $img_src = "uploads\dummy.png";
                                }
                            ?>
                            <img src="<?php echo htmlspecialchars($img_src); ?>"
                                 class="card-img-top"
                                 alt="<?php echo htmlspecialchars($row['judul']); ?>"
                                 style="height: 200px; object-fit: cover;">

                            <div class="card-body d-flex flex-column">
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3"></i> <?php echo date('d M Y', strtotime($row['tgl_dibuat'])); ?>
                                        &nbsp;|&nbsp;
                                        <i class="bi bi-person-fill"></i></i> <?php echo htmlspecialchars($row['username'] ?? 'Admin'); ?>
                                    </small>
                                </div>

                                <h5 class="card-title fw-bold">
                                    <a href="blog_detail.php?slug=<?php echo $row['slug']; ?>" class="text-decoration-none text-dark">
                                        <?php echo htmlspecialchars($row['judul']); ?>
                                    </a>
                                </h5>

                                <p class="card-text text-muted flex-grow-1">
                                    <?php
                                        $cuplikan = strip_tags($row['isi_konten']);
                                        if (strlen($cuplikan) > 100) {
                                            echo substr($cuplikan, 0, 100) . '...';
                                        } else {
                                            echo $cuplikan;
                                        }
                                    ?>
                                </p>

                                <a href="blog_detail.php?slug=<?php echo $row['slug']; ?>" class="btn btn-outline-dark mb-3">
                                    Read More <i class="bi bi-caret-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" width="150" alt="Empty" class="mb-3 opacity-50">
                    <h4 class="text-muted">Tidak ada artikel yang dipublish</h4>
                    <p>Silahkan kembali lagi nanti!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
include 'includes/footer.php';
?>

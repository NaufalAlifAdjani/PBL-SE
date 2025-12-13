<?php
include 'includes/header.php';
$row = $data['article'];
?>

<section class="py-5" style="background-color: #f3f4f6; min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-10">
                
                <div class="blog-detail-card"> 

                    <h1 class="blog-detail-title mb-2"><?php echo $row['judul']; ?></h1>
                    <div class="mb-3">
                        <?php if(($row['kategori'] ?? '') == 'Produk Inovasi'): ?>
                            <span class="badge bg-info text-dark me-2">🚀 Produk Inovasi</span>
                        <?php else: ?>
                            <span class="badge bg-secondary me-2">📝 Artikel</span>
                        <?php endif; ?>

                        <?php if (!empty($row['tags'])): ?>
                            <?php foreach ($row['tags'] as $tag): ?>
                                <span class="badge bg-light text-secondary border me-1">#<?php echo htmlspecialchars($tag); ?></span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="blog-meta-info">
                        <span><i class="bi bi-person-circle"></i> <strong><?php echo $row['pembuat']; ?></strong></span>
                        <span><i class="bi bi-calendar3"></i> <?php echo $row['tgl']; ?></span>
                        
                        <?php if (!empty($row['is_edited']) && $row['is_edited']): ?>
                            <span class="badge badge-edited ms-auto" title="Telah diedit">Edited</span>
                        <?php endif; ?>
                    </div>

                    <?php if ($row['exists']): ?>
                        <div class="mb-4 text-center">
                            <img src="<?php echo $row['gambar']; ?>"
                                 class="blog-featured-image"
                                 alt="Gambar <?php echo $row['judul']; ?>">
                        </div>
                    <?php endif; ?>

                    <div class="blog-content-body">
                        <?php echo $row['konten']; ?>
                    </div>

                    <div class="mt-5 pt-4 border-top">
                        <a href="blog.php" class="blog-back-btn">
                            <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Artikel
                        </a>
                    </div>

                </div> </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
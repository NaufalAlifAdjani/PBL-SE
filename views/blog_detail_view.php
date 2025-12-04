<?php
include 'includes/header.php';
$row = $data['article'];
?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-10">
                
                <div class="blog-detail-card"> 

                    <h1 class="blog-detail-title mb-3"><?php echo $row['judul']; ?></h1>

                    <div class="blog-meta-info">
                        <span><i class="bi bi-person-circle me-1"></i> <strong><?php echo $row['pembuat']; ?></strong></span>
                        <span><i class="bi bi-calendar3 me-1"></i> <?php echo $row['tgl']; ?></span>
                        
                        <?php if (!empty($row['is_edited']) && $row['is_edited']): ?>
                            <span class="badge-edited ms-auto" title="Telah diedit">Edited</span>
                        <?php endif; ?>
                    </div>

                    <?php if ($row['exists']): ?>
                        <div class="mb-4">
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
                            <i class="bi bi-caret-left-fill me-2"></i> Kembali ke Daftar Artikel
                        </a>
                    </div>

                </div> </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

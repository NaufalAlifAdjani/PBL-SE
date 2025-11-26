<?php
include 'includes/header.php';
$art = $data['article'];
?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">

                <h1 class="display-5 fw-bold mb-3"><?php echo $art['judul']; ?></h1>

                <p class="text-muted border-bottom pb-3">
                    <i class="bi bi-person-circle me-1"></i> <strong><?php echo $art['pembuat']; ?></strong>
                    &nbsp;|&nbsp;
                    <i class="bi bi-calendar3"></i> <?php echo $art['tgl']; ?>
                </p>

                <?php if ($art['exists']): ?>
                    <div class="mb-4">
                        <img src="<?php echo $art['gambar']; ?>"
                             class="rounded-3 shadow-sm w-100"
                             alt="Gambar <?php echo $art['judul']; ?>"
                             style="object-fit: cover; max-height: 500px;">
                    </div>
                <?php endif; ?>

                <div class="konten lh-lg">
                    <?php echo $art['konten']; ?>
                </div>

                <div class="mt-5 pt-4 border-top">
                    <a href="blog.php" class="btn rounded-pill px-4">
                        <i class="bi bi-caret-left-fill"></i> Kembali ke Daftar Artikel
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

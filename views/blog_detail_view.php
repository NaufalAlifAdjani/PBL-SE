<?php
include 'includes/header.php';
$row = $data['article'];
?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">

                <h1 class="display-5 fw-bold mb-3"><?php echo $row['judul']; ?></h1>

                <p class="text-muted border-bottom pb-3">
                    <i class="bi bi-person-circle me-1"></i> <strong><?php echo $row['pembuat']; ?></strong>
                    &nbsp;|&nbsp;
                    <i class="bi bi-calendar3"></i>
                    <?php echo $row['tgl']; ?>
                    <?php if (!empty($row['is_edited']) && $row['is_edited']): ?>
                        <span class="badge bg-warning text-dark ms-2" title="Telah diedit">Edited
                        </span>
                    <?php endif; ?>
                </p>

                <?php if ($row['exists']): ?>
                    <div class="mb-4">
                        <img src="<?php echo $row['gambar']; ?>"
                             class="rounded-3 shadow-sm w-100"
                             alt="Gambar <?php echo $row['judul']; ?>"
                             style="object-fit: cover; max-height: 500px;">
                    </div>
                <?php endif; ?>

                <div class="konten lh-lg">
                    <?php echo $row['konten']; ?>
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

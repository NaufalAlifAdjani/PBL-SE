<?php
// Panggil Header Global
include 'includes/header.php';
?>

<style>
    .pview-content-body iframe, 
    .pview-content-body object, 
    .pview-content-body embed {
        width: 100% !important; 
        height: auto !important; 
        aspect-ratio: 16 / 9;
        margin: 20px 0; 
        border-radius: 8px; 
        display: block;
    }
</style>

<div class="banner">
    <?php if ($data): ?>
    <div class="container pview-hero-box">
        <h1><?php echo htmlspecialchars($data['title']); ?></h1>
    </div>
    <?php endif; ?>
</div>

<div class="container mt-5 mb-5 pview-relative-container">
    <?php if ($data): ?>
        <div class="row g-5"> 
            
            <div class="col-lg-3 d-none d-lg-block">
                <div class="pview-sidebar-wrapper">
                    <div class="pview-card">
                        <div class="pview-sidebar-header">
                            Menu Profile
                        </div>
                        <div class="pview-sidebar-menu">
                            <?php
                            if (!empty($sidebar_items)) {
                                foreach ($sidebar_items as $item) {
                                    // Cek menu aktif
                                    $isActive = (isset($_GET['slug']) && $_GET['slug'] == $item['slug']) ? 'active' : '';
                                    echo '<a href="page.php?slug=' . htmlspecialchars($item['slug']) . '" class="' . $isActive . '">';
                                    echo htmlspecialchars($item['title']);
                                    echo '</a>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="pview-card pview-content-area">
                    <div class="pview-content-body">
                        <?php echo $data['content']; ?>
                    </div>
                </div>
            </div>

        </div>
    <?php else: ?>
        <div class="text-center my-5 py-5">
            <h2 class="fw-bold text-dark">Halaman Tidak Ditemukan</h2>
            <p class="text-muted">Maaf, konten yang Anda cari tidak tersedia.</p>
            <a href="index.php" class="btn btn-primary mt-3">Kembali ke Home</a>
        </div>
    <?php endif; ?>
</div>

<?php 
// Panggil Footer Global
include 'includes/footer.php'; 
?>
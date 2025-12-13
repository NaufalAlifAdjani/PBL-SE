<?php include 'includes/header.php'; ?>

<section class="py-5" style="background-color: #f3f4f6; min-height: 100vh;">
    <div class="container">
        
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <h2 class="fw-bold mb-3 text-dark">Blog & Artikel</h2>
                <form action="" method="GET" class="d-flex justify-content-center blog-search-container">
                    <div class="input-group mb-3 shadow-sm" style="max-width: 600px; width: 100%;">
                        <input type="text" name="q" class="form-control" placeholder="Cari artikel..." value="<?php echo htmlspecialchars($data['search_keyword'] ?? ''); ?>">
                        <button class="btn btn-search" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
        <?php if (!empty($data['current_tag'])): ?>
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-light border d-flex align-items-center justify-content-between">
                        <span>
                            Menampilkan hasil untuk tag: 
                            <span class="badge bg-primary"><?php echo htmlspecialchars($data['current_tag']); ?></span>
                        </span>
                        <a href="blog.php" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-x-lg"></i> Reset Filter
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="row justify-content-center mb-4">
            <div class="col-12">
                <div class="blog-filter-container">
                    
                    <a href="blog.php" 
                    class="btn-custom-filter <?php echo empty($data['current_category']) ? 'active' : 'inactive'; ?>">
                        Semua
                    </a>

                    <a href="blog.php?cat=Artikel" 
                    class="btn-custom-filter <?php echo ($data['current_category'] == 'Artikel') ? 'active' : 'inactive'; ?>">
                        <i class="bi bi-file-text"></i> Artikel
                    </a>

                    <a href="blog.php?cat=Produk Inovasi" 
                    class="btn-custom-filter <?php echo ($data['current_category'] == 'Produk Inovasi') ? 'active' : 'inactive'; ?>">
                        <i class="bi bi-rocket-takeoff"></i> Produk Inovasi
                    </a>

                </div>
            </div>
        </div>
        <div class="row g-3 g-md-4">
            <?php if (!empty($data['articles'])): ?>
                <?php foreach ($data['articles'] as $row): ?>
                    
                    <div class="col-6 col-md-4 d-flex align-items-stretch">
                        
                        <div class="card w-100 shadow-sm blog-card">

                            <div class="blog-card-img-wrapper">
                                <img src="<?php echo htmlspecialchars($row['image_path']); ?>"
                                     class="card-img-top blog-card-img"
                                     alt="<?php echo htmlspecialchars($row['judul']); ?>">
                            </div>

                            <div class="card-body blog-card-body">
                                <div class="mb-2">
                                    <?php if($row['kategori'] == 'Produk Inovasi'): ?>
                                        <span class="badge bg-info text-dark"><i class="bi bi-star-fill"></i> Inovasi</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Artikel</span>
                                    <?php endif; ?>
                                </div>
                                <div class="blog-card-meta">
                                    <i class="bi bi-calendar3"></i> <?php echo $row['display_date']; ?>
                                </div>

                                <h5 class="card-title blog-card-title">
                                    <a href="blog_detail.php?slug=<?php echo $row['slug']; ?>">
                                        <?php 
                                            $judul = $row['judul'];
                                            // Potong judul agak pendek di mobile biar gak kepanjangan
                                            echo htmlspecialchars(strlen($judul) > 40 ? substr($judul, 0, 40) . '...' : $judul); 
                                        ?>
                                    </a>
                                </h5>

                                <p class="card-text blog-card-text">
                                    <?php echo htmlspecialchars($row['snippet']); ?>
                                </p>
                                <?php if(!empty($row['tags_array'])): ?>
                                    <div class="mb-3">
                                        <?php foreach($row['tags_array'] as $tag): ?>
                                            <a href="blog.php?tag=<?php echo urlencode($tag); ?>" 
                                            class="badge bg-light text-secondary border me-1 text-decoration-none">
                                            #<?php echo htmlspecialchars($tag); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <a href="blog_detail.php?slug=<?php echo $row['slug']; ?>" class="btn">
                                    Read More
                                </a>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                 <div class="col-12 text-center py-5">
                    <h4 class="text-muted">Tidak ada artikel.</h4>
                </div>
            <?php endif; ?>
        </div>

        <?php if (isset($data['pagination']) && $data['pagination']['total_pages'] > 1): ?>
            <div class="row mt-5">
                <div class="col-12">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <?php 
                                // Helper function untuk membuat URL pagination tetap membawa filter search/kategori
                                function buildUrl($page, $data) {
                                    $params = [
                                        'page' => $page,
                                        'q'    => $data['search_keyword'],
                                        'cat'  => $data['current_category'],
                                        'tag'  => $data['current_tag']
                                    ];
                                    // Hapus parameter kosong agar URL bersih
                                    return 'blog.php?' . http_build_query(array_filter($params));
                                }
                                
                                $current = $data['pagination']['current_page'];
                                $total   = $data['pagination']['total_pages'];
                            ?>

                            <li class="page-item <?php echo ($current <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?php echo ($current > 1) ? buildUrl($current - 1, $data) : '#'; ?>">
                                    Previous
                                </a>
                            </li>

                            <?php 
                            // Tampilkan halaman 1, halaman terakhir, dan halaman di sekitar current page
                            $range = 2; // Jumlah angka di kiri/kanan halaman aktif

                            for ($x = 1; $x <= $total; $x++) : 
                                // Logika untuk menampilkan ..., 1, 2, 3, ... Last
                                if ($x == 1 || $x == $total || ($x >= $current - $range && $x <= $current + $range)) :
                            ?>
                                    <li class="page-item <?php echo ($x == $current) ? 'active' : ''; ?>">
                                        <a class="page-link" href="<?php echo buildUrl($x, $data); ?>">
                                            <?php echo $x; ?>
                                        </a>
                                    </li>
                            <?php elseif ($x == $current - $range - 1 || $x == $current + $range + 1) : ?>
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                            <?php endif; ?>
                            <?php endfor; ?>

                            <li class="page-item <?php echo ($current >= $total) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?php echo ($current < $total) ? buildUrl($current + 1, $data) : '#'; ?>">
                                    Next
                                </a>
                            </li>

                        </ul>
                    </nav>
                </div>
            </div>
        <?php endif; ?>
        </div> </section>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
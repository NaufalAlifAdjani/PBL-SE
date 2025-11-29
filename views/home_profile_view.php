<section id="about-us" class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <img src="uploads/dummy.png" class="img-fluid rounded-3 shadow" alt="About Us">
            </div>
            <div class="col-md-6 text-justify">
                <h2 class="fw-bold mb-3">About Us</h2>
                <div class="lead text-muted">
                    <?php echo $about['content']; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="visi-misi" class="py-5 bg-light-subtle">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="text-center mb-4">
                    <h2 class="fw-bold"><?php echo htmlspecialchars($visi_misi['title']); ?></h2>
                </div>
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <div class="content-body text-justify">
                            <?php echo $visi_misi['content']; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container personil-page py-4 px-md-5" id="personil-lab">
    
    <h1 class="fw-bold mb-5 text-center">Personil Lab</h1>

    <div class="row">
        <div class="col-md-3 mb-4 d-none d-md-block">
            <div class="custom-sticky-sidebar">
                <div class="card border-0 shadow-sm sidebar-adjust" style="border-radius: 20px; overflow: hidden;">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <button onclick="filterCategory('all')" id="btn-all" 
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center personil-sidebar-btn active">
                                Semua Personil
                                <span class="badge rounded-pill bg-light text-dark"><?= (pg_num_rows($dosen) + pg_num_rows($member)) ?></span>
                            </button>
                            
                            <button onclick="filterCategory('dosen')" id="btn-dosen" 
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center personil-sidebar-btn">
                                Dosen
                                <span class="badge rounded-pill bg-light text-dark"><?= pg_num_rows($dosen) ?></span>
                            </button>
                            
                            <button onclick="filterCategory('member')" id="btn-member" 
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center personil-sidebar-btn">
                                Member
                                <span class="badge rounded-pill bg-light text-dark"><?= pg_num_rows($member) ?></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-3 mb-md-0" id="category-title">Semua Data</h3>
                
                <div class="input-group shadow-sm search-container">
                    <span class="input-group-text bg-white border-end-0 ps-3 search-icon-box">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control border-start-0 search-input-field" 
                           placeholder="Cari nama..." onkeyup="filterSearch()">
                </div>
            </div>

            <div id="section-dosen" class="mb-5 personil-section">
                <h4 class="fw-bold mb-3 section-header">Dosen</h4>
                
                <?php if (!$dosen || pg_num_rows($dosen) === 0): ?>
                    <p class="text-muted">Belum ada data dosen.</p>
                <?php else: ?>
                    <div class="row g-3 g-md-4">
                        <?php 
                        pg_result_seek($dosen, 0);
                        while ($d = pg_fetch_assoc($dosen)):
                            $nama    = $d['nama_dosen'] ?? $d['nama'] ?? '';
                            $jabatan = $d['jabatan'] ?? $d['posisi'] ?? '';
                            $slug    = $d['slug'] ?? '';
                            $foto    = $d['foto_profil'] ?? '';
                        ?>
                        <div class="col-6 col-md-6 col-lg-4 search-item">
                            
                            <a href="<?= $slug ? 'personil_detail.php?slug=' . urlencode($slug) : '#' ?>" class="text-decoration-none">
                                <div class="card dosen-card">
                                    <div class="card-body p-0 d-flex flex-column">
                                        
                                        <?php if ($foto): ?>
                                            <div class="dosen-img-wrapper">
                                                <img src="uploads/personil/<?= htmlspecialchars($foto) ?>"
                                                     alt="Foto <?= htmlspecialchars($nama) ?>">
                                            </div>
                                        <?php else: ?>
                                            <div class="dosen-placeholder">
                                                <i class="bi bi-person-fill display-1 text-white opacity-50"></i>
                                            </div>
                                        <?php endif; ?>

                                        <div class="dosen-info-container text-center flex-grow-1 d-flex flex-column justify-content-center">
                                            <div class="dosen-name search-text"><?= htmlspecialchars($nama) ?></div>
                                            <?php if ($jabatan): ?>
                                                <div class="dosen-meta"><?= htmlspecialchars($jabatan) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div id="section-member" class="personil-section">
                <h4 class="fw-bold mb-3 section-header">Member</h4>

                <?php if (!$member || pg_num_rows($member) === 0): ?>
                    <p class="text-muted">Data member tidak ditemukan.</p>
                <?php else: ?>
                    <div class="row g-3 g-md-4">
                        <?php 
                        pg_result_seek($member, 0);
                        while ($m = pg_fetch_assoc($member)):
                            $nama_m    = $m['nama'] ?? '';
                            $nim_m     = $m['nim'] ?? '';
                            $jurusan_m = $m['jurusan'] ?? '';
                            $slug_m    = $m['slug_member'] ?? '';
                            
                            $raw_porto = $m['portofolio'] ?? '';
                            $url_porto = '';
                            
                            if (!empty($raw_porto)) {
                                if (strpos($raw_porto, 'http') !== 0) $raw_porto = 'https://' . $raw_porto;
                                $url_porto = $raw_porto;
                            }
                        ?>
                        <div class="col-6 col-md-6 col-lg-4 search-item d-flex">
                            
                            <div class="card member-card w-100">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="member-name search-text">
                                            <?php if ($slug_m): ?>
                                                <a href="profil_member.php?slug=<?= urlencode($slug_m) ?>">
                                                    <?= htmlspecialchars($nama_m) ?>
                                                </a>
                                            <?php else: ?>
                                                <?= htmlspecialchars($nama_m) ?>
                                            <?php endif; ?>
                                        </div>

                                        <?php if ($nim_m): ?>
                                            <div class="member-meta"><?= htmlspecialchars($nim_m) ?></div>
                                        <?php endif; ?>

                                        <?php if ($jurusan_m): ?>
                                            <div class="member-meta"><?= htmlspecialchars($jurusan_m) ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($url_porto !== ''): ?>
                                        <div class="mt-auto pt-2">
                                            <a href="<?= htmlspecialchars($url_porto) ?>" target="_blank" class="btn btn-light btn-sm rounded-pill px-4 fw-bold">
                                                Portofolio
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div id="no-result" class="text-center py-5 d-none">
                <i class="bi bi-search display-4 text-muted mb-3"></i>
                <p class="text-muted">Tidak ditemukan personil dengan nama tersebut.</p>
            </div>

        </div>
    </div>
</div>

<script>
    function filterCategory(category) {
        const secDosen = document.getElementById('section-dosen');
        const secMember = document.getElementById('section-member');
        const title = document.getElementById('category-title');
        
        document.querySelectorAll('.personil-sidebar-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById('btn-' + category).classList.add('active');

        if (category === 'all') {
            secDosen.style.display = 'block';
            secMember.style.display = 'block';
            title.innerText = 'Semua Personil';
        } else if (category === 'dosen') {
            secDosen.style.display = 'block';
            secMember.style.display = 'none';
            title.innerText = 'Data Dosen';
        } else if (category === 'member') {
            secDosen.style.display = 'none';
            secMember.style.display = 'block';
            title.innerText = 'Data Member';
        }
        
        document.getElementById('searchInput').value = '';
        filterSearch();
    }

    function filterSearch() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const items = document.querySelectorAll('.search-item');
        let visibleCount = 0;

        items.forEach(item => {
            const nameEl = item.querySelector('.search-text'); 
            const nameText = nameEl ? nameEl.textContent.toLowerCase() : '';
            const parentSection = item.closest('.personil-section');
            const isSectionVisible = parentSection.style.display !== 'none';

            if (nameText.includes(input) && isSectionVisible) {
                item.style.display = ''; 
                visibleCount++;
            } else {
                item.style.display = 'none'; 
            }
        });

        const noResult = document.getElementById('no-result');
        if (visibleCount === 0 && input !== '') {
            noResult.classList.remove('d-none');
        } else {
            noResult.classList.add('d-none');
        }
    }
</script>
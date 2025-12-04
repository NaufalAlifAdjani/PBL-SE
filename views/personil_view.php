<?php
// Dari controller: $dosen, $member, $page_title
?>
<div class="container personil-page py-4 px-md-5" id="personil-lab">
    <h1 class="fw-bold mb-4">Personil Lab</h1>

    <h3 class="fw-bold mb-3 text-center">Dosen</h3>

    <?php if (!$dosen || pg_num_rows($dosen) === 0): ?>
        <p class="text-muted text-center">Belum ada data dosen.</p>
    <?php else: ?>
    <div class="row g-4 justify-content-center" style="max-width: 1000px; margin: 0 auto;">
        <?php while ($d = pg_fetch_assoc($dosen)):
            $nama    = $d['nama_dosen'] ?? $d['nama'] ?? '';
            $jabatan = $d['jabatan'] ?? $d['posisi'] ?? '';
            $slug    = $d['slug'] ?? '';
            $foto    = $d['foto_profil'] ?? '';
        ?>
        <div class="col-12 col-md-6 col-lg-4">
            <a href="<?= $slug ? 'personil_detail.php?slug=' . urlencode($slug) : '#' ?>" class="text-decoration-none">
                <div class="card dosen-card h-100">
                    <div class="card-body p-0 d-flex flex-column">
                        <?php if ($foto): ?>
                            <div style="height: 300px; overflow: hidden; background-color: transparent;">
                                <img src="uploads/personil/<?= htmlspecialchars($foto) ?>"
                                     alt="Foto <?= htmlspecialchars($nama) ?>"
                                     style="width: 100%; height: 100%; object-fit: cover; object-position: top center;">
                            </div>
                        <?php else: ?>
                            <div style="height: 300px; background-color: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-person-fill" style="font-size: 5rem; color: rgba(255,255,255,0.5);"></i>
                            </div>
                        <?php endif; ?>

                        <div class="dosen-info-container p-4 text-center flex-grow-1 d-flex flex-column justify-content-center">
                            <div class="dosen-name"><?= htmlspecialchars($nama) ?></div>
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


    <h2 class="fw-bold mt-5 mb-3 text-center">Member</h2>

    <?php if (!$member || pg_num_rows($member) === 0): ?>
        <p class="text-muted text-center">Data member tidak ditemukan.</p>
    <?php else: ?>
    <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center" 
         style="max-width: 1000px; margin: 0 auto;" 
         id="bagian-member">
        
        <?php while ($m = pg_fetch_assoc($member)):
            $nama_m    = $m['nama'] ?? '';
            $nim_m     = $m['nim'] ?? '';
            $jurusan_m = $m['jurusan'] ?? '';
            $slug_m    = $m['slug_member'] ?? '';
            
            $raw_porto = $m['portofolio'] ?? '';
            $url_porto = '';
            
            if (!empty($raw_porto)) {
                if (strpos($raw_porto, 'http') !== 0) {
                    $raw_porto = 'https://' . $raw_porto;
                }
                $url_porto = $raw_porto;
            }
        ?>
        <div class="col d-flex">
            <div class="card member-card w-100">
                <div class="card-body">
                    
                    <div class="mb-3">
                        <div class="member-name">
                            <?php if ($slug_m): ?>
                                <a href="profil_member.php?slug=<?= urlencode($slug_m) ?>" class="text-white text-decoration-none">
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
                                Lihat Portofolio
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
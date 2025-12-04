<?php
// dari controller: $dosen, $pendidikan, $publikasi, $kbm, $page_title

$nama    = $dosen['nama_dosen'] ?? '';
$jabatan = $dosen['jabatan'] ?? '';
$nip     = $dosen['nip'] ?? '';
$nidn    = $dosen['nidn'] ?? '';
$email   = $dosen['email_dosen'] ?? '';
$bidang  = $dosen['bid_kemahiran'] ?? ''; 
$foto    = $dosen['foto_profil'] ?? '';   
?>

<div class="container py-5 detail-personil-page">

    <div class="mb-4">
        <a href="personil.php" class="btn-gradient">
            <i class="bi bi-arrow-left"></i> Kembali ke Personil
        </a>
    </div>

<div class="card card-purple mb-5" style="background: linear-gradient(135deg, #181028, #4c1d95); border-radius: 20px; border: none; overflow: hidden;">
        <div class="card-body p-4 p-lg-5">
            <div class="row align-items-center text-start"> <div class="col-12 col-md-auto mb-4 mb-md-0 d-flex justify-content-center justify-content-md-start">
                    <?php if ($foto): ?>
                        <div style="width: 180px; height: 240px; border-radius: 12px; overflow: hidden; border: 4px solid rgba(255,255,255,0.2); box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
                            <img src="uploads/personil/<?= htmlspecialchars($foto) ?>" 
                                 alt="Foto Profil" 
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    <?php else: ?>
                        <div style="width: 180px; height: 240px; border-radius: 12px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-person-fill text-white display-3"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-12 col-md ms-md-4 text-white">
                    <h2 class="fw-bold mb-1 text-white">
                        <?= htmlspecialchars($nama) ?>
                    </h2>

                    <?php if ($jabatan): ?>
                        <p class="fs-5 mb-4 text-white opacity-75">
                            <?= htmlspecialchars($jabatan) ?>
                        </p>
                    <?php endif; ?>

                    <div class="row mt-4">
                        
                        <div class="col-md-6">
                            <div class="mb-4">
                                <small class="d-block text-uppercase text-white opacity-50" style="font-size: 0.75rem; letter-spacing: 1px;">NIP</small>
                                <span class="fw-semibold fs-5"><?= htmlspecialchars($nip ?: '-') ?></span>
                            </div>

                            <div>
                                <small class="d-block text-uppercase text-white opacity-50" style="font-size: 0.75rem; letter-spacing: 1px;">NIDN</small>
                                <span class="fw-semibold fs-5"><?= htmlspecialchars($nidn ?: '-') ?></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-4"> <small class="d-block text-uppercase text-white opacity-50" style="font-size: 0.75rem; letter-spacing: 1px;">Email</small>
                                <span class="fw-semibold fs-5"><?= htmlspecialchars($email ?: '-') ?></span>
                            </div>
                        </div>

                    </div>
                </div> 

            </div> 
        </div>
    </div>


    <h4 class="judul-section">
        <i class="bi bi-book-half section-icon"></i>
        Mata Kuliah Diampu
    </h4>

    <?php if ($kbm && pg_num_rows($kbm) > 0): ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
            <?php while ($k = pg_fetch_assoc($kbm)): ?>
                <div class="col">
                    <div class="card matkul-card-purple h-100">
                        <div>
                            <div class="matkul-nama">
                                <?= htmlspecialchars($k['nama_matkul'] ?? '-') ?>
                            </div>
                            <?php if (!empty($k['kode_matkul'])): ?>
                                <small><?= htmlspecialchars($k['kode_matkul']) ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-muted fst-italic">Belum ada data mata kuliah.</p>
    <?php endif; ?>


    <h4 class="judul-section mt-5">
        <i class="bi bi-mortarboard-fill section-icon"></i>
        Riwayat Pendidikan
    </h4>

    <?php if ($pendidikan && pg_num_rows($pendidikan) > 0): ?>
        <div class="row row-cols-1 row-cols-md-2 g-3">
            <?php while ($p = pg_fetch_assoc($pendidikan)): ?>
                <div class="col">
                    <div class="edu-card-compact">
                        <div class="edu-badge-small">
                            <?= htmlspecialchars($p['jenjang'] ?? '-') ?>
                        </div>
                        
                        <div class="text-start lh-sm">
                            <div class="fw-bold text-dark mb-1" style="font-size: 1rem;">
                                <?= htmlspecialchars($p['program_studi'] ?? '-') ?>
                            </div>
                            <div class="text-secondary small fw-semibold mb-1">
                                <?= htmlspecialchars($p['nama_kampus'] ?? '-') ?>
                            </div>
                            <div class="text-muted" style="font-size: 0.8rem;">
                                Lulus Tahun: <?= htmlspecialchars($p['thn_lulus'] ?? '-') ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-muted fst-italic">Belum ada data pendidikan.</p>
    <?php endif; ?>


    <h4 class="judul-section">
        <i class="bi bi-journal-text section-icon"></i>
        Publikasi
    </h4>

    <?php if ($publikasi && pg_num_rows($publikasi) > 0): ?>
        <div class="row row-cols-1 row-cols-md-3 g-3">
            <?php while ($pub = pg_fetch_assoc($publikasi)): ?>
                <div class="col">
                    <div class="card card-purple h-100 shadow">
                        <div class="card-body d-flex flex-column p-4">
                            <div class="fw-bold mb-3" style="font-size: 1.1rem; line-height: 1.4;">
                                <?= htmlspecialchars($pub['judul'] ?? '-') ?>
                            </div>
                            
                            <div class="small mb-4 text-muted-purple">
                                <i class="bi bi-calendar-event"></i> Tahun: <?= htmlspecialchars($pub['thn_terbit'] ?? '-') ?>
                            </div>
                            
                            <div class="mt-auto">
                                <?php if (!empty($pub['link_publikasi'])): ?>
                                    <a href="<?= htmlspecialchars($pub['link_publikasi']) ?>" 
                                       target="_blank" 
                                       class="btn btn-sm fw-bold rounded-pill px-3 py-2 w-100"
                                       style="background: white; color: #4c1d95; border: none;">
                                       Baca Jurnal <i class="bi bi-arrow-right-short"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="badge bg-secondary opacity-50 w-100 py-2 rounded-pill">Tidak ada link</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-muted mb-5 fst-italic">Belum ada data publikasi.</p>
    <?php endif; ?>

    <div class="mb-5"></div>
</div>
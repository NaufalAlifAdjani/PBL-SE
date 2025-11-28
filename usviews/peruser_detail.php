<?php
// dari controller: $dosen, $pendidikan, $publikasi, $kbm, $page_title

$nama    = $dosen['nama_dosen'] ?? '';
$jabatan = $dosen['jabatan'] ?? '';
$nip     = $dosen['nip'] ?? '';
$nidn    = $dosen['nidn'] ?? '';
$email   = $dosen['email_dosen'] ?? '';
$bidang  = $dosen['bid_kemahiran'] ?? '';
?>

<div class="container py-5">

    <a href="personil.php" class="btn btn-primary mb-4">
        &laquo; Kembali ke Personil
    </a>

    
    <!--        CARD BIODATA           -->
    
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h2 class="fw-bold mb-1"><?= htmlspecialchars($nama) ?></h2>

            <?php if ($jabatan): ?>
                <p class="text-muted mb-3"><?= htmlspecialchars($jabatan) ?></p>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1"><strong>NIP</strong></p>
                    <p class="mb-2"><?= htmlspecialchars($nip ?: '-') ?></p>

                    <p class="mb-1"><strong>NIDN</strong></p>
                    <p class="mb-2"><?= htmlspecialchars($nidn ?: '-') ?></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong>Email</strong></p>
                    <p class="mb-2"><?= htmlspecialchars($email ?: '-') ?></p>

                    <p class="mb-1"><strong>Bid. Kemahiran</strong></p>
                    <p class="mb-0"><?= htmlspecialchars($bidang ?: '-') ?></p>
                </div>
            </div>
        </div>
    </div>

    <!--            CARD KBM            -->
    
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h4 class="fw-semibold mb-3">Mata Kuliah Diampu</h4>

            <?php if ($kbm && pg_num_rows($kbm) > 0): ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                    <?php while ($k = pg_fetch_assoc($kbm)): ?>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="fw-semibold mb-1">
                                        <?= htmlspecialchars($k['nama_matkul'] ?? '-') ?>
                                    </div>

                                    <?php if (!empty($k['kode_matkul'])): ?>
                                        <div class="text-muted small mb-1">
                                            <?= htmlspecialchars($k['kode_matkul']) ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (empty($k['kode_matkul']) && empty($k['semester'])): ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="text-muted mb-0">Belum ada data KBM yang tercatat.</p>
            <?php endif; ?>
        </div>
    </div>

    <!--     CARD RIWAYAT PENDIDIKAN    -->
   
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h4 class="fw-semibold mb-3">Riwayat Pendidikan</h4>

            <?php if ($pendidikan && pg_num_rows($pendidikan) > 0): ?>
                <div class="row row-cols-1 row-cols-md-2 g-3">
                    <?php while ($p = pg_fetch_assoc($pendidikan)): ?>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="fw-semibold mb-1">
                                        <?= htmlspecialchars($p['jenjang'] ?? '-') ?>
                                    </div>
                                    <div class="text-muted small mb-2">
                                        <?= htmlspecialchars($p['program_studi'] ?? '-') ?>
                                    </div>
                                    <div class="small">
                                        <div><?= htmlspecialchars($p['nama_kampus'] ?? '-') ?></div>
                                        <div>Tahun lulus : <?= htmlspecialchars($p['thn_lulus'] ?? '-') ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="text-muted mb-0">Belum ada data riwayat pendidikan yang tercatat.</p>
            <?php endif; ?>
        </div>
    </div>

   <!--         CARD PUBLIKASI         -->
            
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h4 class="fw-semibold mb-3">Publikasi</h4>

            <?php if ($publikasi && pg_num_rows($publikasi) > 0): ?>
                <div class="row row-cols-1 g-3">
                    <?php while ($pub = pg_fetch_assoc($publikasi)): ?>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="fw-semibold mb-1">
                                        <?= htmlspecialchars($pub['judul'] ?? '-') ?>
                                    </div>
                                    <div class="text-muted small mb-2">
                                        <?= htmlspecialchars($pub['thn_terbit'] ?? '-') ?>
                                    </div>
                                    <?php if (!empty($pub['link_publikasi'])): ?>
                                        <a href="<?= htmlspecialchars($pub['link_publikasi']) ?>" target="_blank" class="small">
                                            <?= htmlspecialchars($pub['link_publikasi']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small">Tidak ada link publikasi.</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="text-muted mb-0">Belum ada data publikasi yang tercatat.</p>
            <?php endif; ?>
        </div>
    </div>
</div>


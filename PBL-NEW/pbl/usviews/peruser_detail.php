<?php
// dari controller: $dosen, $pendidikan, $publikasi, $kbm, $page_title

$nama    = $dosen['nama_dosen'] ?? '';
$jabatan = $dosen['jabatan'] ?? '';
$nip     = $dosen['nip'] ?? '';
$nidn    = $dosen['nidn'] ?? '';
$email   = $dosen['email_dosen'] ?? '';
$foto    = $dosen['foto_profil'] ?? '';
?>

<div class="container py-5 detail-personil-page">

    <a href="personil.php" class="btn btn-kembali-personil mb-4">
        &laquo; Kembali ke Personil
    </a>

    <!--        CARD BIODATA + FOTO        -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row align-items-center">

                <?php if ($foto): ?>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <div class="detail-photo-wrapper">
                            <img src="uploads_personil/<?= htmlspecialchars($foto) ?>"
                                 alt="Foto <?= htmlspecialchars($nama) ?>"
                                 class="detail-photo">
                        </div>
                    </div>
                    <div class="col-md-9">
                <?php else: ?>
                    <div class="col-12">
                <?php endif; ?>

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
                        </div>
                    </div>
                </div> <!-- end col foto+data -->
            </div> <!-- end row -->
        </div>
    </div>

    <!--         MATA KULIAH DIAMPU         -->
    <h4 class="fw-semibold mb-3 mt-4">
    <i class="bi bi-book-half section-icon"></i>
    Mata Kuliah Diampu
</h4>


    <?php if ($kbm && pg_num_rows($kbm) > 0): ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3 mb-4">
            <?php while ($k = pg_fetch_assoc($kbm)): ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="fw-semibold mb-1">
                                <?= htmlspecialchars($k['nama_matkul'] ?? '-') ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-muted mb-4">Belum ada data KBM yang tercatat.</p>
    <?php endif; ?>

    <h4 class="fw-semibold mb-3 mt-4">
    <i class="bi bi-mortarboard-fill section-icon"></i>
    Riwayat Pendidikan
</h4>

<?php if ($pendidikan && pg_num_rows($pendidikan) > 0): ?>
    <div class="edu-timeline mb-4">
        <?php while ($p = pg_fetch_assoc($pendidikan)): ?>
            <div class="edu-item">
                <!-- Kolom kiri: S1/S2/S3 -->
                <div class="edu-level">
                    <?= htmlspecialchars($p['jenjang'] ?? '-') ?>
                </div>

                <!-- titik -->
                <div class="edu-line">
                    <span class="edu-dot"></span>
                </div>

                <!-- Kolom kanan: prodi, kampus, tahun -->
                <div class="edu-content">
                    <div class="fw-semibold mb-1">
                        <?= htmlspecialchars($p['program_studi'] ?? '-') ?>
                    </div>
                    <div><?= htmlspecialchars($p['nama_kampus'] ?? '-') ?></div>
                    <div><?= htmlspecialchars($p['thn_lulus'] ?? '-') ?></div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <p class="text-muted mb-4">Belum ada data riwayat pendidikan yang tercatat.</p>
<?php endif; ?>



    <!--              PUBLIKASI             -->
    <h4 class="fw-semibold mb-3 mt-4">
    <i class="bi bi-journal-text section-icon"></i>
    Publikasi
</h4>


    <?php if ($publikasi && pg_num_rows($publikasi) > 0): ?>
        <div class="row row-cols-1 row-cols-md-3 g-3">
            <?php while ($pub = pg_fetch_assoc($publikasi)): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm publikasi-card">
                        <div class="card-body d-flex flex-column">
                            <div class="fw-semibold mb-1">
                                <?= htmlspecialchars($pub['judul'] ?? '-') ?>
                            </div>
                            <div class="text-muted small mb-2">
                                <?= htmlspecialchars($pub['thn_terbit'] ?? '-') ?>
                            </div>

                            <?php if (!empty($pub['link_publikasi'])): ?>
                                <div class="mt-auto text-start">
                                    <a href="<?= htmlspecialchars($pub['link_publikasi']) ?>"
                                       target="_blank"
                                       class="btn btn-login">
                                        Baca
                                    </a>
                                </div>
                            <?php else: ?>
                                <span class="text-muted small mt-auto">
                                    Tidak ada link publikasi.
                                </span>
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


<?php
// Dari controller: $dosen, $member, $page_title
?>
<div class="container py-4 px-md-5" id="personil-lab">
    <h1 class="fw-bold mb-4">Personil Lab</h1>

    <h3 class="fw-bold mb-3 text-center">Dosen</h3>

    <?php if (!$dosen || pg_num_rows($dosen) === 0): ?>
        <p class="text-muted">Belum ada data dosen.</p>
    <?php else: ?>
        <div class="row g-4 justify-content-center" style="max-width: 900px; margin: 0 auto;">
            <?php while ($d = pg_fetch_assoc($dosen)):
                $nama    = $d['nama_dosen'] ?? $d['nama'] ?? '';
                $jabatan = $d['jabatan'] ?? $d['posisi'] ?? '';
                $slug    = $d['slug'] ?? '';
                $foto    = $d['foto_profil'] ?? '';

                // kolom tambahan dari VIEW
                $jml_riwayat   = (int)($d['jml_riwayat']   ?? 0);
                $jml_publikasi = (int)($d['jml_publikasi'] ?? 0);
                $jml_matkul    = (int)($d['jml_matkul']    ?? 0);

                $link = 'personil_detail.php?slug=' . urlencode($slug);
            ?>
            <div class="col-6 col-lg-4">
                <a href="<?= $slug ? 'personil_detail.php?slug=' . urlencode($slug) : '#' ?>"
                   class="text-decoration-none text-dark">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body p-0 text-center d-flex flex-column">

                            <?php if ($foto): ?>
                                <img src="uploads_personil/<?= htmlspecialchars($foto) ?>"
                                     alt="Foto <?= htmlspecialchars($nama) ?>"
                                     class="w-100"
                                     style="
                                         height:280px;
                                         object-fit:cover;
                                         object-position:center 20%;
                                         border-radius:0.5rem 0.5rem 0 0;
                                     ">
                            <?php else: ?>
                                <div style="
                                         width:100%;
                                         height:280px;
                                         background:#e5e7eb;
                                         border-radius:0.5rem 0.5rem 0 0;
                                     "></div>
                            <?php endif; ?>

                            <!-- NAMA & JABATAN DI BAWAH FOTO -->
                            <div class="p-3">
                                <div class="fw-semibold">
                                    <?= htmlspecialchars($nama) ?>
                                </div>

                                <?php if ($jabatan): ?>
                                    <div class="text-muted small mt-1">
                                        <?= htmlspecialchars($jabatan) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </a>
            </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>

    <h3 class="fw-bold mt-5 mb-3 text-center">Member</h3>

    <!-- <?php if (!$member || pg_num_rows($member) === 0): ?> -->
        <p class="text-muted">Belum ada data member.</p>
    <?php else: ?>
        <div class="row g-4 justify-content-center">
            <?php while ($m = pg_fetch_assoc($member)):
                $nama_m    = $m['nama'] ?? '';
                $nim_m     = $m['nim'] ?? '';
                $jurusan_m = $m['jurusan'] ?? '';
                $slug_m    = $m['slug_member'] ?? '';
            ?>
            <div class="col-6 col-lg-4">
                <a href="<?= $slug_m ? 'profil_member.php?slug=' . urlencode($slug_m) : '#' ?>"
                   class="text-decoration-none text-dark">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center d-flex flex-column justify-content-center">

                            <div class="fw-semibold mb-1">
                                <?= htmlspecialchars($nama_m) ?>
                            </div>

                            <?php if ($nim_m): ?>
                                <div class="text-muted small">
                                    <?= htmlspecialchars($nim_m) ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($jurusan_m): ?>
                                <div class="text-muted small">
                                    <?= htmlspecialchars($jurusan_m) ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </a>
            </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>

</div>


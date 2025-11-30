<?php
// Dari controller: $dosen, $member, $page_title
?>
<div class="container personil-page" id="bagian-dosen">
    <h1 class="fw-bold mb-4">Personil Lab</h1>

    <h2 class="fw-bold mb-3 text-center">Dosen</h3>

    <?php if (!$dosen || pg_num_rows($dosen) === 0): ?>
        <p class="text-muted">Data dosen tidak ditemukan.</p>
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
                    <div class="card h-100 shadow-sm dosen-card">
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

    <h2 class="fw-bold mt-5 mb-3 text-center">Member</h3>
    <?php
    $raw_porto = $m['portofolio'] ?? '';
    if (!empty($raw_porto)) {
        // Tambah https:// kalau user hanya isi "linkedin.com/..." dsb
        if (strpos($raw_porto, 'http://') !== 0 && strpos($raw_porto, 'https://') !== 0) {
            $raw_porto = 'https://' . $raw_porto;
        }
        $url_porto = $raw_porto;
    }
    ?>


    <?php if (!$member || pg_num_rows($member) === 0): ?>
    <p class="text-muted">Data member tidak ditemukan.</p>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-3 g-3 justify-content-center"
     style="max-width: 900px; margin: 0 auto;"
     id="bagian-member">
        <?php while ($m = pg_fetch_assoc($member)):
            $nama_m    = $m['nama'] ?? '';
            $nim_m     = $m['nim'] ?? '';
            $jurusan_m = $m['jurusan'] ?? '';
            $slug_m    = $m['slug_member'] ?? '';
        ?>
        <div class="col d-flex">
            <div class="card member-card flex-fill shadow-sm border-0">
                <div class="card-body d-flex flex-column justify-content-between">

                    <!-- NAMA -->
                    <div>
                        <div class="member-name mb-1">
                            <?php if ($slug_m): ?>
                                <a href="profil_member.php?slug=<?= urlencode($slug_m) ?>"
                                   class="text-decoration-none text-dark">
                                    <?= htmlspecialchars($nama_m) ?>
                                </a>
                            <?php else: ?>
                                <?= htmlspecialchars($nama_m) ?>
                            <?php endif; ?>
                        </div>

                        <!-- NIM -->
                        <?php if ($nim_m): ?>
                            <div class="member-meta">
                                <?= htmlspecialchars($nim_m) ?>
                            </div>
                        <?php endif; ?>

                        <!-- JURUSAN -->
                        <?php if ($jurusan_m): ?>
                            <div class="member-meta mb-2">
                                <?= htmlspecialchars($jurusan_m) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php
                    // --- LOGIKA URL PORTO (TIDAK DIUBAH) ---
                    $raw_porto = $m['portofolio'] ?? '';

                    if ($raw_porto === '' || $raw_porto === null) {
                        // kalau kosong, arahkan ke '#'
                        $url_porto = '#';
                    } else {
                        // kalau user nulis tanpa http/https, tambahin otomatis
                        if (strpos($raw_porto, 'http://') !== 0 && strpos($raw_porto, 'https://') !== 0) {
                            $raw_porto = 'https://' . $raw_porto;
                        }
                        $url_porto = $raw_porto;
                    }
                    ?>

                    <?php if ($url_porto !== ''): ?>
                        <div class="mt-2">
                            <a href="<?= htmlspecialchars(
                                    (str_starts_with($url_porto, 'http://') || str_starts_with($url_porto, 'https://')) 
                                    ? $url_porto 
                                    : 'https://' . $url_porto
                                ) ?>"
                               target="_blank"
                               class="btn btn-primary btn-porto">
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


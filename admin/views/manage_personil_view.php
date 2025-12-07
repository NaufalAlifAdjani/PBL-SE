<?php
/**
 * manage_personil_view.php
 * FIX: Definisikan $action di paling atas agar tidak error "Undefined variable"
 */
$action = $_GET['action'] ?? 'list';

// --- FIX 1: TANGKAP POSISI TAB ---
// Kita cek URL, apakah ada parameter ?tab=member atau ?tab=dosen
// Jika tidak ada, default-nya ke 'dosen'
$active_tab = $_GET['tab'] ?? 'dosen'; 
?>

<?php if ($action === 'list' || $action === 'delete_dosen' || $action === 'delete_member'): ?>
    
    <div class="container-fluid py-4" id="personil-admin">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold m-0">Manajemen Personil</h2>
        </div>
        
        <div class="card card-admin mb-4">
            <div class="card-body p-3">
                <form action="" method="GET">
                    <input type="hidden" name="tab" id="current_tab_input" value="<?= htmlspecialchars($active_tab) ?>">

                    <div class="row g-2">
                        <div class="col-md-5">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control" placeholder="Cari nama, NIP, atau NIM..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                                <button class="btn btn-secondary" type="submit"><i class="bi bi-search"></i> Cari</button>
                            </div>
                        </div>

                        <div class="col-md-auto ps-">
                            <select name="angkatan" class="form-select" onchange="this.form.submit()">
                                <option value="">- Semua Angkatan -</option>
                                <?php 
                                $selected_angkatan = $_GET['angkatan'] ?? '';
                                // $opsi_angkatan dikirim dari controller
                                foreach ($opsi_angkatan as $thn): 
                                ?>
                                    <option value="<?= $thn ?>" <?= $selected_angkatan == $thn ? 'selected' : '' ?>>
                                        Angkatan <?= $thn ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <?php if((isset($_GET['q']) && $_GET['q'] != '') || (isset($_GET['angkatan']) && $_GET['angkatan'] != '')): ?>
                            <div class="col-md-auto">
                                <a href="manage_personil.php?tab=<?= $active_tab ?>" class="btn btn-outline-danger" title="Reset Filter"><i class="bi bi-x-lg"></i></a>
                            </div>
                        <?php endif; ?>

                        <div class="col-md-auto ms-auto ps-2">
                            <a href="manage_personil.php?action=form" class="btn btn-primary-admin">
                                <i class="bi bi-plus-lg me-2"></i>Tambah Dosen
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <ul class="nav nav-tabs mb-0" id="personilTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold <?= $active_tab == 'dosen' ? 'active' : '' ?>" 
                        id="dosen-tab" data-bs-toggle="tab" data-bs-target="#tab-dosen" 
                        type="button" role="tab" aria-controls="tab-dosen" 
                        aria-selected="<?= $active_tab == 'dosen' ? 'true' : 'false' ?>">
                    <i class="bi bi-people-fill me-1"></i> Data Dosen
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold <?= $active_tab == 'member' ? 'active' : '' ?>" 
                        id="member-tab" data-bs-toggle="tab" data-bs-target="#tab-member" 
                        type="button" role="tab" aria-controls="tab-member" 
                        aria-selected="<?= $active_tab == 'member' ? 'true' : 'false' ?>">
                    <i class="bi bi-mortarboard-fill me-1"></i> Data Member
                </button>
            </li>
        </ul>

        <div class="tab-content" id="personilTabsContent">
            
            <div class="tab-pane fade <?= $active_tab == 'dosen' ? 'show active' : '' ?>" id="tab-dosen" role="tabpanel" aria-labelledby="dosen-tab">
                <div class="card card-admin shadow-sm border-top-0 rounded-top-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="px-4 py-3">Nama Dosen</th>
                                        <th>Jabatan</th>
                                        <th class="text-end px-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!isset($dosen) || !$dosen || pg_num_rows($dosen) === 0): ?>
                                        <tr><td colspan="3" class="text-center py-5 text-muted">Data dosen tidak ditemukan.</td></tr>
                                    <?php else: ?>
                                        <?php while ($d = pg_fetch_assoc($dosen)): ?>
                                            <tr>
                                                <td class="px-4">
                                                    <div class="fw-bold text-dark"><?= htmlspecialchars($d['nama_dosen'] ?? ''); ?></div>
                                                    <small class="text-muted"><?= htmlspecialchars($d['email_dosen'] ?? '-'); ?></small>
                                                </td>
                                                <td><span class="badge bg-info text-dark"><?= htmlspecialchars($d['jabatan'] ?? '-'); ?></span></td>
                                                <td class="text-end px-4">
                                                    <a href="manage_personil.php?action=form&id=<?= $d['id_dosen']; ?>" class="btn btn-sm btn-outline-success me-1"><i class="bi bi-pencil-square"></i></a>
                                                    <a href="manage_personil.php?action=delete_dosen&id=<?= $d['id_dosen']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus dosen ini?');"><i class="bi bi-trash"></i></a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade <?= $active_tab == 'member' ? 'show active' : '' ?>" id="tab-member" role="tabpanel" aria-labelledby="member-tab">
                <div class="card card-admin shadow-sm border-top-0 rounded-top-0">
                     <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="px-4 py-3">Nama Mahasiswa</th>
                                        <th>NIM</th>
                                        <th>Jurusan</th>
                                        <th>Angkatan</th>
                                        <th class="text-end px-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!isset($member) || !$member || pg_num_rows($member) === 0): ?>
                                        <tr><td colspan="4" class="text-center py-5 text-muted">Data member tidak ditemukan.</td></tr>
                                    <?php else: ?>
                                        <?php while ($m = pg_fetch_assoc($member)): ?>
                                            <tr>
                                                <td class="px-4 fw-bold text-dark"><?= htmlspecialchars($m['nama'] ?? ''); ?></td>
                                                <td><?= htmlspecialchars($m['nim'] ?? ''); ?></td>
                                                <td><?= htmlspecialchars($m['jurusan'] ?? ''); ?></td>
                                                <td>
                                                    <span class="badge bg-light text-dark border">
                                                        <?= htmlspecialchars($m['angkatan'] ?? '-'); ?>
                                                    </span>
                                                </td>
                                                <td class="text-end px-4">
                                                    <a href="manage_personil.php?action=form_member&id=<?= $m['id_pendaftaran_user']; ?>" class="btn btn-sm btn-outline-success me-1"><i class="bi bi-pencil-square"></i></a>
                                                    <a href="manage_personil.php?action=delete_member&id=<?= $m['id_pendaftaran_user']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus member ini?');"><i class="bi bi-trash"></i></a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div> 
    </div>

    <script>
        // Saat user klik tab, ubah value input hidden 'tab'
        const tabEls = document.querySelectorAll('button[data-bs-toggle="tab"]');
        const hiddenInput = document.getElementById('current_tab_input');

        tabEls.forEach(tabEl => {
            tabEl.addEventListener('shown.bs.tab', function (event) {
                // targetId misal: '#tab-member' atau '#tab-dosen'
                const targetId = event.target.getAttribute('data-bs-target');
                
                // Ubah isi input hidden sesuai tab yang diklik
                if (targetId === '#tab-member') {
                    hiddenInput.value = 'member';
                } else {
                    hiddenInput.value = 'dosen';
                }
            })
        })
    </script>


<?php elseif ($action === 'form'): ?>

    <?php
    // Normalisasi data dari controller
    $dosen = $dosen ?? [];
    $type  = $type ?? 'dosen';
    $data  = $dosen; 

    $id    = $dosen['id_dosen']    ?? ($id ?? '');
    $nama  = $_POST['nama']        ?? ($dosen['nama_dosen']  ?? '');
    $email = $_POST['email']       ?? ($dosen['email_dosen'] ?? '');
    $slug  = $_POST['slug']        ?? ($dosen['slug']        ?? '');
    $foto  = $dosen['foto_profil'] ?? '';

    $title = $page_title ?? ($id ? 'Edit Dosen' : 'Tambah Dosen');
    ?>

    <div class="container-fluid py-4">
        <h2 class="fw-bold mb-3"><?= htmlspecialchars($title) ?></h2>
        <a href="manage_personil.php" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>

        <div class="card card-admin shadow-sm">
          <div class="card-body p-4">

            <form action="manage_personil.php?action=save" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="id_dosen" value="<?= htmlspecialchars($id) ?>">
              <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
              <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($foto) ?>">
              <input type="hidden" name="slug" id="slug" value="<?= htmlspecialchars($slug) ?>">

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-bold">Nama Lengkap</label>
                  <input type="text" id="nama" name="nama_dosen" class="form-control"
                         value="<?= htmlspecialchars($nama) ?>" required placeholder="Contoh: Dr. Budi Santoso, M.Kom">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-bold">Email</label>
                  <input type="email" name="email_dosen" class="form-control"
                         value="<?= htmlspecialchars($email) ?>" placeholder="email@polinema.ac.id">
                </div>
              </div>

              <?php if ($type === 'dosen'): ?>
                
                <div class="card bg-light border-0 mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold text-black mb-3"><i class="bi bi-person-badge me-2"></i>Informasi Akademik</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NIP</label>
                                <input type="text" class="form-control" name="nip" value="<?= $data['nip'] ?? '' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NIDN</label>
                                <input type="text" class="form-control" name="nidn" value="<?= $data['nidn'] ?? '' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jabatan</label>
                                <input type="text" class="form-control" name="jabatan" value="<?= $data['jabatan'] ?? '' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Foto Profil</label>
                                <input type="file" class="form-control" name="foto_profil">
                                <?php if (!empty($data['foto_profil'])): ?>
                                    <div class="mt-2">
                                        <small class="text-muted d-block mb-1">Foto saat ini:</small>
                                        <img src="../uploads_personil/<?= $data['foto_profil'] ?>" width="80" class="rounded border">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card bg-light border-0 mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold text-black mb-3"><i class="bi bi-mortarboard me-2"></i>Riwayat Pendidikan</h6>
                        <?php $riwayat_list = (isset($riwayat_list) && is_array($riwayat_list)) ? $riwayat_list : []; ?>
                        
                        <div id="riwayat-container">
                            <?php foreach ($riwayat_list as $r): ?>
                                <div class="row mb-2 riwayat-item">
                                    <div class="col-md-2 "><input type="text" class="form-control" name="jenjang[]" value="<?= htmlspecialchars($r['jenjang']) ?>" placeholder="Jenjang"></div>
                                    <div class="col-md-3"><input type="text" class="form-control" name="program_studi[]" value="<?= htmlspecialchars($r['program_studi']) ?>" placeholder="Prodi"></div>
                                    <div class="col-md-3"><input type="text" class="form-control" name="nama_kampus[]" value="<?= htmlspecialchars($r['nama_kampus']) ?>" placeholder="Kampus"></div>
                                    <div class="col-md-3"><input type="text" class="form-control" name="thn_lulus[]" value="<?= htmlspecialchars($r['thn_lulus']) ?>" placeholder="Lulus"></div>
                                    <div class="col-md-1"><button type="button" class="btn btn-danger w-100 remove-riwayat"><i class="bi bi-x-lg"></i></button></div>
                                </div>
                            <?php endforeach; ?>

                            <?php if (count($riwayat_list) == 0): ?>
                                <div class="row mb-2 riwayat-item">
                                    <div class="col-md-2"><input type="text" class="form-control" name="jenjang[]" placeholder="Jenjang (S1)"></div>
                                    <div class="col-md-3"><input type="text" class="form-control" name="program_studi[]" placeholder="Program Studi"></div>
                                    <div class="col-md-3"><input type="text" class="form-control" name="nama_kampus[]" placeholder="Nama Kampus"></div>
                                    <div class="col-md-3"><input type="text" class="form-control" name="thn_lulus[]" placeholder="Thn Lulus"></div>
                                    <div class="col-md-1"><button type="button" class="btn btn-danger w-100 remove-riwayat"><i class="bi bi-x-lg"></i></button></div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <button type="button" id="add-riwayat" class="btn btn-sm btn-secondary mt-2"><i class="bi bi-plus-circle me-1"></i> Tambah Riwayat</button>
                    </div>
                </div>

                <div class="card bg-light border-0 mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold text-black mb-3"><i class="bi bi-journal-text me-2"></i>Publikasi</h6>
                        <?php $publikasi_list = (isset($publikasi_list) && is_array($publikasi_list)) ? $publikasi_list : []; ?>
                        
                        <div id="publikasi-container">
                            <?php foreach($publikasi_list as $p): ?>
                            <div class="row mb-2 publikasi-item">
                                <div class="col-md-4"><input type="text" class="form-control" name="judul_pub[]" value="<?= $p['judul'] ?>" placeholder="Judul"></div>
                                <div class="col-md-3"><input type="text" class="form-control" name="tahun_pub[]" value="<?= $p['thn_terbit'] ?>" placeholder="Tahun"></div>
                                <div class="col-md-4"><input type="text" class="form-control" name="link_pub[]" value="<?= $p['link_publikasi'] ?>" placeholder="Link"></div>
                                <div class="col-md-1"><button type="button" class="btn btn-danger w-100 remove-pub"><i class="bi bi-x-lg"></i></button></div>
                            </div>
                            <?php endforeach ?>
                            
                            <?php if(count($publikasi_list)==0): ?>
                            <div class="row mb-2 publikasi-item">
                                <div class="col-md-4"><input type="text" class="form-control" name="judul_pub[]" placeholder="Judul Publikasi"></div>
                                <div class="col-md-3"><input type="text" class="form-control" name="tahun_pub[]" placeholder="Tahun Terbit"></div>
                                <div class="col-md-4"><input type="text" class="form-control" name="link_pub[]" placeholder="Link (URL)"></div>
                                <div class="col-md-1"><button type="button" class="btn btn-danger w-100 remove-pub"><i class="bi bi-x-lg"></i></button></div>
                            </div>
                            <?php endif ?>
                        </div>
                        <button type="button" id="add-pub" class="btn btn-sm btn-secondary mt-2"><i class="bi bi-plus-circle me-1"></i> Tambah Publikasi</button>
                    </div>
                </div>

                <div class="card bg-light border-0 mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold text-black mb-3"><i class="bi bi-book me-2"></i>Mata Kuliah Diampu</h6>
                        <?php
                        $kbm_list   = (isset($kbm_list)   && is_array($kbm_list))   ? $kbm_list   : [];
                        $all_matkul = (isset($all_matkul) && is_array($all_matkul)) ? $all_matkul : [];

                        if (count($kbm_list) === 0) {
                            $kbm_list[] = ['id_matkul' => null];
                        }
                        ?>
                        <div id="kbm-container">
                            <?php foreach ($kbm_list as $k): ?>
                                <?php $selectedId = $k['id_matkul'] ?? null; ?>
                                <div class="row mb-2 kbm-item">
                                    <div class="col-md-11">
                                        <select name="id_matkul[]" class="form-select">
                                            <option value="">-- Pilih Mata Kuliah --</option>
                                            <?php foreach ($all_matkul as $m): ?>
                                                <option value="<?= htmlspecialchars($m['id_matkul']) ?>"
                                                    <?= ($selectedId == $m['id_matkul']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($m['nama_matkul']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger w-100 remove-kbm"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" id="add-kbm" class="btn btn-sm btn-secondary mt-2"><i class="bi bi-plus-circle me-1"></i> Tambah KBM</button>
                    </div>
                </div>

              <?php else: ?>
                <div class="alert alert-warning">Form untuk tipe ini belum disiapkan.</div>
              <?php endif; ?>

              <div class="d-grid gap-2 d-md-block mt-4">
                  <button class="btn btn-success px-5" type="submit"><i class="bi bi-save me-1"></i> Simpan Data</button>
                  <a href="manage_personil.php" class="btn btn-light border">Batal</a>
              </div>
            </form>

          </div>
        </div>
    </div>

    <script>
    document.addEventListener('input', e => {
      if (e.target.id === 'nama') {
        const s = e.target.value.toLowerCase().trim()
          .replace(/[^a-z0-9\s]/g, '')
          .replace(/\s+/g, '-');
        const slug = document.getElementById('slug');
        if (slug) slug.value = s;
      }
    });

    document.addEventListener('click', e => {
      // Fungsi Helper untuk cek ID dan Class
      const targetIs = (id) => e.target.id === id || e.target.closest('#' + id);
      const targetClass = (cls) => e.target.classList.contains(cls) || e.target.closest('.' + cls);

      // --- ADD RIWAYAT ---
      if (targetIs('add-riwayat')) {
        document.querySelector('#riwayat-container').insertAdjacentHTML('beforeend', `
          <div class="row mb-2 riwayat-item">
            <div class="col-md-2"><input class="form-control" name="jenjang[]" placeholder="S1"></div>
            <div class="col-md-3"><input class="form-control" name="program_studi[]" placeholder="Program Studi"></div>
            <div class="col-md-3"><input class="form-control" name="nama_kampus[]" placeholder="Kampus"></div>
            <div class="col-md-3"><input class="form-control" name="thn_lulus[]" placeholder="Tahun"></div>
            <div class="col-md-1"><button type="button" class="btn btn-danger w-100 remove-riwayat"><i class="bi bi-x-lg"></i></button></div>
          </div>`);
      }
      // --- REMOVE RIWAYAT ---
      if (targetClass('remove-riwayat')) {
          e.target.closest('.riwayat-item').remove();
      }

      // --- ADD PUBLIKASI ---
      if (targetIs('add-pub')) {
        document.querySelector('#publikasi-container').insertAdjacentHTML('beforeend', `
          <div class="row mb-2 publikasi-item">
            <div class="col-md-4"><input class="form-control" name="judul_pub[]" placeholder="Judul"></div>
            <div class="col-md-3"><input type="number" class="form-control" name="tahun_pub[]" placeholder="Tahun"></div>
            <div class="col-md-4"><input class="form-control" name="link_pub[]" placeholder="Link"></div>
            <div class="col-md-1"><button type="button" class="btn btn-danger w-100 remove-pub"><i class="bi bi-x-lg"></i></button></div>
          </div>`);
      }
      // --- REMOVE PUBLIKASI ---
      if (targetClass('remove-pub')) {
          e.target.closest('.publikasi-item').remove();
      }

      // --- ADD KBM ---
      if (targetIs('add-kbm')) {
        // Generate opsi select dari PHP
        const opts = `<?php foreach ($all_matkul as $m): ?>
            <option value="<?= $m['id_matkul'] ?>"><?= htmlspecialchars($m['nama_matkul']) ?></option>
        <?php endforeach; ?>`;
        
        document.querySelector('#kbm-container').insertAdjacentHTML('beforeend', `
            <div class="row mb-2 kbm-item">
              <div class="col-md-11">
                <select name="id_matkul[]" class="form-control">
                  <option value="">-- Pilih Mata Kuliah --</option>${opts}
                </select>
              </div>
              <div class="col-md-1">
                <button type="button" class="btn btn-danger w-100 remove-kbm"><i class="bi bi-x-lg"></i></button>
              </div>
            </div>`);
      }
      // --- REMOVE KBM ---
      if (targetClass('remove-kbm')) {
          e.target.closest('.kbm-item').remove();
      }
    });
    </script>


<?php elseif ($action === 'form_member'): ?>

    <?php
    $id_member       = $member_detail['id_member']       ?? null;
    $nama            = $member_detail['nama']            ?? '';
    $nim             = $member_detail['nim']             ?? '';
    $link_portofolio = $member_detail['link_portofolio'] ?? ($member_detail['portofolio'] ?? '');
    ?>

    <div class="container-fluid py-4">
        <h2 class="fw-bold mb-3">
            <?= $id_member ? 'Edit Member' : 'Tambah Member'; ?>
        </h2>
        
        <div class="card card-admin shadow-sm" style="max-width: 600px;">
            <div class="card-body p-4">
                <form action="manage_personil.php?action=update_member" method="post">
                    <input type="hidden" name="id_member" value="<?= (int)$id_member; ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($nama); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">NIM</label>
                        <input type="text" name="nim" class="form-control" value="<?= htmlspecialchars($nim); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Link Portofolio</label>
                        <input type="url" name="link_portofolio" class="form-control" value="<?= htmlspecialchars($link_portofolio); ?>" placeholder="https://" required>
                        <div class="form-text">Masukkan link lengkap (diawali https://).</div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success px-4"><i class="bi bi-save me-1"></i> Simpan</button>
                        <a href="manage_personil.php" class="btn btn-light border ms-2">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php endif; ?>
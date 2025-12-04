<?php
/**
 * PENGGABUNGAN VIEWS PERSONIL
 * Pastikan variabel-variabel data ($dosen, $member, $riwayat_list, dll) 
 * sudah dikirim dari Controller sebelum memanggil file ini.
 */

// Ambil action dari URL, default ke 'list' jika kosong
$action = $_GET['action'] ?? 'list';
?>

<?php if ($action === 'list' || $action === 'delete_dosen' || $action === 'delete_member'): ?>
    
    <div class="container-fluid py-4" id="personil-admin">
        <h2 class="fw-bold mb-4">Dosen</h2>
        
        <div class="d-grid d-md-block mb-3">
            <a href="manage_personil.php?action=form" class="btn btn-primary-admin px-4">
                <i class="bi bi-plus-lg me-2"></i>Tambah Dosen
            </a>
        </div>

        <?php if (!isset($dosen) || !$dosen || pg_num_rows($dosen) === 0): ?>
            <p class="text-muted">Belum ada data dosen.</p>
        <?php else: ?>
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama Dosen</th>
                        <th>Jabatan</th>
                        <th style="width:180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($d = pg_fetch_assoc($dosen)): ?>
                        <?php
                            $id_dosen = $d['id_dosen'] ?? null;
                            $nama     = $d['nama_dosen'] ?? '';
                            $jabatan  = $d['jabatan'] ?? '';
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($nama); ?></td>
                            <td><?= htmlspecialchars($jabatan); ?></td>
                            <td>
                                <?php if ($id_dosen): ?>
                                    <a href="manage_personil.php?action=form&id=<?= (int)$id_dosen; ?>" class="btn btn-sm btn-success me-2">Edit</a>
                                    <a href="manage_personil.php?action=delete_dosen&id=<?= (int)$id_dosen; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus dosen ini?');">Delete</a>
                                <?php else: ?>
                                    <span class="text-muted small">ID tidak valid</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <h2 class="fw-bold mt-5 mb-4">Member</h2>

        <?php if (!isset($member) || !$member || pg_num_rows($member) === 0): ?>
            <p class="text-muted">Belum ada data member.</p>
        <?php else: ?>
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Jurusan</th>
                        <th style="width:180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($m = pg_fetch_assoc($member)): ?>
                        <?php
                            $id_member = $m['id_pendaftaran_user'] ?? null;
                            $nama_m    = $m['nama'] ?? '';
                            $nim_m     = $m['nim'] ?? '';
                            $jurusan_m = $m['jurusan'] ?? '';
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($nama_m); ?></td>
                            <td><?= htmlspecialchars($nim_m); ?></td>
                            <td><?= htmlspecialchars($jurusan_m); ?></td>
                           <td>
                            <?php if ($id_member): ?>
                                <a href="manage_personil.php?action=form_member&id=<?= (int)$id_member; ?>" class="btn btn-sm btn-success me-2">Edit</a>
                                <a href="manage_personil.php?action=delete_member&id=<?= (int)$id_member; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus member ini?');">Delete</a>
                            <?php else: ?>
                                <span class="text-muted small">ID tidak valid</span>
                            <?php endif; ?>
                        </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>


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

    <h1 class="fw-bold"><?= htmlspecialchars($title) ?></h1>
    <a href="manage_personil.php" class="btn btn-outline-secondary mb-3">« Kembali</a>

    <div class="card card-admin">
      <div class="card-body p-4">

        <form action="manage_personil.php?action=save" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id_dosen" value="<?= htmlspecialchars($id) ?>">
          <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
          <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($foto) ?>">
          <input type="hidden" name="slug" id="slug" value="<?= htmlspecialchars($slug) ?>">

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Nama Lengkap</label>
              <input type="text" id="nama" name="nama_dosen" class="form-control"
                     value="<?= htmlspecialchars($nama) ?>" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Email</label>
              <input type="email" name="email_dosen" class="form-control"
                     value="<?= htmlspecialchars($email) ?>">
            </div>
          </div>

          <?php if ($type === 'dosen'): ?>
            
            <div class="p-3 bg-light rounded mb-3">
                <h6 class="fw-bold text-black">Informasi Akademik Dosen</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>NIP</label>
                        <input type="text" class="form-control" name="nip" value="<?= $data['nip'] ?? '' ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>NIDN</label>
                        <input type="text" class="form-control" name="nidn" value="<?= $data['nidn'] ?? '' ?>">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Jabatan</label>
                        <input type="text" class="form-control" name="jabatan" value="<?= $data['jabatan'] ?? '' ?>">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Foto Profil</label>
                        <input type="file" class="form-control" name="foto_profil">
                        <?php if (!empty($data['foto_profil'])): ?>
                            <img src="../uploads_personil/<?= $data['foto_profil'] ?>" width="120" class="mt-2">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="p-3 bg-light rounded mb-3">
                <h6 class="fw-bold text-black mb-3">Riwayat Pendidikan</h6>
                <?php
                $riwayat_list = (isset($riwayat_list) && is_array($riwayat_list)) ? $riwayat_list : [];
                ?>
                <div id="riwayat-container">
                    <?php foreach ($riwayat_list as $r): ?>
                        <div class="row mb-2 riwayat-item">
                            <div class="col-md-2 "><input type="text" class="form-control" name="jenjang[]" value="<?= htmlspecialchars($r['jenjang']) ?>"></div>
                            <div class="col-md-3"><input type="text" class="form-control" name="program_studi[]" value="<?= htmlspecialchars($r['program_studi']) ?>"></div>
                            <div class="col-md-3"><input type="text" class="form-control" name="nama_kampus[]" value="<?= htmlspecialchars($r['nama_kampus']) ?>"></div>
                            <div class="col-md-3"><input type="text" class="form-control" name="thn_lulus[]" value="<?= htmlspecialchars($r['thn_lulus']) ?>"></div>
                            <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm remove-riwayat">X</button></div>
                        </div>
                    <?php endforeach; ?>

                    <?php if (count($riwayat_list) == 0): ?>
                        <div class="row mb-2 riwayat-item">
                            <div class="col-md-2"><input type="text" class="form-control" name="jenjang[]" placeholder="Jenjang"></div>
                            <div class="col-md-3"><input type="text" class="form-control" name="program_studi[]" placeholder="Program Studi"></div>
                            <div class="col-md-3"><input type="text" class="form-control" name="nama_kampus[]" placeholder="Kampus"></div>
                            <div class="col-md-3"><input type="text" class="form-control" name="thn_lulus[]" placeholder="Tahun Lulus"></div>
                            <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm remove-riwayat">X</button></div>
                        </div>
                    <?php endif; ?>
                </div>
                <button type="button" id="add-riwayat" class="fw-bold bg-black text-white btn btn-secondary btn-sm mt-2">+ Tambah Riwayat</button>
            </div>
            <div class="p-3 bg-light rounded mb-3">
                <h6 class="fw-bold text-black">Publikasi</h6>
                <?php
                $publikasi_list = (isset($publikasi_list) && is_array($publikasi_list)) ? $publikasi_list : [];
                ?>
                <div id="publikasi-container">
                    <?php foreach($publikasi_list as $p): ?>
                    <div class="row mb-2 publikasi-item">
                        <div class="col-md-4"><input type="text" class="form-control" name="judul_pub[]" value="<?= $p['judul'] ?>"></div>
                        <div class="col-md-3"><input type="text" class="form-control" name="tahun_pub[]" value="<?= $p['thn_terbit'] ?>"></div>
                        <div class="col-md-4"><input type="text" class="form-control" name="link_pub[]" value="<?= $p['link_publikasi'] ?>"></div>
                        <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm remove-pub">X</button></div>
                    </div>
                    <?php endforeach ?>
                    
                    <?php if(count($publikasi_list)==0): ?>
                    <div class="row mb-2 publikasi-item">
                        <div class="col-md-4"><input type="text" class="form-control" name="judul_pub[]" placeholder="Judul"></div>
                        <div class="col-md-3"><input type="text" class="form-control" name="tahun_pub[]" placeholder="Tahun Terbit"></div>
                        <div class="col-md-4"><input type="text" class="form-control" name="link_pub[]" placeholder="Link"></div>
                        <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm remove-pub">X</button></div>
                    </div>
                    <?php endif ?>
                </div>
                <button type="button" id="add-pub" class="fw-bold bg-black text-white btn btn-secondary btn-sm mt-2">+ Tambah Publikasi</button>
            </div>
            <div class="p-3 bg-light rounded mb-3">
                <h6 class="fw-bold text-black">Mata Kuliah Diampu</h6>
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
                                    <option value=""> Pilih Mata Kuliah </option>
                                    <?php foreach ($all_matkul as $m): ?>
                                        <option value="<?= htmlspecialchars($m['id_matkul']) ?>"
                                            <?= ($selectedId == $m['id_matkul']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($m['nama_matkul']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-1 d-flex">
                                <button type="button" class="h-10 w-10 self-center btn btn-danger btn-sm ms-1 remove-kbm">X</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" id="add-kbm" class="fw-bold bg-black text-white btn btn-secondary btn-sm mt-2">
                    + Tambah KBM
                </button>
            </div>
            <?php else: ?>
            <p>Form untuk tipe ini belum disiapkan.</p>
          <?php endif; ?>

          <button class="btn btn-primary mt-3" type="submit">Simpan</button>
        </form>

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
      const add = id => e.target.id === id;
      const rm  = cls => e.target.classList.contains(cls);

      // JS untuk Riwayat
      if (add('add-riwayat')) {
        const c = document.querySelector('#riwayat-container');
        if (c) c.insertAdjacentHTML('beforeend', `
          <div class="row mb-2 riwayat-item">
            <div class="col-md-2"><input class="form-control" name="jenjang[]" placeholder="S1"></div>
            <div class="col-md-3"><input class="form-control" name="program_studi[]" placeholder="Program Studi"></div>
            <div class="col-md-3"><input class="form-control" name="nama_kampus[]" placeholder="Kampus"></div>
            <div class="col-md-3"><input class="form-control" name="thn_lulus[]" placeholder="Tahun Lulus"></div>
            <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm remove-riwayat">X</button></div>
          </div>`);
      }
      if (rm('remove-riwayat')) e.target.closest('.riwayat-item')?.remove();

      // JS untuk Publikasi
      if (add('add-pub')) {
        const c = document.querySelector('#publikasi-container');
        if (c) c.insertAdjacentHTML('beforeend', `
          <div class="row mb-2 publikasi-item">
            <div class="col-md-4"><input class="form-control" name="judul_pub[]" placeholder="Judul"></div>
            <div class="col-md-3"><input type="number" class="form-control" name="tahun_pub[]" placeholder="Tahun"></div>
            <div class="col-md-4"><input class="form-control" name="link_pub[]" placeholder="Link Publikasi"></div>
            <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm remove-pub">X</button></div>
          </div>`);
      }
      if (rm('remove-pub')) e.target.closest('.publikasi-item')?.remove();

      // JS untuk KBM
      if (add('add-kbm')) {
        const c = document.querySelector('#kbm-container');
        if (c) {
          // Kita pakai PHP untuk generate opsi select di dalam JS template string
          const opts = `<?php foreach ($all_matkul as $m): ?>
            <option value="<?= $m['id_matkul'] ?>"><?= htmlspecialchars($m['nama_matkul']) ?></option>
          <?php endforeach; ?>`;
          
          c.insertAdjacentHTML('beforeend', `
            <div class="row mb-2 kbm-item">
              <div class="col-md-11">
                <select name="id_matkul[]" class="form-control">
                  <option value="">-- Pilih Mata Kuliah --</option>${opts}
                </select>
              </div>
              <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-kbm">X</button>
              </div>
            </div>`);
        }
      }
      if (rm('remove-kbm')) e.target.closest('.kbm-item')?.remove();
    });
    </script>


<?php elseif ($action === 'form_member'): ?>

    <?php
    // dari controller sudah dikirim $member_detail
    $id_member       = $member_detail['id_member']       ?? null;
    $nama            = $member_detail['nama']            ?? '';
    $nim             = $member_detail['nim']             ?? '';
    $link_portofolio = $member_detail['link_portofolio'] ?? ($member_detail['portofolio'] ?? '');
    ?>

    <div class="container py-4">
        <h2 class="fw-bold mb-4">
            <?= $id_member ? 'Edit Member' : 'Tambah Member'; ?>
        </h2>

        <form action="manage_personil.php?action=update_member" method="post">
            <input type="hidden" name="id_member" value="<?= (int)$id_member; ?>">

            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($nama); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">NIM</label>
                <input type="text" name="nim" class="form-control" value="<?= htmlspecialchars($nim); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Link Portofolio</label>
                <input type="url" name="link_portofolio" class="form-control" value="<?= htmlspecialchars($link_portofolio); ?>" placeholder="https://contoh.com/portofolio" required>
            </div>

            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            <a href="manage_personil.php" class="btn btn-secondary ms-2">Batal</a>
        </form>
    </div>

<?php endif; ?>
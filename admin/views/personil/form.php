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
      <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
      <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
      <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($foto) ?>">
      <input type="hidden" name="slug" id="slug" value="<?= htmlspecialchars($slug) ?>">

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Nama Lengkap</label>
          <input type="text" id="nama" name="nama" class="form-control"
                 value="<?= htmlspecialchars($nama) ?>" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Email</label>
          <input type="email" name="email" class="form-control"
                 value="<?= htmlspecialchars($email) ?>">
        </div>
      </div>

      <?php if ($type === 'dosen'): ?>
        <?php include __DIR__ . '/form_dosen.php'; ?>
        <?php include __DIR__ . '/form_riwayat.php'; ?>
        <?php include __DIR__ . '/form_publikasi.php'; ?>
        <?php include __DIR__ . '/form_kbm.php'; ?>
      <?php else: ?>
        <p>Form untuk tipe ini belum disiapkan.</p>
      <?php endif; ?>

      <button class="btn btn-primary-admin mt-3" type="submit">Simpan</button>
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

  if (add('add-kbm')) {
    const c = document.querySelector('#kbm-container');
    if (c) {
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






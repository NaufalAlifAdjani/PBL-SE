<?php include 'includes/header_admin.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="fw-bold"><?= $page_title ?></h1>
    <a href="manage_portofolio.php" class="btn btn-outline-secondary">&laquo; Kembali</a>
</div>

<div class="card card-admin shadow-sm">
    <div class="card-body p-4">
        <form action="manage_portofolio.php?action=save" method="POST" enctype="multipart/form-data">
            
            <input type="hidden" name="id" value="<?= $data['id_portofolio'] ?? '' ?>">
            
            <div class="row mb-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Judul Karya</label>
                    <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($data['judul'] ?? '') ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tahun</label>
                    <input type="number" name="tahun" class="form-control" value="<?= htmlspecialchars($data['tahun'] ?? date('Y')) ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kategori</label>
                    <select name="kategori" class="form-select" required>
                        <?php $cat = $data['kategori'] ?? ''; ?>
                        <option value="publikasi" <?= $cat == 'publikasi' ? 'selected' : '' ?>>Publikasi Ilmiah</option>
                        <option value="produk" <?= $cat == 'produk' ? 'selected' : '' ?>>Produk Inovasi</option>
                        <option value="penelitian" <?= $cat == 'penelitian' ? 'selected' : '' ?>>Penelitian</option>
                        <option value="pengabdian" <?= $cat == 'pengabdian' ? 'selected' : '' ?>>Pengabdian Masyarakat</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Link Eksternal</label>
                    <input type="url" name="link_eksternal" class="form-control" value="<?= htmlspecialchars($data['link_eksternal'] ?? '') ?>" placeholder="https://...">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Penulis Utama</label>
                <input type="text" name="penulis" class="form-control" value="<?= $data['penulis'] ?? '' ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Anggota Tim (Opsional)</label>
                <input type="text" name="penulis_anggota" class="form-control" 
                    value="<?= $data['penulis_anggota'] ?? '' ?>" 
                    placeholder="Contoh: Budi, Siti, Asep">
                <div class="form-text text-muted">Pisahkan nama anggota dengan koma.</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="5"><?= htmlspecialchars($data['deskripsi'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Gambar</label>
                <?php if (!empty($data['gambar'])): ?>
                    <div class="mb-2">
                        <img src="../uploads/portofolio/<?= $data['gambar'] ?>" alt="Preview" style="height: 100px;" class="rounded border">
                        <div class="form-text text-primary">Gambar saat ini. Biarkan kosong jika tidak ingin mengubah.</div>
                    </div>
                <?php endif; ?>
                <input type="file" name="gambar" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary-admin">Simpan</button>
        </form>
    </div>
</div>

<?php include 'includes/footer_admin.php'; ?>
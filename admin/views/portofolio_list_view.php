<?php include 'includes/header_admin.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-0"><?= $page_title ?></h2>
        <p class="text-muted small mb-0">Kelola semua data portofolio Anda di sini.</p>
    </div>
    <a href="manage_portofolio.php?action=add" class="btn btn-primary-admin shadow-sm">
        <i class="bi bi-plus-lg"></i> Tambah Data
    </a>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body p-3">
        <form method="GET" action="manage_portofolio.php">
            <div class="row g-2">
                <div class="col-md-3">
                    <select name="kategori" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        <?php foreach($list_kategori as $cat): ?>
                            <option value="<?= $cat['kategori'] ?>" 
                                <?= ($filters['kategori'] == $cat['kategori']) ? 'selected' : '' ?>>
                                <?= ucfirst($cat['kategori']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        <?php foreach($list_tahun as $thn): ?>
                            <option value="<?= $thn['tahun'] ?>" 
                                <?= ($filters['tahun'] == $thn['tahun']) ? 'selected' : '' ?>>
                                <?= $thn['tahun'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <?php if(!empty($filters['kategori']) || !empty($filters['tahun']) || !empty($filters['search'])): ?>
                        <a href="manage_portofolio.php" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" 
                               placeholder="Cari judul/penulis..." 
                               value="<?= htmlspecialchars($filters['search']) ?>">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light sticky-top">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="10%">Gambar</th>
                        <th width="35%">Judul & Tahun</th>
                        <th width="20%">Kategori</th>
                        <th width="15%">Penulis</th>
                        <th width="15%" class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if(!empty($portofolios)):
                        foreach ($portofolios as $row) : 
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td>
                            <?php if($row['gambar']): ?>
                                <img src="../uploads/portofolio/<?= $row['gambar'] ?>" class="rounded border" style="width: 48px; height: 48px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted small" style="width: 48px; height: 48px;">N/A</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-bold text-dark text-truncate" style="max-width: 250px;" title="<?= $row['judul'] ?>">
                                <?= $row['judul'] ?>
                            </div>
                            <small class="text-muted"><i class="bi bi-calendar-event me-1"></i><?= $row['tahun'] ?></small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border fw-normal"><?= ucfirst($row['kategori']) ?></span>
                        </td>
                        <td><small class="text-muted"><?= $row['penulis'] ?></small></td>
                        <td class="text-end pe-4">
                            <div class="btn-group" role="group">
                                <a href="manage_portofolio.php?action=edit&id=<?= $row['id_portofolio'] ?>" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="manage_portofolio.php?action=delete&id=<?= $row['id_portofolio'] ?>" 
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada data ditemukan dengan filter tersebut.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer_admin.php'; ?>
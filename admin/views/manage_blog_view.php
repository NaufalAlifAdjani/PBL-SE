<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="fw-bold fs-3">Manage Articles</h1>
</div>

<div class="card card-admin mb-4">
    <div class="card-body p-3">
        <form method="GET" action="">
            <div class="row g-2 align-items-center">
                <div class="col-md-4 me-auto">
                    <a href="blog_form.php" class="btn btn-primary text-white">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Baru
                    </a>
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Semua Status --</option>
                        <option value="Published" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Published') ? 'selected' : ''; ?>>Published</option>
                        <option value="Draft" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Draft') ? 'selected' : ''; ?>>Draft</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Cari judul artikel..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                        <button class="btn btn-secondary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card card-admin shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3">Judul Artikel</th>
                        <th>Penulis</th>
                        <th>Status</th>
                        <th>Tanggal Update</th>
                        <th class="text-center px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($artikel)): ?>
                        <?php foreach ($artikel as $row): ?>
                            <tr>
                                <td class="px-4">
                                    <h6 class="fw-bold mb-1 text-dark text-decoration-none">
                                        <?php echo substr(strip_tags($row['isi_konten']), 0, 50) . '...';?>
                                    </h6>
                                    <small class="text-muted">
                                        <?php echo substr(strip_tags($row['isi_konten']), 0, 30) . '...'; ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="fw-semibold text-secondary">
                                        <?php echo htmlspecialchars($row['username'] ?? 'Unknown'); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill <?php echo ($row['status_artikel'] == 'Published') ? 'bg-success' : 'bg-secondary'; ?>">
                                        <?php echo htmlspecialchars($row['status_artikel']); ?>
                                    </span>
                                </td>
                                <td class="text-muted">
                                    <small><i class="bi bi-clock me-1"></i> <?php echo date('d M Y', strtotime($row['tgl_diperbarui'])); ?></small>
                                </td>
                                <td class="text-end px-4">
                                    <a href="blog_form.php?id=<?php echo $row['id_artikel']; ?>" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="blog_delete.php?id=<?php echo $row['id_artikel']; ?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Hapus artikel ini?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada artikel yang cocok.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
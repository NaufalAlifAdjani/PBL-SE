<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="fw-bold">Manage Articles</h1>
    <a href="blog_form.php" class="btn btn-outline-dark"><i class="bi bi-plus-circle"></i> Tambah Artikel</a>
</div>

<?php if (!empty($artikel)): ?>
    <?php foreach ($artikel as $row): ?>
        <div class="card card-admin mb-3 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="fw-semibold">
                            <?php echo htmlspecialchars($row['judul']); ?>
                        </h5>
                        <p class="text-muted mb-1">
                            <?php echo substr(strip_tags($row['isi_konten']), 0, 120) . '...'; ?>
                        </p>
                        <small class="text-muted">
                            Penulis: <?php echo htmlspecialchars($row['username'] ?? 'Unknown'); ?> &nbsp;&bull;&nbsp;
                            Status:
                            <span class="badge <?php echo ($row['status_artikel'] == 'Published') ? 'bg-success' : 'bg-secondary'; ?>">
                                <?php echo htmlspecialchars($row['status_artikel']); ?>
                            </span>
                            &nbsp;&bull;&nbsp;
                            Update: <?php echo date('d M Y H:i', strtotime($row['tgl_diperbarui'])); ?>
                        </small>
                    </div>
                    <div class="d-flex align-items-center">
                        <a href="blog_form.php?id=<?php echo $row['id_artikel']; ?>" class="btn btn-primary btn-sm me-2 text-white">
                            <i class="bi bi-pen-fill"></i> Edit
                        </a>
                        <a href="blog_delete.php?id=<?php echo $row['id_artikel']; ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Hapus artikel ini?');">
                            <i class="bi bi-trash-fill"></i> Remove
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="alert alert-info">Belum ada artikel. Silakan tambah baru.</div>
<?php endif; ?>

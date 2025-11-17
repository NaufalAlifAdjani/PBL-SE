<?php
    $activePage = 'blog';
    include 'includes/header_admin.php';
    include '../includes/db.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Manage Blog Posts</h1>
    <a href="blog_form.php" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i> Tambah Artikel Baru
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Judul</th>
                        <th scope="col">Status</th>
                        <th scope="col">Tanggal Dibuat</th>
                        <th scope="col" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        // Ambil semua data dari tabel 'artikel'
                        $sql = "SELECT id_artikel, judul, status_artikel, tgl_dibuat
                                FROM artikel
                                ORDER BY tgl_dibuat DESC";
                        $stmt = $conn->query($sql);

                        $count = 1;
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <th scope="row"><?php echo $count++; ?></th>
                        <td><?php echo htmlspecialchars($row['judul']); ?></td>
                        <td>
                            <?php if ($row['status_artikel'] == 'Published'): ?>
                                <span class="badge bg-success">Published</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('d M Y', strtotime($row['tgl_dibuat'])); ?></td>
                        <td class="text-end">
                            <a href="blog_form.php?id=<?php echo $row['id_artikel']; ?>" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-fill"></i> Edit
                            </a>
                            <a href="blog_delete.php?id=<?php echo $row['id_artikel']; ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin ingin menghapus artikel ini?');">
                                <i class="bi bi-trash-fill"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php
                        } // Akhir while loop
                    } catch (PDOException $e) {
                        echo "<tr><td colspan='5' class='text-center'>Gagal mengambil data: " . $e->getMessage() . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
    include 'includes/footer_admin.php';
?>

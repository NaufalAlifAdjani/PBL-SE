<?php
include 'includes/header_admin.php'; 
include '../includes/db.php'; 

// Query untuk mengambil semua data dari tabel Profile
$result = pg_query($conn, "SELECT id, title, slug, menu_group, is_published, updated_at 
                           FROM Profile 
                           ORDER BY display_order ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="fw-bold">Kelola Halaman Profile</h1>
    <a href="profile_form.php" class="btn btn_primary-admin"><i class="bi bi-plus-circle"></i> Tambah Halaman</a>
</div>
<p class="text-muted">Tambah, edit, dan hapus halaman statis di menu profile.</p>

<div class="card card-admin">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Judul Halaman</th>
                    <th>Slug</th>
                    <th>Grup Menu</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && pg_num_rows($result) > 0) {
                    while($row = pg_fetch_assoc($result)) {
                ?>
                <tr>
                    <td class="fw-semibold"><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['slug']); ?></td>
                    <td><?php echo htmlspecialchars($row['menu_group']); ?></td>
                    <td>
                        <?php if ($row['is_published'] == 't'): // 't' adalah true di postgres ?>
                            <span class="badge bg-success">Published</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Draft</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="profile_form.php?id=<?php echo $row['id']; ?>" class="btn btn-action-edit me-2"><i class="bi bi-pencil-fill"></i> Edit</a>
                        <a href="profile_delete.php?id=<?php echo $row['id']; ?>" class="btn btn-action-delete" onclick="return confirm('Yakin ingin menghapus halaman ini?')"><i class="bi bi-trash-fill"></i> Hapus</a>
                    </td>
                </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>Belum ada data.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer_admin.php'; ?>
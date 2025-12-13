<?php
// admin/views/manage_profile_view.php

// Header biasanya di-include di View atau dipanggil lewat Controller. 
// Karena path relative terhadap entry point (manage_profile.php), maka path ini benar:
include 'includes/header_admin.php'; 
?>

<div class="mb-4">
    <div class="mb-3">
        <h1 class="fw-bold mb-1" style="font-size: 1.75rem;">Kelola Halaman Profile</h1>
        <p class="text-muted mb-0">Tambah, edit, dan hapus halaman statis di menu profile.</p>
    </div>

    <div class="d-grid d-md-block">
        <a href="manage_profile.php?action=add" class="btn btn-primary-admin px-4">
            <i class="bi bi-plus-lg me-2"></i>Tambah Halaman
        </a>
    </div>
</div>

<div class="card card-admin">
    <div class="card-body p-0"> 
        <div class="table-responsive">
            <table class="table table-hover align-middle text-nowrap mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3">Judul Halaman</th>
                        <th class="px-4 py-3">Slug</th>
                        <th class="px-4 py-3">Grup Menu</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // $result dikirim dari ProfileController->index()
                    if ($result && pg_num_rows($result) > 0) {
                        foreach ($profiles as $row) {
                    ?>
                    <tr>
                        <td class="px-4 fw-semibold text-dark">
                            <?php echo htmlspecialchars($row['title']); ?>
                        </td>
                        <td class="px-4 text-muted">
                            /<?php echo htmlspecialchars($row['slug']); ?>
                        </td>
                        <td class="px-4">
                            <span class="badge bg-light text-dark border">
                                <?php echo htmlspecialchars($row['menu_group']); ?>
                            </span>
                        </td>
                        <td class="px-4">
                            <?php if ($row['is_published'] == 't'): ?>
                                <span class="badge bg-success text-white px-3 py-2 rounded-pill">Published</span>
                            <?php else: ?>
                                <span class="badge bg-secondary text-secondary px-3 py-2 rounded-pill">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end px-4">
                            <a href="manage_profile.php?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-success me-1"><i class="bi bi-pencil-square"></i></a>
                            <a href="manage_profile.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus dosen ini?');"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center py-5 text-muted'>Belum ada data halaman.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div> 
    </div>
</div>

<?php include 'includes/footer_admin.php'; ?>



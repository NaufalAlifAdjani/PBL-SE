<?php
include 'includes/header_admin.php'; 
include '../includes/db.php'; 

// Query data
$result = pg_query($conn, "SELECT id, title, slug, menu_group, is_published, updated_at 
                           FROM Profile 
                           ORDER BY display_order ASC");
?>

<div class="mb-4">
    <div class="mb-3">
        <h1 class="fw-bold mb-1" style="font-size: 1.75rem;">Kelola Halaman Profile</h1>
        <p class="text-muted mb-0">Tambah, edit, dan hapus halaman statis di menu profile.</p>
    </div>

    <div class="d-grid d-md-block">
        <a href="profile_form.php" class="btn btn-primary-admin px-4">
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
                    if ($result && pg_num_rows($result) > 0) {
                        while($row = pg_fetch_assoc($result)) {
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
                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Published</span>
                            <?php else: ?>
                                <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 text-end">
                            <div class="d-inline-flex gap-2">
                                <a href="profile_form.php?id=<?php echo $row['id']; ?>" class="btn btn-action-edit" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="profile_delete.php?id=<?php echo $row['id']; ?>" class="btn btn-action-delete btn-delete" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
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

<script>
    // 1. Cek Parameter URL (?status=...)
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    // Jika ada status, tampilkan Alert
    if (status) {
        if (status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data halaman berhasil disimpan.',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (status === 'deleted') {
            Swal.fire({
                icon: 'success',
                title: 'Dihapus!',
                text: 'Data halaman berhasil dihapus.',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (status === 'error') {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat memproses data.',
            });
        }

        // --- BAGIAN BARU: BERSIHKAN URL ---
        // Hapus parameter ?status=... dari address bar tanpa refresh halaman
        const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
        window.history.replaceState({ path: newUrl }, '', newUrl);
    }

    // 2. Konfirmasi Hapus dengan SweetAlert
    const deleteButtons = document.querySelectorAll('.btn-delete');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); 
            const href = this.getAttribute('href'); 

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });
    });
</script>
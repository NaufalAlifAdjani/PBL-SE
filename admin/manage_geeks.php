<?php
include 'includes/header_admin.php'; 
include '../includes/db.php'; 

// Query ambil semua data, urutkan Pending paling atas
$query = "SELECT * FROM pendaftaran_user 
          ORDER BY CASE WHEN status = 'Pending' THEN 1 ELSE 2 END, id_pendaftaran_user DESC";
$result = pg_query($conn, $query);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="fw-bold">Kelola Pendaftaran SE Geeks</h1>
</div>
<p class="text-muted">Review pendaftar masuk dan kelola anggota komunitas.</p>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($_GET['msg']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card card-admin">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Nama & Email</th>
                        <th>Info Akademik</th>
                        <th>Portofolio</th>
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
                        <td>
                            <div class="fw-bold"><?php echo htmlspecialchars($row['nama']); ?></div>
                            <small class="text-muted"><?php echo htmlspecialchars($row['email']); ?></small>
                        </td>
                        <td>
                            <div><?php echo htmlspecialchars($row['jurusan']); ?></div>
                            <small class="text-muted">Angkatan: <?php echo htmlspecialchars($row['angkatan']); ?></small>
                        </td>
                        <td>
                            <?php if($row['portofolio']): ?>
                                <a href="<?php echo htmlspecialchars($row['portofolio']); ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-link-45deg"></i> Link
                                </a>
                            <?php else: ?> - <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row['status'] == 'Pending'): ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php elseif ($row['status'] == 'Diterima'): ?>
                                <span class="badge bg-success">Diterima</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Ditolak</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <?php if ($row['status'] != 'Diterima'): ?>
                                    <a href="geeks_action.php?action=approve&id=<?php echo $row['id_pendaftaran_user']; ?>" class="btn btn-sm btn-success" title="Terima">
                                        <i class="bi bi-check-lg"></i>
                                    </a>
                                <?php endif; ?>

                                <?php if ($row['status'] == 'Pending'): ?>
                                    <a href="geeks_action.php?action=reject&id=<?php echo $row['id_pendaftaran_user']; ?>" class="btn btn-sm btn-warning" title="Tolak">
                                        <i class="bi bi-x-lg"></i>
                                    </a>
                                <?php endif; ?>

                                <a href="geeks_action.php?action=delete&id=<?php echo $row['id_pendaftaran_user']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini permanen?')" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>Belum ada data pendaftar.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer_admin.php'; ?>
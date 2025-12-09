<?php 
// Helper View: Fungsi Pagination Link
function getLink($newPage, $currentAksi, $currentTgl) {
    return "?page=$newPage&filter_aksi=$currentAksi&filter_tgl=$currentTgl";
}

// Header sudah di-include di main entry, tapi jika layout menuntut include di sini, silakan sesuaikan.
// Kita asumsikan header_admin.php sudah dimuat SEBELUM masuk ke controller di main entry
// atau kita include manual di sini jika struktur project menuntutnya.
// include 'includes/header_admin.php'; 
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div>
            <h1 class="h2">Activity Logs</h1>
            <p class="text-muted">Pantau riwayat aktivitas dan perubahan data sistem.</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="activity_logs.php" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" action="activity_logs.php">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Filter Aksi</label>
                        <select name="filter_aksi" class="form-select form-select-sm">
                            <option value="">Semua Aksi</option>
                            <option value="CREATE" <?= $filter_aksi == 'CREATE' ? 'selected' : '' ?>>CREATE (Tambah)</option>
                            <option value="UPDATE" <?= $filter_aksi == 'UPDATE' ? 'selected' : '' ?>>UPDATE (Ubah)</option>
                            <option value="DELETE" <?= $filter_aksi == 'DELETE' ? 'selected' : '' ?>>DELETE (Hapus)</option>
                            <option value="LOGIN"  <?= $filter_aksi == 'LOGIN'  ? 'selected' : '' ?>>LOGIN</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Tanggal</label>
                        <input type="date" name="filter_tgl" class="form-control form-control-sm" value="<?= htmlspecialchars($filter_tgl) ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary-admin btn-sm w-100 h-50">
                            <i class="bi bi-filter"></i> Terapkan
                        </button>
                    </div>
                     <div class="col-md-1">
                        <a href="activity_logs.php" class="btn btn-light btn-sm w-100 border">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" width="15%">Waktu</th>
                            <th width="18%">Admin</th>
                            <th width="10%">Aksi</th>
                            <th width="15%">Target</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (pg_num_rows($logs) > 0): ?>
                            <?php while($row = pg_fetch_assoc($logs)): 
                                $badgeClass = match($row['aksi']) {
                                    'CREATE' => 'bg-success-subtle text-success border-success-subtle',
                                    'UPDATE' => 'bg-primary-subtle text-primary border-primary-subtle',
                                    'DELETE' => 'bg-danger-subtle text-danger border-danger-subtle',
                                    'LOGIN'  => 'bg-info-subtle text-info border-info-subtle',
                                    default  => 'bg-secondary-subtle text-secondary'
                                };
                                $phpDate = strtotime($row['created_at']);
                                $username = !empty($row['username']) ? $row['username'] : 'System';
                                $initial = strtoupper(substr($username, 0, 2));
                                $avatarColor = !empty($row['username']) ? 'bg-primary' : 'bg-secondary';
                            ?>
                            <tr>
                                <td class="ps-4 text-muted small">
                                    <i class="bi bi-calendar3 me-1"></i> <?= date('d M Y', $phpDate) ?><br>
                                    <i class="bi bi-clock me-1"></i> <?= date('H:i', $phpDate) ?> WIB
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle <?= $avatarColor ?> text-white me-2 small d-flex justify-content-center align-items-center" style="width: 32px; height: 32px; border-radius: 50%; font-size: 10px;">
                                            <?= $initial ?>
                                        </div>
                                        <span class="fw-medium text-dark"><?= htmlspecialchars($username) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill border <?= $badgeClass ?> px-3">
                                        <?= htmlspecialchars($row['aksi']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?= $row['tabel_target'] ? '<span class="badge bg-light text-secondary border">'.strtoupper(htmlspecialchars($row['tabel_target'])).'</span>' : '<span class="text-muted">-</span>' ?>
                                </td>
                                <td class="text-secondary">
                                    <?= htmlspecialchars($row['keterangan']) ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Belum ada aktivitas yang tercatat.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($totalPages > 1): ?>
            <div class="card-footer bg-white border-top-0 py-3">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end mb-0 pagination-sm">
                        <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= getLink($currentPage - 1, $filter_aksi, $filter_tgl) ?>">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <?php if ($i == 1 || $i == $totalPages || ($i >= $currentPage - 1 && $i <= $currentPage + 1)): ?>
                                <li class="page-item <?= ($currentPage == $i) ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= getLink($i, $filter_aksi, $filter_tgl) ?>"><?= $i ?></a>
                                </li>
                            <?php elseif ($i == 2 || $i == $totalPages - 1): ?>
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= getLink($currentPage + 1, $filter_aksi, $filter_tgl) ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
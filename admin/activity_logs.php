<?php
// === 1. KONFIGURASI & KONEKSI ===
include 'includes/header_admin.php'; 
// include '../includes/db.php'; // (Sudah otomatis via header_admin)

// === 2. LOGIKA PAGINATION & FILTER ===
$limit = 10; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Siapkan variable filter
$whereClause = "WHERE 1=1";
$params = [];
$paramIndex = 1; // Counter untuk $1, $2, dst di PostgreSQL

// Filter: AKSI
$filter_aksi = isset($_GET['filter_aksi']) ? $_GET['filter_aksi'] : '';
if (!empty($filter_aksi)) {
    $whereClause .= " AND l.aksi = $" . $paramIndex++;
    $params[] = $filter_aksi;
}

// Filter: TANGGAL
$filter_tgl = isset($_GET['filter_tgl']) ? $_GET['filter_tgl'] : '';
if (!empty($filter_tgl)) {
    // Casting timestamp ke date untuk pencarian
    $whereClause .= " AND DATE(l.created_at) = $" . $paramIndex++;
    $params[] = $filter_tgl;
}

// === 3. QUERY TOTAL DATA (Untuk Pagination) ===
// Kita butuh tahu total data dulu sebelum di-limit untuk hitung jumlah halaman
$sqlCount = "SELECT COUNT(*) as total FROM activity_logs l $whereClause";
$resCount = pg_query_params($conn, $sqlCount, $params);
$rowCount = pg_fetch_assoc($resCount);
$totalData = $rowCount['total'];
$totalPages = ceil($totalData / $limit);

// === 4. QUERY DATA UTAMA ===
// Tambahkan LIMIT dan OFFSET ke parameter
$sqlData = "SELECT l.*, a.username 
            FROM activity_logs l 
            LEFT JOIN admin a ON l.id_admin = a.id_admin 
            $whereClause 
            ORDER BY l.created_at DESC 
            LIMIT $" . $paramIndex++ . " OFFSET $" . $paramIndex++;

// Masukkan limit & offset ke array params
$params[] = $limit;
$params[] = $offset;

$result = pg_query_params($conn, $sqlData, $params);

// === HELPER: Fungsi Mempertahankan Filter di Link Pagination ===
function getLink($newPage, $currentAksi, $currentTgl) {
    return "?page=$newPage&filter_aksi=$currentAksi&filter_tgl=$currentTgl";
}
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
            <form method="GET" action="">
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
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-filter"></i> Terapkan
                        </button>
                    </div>
                     <div class="col-md-1">
                        <a href="activity_logs.php" class="btn btn-light btn-sm w-100 border">
                             Reset
                        </a>
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
                        <?php if (pg_num_rows($result) > 0): ?>
                            <?php while($row = pg_fetch_assoc($result)): 
                                // 1. Tentukan Warna Badge
                                $badgeClass = match($row['aksi']) {
                                    'CREATE' => 'bg-success-subtle text-success border-success-subtle',
                                    'UPDATE' => 'bg-primary-subtle text-primary border-primary-subtle',
                                    'DELETE' => 'bg-danger-subtle text-danger border-danger-subtle',
                                    'LOGIN'  => 'bg-info-subtle text-info border-info-subtle',
                                    default  => 'bg-secondary-subtle text-secondary'
                                };

                                // 2. Format Tanggal
                                $phpDate = strtotime($row['created_at']);
                                $tgl = date('d M Y', $phpDate);
                                $jam = date('H:i', $phpDate);

                                // 3. Avatar Inisial
                                $username = !empty($row['username']) ? $row['username'] : 'System';
                                $initial = strtoupper(substr($username, 0, 2));
                                $avatarColor = !empty($row['username']) ? 'bg-primary' : 'bg-secondary';
                            ?>
                            
                            <tr>
                                <td class="ps-4 text-muted small">
                                    <i class="bi bi-calendar3 me-1"></i> <?= $tgl ?><br>
                                    <i class="bi bi-clock me-1"></i> <?= $jam ?> WIB
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
                                    <?php if($row['tabel_target']): ?>
                                        <span class="badge bg-light text-secondary border">
                                            <?= strtoupper(htmlspecialchars($row['tabel_target'])) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-secondary">
                                    <?= htmlspecialchars($row['keterangan']) ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <div class="mb-3">
                                        <i class="bi bi-clipboard-x display-4 text-secondary opacity-50"></i>
                                    </div>
                                    <p class="mb-0">Belum ada aktivitas yang tercatat.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($totalPages > 1): ?>
            <div class="card-footer bg-white border-top-0 py-3">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end mb-0 pagination-sm">
                        
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= getLink($page - 1, $filter_aksi, $filter_tgl) ?>">Previous</a>
                        </li>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <?php if ($i == 1 || $i == $totalPages || ($i >= $page - 1 && $i <= $page + 1)): ?>
                                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= getLink($i, $filter_aksi, $filter_tgl) ?>"><?= $i ?></a>
                                </li>
                            <?php elseif ($i == 2 || $i == $totalPages - 1): ?>
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= getLink($page + 1, $filter_aksi, $filter_tgl) ?>">Next</a>
                        </li>

                    </ul>
                </nav>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php include 'includes/footer_admin.php'; ?>
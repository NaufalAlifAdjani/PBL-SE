<?php include 'includes/header_admin.php'; ?>

<link rel="stylesheet" href="assets/css/admin_recruitment.css">

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0"><i class=""></i> Manage Pendaftaran</h2>
        <p class="text-muted mb-0">Manage data calon member Batch <?php echo htmlspecialchars($current_batch); ?></p>
    </div>

    <div class="filter-box d-flex align-items-center gap-3 bg-white p-2 rounded shadow-sm border">
            
            <div class="d-flex align-items-center border-end pe-3">
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" role="switch" id="recruitmentToggle" style="cursor: pointer; transform: scale(1.2);" <?php echo $is_recruitment_open ? 'checked' : ''; ?>>
                    <label class="form-check-label ms-2 fw-bold small text-uppercase" for="recruitmentToggle" id="statusLabel">
                        <?php if($is_recruitment_open): ?>
                            <span class="text-success"><i class="bi bi-unlock-fill"></i> Pendaftaran Buka</span>
                        <?php else: ?>
                            <span class="text-danger"><i class="bi bi-lock-fill"></i> Pendaftaran Tutup</span>
                        <?php endif; ?>
                    </label>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <label class="fw-bold small text-muted text-uppercase mb-0">Angkatan:</label>
                <form method="GET" action="manage_recruitment.php" class="m-0">
                    <select name="filter_batch" class="form-select form-select-sm fw-bold border-0 bg-transparent" style="cursor: pointer;" onchange="this.form.submit()">
                        <?php
                        if ($list_batch && pg_num_rows($list_batch) > 0) {
                            while($b = pg_fetch_assoc($list_batch)) {
                                $sel = ($current_batch == $b['batch']) ? 'selected' : '';
                                echo "<option value='{$b['batch']}' $sel>Batch {$b['batch']}</option>";
                            }
                        } else {
                            echo "<option value='".date('Y')."'>".date('Y')."</option>";
                        }
                        ?>
                    </select>
                </form>
            </div>
        </div>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-<?php echo ($_GET['status'] == 'error' ? 'danger' : 'success'); ?> alert-dismissible fade show shadow-sm" role="alert">
        <?php echo htmlspecialchars($_GET['msg']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<ul class="nav nav-tabs mb-3" id="recruitmentTab" role="tablist">
    <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-pending" type="button">
            <i class="bi bi-hourglass-split"></i> Permintaan Baru 
            <?php if(count($data_pending) > 0): ?>
                <span class="badge bg-danger rounded-pill ms-1"><?php echo count($data_pending); ?></span>
            <?php endif; ?>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-accepted" type="button">
            <i class="bi bi-check-circle"></i> Member Resmi
            <span class="badge bg-secondary rounded-pill ms-1"><?php echo count($data_diterima); ?></span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-rejected" type="button">
            <i class="bi bi-x-circle"></i> Riwayat Ditolak
        </button>
    </li>
</ul>

<div class="tab-content" id="recruitmentTabContent">
    
    <div class="tab-pane fade show active" id="tab-pending">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Nama Calon Member</th>
                                <th>NIM</th>
                                <th>Info</th>
                                <th>Portofolio</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data_pending)): ?>
                                <?php foreach ($data_pending as $row): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold"><?php echo htmlspecialchars($row['nama']); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($row['email']); ?></small>
                                    </td>
                                    <td class="font-monospace"><?php echo htmlspecialchars($row['nim']); ?></td>
                                    <td><?php echo htmlspecialchars($row['jurusan']); ?></td>
                                    
                                    <td>
                                        <?php if($row['portofolio']): ?>
                                            <a href="<?php echo htmlspecialchars($row['portofolio']); ?>" target="_blank" class="btn btn-sm btn-primary shadow-sm">
                                                <i class="bi bi-box-arrow-up-right"></i> Buka Link
                                            </a>
                                        <?php else: ?> 
                                            <span class="badge bg-light text-muted border">Tidak ada</span> 
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-end pe-4">
                                        <a href="manage_recruitment.php?action=approve&id=<?php echo $row['id_pendaftaran_user']; ?>" class="btn btn-sm btn-success shadow-sm">
                                            <i class="bi bi-check-lg"></i> Terima
                                        </a>
                                        <a href="manage_recruitment.php?action=reject&id=<?php echo $row['id_pendaftaran_user']; ?>" class="btn btn-sm btn-outline-danger shadow-sm" onclick="return confirmAction('Yakin tolak?')">
                                            <i class="bi bi-x-lg"></i> Tolak
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center py-5 text-muted">Tidak ada permintaan baru.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="tab-accepted">
        <div class="card shadow-sm border-0 border-start border-success border-4">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Nama Member</th>
                            <th>NIM</th>
                            <th>Jurusan</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data_diterima)): ?>
                            <?php foreach ($data_diterima as $row): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-success"><?php echo htmlspecialchars($row['nama']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($row['email']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($row['nim']); ?></td>
                                <td><?php echo htmlspecialchars($row['jurusan']); ?></td>
                                <td><span class="badge badge-soft-success">Aktif</span></td>
                                <td class="text-end pe-4">
                                    <a href="manage_recruitment.php?action=delete&id=<?php echo $row['id_pendaftaran_user']; ?>" class="btn btn-sm btn-light text-danger" onclick="return confirmAction('Hapus member ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada member diterima.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="tab-rejected">
        <div class="card shadow-sm border-0 bg-light">
            <div class="card-body p-0">
                <table class="table table-sm text-secondary mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Nama</th>
                            <th>NIM</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data_ditolak)): ?>
                            <?php foreach ($data_ditolak as $row): ?>
                            <tr>
                                <td class="ps-4"><?php echo htmlspecialchars($row['nama']); ?></td>
                                <td><?php echo htmlspecialchars($row['nim']); ?></td>
                                <td><span class="badge bg-secondary">Ditolak</span></td>
                                <td class="text-end pe-4">
                                    <a href="manage_recruitment.php?action=delete&id=<?php echo $row['id_pendaftaran_user']; ?>" class="text-danger" onclick="return confirmAction('Hapus permanen?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center py-4">Tidak ada data penolakan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('recruitmentToggle');
    const statusLabel = document.getElementById('statusLabel');

    toggleBtn.addEventListener('change', function() {
        const isChecked = this.checked;
        
        // 1. Ubah Tampilan UI Sementara (Loading/Optimistic UI)
        if(isChecked) {
            statusLabel.innerHTML = '<span class="text-muted"><i class="bi bi-hourglass-split"></i> Mengaktifkan...</span>';
        } else {
            statusLabel.innerHTML = '<span class="text-muted"><i class="bi bi-hourglass-split"></i> Menutup...</span>';
        }
        this.disabled = true; // Matikan tombol agar tidak di-klik berkali-kali

        // 2. Kirim ke Backend (Pastikan file update_recruitment.php ada di folder yang sama/sesuai path)
        fetch('update_recruitment.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ status: isChecked })
        })
        .then(response => response.json())
        .then(data => {
            this.disabled = false; // Hidupkan tombol lagi

            if (data.status === 'success') {
                // 3. Update UI jika Sukses
                if (isChecked) {
                    statusLabel.innerHTML = '<span class="text-success"><i class="bi bi-unlock-fill"></i> Pendaftaran Buka</span>';
                    // Opsional: Tampilkan notifikasi kecil/toast
                } else {
                    statusLabel.innerHTML = '<span class="text-danger"><i class="bi bi-lock-fill"></i> Pendaftaran Tutup</span>';
                }
            } else {
                // Jika Gagal
                alert("Gagal update: " + data.message);
                this.checked = !isChecked; // Balikkan posisi tombol
                // Reset label
                statusLabel.innerHTML = isChecked ? 
                    '<span class="text-danger"><i class="bi bi-lock-fill"></i> Pendaftaran Tutup</span>' : 
                    '<span class="text-success"><i class="bi bi-unlock-fill"></i> Pendaftaran Buka</span>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.disabled = false;
            this.checked = !isChecked; // Balikkan posisi tombol
            alert("Terjadi kesalahan koneksi server.");
        });
    });
});
</script>

<script src="assets/js/admin_recruitment.js"></script>

<?php include 'includes/footer_admin.php'; ?>
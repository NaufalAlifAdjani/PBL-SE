<div class="container-fluid py-4" id="personil-admin">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0">Manajemen Personil</h2>
    </div>
    
    <div class="card card-admin mb-4">
        <div class="card-body p-3">
            <form action="" method="GET">
                <input type="hidden" name="tab" id="current_tab_input" value="<?= htmlspecialchars($active_tab) ?>">

                <div class="row g-2">
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="Cari nama, NIP, atau NIM..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                            <button class="btn btn-secondary" type="submit"><i class="bi bi-search"></i> Cari</button>
                        </div>
                    </div>

                    <div class="col-md-auto ps-">
                        <select name="angkatan" class="form-select" onchange="this.form.submit()">
                            <option value="">- Semua Angkatan -</option>
                            <?php 
                            $selected_angkatan = $_GET['angkatan'] ?? '';
                            foreach ($opsi_angkatan as $thn): 
                            ?>
                                <option value="<?= $thn ?>" <?= $selected_angkatan == $thn ? 'selected' : '' ?>>
                                    Angkatan <?= $thn ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <?php if((isset($_GET['q']) && $_GET['q'] != '') || (isset($_GET['angkatan']) && $_GET['angkatan'] != '')): ?>
                        <div class="col-md-auto">
                            <a href="manage_personil.php?tab=<?= $active_tab ?>" class="btn btn-outline-danger" title="Reset Filter"><i class="bi bi-x-lg"></i></a>
                        </div>
                    <?php endif; ?>

                    <div class="col-md-auto ms-auto ps-2">
                        <a href="manage_personil.php?action=form" class="btn btn-primary-admin">
                            <i class="bi bi-plus-lg me-2"></i>Tambah Dosen
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <ul class="nav nav-tabs mb-0" id="personilTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold <?= $active_tab == 'dosen' ? 'active' : '' ?>" 
                    id="dosen-tab" data-bs-toggle="tab" data-bs-target="#tab-dosen" 
                    type="button" role="tab" aria-controls="tab-dosen" 
                    aria-selected="<?= $active_tab == 'dosen' ? 'true' : 'false' ?>">
                <i class="bi bi-people-fill me-1"></i> Data Dosen
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold <?= $active_tab == 'member' ? 'active' : '' ?>" 
                    id="member-tab" data-bs-toggle="tab" data-bs-target="#tab-member" 
                    type="button" role="tab" aria-controls="tab-member" 
                    aria-selected="<?= $active_tab == 'member' ? 'true' : 'false' ?>">
                <i class="bi bi-mortarboard-fill me-1"></i> Data Member
            </button>
        </li>
    </ul>

    <div class="tab-content" id="personilTabsContent">
        
        <div class="tab-pane fade <?= $active_tab == 'dosen' ? 'show active' : '' ?>" id="tab-dosen" role="tabpanel" aria-labelledby="dosen-tab">
            <div class="card card-admin shadow-sm border-top-0 rounded-top-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">Nama Dosen</th>
                                    <th>Jabatan</th>
                                    <th class="text-end px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!isset($dosen) || !$dosen || pg_num_rows($dosen) === 0): ?>
                                    <tr><td colspan="3" class="text-center py-5 text-muted">Data dosen tidak ditemukan.</td></tr>
                                <?php else: ?>
                                    <?php while ($d = pg_fetch_assoc($dosen)): ?>
                                        <tr>
                                            <td class="px-4">
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($d['nama_dosen'] ?? ''); ?></div>
                                                <small class="text-muted"><?= htmlspecialchars($d['email_dosen'] ?? '-'); ?></small>
                                            </td>
                                            <td><span class="badge bg-info text-dark"><?= htmlspecialchars($d['jabatan'] ?? '-'); ?></span></td>
                                            <td class="text-end px-4">
                                                <a href="manage_personil.php?action=form&id=<?= $d['id_dosen']; ?>" class="btn btn-sm btn-outline-success me-1"><i class="bi bi-pencil-square"></i></a>
                                                <a href="manage_personil.php?action=delete_dosen&id=<?= $d['id_dosen']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus dosen ini?');"><i class="bi bi-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade <?= $active_tab == 'member' ? 'show active' : '' ?>" id="tab-member" role="tabpanel" aria-labelledby="member-tab">
            <div class="card card-admin shadow-sm border-top-0 rounded-top-0">
                    <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">Nama Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Jurusan</th>
                                    <th>Angkatan</th>
                                    <th class="text-end px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!isset($member) || !$member || pg_num_rows($member) === 0): ?>
                                    <tr><td colspan="5" class="text-center py-5 text-muted">Data member tidak ditemukan.</td></tr>
                                <?php else: ?>
                                    <?php while ($m = pg_fetch_assoc($member)): ?>
                                        <tr>
                                            <td class="px-4 fw-bold text-dark"><?= htmlspecialchars($m['nama'] ?? ''); ?></td>
                                            <td><?= htmlspecialchars($m['nim'] ?? ''); ?></td>
                                            <td><?= htmlspecialchars($m['jurusan'] ?? ''); ?></td>
                                            <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($m['angkatan'] ?? '-'); ?></span></td>
                                            <td class="text-end px-4">
                                                <a href="manage_personil.php?action=form_member&id=<?= $m['id_pendaftaran_user']; ?>" class="btn btn-sm btn-outline-success me-1"><i class="bi bi-pencil-square"></i></a>
                                                <a href="manage_personil.php?action=delete_member&id=<?= $m['id_pendaftaran_user']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus member ini?');"><i class="bi bi-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div> 
</div>

<script>
    const tabEls = document.querySelectorAll('button[data-bs-toggle="tab"]');
    const hiddenInput = document.getElementById('current_tab_input');
    tabEls.forEach(tabEl => {
        tabEl.addEventListener('shown.bs.tab', function (event) {
            const targetId = event.target.getAttribute('data-bs-target');
            if (targetId === '#tab-member') {
                hiddenInput.value = 'member';
            } else {
                hiddenInput.value = 'dosen';
            }
        })
    })
</script>
<?php
$id_member       = $member_detail['id_member']       ?? null;
$nama            = $member_detail['nama']            ?? '';
$nim             = $member_detail['nim']             ?? '';
$link_portofolio = $member_detail['link_portofolio'] ?? ($member_detail['portofolio'] ?? '');
?>

<div class="container-fluid py-4">
    <h2 class="fw-bold mb-3">
        <?= $id_member ? 'Edit Member' : 'Tambah Member'; ?>
    </h2>
    
    <div class="card card-admin shadow-sm" style="max-width: 600px;">
        <div class="card-body p-4">
            <form action="manage_personil.php?action=update_member" method="post">
                <input type="hidden" name="id_member" value="<?= (int)$id_member; ?>">

                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($nama); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">NIM</label>
                    <input type="text" name="nim" class="form-control" value="<?= htmlspecialchars($nim); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Link Portofolio</label>
                    <input type="url" name="link_portofolio" class="form-control" value="<?= htmlspecialchars($link_portofolio); ?>" placeholder="https://" required>
                    <div class="form-text">Masukkan link lengkap (diawali https://).</div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success px-4"><i class="bi bi-save me-1"></i> Simpan</button>
                    <a href="manage_personil.php" class="btn btn-light border ms-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
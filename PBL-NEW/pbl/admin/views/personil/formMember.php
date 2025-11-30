<?php
// dari controller sudah dikirim $member_detail

$id_member       = $member_detail['id_member']       ?? null;
$nama            = $member_detail['nama']            ?? '';
$nim             = $member_detail['nim']             ?? '';
//  dua key: link_portofolio / portofolio
$link_portofolio = $member_detail['link_portofolio'] ?? ($member_detail['portofolio'] ?? '');
?>

<div class="container py-4">
    <h2 class="fw-bold mb-4">
        <?= $id_member ? 'Edit Member' : ''; ?>
    </h2>

    <form action="manage_personil.php?action=update_member" method="post">
        <input type="hidden" name="id_member" value="<?= (int)$id_member; ?>">

        <!-- Nama -->
        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text"
                   name="nama"
                   class="form-control"
                   value="<?= htmlspecialchars($nama); ?>"
                   required>
        </div>

        <!-- NIM -->
        <div class="mb-3">
            <label class="form-label">NIM</label>
            <input type="text"
                   name="nim"
                   class="form-control"
                   value="<?= htmlspecialchars($nim); ?>"
                   required>
        </div>

        <!-- Link Portofolio -->
        <div class="mb-3">
            <label class="form-label">Link Portofolio</label>
            <input type="url"
                   name="link_portofolio"
                   class="form-control"
                   value="<?= htmlspecialchars($link_portofolio); ?>"
                   placeholder="https://contoh.com/portofolio"
                   required>
        </div>

        <button type="submit" class="btn btn-success">
            Simpan Perubahan
        </button>
        <a href="manage_personil.php" class="btn btn-secondary ms-2">
            Batal
        </a>
    </form>
</div>


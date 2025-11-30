<div class="p-3 bg-light rounded mb-3">
    <h6 class="fw-bold text-black">Informasi Akademik Dosen</h6>

    <div class="row">

        <div class="col-md-6 mb-3">
            <label>NIP</label>
            <input type="text" class="form-control" name="nip"
                   value="<?= $data['nip'] ?? '' ?>">
        </div>

        <div class="col-md-6 mb-3">
            <label>NIDN</label>
            <input type="text" class="form-control" name="nidn"
                   value="<?= $data['nidn'] ?? '' ?>">
        </div>

        <div class="col-md-12 mb-3">
            <label>Jabatan</label>
            <input type="text" class="form-control" name="jabatan"
                   value="<?= $data['jabatan'] ?? '' ?>">
        </div>

        <div class="col-md-12 mb-3">
            <label>Foto Profil</label>
            <input type="file" class="form-control" name="foto_profil">

            <?php if (!empty($data['foto_profil'])): ?>
                <img src="../uploads_personil/<?= $data['foto_profil'] ?>" width="120" class="mt-2">
            <?php endif; ?>
        </div>

    </div>
</div>

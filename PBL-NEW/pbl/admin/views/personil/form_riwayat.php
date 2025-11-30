<div class="p-3 bg-light rounded mb-3">
    <h6 class="fw-bold text-black mb-3">Riwayat Pendidikan</h6>

    <?php
    // memastikan array
    $riwayat_list = (isset($riwayat_list) && is_array($riwayat_list))
        ? $riwayat_list
        : [];
    ?>

    <div id="riwayat-container">
        <?php foreach ($riwayat_list as $r): ?>
            <div class="row mb-2 riwayat-item">
                <div class="col-md-2 "><input type="text" class="form-control" name="jenjang[]"        value="<?= htmlspecialchars($r['jenjang']) ?>"></div>
                <div class="col-md-3"><input type="text" class="form-control" name="program_studi[]" value="<?= htmlspecialchars($r['program_studi']) ?>"></div>
                <div class="col-md-3"><input type="text" class="form-control" name="nama_kampus[]"    value="<?= htmlspecialchars($r['nama_kampus']) ?>"></div>
                <div class="col-md-3"><input type="text" class="form-control" name="thn_lulus[]"      value="<?= htmlspecialchars($r['thn_lulus']) ?>"></div>
                <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm remove-riwayat">X</button></div>
            </div>
        <?php endforeach; ?>

        <?php if (count($riwayat_list) == 0): ?>
            <div class="row mb-2 riwayat-item">
                <div class="col-md-2"><input type="text" class="form-control" name="jenjang[]"        placeholder="Jenjang"></div>
                <div class="col-md-3"><input type="text" class="form-control" name="program_studi[]" placeholder="Program Studi"></div>
                <div class="col-md-3"><input type="text" class="form-control" name="nama_kampus[]"    placeholder="Kampus"></div>
                <div class="col-md-3"><input type="text" class="form-control" name="thn_lulus[]"      placeholder="Tahun Lulus"></div>
                <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm remove-riwayat">X</button></div>
            </div>
        <?php endif; ?>
    </div>

    <button type="button" id="add-riwayat" class="fw-bold bg-black text-white btn btn-secondary btn-sm mt-2">
        + Tambah Riwayat
    </button>
</div>

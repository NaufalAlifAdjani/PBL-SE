<div class="p-3 bg-light rounded mb-3">
    <h6 class="fw-bold text-black">Mata Kuliah Diampu</h6>

    <?php
    // memastikan variabel aman
    $kbm_list   = (isset($kbm_list)   && is_array($kbm_list))   ? $kbm_list   : [];
    $all_matkul = (isset($all_matkul) && is_array($all_matkul)) ? $all_matkul : [];

    // jika belum ada data KBM, buat satu baris kosong
    if (count($kbm_list) === 0) {
        $kbm_list[] = ['id_matkul' => null];
    }
    ?>

    <div id="kbm-container">
        <?php foreach ($kbm_list as $k): ?>
            <?php $selectedId = $k['id_matkul'] ?? null; ?>

            <div class="row mb-2 kbm-item">
                <div class="col-md-11">
                    <select name="id_matkul[]" class="form-select">
                        <option value=""> Pilih Mata Kuliah </option>
                        <?php foreach ($all_matkul as $m): ?>
                            <option value="<?= htmlspecialchars($m['id_matkul']) ?>"
                                <?= ($selectedId == $m['id_matkul']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m['nama_matkul']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-1 d-flex">
                    <button type="button" class="h-10 w-10 self-center btn btn-danger btn-sm ms-1 remove-kbm">X</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <button type="button" id="add-kbm" class="fw-bold bg-black text-white btn btn-secondary btn-sm mt-2">
        + Tambah KBM
    </button>
</div>
